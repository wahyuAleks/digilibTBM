---
description: Upload project ke GitHub
---

# Upload Project ke GitHub

Workflow ini menjelaskan cara mengupload project digilibTBM ke GitHub.

## Prasyarat
- Sudah memiliki akun GitHub
- Git sudah terinstall di komputer

## Langkah-langkah

### 1. Inisialisasi Git Repository Lokal

Buka terminal di folder project (`d:\PROJEKTBM\digilibTBM`) dan jalankan:

```bash
git init
```

### 2. Add dan Commit File

```bash
git add .
git commit -m "Initial commit: DigiLib TBM application"
```

### 3. Buat Repository di GitHub

1. Buka [github.com](https://github.com) dan login
2. Klik tombol **"New"** atau **"+"** di pojok kanan atas
3. Pilih **"New repository"**
4. Isi detail repository:
   - **Repository name**: `digilibTBM` (atau nama lain yang diinginkan)
   - **Description**: "Aplikasi Digital Library Taman Bacaan Masyarakat"
   - **Visibility**: Pilih Public atau Private
   - **JANGAN centang** "Initialize this repository with a README"
5. Klik **"Create repository"**

### 4. Hubungkan Local Repository ke GitHub

Setelah repository dibuat, GitHub akan menampilkan instruksi. Jalankan perintah berikut di terminal (ganti `USERNAME` dengan username GitHub Anda dan `REPO_NAME` dengan nama repository):

```bash
git remote add origin https://github.com/USERNAME/REPO_NAME.git
git branch -M main
git push -u origin main
```

**Contoh:**
```bash
git remote add origin https://github.com/johndoe/digilibTBM.git
git branch -M main
git push -u origin main
```

### 5. Masukkan Kredensial GitHub

Saat diminta, masukkan:
- **Username**: username GitHub Anda
- **Password**: gunakan **Personal Access Token** (bukan password biasa)

#### Cara Membuat Personal Access Token:
1. Buka GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Klik "Generate new token (classic)"
3. Beri nama token (misal: "DigiLib Upload")
4. Pilih scope: centang **"repo"** (full control of private repositories)
5. Klik "Generate token"
6. **SALIN TOKEN** dan simpan di tempat aman (tidak akan ditampilkan lagi!)

### 6. Verifikasi Upload

Buka repository di GitHub dan pastikan semua file sudah terupload.

## Update File di Masa Depan

Setelah mengubah file, jalankan:

```bash
git add .
git commit -m "Deskripsi perubahan"
git push
```

## Tips Keamanan

✅ **File yang sudah di-ignore (tidak akan terupload):**
- `/vendor` - dependencies (akan di-install via composer)
- `/runtime/*` - file log dan cache
- `/config/db-local.php` - konfigurasi database lokal
- `.env` - environment variables
- `/web/uploads/*` - file upload user

⚠️ **Pastikan TIDAK mengupload:**
- Password database
- API keys
- File konfigurasi lokal dengan data sensitif

## Troubleshooting

**Problem**: `git push` meminta username/password berulang kali
**Solusi**: Gunakan Personal Access Token, bukan password biasa

**Problem**: "repository not found"
**Solusi**: Pastikan URL repository benar dan Anda punya akses ke repository tersebut

**Problem**: File terlalu besar
**Solusi**: Pastikan folder `vendor` dan `runtime` sudah di-ignore di `.gitignore`
