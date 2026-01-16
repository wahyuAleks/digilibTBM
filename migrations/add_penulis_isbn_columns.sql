-- ============================================
-- ENHANCEMENT: Add Penulis & ISBN to Buku Table
-- Safe to run - uses ALTER TABLE ADD COLUMN IF NOT EXISTS
-- ============================================

USE `db_digilib_tbm`;

-- Add penulis (author) column
ALTER TABLE `buku` 
ADD COLUMN IF NOT EXISTS `penulis` VARCHAR(255) NULL AFTER `judul`;

-- Add ISBN column  
ALTER TABLE `buku`
ADD COLUMN IF NOT EXISTS `isbn` VARCHAR(50) NULL AFTER `penulis`;

-- Verify columns were added
DESCRIBE `buku`;
