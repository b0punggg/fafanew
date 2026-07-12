/**
 * Buat sertifikat self-signed untuk https://localhost (sekali saja).
 */
const fs = require('fs');
const path = require('path');

const SSL_DIR = path.join(__dirname, 'ssl');
const CERT_PATH = path.join(SSL_DIR, 'cert.pem');
const KEY_PATH = path.join(SSL_DIR, 'key.pem');

function ensureSslCerts() {
  if (fs.existsSync(CERT_PATH) && fs.existsSync(KEY_PATH)) {
    return { certPath: CERT_PATH, keyPath: KEY_PATH };
  }

  let selfsigned;
  try {
    selfsigned = require('selfsigned');
  } catch (e) {
    throw new Error('Paket selfsigned belum terinstall. Jalankan: npm install');
  }

  if (!fs.existsSync(SSL_DIR)) {
    fs.mkdirSync(SSL_DIR);
  }

  const attrs = [{ name: 'commonName', value: 'localhost' }];
  const pems = selfsigned.generate(attrs, {
    days: 3650,
    keySize: 2048,
    algorithm: 'sha256',
    extensions: [
      {
        name: 'subjectAltName',
        altNames: [
          { type: 2, value: 'localhost' },
          { type: 7, ip: '127.0.0.1' },
        ],
      },
    ],
  });

  fs.writeFileSync(CERT_PATH, pems.cert);
  fs.writeFileSync(KEY_PATH, pems.private);
  console.log('Sertifikat SSL dibuat di folder ssl/');
  return { certPath: CERT_PATH, keyPath: KEY_PATH };
}

if (require.main === module) {
  ensureSslCerts();
}

module.exports = { ensureSslCerts, CERT_PATH, KEY_PATH };
