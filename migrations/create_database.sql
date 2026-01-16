-- Script untuk membuat ulang database db_digilib_tbm
-- Database: db_digilib_tbm
-- Charset: utf8mb4
-- Collation: utf8mb4_unicode_ci

-- Hapus database jika sudah ada (HATI-HATI: ini akan menghapus semua data!)
-- DROP DATABASE IF EXISTS `db_digilib_tbm`;

-- Buat database baru
CREATE DATABASE IF NOT EXISTS `db_digilib_tbm` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `db_digilib_tbm`;

-- ============================================
-- TABEL USER (Akun pengguna)
-- ============================================
CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tipe_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL ANGGOTA (Anggota perpustakaan)
-- ============================================
CREATE TABLE IF NOT EXISTS `anggota` (
  `anggotaID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`anggotaID`),
  CONSTRAINT `fk_anggota_user` FOREIGN KEY (`anggotaID`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL KATEGORI (Kategori buku)
-- ============================================
CREATE TABLE IF NOT EXISTS `kategori` (
  `kategoriID` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`kategoriID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL RAK (Rak penyimpanan buku)
-- ============================================
CREATE TABLE IF NOT EXISTS `rak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL BUKU (Data buku)
-- ============================================
CREATE TABLE IF NOT EXISTS `buku` (
  `bukuID` int(11) NOT NULL AUTO_INCREMENT,
  `kategoriID` int(11) NOT NULL,
  `rakID` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `stok` int(11) DEFAULT NULL,
  PRIMARY KEY (`bukuID`),
  KEY `idx_kategoriID` (`kategoriID`),
  KEY `idx_rakID` (`rakID`),
  CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`kategoriID`) REFERENCES `kategori` (`kategoriID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_buku_rak` FOREIGN KEY (`rakID`) REFERENCES `rak` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL PEMINJAMAN (Transaksi peminjaman)
-- ============================================
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

-- ============================================
-- TABEL ITEM_PEMINJAMAN (Detail buku yang dipinjam)
-- ============================================
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

-- ============================================
-- TABEL DENDA (Data denda)
-- ============================================
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

-- ============================================
-- DATA SAMPLE (Opsional - untuk testing)
-- ============================================

-- Insert sample kategori
INSERT INTO `kategori` (`kategoriID`, `nama`) VALUES
(1, 'Fiksi'),
(2, 'Non-Fiksi'),
(3, 'Pendidikan'),
(4, 'Teknologi')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

-- Insert sample rak
INSERT INTO `rak` (`id`, `nama`, `lokasi`) VALUES
(1, 'Rak A', 'Lantai 1'),
(2, 'Rak B', 'Lantai 1'),
(3, 'Rak C', 'Lantai 2')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `lokasi` = VALUES(`lokasi`);

-- Insert sample user admin (password: 12345)
INSERT INTO `user` (`userid`, `nama`, `email`, `passwordHash`, `status`, `tipe_user`) VALUES
(1, 'Administrator', 'admin@digilib.com', '12345', 'aktif', 'admin')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `email` = VALUES(`email`);

-- ============================================
-- SAMPLE BUKU (Tambahan untuk testing filter kategori)
-- 10 buku per kategori agar filter terlihat
-- ============================================
INSERT INTO `buku` (`kategoriID`, `rakID`, `judul`, `stok`) VALUES
(1,1,'Fiksi: Petualangan di Pulau A1',5),
(1,2,'Fiksi: Kisah Malam Hari A2',3),
(1,3,'Fiksi: Misteri Kota A3',4),
(1,1,'Fiksi: Cahaya di Ujung Jalan A4',6),
(1,2,'Fiksi: Rahasia Kamar Lama A5',2),
(1,3,'Fiksi: Langit dan Laut A6',7),
(1,1,'Fiksi: Jejak Sang Penulis A7',5),
(1,2,'Fiksi: Senja di Desa A8',3),
(1,3,'Fiksi: Sang Penjelajah A9',4),
(1,1,'Fiksi: Malam Tanpa Bintang A10',6),

(2,1,'Non-Fiksi: Sejarah Bangsa B1',5),
(2,2,'Non-Fiksi: Biografi Tokoh B2',4),
(2,3,'Non-Fiksi: Panduan Hidup B3',6),
(2,1,'Non-Fiksi: Kumpulan Esai B4',2),
(2,2,'Non-Fiksi: Sains untuk Semua B5',3),
(2,3,'Non-Fiksi: Ekonomi Praktis B6',5),
(2,1,'Non-Fiksi: Politik dan Masyarakat B7',4),
(2,2,'Non-Fiksi: Seni & Budaya B8',6),
(2,3,'Non-Fiksi: Kesehatan Modern B9',5),
(2,1,'Non-Fiksi: Teknik Menulis B10',3),

(3,1,'Pendidikan: Matematika Dasar C1',10),
(3,2,'Pendidikan: Bahasa Indonesia C2',8),
(3,3,'Pendidikan: Fisika SMA C3',7),
(3,1,'Pendidikan: Kimia Dasar C4',9),
(3,2,'Pendidikan: Sejarah Dunia C5',6),
(3,3,'Pendidikan: Biologi C6',8),
(3,1,'Pendidikan: Metodologi Penelitian C7',5),
(3,2,'Pendidikan: Statistik Terapan C8',4),
(3,3,'Pendidikan: Pendidikan Karakter C9',6),
(3,1,'Pendidikan: Pengantar Ekonomi C10',5),

(4,1,'Teknologi: Pemrograman PHP D1',12),
(4,2,'Teknologi: Pengantar Web D2',10),
(4,3,'Teknologi: Jaringan Komputer D3',8),
(4,1,'Teknologi: Sistem Operasi D4',7),
(4,2,'Teknologi: Basis Data D5',9),
(4,3,'Teknologi: Keamanan Siber D6',6),
(4,1,'Teknologi: AI & Machine Learning D7',5),
(4,2,'Teknologi: Cloud Computing D8',8),
(4,3,'Teknologi: DevOps Praktis D9',7),
(4,1,'Teknologi: Rekayasa Perangkat Lunak D10',11);


