# Setup Database - digilibTBM

Dokumentasi cara setup database untuk aplikasi digilibTBM.

## Cara 1: Pakai phpMyAdmin (Paling Mudah)

1. Buka XAMPP/WAMP, start MySQL
2. Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`)
3. Klik "New" untuk buat database baru
4. Nama database: `db_digilib_tbm`
5. Collation: `utf8mb4_general_ci`
6. Klik "Create"
7. Pilih database yang baru dibuat
8. Klik tab "SQL"
9. Buka file `SQL_UNTUK_PHPMYADMIN.sql` di folder project
10. Copy semua isi file
11. Paste di box SQL
12. Klik "Go"
13. Done!

## Cara 2: Pakai Script Otomatis (Recommended)

Lebih gampang pakai script yang udah disediain:

```bash
.\RUN_CREATE_DATABASE.bat
```

Script ini akan otomatis:
- Cek koneksi database
- Buat database `db_digilib_tbm`
- Buat semua tabel
- Insert data default (admin, kategori)

## Cara 3: Manual via Command Line

Kalau mau manual lewat terminal:

```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE db_digilib_tbm;

# Gunakan database
USE db_digilib_tbm;

# Import file SQL
SOURCE d:/PROJEKTBM/digilibTBM/migrations/create_database.sql;
```

## Struktur Tabel

Database punya 8 tabel:

1. **user** - Data user (admin & anggota)
2. **anggota** - Data anggota perpustakaan
3. **buku** - Data koleksi buku
4. **kategori** - Kategori buku (Fiksi, Non-Fiksi, dll)
5. **rak** - Data rak penyimpanan
6. **peminjaman** - Transaksi peminjaman
7. **item_peminjaman** - Detail buku yang dipinjam
8. **denda** - Data denda keterlambatan

## Data Default

Setelah setup, database terisi:

**User Admin:**
- Email: admin@digilib.com
- Password: 12345
- Tipe: admin

**Kategori:**
1. Fiksi
2. Non-Fiksi
3. Pendidikan
4. Teknologi

## Troubleshooting

### Error: Access denied for user 'root'

Berarti password MySQL salah. Cek password di config atau reset password MySQL.

### Error: Database already exists

Database udah ada. Bisa:
1. Drop database lama: `DROP DATABASE db_digilib_tbm;`
2. Atau pakai database yang ada

### Error: PHP not found

Path PHP belum di-set. Lihat `SETUP_PHP_PATH.md` untuk cara setting.

## Konfigurasi Database

Setelah setup, jangan lupa edit `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=db_digilib_tbm',
    'username' => 'root',     // sesuaikan
    'password' => '',         // sesuaikan
    'charset' => 'utf8mb4',
];
```

## Backup Database

Untuk backup database:

**Via phpMyAdmin:**
1. Pilih database `db_digilib_tbm`
2. Klik tab "Export"
3. Pilih format SQL
4. Klik "Go"
5. File SQL akan ke-download

**Via Command Line:**
```bash
mysqldump -u root -p db_digilib_tbm > backup.sql
```

## Restore Database

Kalau mau restore dari backup:

```bash
mysql -u root -p db_digilib_tbm < backup.sql
```
