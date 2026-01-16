# Cara Menjalankan create_database.php

Ada beberapa cara untuk menjalankan script `create_database.php`:

## 🚀 Metode 1: Menggunakan File Batch (PALING MUDAH)

**Double-click file:** `RUN_CREATE_DATABASE.bat`

File ini akan otomatis mencari PHP di lokasi umum (XAMPP, WAMP) dan menjalankan script.

---

## 💻 Metode 2: Menggunakan Command Prompt/PowerShell

### Langkah 1: Buka Command Prompt atau PowerShell
- Tekan `Windows + R`
- Ketik `cmd` atau `powershell`
- Tekan Enter

### Langkah 2: Masuk ke Folder Project
```bash
cd D:\PROJEKTBM\digilibTBM
```

### Langkah 3: Jalankan Script

**Jika menggunakan XAMPP:**
```bash
C:\xampp\php\php.exe create_database.php
```

**Jika menggunakan WAMP:**
```bash
C:\wamp64\bin\php\php8.2.0\php.exe create_database.php
```
*(Sesuaikan versi PHP Anda, bisa php8.1.0, php8.3.0, dll)*

**Jika PHP sudah di PATH:**
```bash
php create_database.php
```

---

## 🌐 Metode 3: Menggunakan phpMyAdmin (Manual)

Jika Anda lebih nyaman menggunakan phpMyAdmin:

1. **Buka phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`)

2. **Klik tab "SQL"**

3. **Copy isi file** `migrations/create_database.sql`

4. **Paste ke textarea SQL**

5. **Klik "Go" atau "Execute"**

---

## 🔍 Cara Mengetahui Lokasi PHP

### Jika menggunakan XAMPP:
- Lokasi: `C:\xampp\php\php.exe`
- Atau buka XAMPP Control Panel, klik "Shell", lalu ketik `php -v`

### Jika menggunakan WAMP:
- Lokasi: `C:\wamp64\bin\php\php[versi]\php.exe`
- Atau buka WAMP menu, klik "Tools" > "PHP" > pilih versi

### Jika menggunakan Laragon:
- Lokasi: `C:\laragon\bin\php\php[versi]\php.exe`

---

## ✅ Setelah Script Berjalan

Jika berhasil, Anda akan melihat output seperti ini:

```
========================================
Membuat Database db_digilib_tbm
========================================

Konfigurasi Database:
  Host: localhost
  Database: db_digilib_tbm
  Username: root

1. Membuat database 'db_digilib_tbm'...
   ✓ Database berhasil dibuat/ditemukan

2. Membuat tabel 'user'...
   ✓ Tabel user berhasil dibuat

... (dan seterusnya)

========================================
✓ Database berhasil dibuat dengan lengkap!
========================================
```

---

## ❌ Troubleshooting

### Error: "php is not recognized"
- **Solusi:** Gunakan path lengkap ke php.exe (lihat Metode 2)
- Atau gunakan file `RUN_CREATE_DATABASE.bat`

### Error: "Access denied for user 'root'@'localhost'"
- **Solusi:** Pastikan MySQL sudah berjalan
- Atau edit `config/db.php` dan sesuaikan username/password

### Error: "Could not find driver"
- **Solusi:** Pastikan extension `pdo_mysql` sudah diaktifkan di `php.ini`
- Di XAMPP: Edit `C:\xampp\php\php.ini`, cari `;extension=pdo_mysql` dan hapus tanda `;`

### Error: "Class 'yii\web\Application' not found"
- **Solusi:** Pastikan sudah menjalankan `composer install` terlebih dahulu

---

## 📝 Catatan Penting

- Script ini akan membuat database jika belum ada
- Jika tabel sudah ada, script akan melewatinya (tidak akan error)
- Data sample akan dimasukkan (kategori, rak, user admin)
- User admin default: email `admin@digilib.com`, password `12345`

---

## 🎯 Rekomendasi

**Gunakan Metode 1 (File Batch)** karena paling mudah dan otomatis mencari PHP!

