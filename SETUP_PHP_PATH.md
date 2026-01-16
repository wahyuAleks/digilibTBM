# Cara Menambahkan PHP ke PATH Windows

## Metode 1: Menggunakan XAMPP

1. **Buka System Properties:**
   - Tekan `Windows + Pause/Break`
   - Atau klik kanan "This PC" > Properties
   - Klik "Advanced system settings"

2. **Buka Environment Variables:**
   - Klik tombol "Environment Variables"

3. **Edit PATH:**
   - Di bagian "System variables", cari variabel `Path`
   - Klik "Edit"
   - Klik "New"
   - Tambahkan: `C:\xampp\php`
   - Klik "OK" di semua jendela

4. **Restart PowerShell/Command Prompt**

5. **Test:**
   ```powershell
   php -v
   ```

## Metode 2: Menggunakan Path Lengkap (Tidak Perlu Setup PATH)

Jika tidak ingin mengubah PATH, gunakan path lengkap:

**Untuk XAMPP:**
```powershell
C:\xampp\php\php.exe create_database.php
```

**Untuk WAMP:**
```powershell
C:\wamp64\bin\php\php8.2.0\php.exe create_database.php
```
*(Sesuaikan versi PHP Anda)*

## Metode 3: Menggunakan File Batch

File `RUN_CREATE_DATABASE.bat` sudah otomatis mencari PHP di lokasi umum. 
Cukup double-click file tersebut.

