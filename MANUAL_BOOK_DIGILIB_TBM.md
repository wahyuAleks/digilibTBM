# Manual Book - Sistem digilibTBM

**Versi**: 1.0  
**Framework**: Yii2 PHP Framework

---

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Instalasi & Setup](#instalasi--setup)
3. [Panduan Admin](#panduan-admin)
4. [Panduan Anggota](#panduan-anggota)
5. [Troubleshooting](#troubleshooting)

---

## Pendahuluan

### Tentang digilibTBM

digilibTBM adalah sistem manajemen perpustakaan digital untuk Taman Bacaan Masyarakat. Sistem ini memudahkan pengelolaan koleksi buku, anggota, dan transaksi peminjaman.

### Fitur Utama

- Manajemen Buku (tambah, edit, hapus, cari)
- Katalog Digital dengan pencarian
- Peminjaman Online
- Pengembalian Mandiri
- Sistem Denda Otomatis
- Dashboard untuk Admin dan Member

### Role Pengguna

**Admin**: Full access - bisa kelola semua data (buku, anggota, peminjaman, kategori, rak)

**Member/Anggota**: Akses terbatas - browse katalog, pinjam buku, lihat riwayat

---

## Instalasi & Setup

### Requirements

- PHP 7.4+
- MySQL/MariaDB
- Composer
- Web server atau PHP Built-in Server

### Langkah Install

#### 1. Download Project

```bash
cd d:\PROJEKTBM\digilibTBM
```

#### 2. Install Dependencies

```bash
composer install
```

#### 3. Setup Database

**Cara 1: Pakai phpMyAdmin**

1. Buka phpMyAdmin
2. Buat database baru: `db_digilib_tbm`
3. Import file `SQL_UNTUK_PHPMYADMIN.sql`

**Cara 2: Pakai Script Otomatis**

```bash
.\RUN_CREATE_DATABASE.bat
```

#### 4. Edit Config Database

Edit file `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=db_digilib_tbm',
    'username' => 'root',  // sesuaikan
    'password' => '',      // sesuaikan
    'charset' => 'utf8mb4',
];
```

#### 5. Jalankan Server

```bash
.\START_SERVER.bat
```

atau manual:

```bash
php yii serve --port=8080
```

#### 6. Akses Aplikasi

```
http://localhost:8080
```

### Akun Default

**Admin**
- Email: admin@digilib.com
- Password: 12345

*Note: Ganti password setelah login pertama kali!*

---

## Panduan Admin

### Login Admin

1. Buka halaman utama
2. Klik "Login"
3. Masukkan email dan password admin
4. Akan masuk ke Dashboard Admin

### Dashboard Admin

Dashboard menampilkan:
- Total Buku
- Total Anggota
- Peminjaman Aktif
- Buku Terlambat
- Denda Belum Dibayar

### Kelola Buku

**Lihat Daftar Buku**
- Klik menu "Buku"
- Ada tabel dengan semua data buku

**Tambah Buku**
1. Klik "Tambah Buku"
2. Isi form (Judul, Kategori, Rak, Stok, Thumbnail)
3. Klik "Simpan"

**Edit Buku**
1. Klik icon pensil di buku yang mau diedit
2. Ubah data
3. Klik "Update"

**Hapus Buku**
- Klik icon tempat sampah
- Konfirmasi
- *Note: Buku yang sedang dipinjam ga bisa dihapus*

**Cari Buku**
- Ketik judul/penulis/ISBN di search box

### Kelola Anggota

**Lihat Anggota**
- Klik menu "Anggota"
- Tampil daftar semua member

**Tambah Anggota**
1. Klik "Tambah Anggota"
2. Isi nama, email, password, status
3. Klik "Daftar"

**Edit/Hapus**
- Sama kayak kelola buku, pake icon pensil/tempat sampah

### Kelola Peminjaman

Menu Peminjaman ada 4 tab:

1. **Menunggu Verifikasi** - peminjaman baru yang belum disetujui
2. **Aktif** - peminjaman yang sedang berjalan
3. **Terlambat** - peminjaman lewat jatuh tempo
4. **Riwayat** - semua peminjaman yang udah selesai

**Verifikasi Peminjaman**
1. Buka tab "Menunggu Verifikasi"
2. Review detail peminjaman
3. Klik "Verifikasi & Aktifkan"
4. Status jadi `dipinjam` dan stok berkurang

**Kembalikan Buku (Manual)**
1. Cari di tab "Aktif"
2. Klik "Kembalikan Buku"
3. Sistem auto hitung denda kalau telat
4. Stok buku nambah lagi

### Kelola Kategori

1. Klik menu "Kategori"
2. Klik "Tambah Kategori"
3. Masukkan nama kategori
4. Simpan

*Note: Kategori yang ada bukunya ga bisa dihapus*

### Kelola Rak

1. Klik menu "Rak"
2. Klik "Tambah Rak"
3. Isi nama rak dan lokasi
4. Simpan

---

## Panduan Anggota

### Registrasi

1. Klik "Daftar" di halaman utama
2. Isi form:
   - Nama Lengkap
   - Email
   - Password (min 6 karakter)
   - Konfirmasi Password
3. Klik "Daftar"
4. Login dengan akun baru

### Dashboard Anggota

Dashboard member menampilkan:
- Buku yang sedang dipinjam
- Buku yang mau jatuh tempo
- Total denda (kalau ada)
- Katalog Digital
- Riwayat Peminjaman

### Browse Katalog

1. Scroll ke "Katalog Digital" atau klik menu "Katalog"
2. Buku ditampilkan dalam grid dengan thumbnail
3. Bisa search berdasarkan judul/penulis/ISBN
4. Bisa filter berdasarkan kategori

### Pinjam Buku

1. Cari buku yang diinginkan di katalog
2. Klik "Pinjam Sekarang"
3. Sistem buat peminjaman dengan status `menunggu_verifikasi_admin`
4. Datang ke perpus untuk ambil buku
5. Admin akan verifikasi
6. Status jadi `dipinjam`

**Ketentuan:**
- Durasi: 14 hari
- Denda: Rp 1.000/hari kalau telat

### Lihat Peminjaman Aktif

1. Klik "Peminjaman Saya"
2. Tab "Sedang Dipinjam" menampilkan:
   - Buku yang dipinjam
   - Tanggal pinjam
   - Tanggal jatuh tempo
   - Countdown hari tersisa

### Kembalikan Buku

1. Di tab "Sedang Dipinjam"
2. Klik "Kembalikan Buku"
3. Sistem otomatis:
   - Isi tanggal kembali
   - Hitung denda kalau telat
   - Status jadi `dikembalikan`
4. Kembalikan buku fisik ke perpus dan bayar denda (kalau ada)

**Perhitungan Denda:**
```
Denda = (Tanggal Kembali - Tanggal Jatuh Tempo) × Rp 1.000
```

Contoh:
- Jatuh Tempo: 1 Jan 2026
- Kembali: 6 Jan 2026
- Telat: 5 hari
- Denda: 5 × Rp 1.000 = Rp 5.000

### Update Profil

1. Klik menu "Profil"
2. Bisa ubah nama, email, password
3. Klik "Update Profil"

---

## Troubleshooting

### Server tidak bisa diakses

**Masalah**: Connection Refused

**Solusi**:
```bash
.\START_SERVER.bat
```
atau cek apakah port 8080 sudah dipakai aplikasi lain

### Database Connection Failed

**Masalah**: Koneksi database gagal

**Solusi**:
1. Cek MySQL/XAMPP sudah running
2. Cek kredensial di `config/db.php`
3. Pastikan database `db_digilib_tbm` sudah ada

### Email sudah terdaftar

**Masalah**: Duplicate entry for key 'email'

**Solusi**: Pakai email yang lain atau hapus user lama

### Forbidden Access

**Masalah**: Not allowed to access this page

**Solusi**:
- Pastikan login dengan role yang sesuai
- Fitur tertentu hanya untuk admin
- Coba logout dan login ulang

### Buku ga bisa dipinjam

**Kemungkinan**:
1. Stok habis
2. Udah punya peminjaman aktif yang belum dikembalikan
3. Akun belum diverifikasi admin

### Lupa Password

**Untuk Anggota**: Hubungi admin

**Untuk Admin**: 
1. Akses database via phpMyAdmin
2. Update password di tabel `user`
3. Login dengan password baru

---

## FAQ

**Q: Berapa lama durasi peminjaman?**
A: Default 14 hari kalender

**Q: Bisa perpanjang peminjaman?**
A: Hubungi admin untuk perpanjangan manual

**Q: Cara bayar denda?**
A: Tunai di perpustakaan saat kembalikan buku

**Q: Bisa pinjam lebih dari 1 buku?**
A: Bisa, pinjam satu per satu lewat katalog

**Q: Kenapa peminjaman masih "Menunggu Verifikasi"?**
A: Harus datang ke perpus untuk ambil buku, nanti admin verifikasi

**Q: Cara backup database?**
A: Via phpMyAdmin → Export → pilih format SQL

**Q: Cara restore database?**
A: Via phpMyAdmin → Import → pilih file backup SQL

---

## Struktur Folder

```
digilibTBM/
├── assets/         # Asset management
├── commands/       # Console commands
├── config/         # Konfigurasi aplikasi
├── controllers/    # Controllers
├── models/         # Models
├── runtime/        # File runtime (logs, cache)
├── scripts/        # Utility scripts
├── views/          # View templates
├── web/            # Public files
└── migrations/     # Database migrations
```

---

**Catatan**: Dokumentasi ini dibuat untuk memudahkan penggunaan sistem digilibTBM. Kalau ada yang kurang jelas, silakan hubungi developer.
