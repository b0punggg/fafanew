/**
 * Print Bridge - layanan cetak lokal di PC kasir (Windows).
 * Jalankan: npm install && npm start
 */
const express = require('express');
const cors = require('cors');
const fs = require('fs');
const path = require('path');
const os = require('os');
const https = require('https');
const { exec } = require('child_process');
const { ensureSslCerts } = require('./generate-cert');
const { buildNotaText, loadLogoRaster } = require('./nota-builder');

const PORT = process.env.PORT || 3000;
const CONFIG_PATH = path.join(__dirname, 'printer-config.json');
const RAW_PRINT_PS1 = path.join(__dirname, 'raw-print.ps1');

function loadConfig() {
  const defaults = {
    displayName: process.env.PRINTER_DISPLAY_NAME || 'BP-LITE 80D+80X Printer',
    shareName: process.env.PRINTER_SHARE_NAME || 'BP-LITE80D',
    altNames: ['BP-LITE 80D+80X Printer', 'BP-LITE80D', 'BP-LITE 80D+80X'],
    nmToko: 'Fafa COLLECTION',
    alToko: 'Pasar Pracimantoro',
    allowCopy: false,
  };
  try {
    if (fs.existsSync(CONFIG_PATH)) {
      const file = JSON.parse(fs.readFileSync(CONFIG_PATH, 'utf8'));
      return Object.assign(defaults, file);
    }
  } catch (e) {
    console.warn('printer-config.json tidak valid, pakai default.');
  }
  return defaults;
}

const PRINTER_CFG = loadConfig();

const app = express();
app.use(cors());
app.use(express.json({ limit: '10mb' }));
app.use(express.text({ limit: '10mb', type: 'text/plain' }));

function execCommand(cmd, timeoutMs) {
  const timeout = timeoutMs || 20000;
  return new Promise((resolve, reject) => {
    const child = exec(cmd, { windowsHide: true, maxBuffer: 1024 * 1024 }, (err, stdout, stderr) => {
      clearTimeout(timer);
      const out = ((stdout || '') + (stderr || '')).trim();
      if (err) {
        const error = new Error(out || err.message);
        error.output = out;
        return reject(error);
      }
      resolve(out);
    });

    const timer = setTimeout(() => {
      try {
        child.kill();
      } catch (e) {}
      reject(new Error('Timeout setelah ' + timeout + 'ms'));
    }, timeout);
  });
}

function isCopySuccess(output) {
  const o = (output || '').toLowerCase();
  if (o.indexOf('0 file(s) copied') !== -1 || o.indexOf('0 file copied') !== -1) {
    return false;
  }
  return /1 file\(s\) copied/.test(o) || /1 file copied/.test(o) || /1 berkas/.test(o);
}

function getPrinterNames() {
  const names = [];
  function add(name) {
    if (name && names.indexOf(name) === -1) {
      names.push(name);
    }
  }
  add(PRINTER_CFG.displayName);
  add(PRINTER_CFG.shareName);
  (PRINTER_CFG.altNames || []).forEach(add);
  return names;
}

function getPrinterTargets() {
  const names = getPrinterNames();
  const targets = [];

  // WinSpool RAW paling andal untuk ESC/POS — coba semua nama printer dulu
  names.forEach((name) => {
    targets.push({ name, method: 'winspool', key: name + '|winspool' });
  });

  names.forEach((name) => {
    targets.push({ name, method: 'print', key: name + '|print' });
  });

  // copy /B sering "sukses" tanpa kertas keluar — hanya jika diizinkan
  if (PRINTER_CFG.allowCopy === true) {
    names.forEach((name) => {
      targets.push({ name, method: 'copy', key: name + '|copy' });
    });
  }

  return targets;
}

function winSpoolTimeout(buffer) {
  return Math.max(30000, 15000 + Math.floor(buffer.length / 50));
}

async function printViaWinSpool(buffer, printerName) {
  const tmpFile = path.join(os.tmpdir(), 'thprint_' + Date.now() + '.bin');
  fs.writeFileSync(tmpFile, buffer);
  try {
    const cmd = 'powershell -NoProfile -ExecutionPolicy Bypass -File "' + RAW_PRINT_PS1 + '" -PrinterName "' + printerName + '" -FilePath "' + tmpFile + '"';
    const out = await execCommand(cmd, winSpoolTimeout(buffer));
    if (out.indexOf('OK') !== -1) {
      return { method: 'winspool', printer: printerName, output: out };
    }
    throw new Error(out || 'WinSpool gagal');
  } finally {
    try { fs.unlinkSync(tmpFile); } catch (e) {}
  }
}

async function printViaCopy(buffer, printerName) {
  const tmpFile = path.join(os.tmpdir(), 'thprint_' + Date.now() + '.bin');
  fs.writeFileSync(tmpFile, buffer);
  try {
    const cmd = 'copy /B "' + tmpFile + '" "\\\\localhost\\' + printerName + '"';
    const out = await execCommand(cmd, 8000);
    if (!isCopySuccess(out)) {
      throw new Error(out || 'Copy gagal');
    }
    return { method: 'copy', printer: printerName, output: out };
  } finally {
    try { fs.unlinkSync(tmpFile); } catch (e) {}
  }
}

