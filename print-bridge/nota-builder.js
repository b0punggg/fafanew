const fs = require('fs');
const path = require('path');

// Lebar kertas nota lama (f_jualcetaknota.php pakai 47 karakter)
const LINE_WIDTH = 47;
const LOGO_BIN = path.join(__dirname, 'assets', 'logo-raster.bin');

const DEFAULT_STORE = {
  nmToko: 'Fafa COLLECTION',
  alToko: 'Pasar Pracimantoro',
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
  return formatNumber(val);
}

function amountPositive(val) {
  return parseAmount(val) > 0;
}

function extractDate(tgltime) {
  const raw = String(tgltime || '').trim();
  if (!raw) {
    return '';
  }
  const parts = raw.split(/[\s\/]/);
  return parts[0] || raw;
}

function discPercent(item) {
  const hrg = parseAmount(item.hrg);
  const disc = parseAmount(item.disc);
  if (hrg <= 0 || disc <= 0) {
    return '0';
  }
  return String(Math.round((disc / hrg) * 100));
}

function moneyLine(label, amount) {
  return (
    padRight('', 8) +
    padLeft(label, 15) +
    padRight('', 4) +
    'Rp. ' +
    padLeft(formatAmount(amount), 16)
  );
}

function buildNotaText(data, config) {
  const cfg = config || {};
  const store = Object.assign({}, DEFAULT_STORE, cfg, {
    nmToko: (data && data.nm_toko) || cfg.nmToko || DEFAULT_STORE.nmToko,
    alToko: (data && data.al_toko) || cfg.alToko || DEFAULT_STORE.alToko,
  });
  const includeLogo = cfg.includeLogo === true && loadLogoRaster().length > 0;

  const init = Buffer.from([0x1b, 0x40]);
  const openDrawer = Buffer.from([0x1b, 0x70, 0x30, 0x19, 0xfa]);
  const cutPaper = Buffer.from([0x1d, 0x56, 0x30, 0x00]);

  const lines = [];
  const sep = '-'.repeat(LINE_WIDTH);

  lines.push(center(store.nmToko, LINE_WIDTH));
  lines.push(center(store.alToko, LINE_WIDTH));
  lines.push('');
  lines.push('No.Struk ' + padRight('', 5) + ':' + padRight(data.no_fakjual || '', 20));
  lines.push('Tanggal  ' + padRight('', 5) + ':' + padRight(extractDate(data.tgltime), 10));

  lines.push(sep);
  lines.push('No.' + padRight('', 5) + 'Nama Barang');
  lines.push(
    padRight('', 5) +
    padRight('Jml', 10) +
    padRight('Disc%', 12) +
    padRight('Harga', 11) +
    padRight('SubTotal', 12)
  );
  lines.push(sep);

  (data.items || []).forEach((item, idx) => {
    const no = String(idx + 1);
    lines.push(no + '.' + padRight('', 1) + String(item.nmbrg || ''));

    const qtySat = String(item.qty || 0) + String(item.sat || '').toLowerCase();
    lines.push(
      padRight('', 5) +
      padLeft(String(item.qty || 0), 3) +
      padRight(String(item.sat || '').toLowerCase(), 5) +
      padLeft(discPercent(item), 9) +
      padRight('', 2) +
      padLeft(formatAmount(item.hrg || 0), 9) +
      padRight('', 2) +
      padLeft(formatAmount(item.subtot || 0), 12)
    );
  });

  lines.push(sep);
  lines.push(moneyLine('Total', data.belanja || data.total || 0));

  if (amountPositive(data.disctot)) {
    lines.push(moneyLine('Disc Nota', data.disctot));
  }
  if (amountPositive(data.ongkir)) {
    lines.push(moneyLine('Ongkir', data.ongkir));
  }

  const kdBayar = String(data.kd_bayar || 'TUNAI').toUpperCase();
  if (kdBayar === 'TUNAI') {
    lines.push(moneyLine('Uang Tunai', data.bayar || 0));
    lines.push(moneyLine('Kembali', data.susuk || 0));
  } else {
    lines.push(moneyLine('Uang Tunai', data.bayar || 0));
    lines.push(moneyLine('Kekurangan', data.saldohut || 0));
    lines.push(moneyLine('Jatuh Tempo', data.jtempo || 0));
  }

  lines.push('');
  lines.push(center('BARANG YG.SUDAH DIBELI TDK BISA DIKEMBALIKAN', LINE_WIDTH));
  lines.push(center('*TERIMA KASIH*', LINE_WIDTH));
  lines.push('');
  lines.push('');

  const textBody = lines.join('\n') + '\n\n\n\n\n\n';
  const chunks = [init, openDrawer];

  if (includeLogo) {
    chunks.push(loadLogoRaster());
  }

  chunks.push(Buffer.from(textBody, 'latin1'), cutPaper);
  return Buffer.concat(chunks);
}

module.exports = { buildNotaText, loadLogoRaster, LINE_WIDTH };
