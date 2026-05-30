-- SQL untuk membuat tabel persediaan_bulan
-- Jalankan query ini di database MySQL

CREATE TABLE IF NOT EXISTS `persediaan_bulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_brg` varchar(50) NOT NULL,
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `stok_juals` decimal(15,2) DEFAULT 0.00,
  `hrg_beli` decimal(15,2) DEFAULT 0.00,
  `nilai_persediaan` decimal(15,2) DEFAULT 0.00,
  `kd_sup` varchar(50) DEFAULT NULL,
  `id_bag` int(11) DEFAULT NULL,
  `kd_toko` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_persediaan` (`kd_brg`, `bulan`, `tahun`, `kd_toko`),
  KEY `idx_kd_brg` (`kd_brg`),
  KEY `idx_bulan_tahun` (`bulan`, `tahun`),
  KEY `idx_kd_toko` (`kd_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