async function printViaPrintCmd(buffer, printerName) {
  const tmpFile = path.join(os.tmpdir(), 'thprint_' + Date.now() + '.bin');
  fs.writeFileSync(tmpFile, buffer);
  try {
    const cmd = 'print /D:"' + printerName + '" "' + tmpFile + '"';
    const out = await execCommand(cmd, 15000);
    return { method: 'print', printer: printerName, output: out };
  } finally {
    try { fs.unlinkSync(tmpFile); } catch (e) {}
  }
}

async function printWithFallback(buffer) {
  const targets = getPrinterTargets();
  const errors = [];

  for (const target of targets) {
    try {
      if (target.method === 'winspool') {
        const result = await printViaWinSpool(buffer, target.name);
        console.log('Cetak OK', result.method, result.printer, buffer.length + ' bytes');
        return result;
      }
      if (target.method === 'copy') {
        const result = await printViaCopy(buffer, target.name);
        console.log('Cetak OK', result.method, result.printer, buffer.length + ' bytes');
        return result;
      }
      if (target.method === 'print') {
        const result = await printViaPrintCmd(buffer, target.name);
        console.log('Cetak OK', result.method, result.printer, buffer.length + ' bytes');
        return result;
      }
    } catch (e) {
      errors.push(target.method + ':' + target.name + ' -> ' + e.message);
      console.warn('Cetak gagal', target.method, target.name, e.message);
    }
  }

  throw new Error(errors.join(' | ') || 'Semua metode cetak gagal');
}

async function printNotaBuffer(data) {
  const storeConfig = {
    nmToko: data.nm_toko || PRINTER_CFG.nmToko,
    alToko: data.al_toko || PRINTER_CFG.alToko,
    includeLogo: true,
  };

  const attempts = [
    { includeLogo: true, label: 'dengan logo' },
    { includeLogo: false, label: 'tanpa logo' },
  ];

  const errors = [];
  for (let i = 0; i < attempts.length; i++) {
    const attempt = attempts[i];
    if (i === 1 && loadLogoRaster().length === 0) {
      continue;
    }
    const buffer = buildNotaText(data, Object.assign({}, storeConfig, {
      includeLogo: attempt.includeLogo,
    }));
    try {
      const result = await printWithFallback(buffer);
      result.logo = attempt.includeLogo;
      return result;
    } catch (e) {
      errors.push(attempt.label + ': ' + e.message);
      console.warn('Cetak nota gagal', attempt.label, e.message);
    }
  }

  throw new Error(errors.join(' | ') || 'Cetak nota gagal');
}

app.get('/health', (req, res) => {
  res.json({
    ok: true,
    protocol: 'https',
    displayName: PRINTER_CFG.displayName,
    shareName: PRINTER_CFG.shareName,
    allowCopy: PRINTER_CFG.allowCopy === true,
    hasLogo: loadLogoRaster().length > 0,
    port: PORT,
  });
});

app.post('/print/test', async (req, res) => {
  try {
    const buffer = Buffer.from([0x1b, 0x40, 0x54, 0x45, 0x53, 0x54, 0x20, 0x50, 0x52, 0x49, 0x4e, 0x54, 0x0a, 0x0a, 0x0a, 0x1d, 0x56, 0x00]);
    const result = await printWithFallback(buffer);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

app.post('/print/raw', async (req, res) => {
  try {
    let buffer;
    if (req.body.rawBase64) {
      buffer = Buffer.from(req.body.rawBase64, 'base64');
    } else if (req.body.raw) {
      buffer = Buffer.from(req.body.raw, 'binary');
    } else if (typeof req.body === 'string') {
      buffer = Buffer.from(req.body, 'binary');
    } else {
      return res.status(400).json({ success: false, error: 'raw atau rawBase64 diperlukan' });
    }
    const result = await printWithFallback(buffer);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

app.post('/print/nota', async (req, res) => {
  try {
    const data = req.body.data || req.body;
    if (!data || !(data.items && data.items.length)) {
      return res.status(400).json({ success: false, error: 'Data nota kosong atau items tidak ada' });
    }
    const result = await printNotaBuffer(data);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

app.post('/print/html', async (req, res) => {
  try {
    const html = req.body.html || '';
    const text = html.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim() + '\n\n\n';
    const init = Buffer.from([0x1b, 0x40]);
    const cutPaper = Buffer.from([0x1d, 0x56, 0x30, 0x00]);
    const buffer = Buffer.concat([init, Buffer.from(text, 'latin1'), cutPaper]);
    const result = await printWithFallback(buffer);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

const { certPath, keyPath } = ensureSslCerts();
const credentials = {
  cert: fs.readFileSync(certPath),
  key: fs.readFileSync(keyPath),
};

https.createServer(credentials, app).listen(PORT, () => {
  console.log('Print bridge running on https://localhost:' + PORT);
  console.log('Printer display : ' + PRINTER_CFG.displayName);
  console.log('Printer share   : ' + PRINTER_CFG.shareName);
  console.log('Metode copy     : ' + (PRINTER_CFG.allowCopy === true ? 'aktif' : 'nonaktif (WinSpool prioritas)'));
  console.log('Logo nota       : ' + (loadLogoRaster().length > 0 ? 'ada' : 'tidak ada'));
  console.log('');
  console.log('PENTING: Buka https://localhost:' + PORT + '/health di browser');
  console.log('         lalu klik "Lanjutkan" / accept sertifikat (sekali saja).');
});
