/**
 * Print Bridge - layanan cetak lokal di PC kasir (Windows).
 * Jalankan: npm install && npm start
 * Default: http://localhost:3000
 */
const express = require('express');
const cors = require('cors');
const fs = require('fs');
const path = require('path');
const os = require('os');
const { exec } = require('child_process');

const PORT = process.env.PORT || 3000;
const DEFAULT_PRINTER = process.env.PRINTER_NAME || 'BP-LITE 80D+80X Printer';
const ALT_PRINTERS = [
  DEFAULT_PRINTER,
  'BP-LITE 80D+80X',
  'GP-80250N Series',
  'POS-80C',
  'GP-80220(Cut) Series',
  'ZJ-80',
];

const app = express();
app.use(cors());
app.use(express.json({ limit: '10mb' }));
app.use(express.text({ limit: '10mb', type: 'text/plain' }));

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
  let lines = [];
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

  const items = data.items || [];
  items.forEach((item, idx) => {
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

function printRawBuffer(buffer, printerName) {
  return new Promise((resolve, reject) => {
    const tmpFile = path.join(os.tmpdir(), `thprint_${Date.now()}.bin`);
    fs.writeFileSync(tmpFile, buffer);
    const cmd = `copy /B "${tmpFile}" "\\\\localhost\\${printerName}"`;
    exec(cmd, { windowsHide: true }, (err, stdout, stderr) => {
      try { fs.unlinkSync(tmpFile); } catch (e) {}
      const out = (stdout || '') + (stderr || '');
      if (err && out.toLowerCase().indexOf('copied') === -1 && out.toLowerCase().indexOf('disalin') === -1) {
        return reject(new Error(out || err.message));
      }
      if (out.toLowerCase().indexOf('copied') !== -1 || out.toLowerCase().indexOf('disalin') !== -1) {
        return resolve({ printer: printerName, output: out.trim() });
      }
      reject(new Error(out || 'Copy gagal'));
    });
  });
}

async function printWithFallback(buffer, printerName) {
  const candidates = printerName ? [printerName] : [];
  ALT_PRINTERS.forEach((p) => {
    if (!candidates.includes(p)) candidates.push(p);
  });

  let lastError = null;
  for (const name of candidates) {
    try {
      const result = await printRawBuffer(buffer, name);
      return result;
    } catch (e) {
      lastError = e;
    }
  }
  throw lastError || new Error('Semua printer gagal');
}

app.get('/health', (req, res) => {
  res.json({ ok: true, printer: DEFAULT_PRINTER, port: PORT });
});

app.post('/print/raw', async (req, res) => {
  try {
    const printerName = req.body.printerName || req.body.printer || DEFAULT_PRINTER;
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
    const result = await printWithFallback(buffer, printerName);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

app.post('/print/nota', async (req, res) => {
  try {
    const data = req.body.data || req.body;
    const printerName = req.body.printerName || DEFAULT_PRINTER;
    const buffer = buildNotaText(data);
    const result = await printWithFallback(buffer, printerName);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

app.post('/print/html', async (req, res) => {
  try {
    const printerName = req.body.printerName || req.body.printer || DEFAULT_PRINTER;
    const html = req.body.html || '';
    const text = html.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim() + '\n\n\n';
    const cutPaper = Buffer.from([0x1d, 0x56, 0x30, 0x00]);
    const buffer = Buffer.concat([Buffer.from(text, 'utf8'), cutPaper]);
    const result = await printWithFallback(buffer, printerName);
    res.json({ success: true, ...result });
  } catch (e) {
    res.status(500).json({ success: false, error: e.message });
  }
});

app.listen(PORT, () => {
  console.log(`Print bridge running on http://localhost:${PORT}`);
  console.log(`Default printer: ${DEFAULT_PRINTER}`);
});
