-- Migration untuk membuat tabel item_peminjaman
-- Tabel ini menghubungkan peminjaman dengan buku yang dipinjam

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

