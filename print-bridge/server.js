/**
 * Print Bridge - layanan cetak lokal di PC kasir (Windows).
 * HTTPS agar browser HTTPS (Hostinger) boleh fetch ke localhost.
 */
const express = require('express');
const https = require('https');
const cors = require('cors');
const fs = require('fs');
const path = require('path');
const os = require('os');
const { exec } = require('child_process');
const { ensureSslCerts } = require('./generate-cert');
const { buildNotaText } = require('./nota-layout');

const PORT = process.env.PORT || 3000;
const CONFIG_PATH = path.join(__dirname, 'printer-config.json');
const RAW_PRINT_PS1 = path.join(__dirname, 'raw-print.ps1');
const EXEC_TIMEOUT_MS = 20000;

function loadConfig() {
  const defaults = {
    displayName: process.env.PRINTER_DISPLAY_NAME || 'BP-LITE 80D+80X Printer',
    shareName: process.env.PRINTER_SHARE_NAME || 'BP-LITE80D',
    altNames: ['BP-LITE 80D+80X Printer', 'BP-LITE80D', 'BP-LITE 80D+80X'],
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
  const limit = timeoutMs || EXEC_TIMEOUT_MS;
  return new Promise((resolve, reject) => {
    const child = exec(
      cmd,
      { windowsHide: true, maxBuffer: 1024 * 1024, timeout: limit },
      (err, stdout, stderr) => {
        const out = ((stdout || '') + (stderr || '')).trim();
        if (err) {
          const msg = out || err.message || 'Perintah gagal';
          const error = new Error(msg);
          error.output = out;
          error.killed = err.killed;
          return reject(error);
        }
        resolve(out);
      }
    );
    child.on('error', reject);
  });
}

function isCopySuccess(output) {
  const o = (output || '').toLowerCase();
  if (o.indexOf('0 file(s) copied') !== -1 || o.indexOf('0 file copied') !== -1) {
    return false;
  }
  return /1 file\(s\) copied/.test(o) || /1 file copied/.test(o) || /1 berkas/.test(o);
}

function getPrinterTargets() {
  const targets = [];
  function add(name, method) {
    if (!name) return;
    const key = name + '|' + method;
    if (!targets.some((t) => t.key === key)) {
      targets.push({ name, method, key });
    }
  }
  add(PRINTER_CFG.displayName, 'winspool');
  add(PRINTER_CFG.shareName, 'copy');
  add(PRINTER_CFG.displayName, 'print');
  (PRINTER_CFG.altNames || []).forEach((n) => {
    add(n, 'winspool');
    add(n, 'copy');
  });
  return targets;
}

async function printViaWinSpool(buffer, printerName) {
  const tmpFile = path.join(os.tmpdir(), 'thprint_' + Date.now() + '.bin');
  fs.writeFileSync(tmpFile, buffer);
  try {
    const cmd =
      'powershell -NoProfile -ExecutionPolicy Bypass -File "' +
      RAW_PRINT_PS1 +
      '" -PrinterName "' +
      printerName +
      '" -FilePath "' +
      tmpFile +
      '"';
    const out = await execCommand(cmd, 15000);
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
    const out = await execCommand(cmd, 10000);
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
    const out = await execCommand(cmd, 10000);
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
        return await printViaWinSpool(buffer, target.name);
      }
      if (target.method === 'copy') {
        return await printViaCopy(buffer, target.name);
      }
      if (target.method === 'print') {
        return await printViaPrintCmd(buffer, target.name);
      }
    } catch (e) {
      errors.push(target.method + ':' + target.name + ' -> ' + e.message);
      console.warn('Cetak gagal', target.method, target.name, e.message);
    }
  }

  throw new Error(errors.join(' | ') || 'Semua metode cetak gagal');
}

app.get('/health', (req, res) => {
  res.json({
    ok: true,
    https: true,
    displayName: PRINTER_CFG.displayName,
    shareName: PRINTER_CFG.shareName,
    port: PORT,
  });
});

app.post('/print/test', async (req, res) => {
  try {
    const buffer = Buffer.from([
      0x1b, 0x40,
      0x1b, 0x61, 0x01,
      0x54, 0x45, 0x53, 0x54, 0x20, 0x50, 0x52, 0x49, 0x4e, 0x54, 0x0a,
      0x54, 0x4f, 0x4b, 0x4f, 0x46, 0x41, 0x46, 0x41, 0x0a, 0x0a, 0x0a,
      0x1d, 0x56, 0x30, 0x00,
    ]);
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
    const buffer = buildNotaText(data);
    const result = await printWithFallback(buffer);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

app.post('/print/html', async (req, res) => {
  try {
    const html = req.body.html || '';
    const text = html.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim() + '\n\n\n';
    const cutPaper = Buffer.from([0x1d, 0x56, 0x30, 0x00]);
    const buffer = Buffer.concat([Buffer.from(text, 'utf8'), cutPaper]);
    const result = await printWithFallback(buffer);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

const { certPath, keyPath } = ensureSslCerts();
const sslOptions = {
  cert: fs.readFileSync(certPath),
  key: fs.readFileSync(keyPath),
};

https.createServer(sslOptions, app).listen(PORT, () => {
  console.log('Print bridge running on https://localhost:' + PORT);
  console.log('Printer display : ' + PRINTER_CFG.displayName);
  console.log('Printer share   : ' + PRINTER_CFG.shareName);
  console.log('');
  console.log('PENTING: Buka https://localhost:' + PORT + '/health di browser kasir');
  console.log('         dan terima sertifikat (sekali saja) agar cetak dari web berjalan.');
});
