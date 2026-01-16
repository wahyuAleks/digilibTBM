# Panduan Membuat Ulang Database

Database `db_digilib_tbm` telah terhapus. Gunakan salah satu metode di bawah ini untuk membuat ulang database.

## Metode 1: Menggunakan Script PHP (Direkomendasikan)

Cara termudah adalah menggunakan script PHP yang sudah disediakan:

```bash
php create_database.php
```

Script ini akan:
- Membuat database `db_digilib_tbm` jika belum ada
- Membuat semua tabel yang diperlukan
- Memasukkan data sample (kategori, rak, dan user admin)

**User Admin Default:**
- Email: `admin@digilib.com`
- Password: `12345`

## Metode 2: Menggunakan SQL File

Jika Anda lebih suka menggunakan SQL langsung:

1. Buka phpMyAdmin atau tool database lainnya
2. Login ke MySQL dengan user `root` (tanpa password sesuai konfigurasi)
3. Buka tab "SQL"
4. Copy dan paste isi file `migrations/create_database.sql`
5. Klik "Go" atau "Execute"

Atau menggunakan command line MySQL:

```bash
mysql -u root < migrations/create_database.sql
```

## Struktur Database

Database ini terdiri dari 8 tabel:

1. **user** - Data akun pengguna
   - userid (PK)
   - nama
   - email (unique)
   - passwordHash
   - status
   - tipe_user

2. **anggota** - Data anggota perpustakaan
   - anggotaID (PK, FK ke user.userid)

3. **kategori** - Kategori buku
   - kategoriID (PK)
   - nama

4. **rak** - Rak penyimpanan buku
   - id (PK)
   - nama
   - lokasi

5. **buku** - Data buku
   - bukuID (PK)
   - kategoriID (FK)
   - rakID (FK)
   - judul
   - stok

6. **peminjaman** - Transaksi peminjaman
   - id (PK)
   - anggotaID (FK)
   - tanggalPinjam
   - tanggalKembali
   - tglJatuhTempo
   - status

7. **item_peminjaman** - Detail buku yang dipinjam
   - id (PK)
   - peminjamanID (FK)
   - bukuID (FK)

8. **denda** - Data denda
   - id (PK)
   - peminjamanID (FK)
   - jumlah
   - hariTerlambat
   - tanggalDibuat

## Konfigurasi Database

Pastikan konfigurasi di `config/db.php` sudah benar:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=db_digilib_tbm',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

## Troubleshooting

### Error: Access denied
- Pastikan MySQL service sudah berjalan
- Pastikan username dan password di `config/db.php` benar

### Error: Database already exists
- Tidak masalah, script akan membuat tabel jika belum ada
- Jika ingin menghapus database lama, hapus manual dulu di phpMyAdmin

### Error: Foreign key constraint fails
- Pastikan tabel dibuat dalam urutan yang benar
- Script PHP sudah mengatur urutan pembuatan tabel dengan benar

## Setelah Database Dibuat

1. Pastikan aplikasi dapat mengakses database
2. Login dengan user admin default (email: `admin@digilib.com`, password: `12345`)
3. Mulai menambahkan data sesuai kebutuhan

