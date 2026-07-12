const fs = require('fs');
const path = require('path');

const LINE_WIDTH = 48;
const LOGO_BIN = path.join(__dirname, 'assets', 'logo-raster.bin');

const DEFAULT_STORE = {
  nmToko: 'Fafa COLLECTION',
  alToko: 'Pasar Pracimantoro',
  footerLine1: 'Fashion, Hijab, Kosmetik, Aksesoris, Sepatu',
  footerLine2: 'Sandal, Tas, Dompet, Alat Tulis, dll',
};

let cachedLogo = null;

function loadLogoRaster() {
  if (cachedLogo !== null) {
    return cachedLogo;
  }
  if (fs.existsSync(LOGO_BIN)) {
    cachedLogo = fs.readFileSync(LOGO_BIN);
    return cachedLogo;
  }
  cachedLogo = Buffer.alloc(0);
  return cachedLogo;
}

function stripLeadingInit(buf) {
  let start = 0;
  while (start < buf.length && buf[start] === 0x1b) {
    if (start + 1 < buf.length && buf[start + 1] === 0x40) {
      start += 2;
      continue;
    }
    if (start + 2 < buf.length && buf[start + 1] === 0x61) {
      start += 3;
      continue;
    }
    break;
  }
  return start > 0 ? buf.slice(start) : buf;
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
  if (str.length >= width) {
    return str.substring(0, width);
  }
  const left = Math.floor((width - str.length) / 2);
  return ' '.repeat(left) + str + ' '.repeat(width - str.length - left);
}

function parseAmount(val) {
  if (val === null || val === undefined || val === '') {
    return 0;
  }
  if (typeof val === 'number') {
    return Math.round(val);
  }
  const cleaned = String(val).replace(/[^\d-]/g, '');
  return Math.round(Number(cleaned) || 0);
}

function formatNumber(n) {
  return String(parseAmount(n)).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatAmount(val) {
  const num = parseAmount(val);
  if (num === 0 && (val === 0 || val === '0' || val === '0.0' || val === '0,0')) {
    return '0';
  }
  return formatNumber(num);
}

function amountPositive(val) {
  return parseAmount(val) > 0;
}

function moneyLine(label, amount) {
  return (
    padRight('', 16) +
    padLeft(label, 12) +
    padRight('', 2) +
    'Rp.' +
    padRight('', 2) +
    padLeft(formatAmount(amount), 13)
  );
}

function buildNotaText(data, config) {
  const cfg = config || {};
  const store = Object.assign({}, DEFAULT_STORE, cfg, {
    nmToko: (data && data.nm_toko) || cfg.nmToko || DEFAULT_STORE.nmToko,
    alToko: (data && data.al_toko) || cfg.alToko || DEFAULT_STORE.alToko,
  });
  const includeLogo = cfg.includeLogo !== false && loadLogoRaster().length > 0;

  const init = Buffer.from([0x1b, 0x40]);
  const openDrawer = Buffer.from([0x1b, 0x70, 0x30, 0x19, 0xfa]);
  const cutPaper = Buffer.from([0x1d, 0x56, 0x30, 0x00]);
  const alignCenter = Buffer.from([0x1b, 0x61, 0x01]);
  const alignLeft = Buffer.from([0x1b, 0x61, 0x00]);

  const lines = [];
  const sep = '-'.repeat(LINE_WIDTH);

  if (!includeLogo) {
    lines.push(center(store.nmToko, LINE_WIDTH));
  }
  lines.push(center(store.alToko, LINE_WIDTH));
  lines.push('');
  lines.push('Struk   : ' + padRight(data.no_fakjual || '', LINE_WIDTH - 10));
  lines.push('Tanggal : ' + padRight(data.tgltime || '', LINE_WIDTH - 10));
  if (data.nm_pel) {
    lines.push('Pembeli : ' + padRight(data.nm_pel, LINE_WIDTH - 10));
  }
  if (data.alamat && String(data.alamat).trim() !== '' && String(data.alamat).trim() !== '-') {
    lines.push('Alamat  : ' + padRight(data.alamat, LINE_WIDTH - 10));
  }

  lines.push(sep);
  lines.push('No.  Nama Barang' + padLeft('SubTotal', LINE_WIDTH - 16));
  lines.push(padRight('', 5) + 'Jml' + padRight('', 6) + 'Harga' + padRight('', 4) + 'Disc');
  lines.push(sep);

  (data.items || []).forEach((item, idx) => {
    const no = String(idx + 1) + '.';
    const subtot = formatAmount(item.subtot || 0);
    const name = String(item.nmbrg || '');
    const nameWidth = Math.max(4, LINE_WIDTH - 4 - subtot.length);
    lines.push(padRight(no, 4) + padRight(name, nameWidth) + subtot);

    const qtySat = String(item.qty || 0) + String(item.sat || '').toLowerCase();
    lines.push(
      padRight('', 4) +
      padRight(qtySat, 10) +
      ' ' +
      padLeft(formatAmount(item.hrg || 0), 12) +
      ' ' +
      padLeft(formatAmount(item.disc || 0), 7)
    );
  });

  lines.push(sep);
  lines.push(moneyLine('Total', data.total || data.belanja || 0));

  if (amountPositive(data.disctot)) {
    lines.push(moneyLine('Disc Nota', data.disctot));
  }
  if (amountPositive(data.voucher)) {
    lines.push(moneyLine('Voucher', data.voucher));
  }
  if (amountPositive(data.ongkir)) {
    lines.push(moneyLine('Ongkir', data.ongkir));
  }

  const kdBayar = String(data.kd_bayar || 'TUNAI').toUpperCase();
  if (kdBayar === 'TUNAI') {
    lines.push(moneyLine('Uang Tunai', data.bayar || 0));
    lines.push(moneyLine('Kembalian', data.susuk || 0));
  } else {
    lines.push(moneyLine('Uang Tunai', data.bayar || 0));
    lines.push(moneyLine('Kekurangan', data.saldohut || 0));
    lines.push(
      padRight('', 16) +
      padLeft('Jth.Tempo', 12) +
      padRight('', 2) +
      ':' +
      padRight('', 2) +
      padLeft(String(data.jtempo || ''), 13)
    );
  }

  lines.push('');
  lines.push(center(store.footerLine1, LINE_WIDTH));
  lines.push(center(store.footerLine2, LINE_WIDTH));
  lines.push('');
  lines.push(center('*TERIMA KASIH ATAS KUNJUNGANNYA*', LINE_WIDTH));
  lines.push('');
  lines.push('');

  const textBody = lines.join('\n') + '\n\n\n';
  const chunks = [init];

  if (includeLogo) {
    chunks.push(alignCenter, stripLeadingInit(loadLogoRaster()), alignLeft);
  }

  chunks.push(Buffer.from(textBody, 'latin1'), cutPaper, openDrawer);
  return Buffer.concat(chunks);
}

module.exports = { buildNotaText, loadLogoRaster, LINE_WIDTH };
