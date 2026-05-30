-- SQL untuk membuat tabel member
-- Struktur tabel member mengikuti struktur tabel pelanggan

CREATE TABLE IF NOT EXISTS `member` (
  `no_urut` int(11) NOT NULL AUTO_INCREMENT,
  `kd_member` varchar(50) NOT NULL,
  `nm_member` varchar(255) NOT NULL,
  `nm_toko` varchar(255) DEFAULT NULL,
  `al_member` text NOT NULL,
  `no_telp` varchar(50) NOT NULL,
  `poin` decimal(15,2) DEFAULT 0.00,
  `tgl_daftar` date DEFAULT NULL,
  `kd_toko` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`no_urut`),
  KEY `idx_member_kdtoko` (`kd_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update tabel member yang sudah ada untuk menambahkan field poin
ALTER TABLE `member` ADD COLUMN IF NOT EXISTS `poin` decimal(15,2) DEFAULT 0.00 AFTER `no_telp`;

-- Tanggal member terdaftar / ditambahkan (isi otomatis saat insert)
ALTER TABLE `member` ADD COLUMN IF NOT EXISTS `tgl_daftar` date DEFAULT NULL AFTER `poin`;

-- Informasi toko asal member (untuk otorisasi per toko)
ALTER TABLE `member` ADD COLUMN IF NOT EXISTS `nm_toko` varchar(255) DEFAULT NULL AFTER `nm_member`;
ALTER TABLE `member` ADD COLUMN IF NOT EXISTS `kd_toko` varchar(50) DEFAULT NULL AFTER `tgl_daftar`;
ALTER TABLE `member` ADD INDEX IF NOT EXISTS `idx_member_kdtoko` (`kd_toko`);

-- Sinkronisasi data lama member agar terikat ke toko (WAJIB untuk filter otoritas)
-- 1) Isi kd_toko dari tabel toko berdasarkan kemiripan nama toko (case-insensitive)
UPDATE `member` m
JOIN `toko` t ON UPPER(TRIM(m.nm_toko)) = UPPER(TRIM(t.nm_toko))
SET m.kd_toko = t.kd_toko
WHERE (m.kd_toko IS NULL OR m.kd_toko = '')
  AND m.nm_toko IS NOT NULL
  AND TRIM(m.nm_toko) <> '';

-- 2) Fallback: isi kd_toko dari histori transaksi penjualan member
UPDATE `member` m
JOIN (
  SELECT kd_member, MAX(kd_toko) AS kd_toko
  FROM mas_jual
  WHERE kd_member IS NOT NULL AND kd_member <> '' AND kd_toko IS NOT NULL AND kd_toko <> ''
  GROUP BY kd_member
) x ON x.kd_member = m.kd_member
SET m.kd_toko = x.kd_toko
WHERE (m.kd_toko IS NULL OR m.kd_toko = '');

-- 3) Rapikan nm_toko agar konsisten dengan master toko
UPDATE `member` m
JOIN `toko` t ON m.kd_toko = t.kd_toko
SET m.nm_toko = t.nm_toko
WHERE (m.nm_toko IS NULL OR m.nm_toko = '' OR UPPER(TRIM(m.nm_toko)) <> UPPER(TRIM(t.nm_toko)));

-- Update tabel mas_jual untuk menambahkan field member dan poin
ALTER TABLE `mas_jual` ADD COLUMN IF NOT EXISTS `kd_member` varchar(50) DEFAULT '' AFTER `kd_pel`;
ALTER TABLE `mas_jual` ADD COLUMN IF NOT EXISTS `poin_earned` decimal(15,2) DEFAULT 0.00 AFTER `kd_member`;

-- Buat tabel riwayat poin member
CREATE TABLE IF NOT EXISTS `member_poin_history` (
  `no_urut` int(11) NOT NULL AUTO_INCREMENT,
  `kd_member` varchar(50) NOT NULL,
  `no_fakjual` varchar(50) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `poin_masuk` decimal(15,2) DEFAULT 0.00,
  `poin_keluar` decimal(15,2) DEFAULT 0.00,
  `poin_saldo` decimal(15,2) DEFAULT 0.00,
  `keterangan` text,
  `kd_toko` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_urut`),
  KEY `idx_kd_member` (`kd_member`),
  KEY `idx_no_fakjual` (`no_fakjual`),
  KEY `idx_tgl_transaksi` (`tgl_transaksi`),
  KEY `idx_kd_toko` (`kd_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

