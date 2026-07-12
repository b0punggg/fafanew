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

const PORT = process.env.PORT || 3000;
const CONFIG_PATH = path.join(__dirname, 'printer-config.json');
const RAW_PRINT_PS1 = path.join(__dirname, 'raw-print.ps1');

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

function padRight(str, len) {
  str = String(str || '');
  return str.length >= len ? str.substring(0, len) : str + ' '.repeat(len - str.length);
}

function padLeft(str, len) {
  str = String(str || '');
  return str.length >= len ? str.substring(0, len) : ' '.repeat(len - str.length) + str;
}

function center(str, width) {
  str = String(str || '');
  if (str.length >= width) return str.substring(0, width);
  const left = Math.floor((width - str.length) / 2);
  return ' '.repeat(left) + str + ' '.repeat(width - str.length - left);
}

function formatNumber(n) {
  return String(Math.round(Number(n) || 0)).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function buildNotaText(data) {
  const cutPaper = Buffer.from([0x1d, 0x56, 0x30, 0x00]);
  const openDrawer = Buffer.from([0x1b, 0x70, 0x30, 0x19, 0xfa]);
  const lines = [];
  lines.push(center('TOKOFAFA', 47));
  lines.push('');
  lines.push(padRight('No.Struk     :', 20) + padRight(data.no_fakjual || '', 27));
  lines.push(padRight('Tanggal      :', 20) + padRight(data.tgltime || '', 27));
  if (data.nm_pel) {
    lines.push(padRight('Pelanggan    :', 20) + padRight(data.nm_pel, 27));
  }
  lines.push('-----------------------------------------------');
  lines.push('No.     Nama Barang');
  lines.push('     Jml       Disc%      Harga     SubTotal');
  lines.push('-----------------------------------------------');

  (data.items || []).forEach((item, idx) => {
    lines.push(padRight(String(idx + 1) + '.', 4) + (item.nmbrg || ''));
    lines.push(
      '    ' +
      padLeft(item.qty || 0, 3) +
      padRight(item.sat || '', 7) +
      padLeft(item.disc || 0, 9) +
      '  ' +
      padLeft(formatNumber(item.hrg || 0), 9) +
      '  ' +
      padLeft(formatNumber(item.subtot || 0), 12)
    );
  });

  lines.push('-----------------------------------------------');
  lines.push(padRight('', 12) + padRight('Total', 15) + '    Rp. ' + padLeft(formatNumber(data.total || data.belanja || 0), 16));

  if (Number(data.disctot) > 0) {
    lines.push(padRight('', 12) + padRight('Disc Nota', 15) + '    Rp. ' + padLeft(String(data.disctot), 16));
  }
  if (Number(data.ongkir) > 0) {
    lines.push(padRight('', 12) + padRight('Ongkir', 15) + '    Rp. ' + padLeft(String(data.ongkir), 16));
  }

  const kdBayar = (data.kd_bayar || 'TUNAI').toUpperCase();
  if (kdBayar === 'TUNAI') {
    lines.push(padRight('', 12) + padRight('Uang Tunai', 15) + '    Rp. ' + padLeft(formatNumber(data.bayar || 0), 16));
    lines.push(padRight('', 12) + padRight('Kembali', 15) + '    Rp. ' + padLeft(formatNumber(data.susuk || 0), 16));
  } else {
    lines.push(padRight('', 12) + padRight('Uang Tunai', 15) + '    Rp. ' + padLeft(formatNumber(data.bayar || 0), 16));
    lines.push(padRight('', 12) + padRight('Kekurangan', 15) + '    Rp. ' + padLeft(formatNumber(data.saldohut || 0), 16));
    lines.push(padRight('', 12) + padRight('Jatuh Tempo', 15) + '    ' + padLeft(String(data.jtempo || ''), 16));
  }

  lines.push('');
  lines.push(center('BARANG YG.SUDAH DIBELI TDK BISA DIKEMBALIKAN', 46));
  lines.push(center('*TERIMA KASIH*', 47));
  lines.push('');
  lines.push('');

  const text = openDrawer.toString('binary') + lines.join('\n') + '\n\n\n\n';
  return Buffer.concat([Buffer.from(text, 'binary'), cutPaper]);
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

  // Share copy dulu (cepat), lalu WinSpool display name, sisanya fallback
  add(PRINTER_CFG.shareName, 'copy');
  add(PRINTER_CFG.displayName, 'winspool');
  add(PRINTER_CFG.displayName, 'print');
  (PRINTER_CFG.altNames || []).forEach((n) => {
    if (n !== PRINTER_CFG.shareName && n !== PRINTER_CFG.displayName) {
      add(n, 'copy');
      add(n, 'winspool');
      add(n, 'print');
    }
  });
  return targets;
}

async function printViaWinSpool(buffer, printerName) {
  const tmpFile = path.join(os.tmpdir(), 'thprint_' + Date.now() + '.bin');
  fs.writeFileSync(tmpFile, buffer);
  try {
    const cmd = 'powershell -NoProfile -ExecutionPolicy Bypass -File "' + RAW_PRINT_PS1 + '" -PrinterName "' + printerName + '" -FilePath "' + tmpFile + '"';
    const out = await execCommand(cmd, 10000);
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
    const out = await execCommand(cmd, 5000);
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
    protocol: 'https',
    displayName: PRINTER_CFG.displayName,
    shareName: PRINTER_CFG.shareName,
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
const credentials = {
  cert: fs.readFileSync(certPath),
  key: fs.readFileSync(keyPath),
};

https.createServer(credentials, app).listen(PORT, () => {
  console.log('Print bridge running on https://localhost:' + PORT);
  console.log('Printer display : ' + PRINTER_CFG.displayName);
  console.log('Printer share   : ' + PRINTER_CFG.shareName);
  console.log('');
  console.log('PENTING: Buka https://localhost:' + PORT + '/health di browser');
  console.log('         lalu klik "Lanjutkan" / accept sertifikat (sekali saja).');
});
