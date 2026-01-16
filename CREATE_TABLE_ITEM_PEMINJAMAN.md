# Instruksi Membuat Tabel item_peminjaman

Tabel `item_peminjaman` diperlukan untuk menghubungkan peminjaman dengan buku yang dipinjam.

## Cara 1: Menggunakan SQL langsung

Jalankan SQL berikut di database Anda:

```sql
CREATE TABLE IF NOT EXISTS `item_peminjaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peminjamanID` int(11) NOT NULL,
  `bukuID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_peminjamanID` (`peminjamanID`),
  KEY `idx_bukuID` (`bukuID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Catatan:** 
- Jika primary key tabel `peminjaman` bukan `id`, ganti `peminjamanID` dengan nama kolom yang sesuai
- Jika primary key tabel `buku` bukan `bukuID`, ganti `bukuID` dengan nama kolom yang sesuai

## Cara 2: Menggunakan phpMyAdmin atau tool database lainnya

1. Buka phpMyAdmin atau tool database Anda
2. Pilih database `db_digilib_tbm`
3. Klik tab "SQL"
4. Copy dan paste SQL di atas
5. Klik "Go" atau "Execute"

## Struktur Tabel

- `id`: Primary key (auto increment)
- `peminjamanID`: Foreign key ke tabel `peminjaman`
- `bukuID`: Foreign key ke tabel `buku`

Setelah tabel dibuat, aplikasi akan berfungsi dengan normal.

