-- ============================================
-- COPY SEMUA KODE DI BAWAH INI DAN PASTE KE PHPMYADMIN
-- ============================================

-- Buat database
CREATE DATABASE IF NOT EXISTS `db_digilib_tbm` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `db_digilib_tbm`;

-- Tabel USER
CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tipe_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel ANGGOTA
CREATE TABLE IF NOT EXISTS `anggota` (
  `anggotaID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`anggotaID`),
  CONSTRAINT `fk_anggota_user` FOREIGN KEY (`anggotaID`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel KATEGORI
CREATE TABLE IF NOT EXISTS `kategori` (
  `kategoriID` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`kategoriID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel RAK
CREATE TABLE IF NOT EXISTS `rak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel BUKU
CREATE TABLE IF NOT EXISTS `buku` (
  `bukuID` int(11) NOT NULL AUTO_INCREMENT,
  `kategoriID` int(11) NOT NULL,
  `rakID` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `stok` int(11) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bukuID`),
  KEY `idx_kategoriID` (`kategoriID`),
  KEY `idx_rakID` (`rakID`),
  CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`kategoriID`) REFERENCES `kategori` (`kategoriID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_buku_rak` FOREIGN KEY (`rakID`) REFERENCES `rak` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel PEMINJAMAN
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anggotaID` int(11) NOT NULL,
  `tanggalPinjam` datetime DEFAULT NULL,
  `tanggalKembali` datetime DEFAULT NULL,
  `tglJatuhTempo` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_anggotaID` (`anggotaID`),
  CONSTRAINT `fk_peminjaman_anggota` FOREIGN KEY (`anggotaID`) REFERENCES `anggota` (`anggotaID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel ITEM_PEMINJAMAN
CREATE TABLE IF NOT EXISTS `item_peminjaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peminjamanID` int(11) NOT NULL,
  `bukuID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_peminjamanID` (`peminjamanID`),
  KEY `idx_bukuID` (`bukuID`),
  CONSTRAINT `fk_item_peminjaman_peminjaman` FOREIGN KEY (`peminjamanID`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_item_peminjaman_buku` FOREIGN KEY (`bukuID`) REFERENCES `buku` (`bukuID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel DENDA
CREATE TABLE IF NOT EXISTS `denda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peminjamanID` int(11) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `hariTerlambat` int(11) NOT NULL,
  `tanggalDibuat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_peminjamanID` (`peminjamanID`),
  CONSTRAINT `fk_denda_peminjaman` FOREIGN KEY (`peminjamanID`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Sample Kategori
INSERT INTO `kategori` (`kategoriID`, `nama`) VALUES
(1, 'Fiksi'),
(2, 'Non-Fiksi'),
(3, 'Pendidikan'),
(4, 'Teknologi')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

-- Data Sample Rak
INSERT INTO `rak` (`id`, `nama`, `lokasi`) VALUES
(1, 'Rak A', 'Lantai 1'),
(2, 'Rak B', 'Lantai 1'),
(3, 'Rak C', 'Lantai 2')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `lokasi` = VALUES(`lokasi`);

-- Data Sample User Admin (password: 12345)
INSERT INTO `user` (`userid`, `nama`, `email`, `passwordHash`, `status`, `tipe_user`) VALUES
(1, 'Administrator', 'admin@digilib.com', '12345', 'aktif', 'admin')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `email` = VALUES(`email`);

