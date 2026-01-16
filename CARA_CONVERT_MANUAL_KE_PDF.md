# 📄 Cara Mengonversi Manual Book ke PDF

Ada beberapa cara untuk mengonversi file `MANUAL_BOOK_DIGILIB_TBM.md` menjadi PDF. Pilih yang paling mudah untuk Anda:

---

## ✅ **Metode 1: Print to PDF dari Browser** (PALING MUDAH)

Cara ini tidak perlu install software apapun!

### Langkah-langkah:

1. **Buka file markdown di browser**
   - Klik kanan pada file `MANUAL_BOOK_DIGILIB_TBM.md`
   - Pilih **"Open with"** → **Chrome** atau **Edge**
   - Atau drag & drop file ke browser

2. **Print to PDF**
   - Tekan `Ctrl + P` di keyboard
   - Pada bagian **Destination** atau **Printer**, pilih **"Save as PDF"**
   - Atur pengaturan (opsional):
     - Layout: Portrait
     - Paper size: A4
     - Margins: Default
     - Include background graphics: ✅ Centang (agar lebih bagus)
   - Klik **"Save"**
   - Pilih lokasi untuk menyimpan PDF
   - **Done!** ✨

**Keuntungan**: 
- ✅ Tidak perlu install apapun
- ✅ Cepat dan mudah
- ✅ Hasil cukup bagus

**Kekurangan**:
- ❌ Mermaid diagram tidak akan ter-render (jadi plain text)
- ❌ Formatting mungkin tidak se-perfect tools khusus

---

## ⭐ **Metode 2: Menggunakan VS Code Extension** (RECOMMENDED)

Jika Anda pakai VS Code (yang biasa untuk coding):

### Langkah-langkah:

1. **Install Extension**
   - Buka VS Code
   - Tekan `Ctrl + Shift + X` (Extensions)
   - Cari: **"Markdown PDF"** by yzane
   - Klik **Install**

2. **Convert ke PDF**
   - Buka file `MANUAL_BOOK_DIGILIB_TBM.md` di VS Code
   - Klik kanan di editor
   - Pilih **"Markdown PDF: Export (pdf)"**
   - Tunggu proses selesai
   - PDF otomatis tersimpan di folder yang sama

**Keuntungan**:
- ✅ Hasil profesional
- ✅ Table of Contents otomatis
- ✅ Support syntax highlighting code
- ✅ Bisa customize style (CSS)

**Kekurangan**:
- ❌ Perlu install VS Code (tapi mungkin sudah punya)

---

## 🔧 **Metode 3: Menggunakan Pandoc** (KUALITAS TERBAIK)

Untuk hasil yang paling profesional dan customizable:

### Install Pandoc:

**Cara 1: Download Installer**
1. Download dari: https://pandoc.org/installing.html
2. Jalankan installer
3. Restart PowerShell/Command Prompt

**Cara 2: Via Chocolatey** (jika sudah punya)
```powershell
choco install pandoc
```

### Install wkhtmltopdf (untuk PDF engine):
1. Download dari: https://wkhtmltopdf.org/downloads.html
2. Install (pilih sesuai OS Windows Anda)

### Convert ke PDF:

```powershell
# Masuk ke folder project
cd d:\PROJEKTBM\digilibTBM

# Convert ke PDF
pandoc MANUAL_BOOK_DIGILIB_TBM.md -o MANUAL_BOOK_DIGILIB_TBM.pdf --pdf-engine=wkhtmltopdf

# Dengan Table of Contents:
pandoc MANUAL_BOOK_DIGILIB_TBM.md -o MANUAL_BOOK_DIGILIB_TBM.pdf --pdf-engine=wkhtmltopdf --toc

# Dengan custom CSS:
pandoc MANUAL_BOOK_DIGILIB_TBM.md -o MANUAL_BOOK_DIGILIB_TBM.pdf --pdf-engine=wkhtmltopdf --css=style.css
```

**Keuntungan**:
- ✅ Kualitas output paling bagus
- ✅ Banyak opsi customization
- ✅ Table of Contents otomatis
- ✅ Bisa tambah cover page, header, footer
- ✅ Support berbagai format output (PDF, DOCX, EPUB, dll)

**Kekurangan**:
- ❌ Perlu install 2 software (Pandoc + wkhtmltopdf)
- ❌ Sedikit lebih kompleks (command line)

---

## 🌐 **Metode 4: Online Converter** (TANPA INSTALL)

Jika tidak ingin install apapun, gunakan online converter:

### Website yang Recommended:

1. **Markdown to PDF** - https://www.markdowntopdf.com/
   - Upload file `.md`
   - Klik "Convert"
   - Download PDF

2. **Dillinger** - https://dillinger.io/
   - Paste konten markdown
   - Klik "Export as" → "PDF"

3. **CloudConvert** - https://cloudconvert.com/md-to-pdf
   - Upload file
   - Convert
   - Download

**Keuntungan**:
- ✅ Tidak perlu install apapun
- ✅ Bisa akses dari mana saja

**Kekurangan**:
- ❌ Perlu upload file (privacy concern untuk dokumen sensitif)
- ❌ Perlu internet
- ❌ Hasil bisa bervariasi tergantung service

---

## 📝 **Metode 5: Menggunakan Microsoft Word**

Jika punya MS Word:

1. Buka Word
2. File → Open → pilih `MANUAL_BOOK_DIGILIB_TBM.md`
3. Word akan otomatis render markdown
4. File → Save As → pilih format **PDF**

**Keuntungan**:
- ✅ Mudah jika sudah biasa pakai Word
- ✅ Bisa edit layout sebelum export

**Kekurangan**:
- ❌ Perlu lisensi MS Office
- ❌ Rendering markdown tidak perfect

---

## 🎯 **Rekomendasi Berdasarkan Kebutuhan**

| Kebutuhan | Metode Terbaik |
|-----------|----------------|
| **Paling cepat & mudah** | Browser Print to PDF |
| **Hasil terbaik dengan mudah** | VS Code + Markdown PDF Extension |
| **Kualitas maksimal & customizable** | Pandoc + wkhtmltopdf |
| **Tanpa install apapun** | Online Converter |
| **Edit dulu sebelum PDF** | Microsoft Word |

---

## 💡 Tips Tambahan

### Untuk hasil PDF yang lebih baik:

1. **Cek formatting sebelum convert**
   - Preview markdown di editor dulu
   - Pastikan link, gambar, tabel sudah benar

2. **Custom CSS untuk Pandoc**
   Buat file `style.css`:
   ```css
   body {
       font-family: 'Segoe UI', Arial, sans-serif;
       font-size: 12pt;
       line-height: 1.6;
       margin: 2cm;
   }
   
   h1 {
       color: #2c3e50;
       border-bottom: 3px solid #3498db;
       padding-bottom: 10px;
   }
   
   code {
       background-color: #f4f4f4;
       padding: 2px 6px;
       border-radius: 3px;
   }
   ```

3. **Tambah metadata untuk Pandoc**
   Tambahkan di awal file markdown:
   ```yaml
   ---
   title: "Manual Book - Sistem digilibTBM"
   author: "Tim Developer digilibTBM"
   date: "Januari 2026"
   ---
   ```

4. **Untuk mermaid diagram**
   - Gunakan VS Code extension "Markdown Preview Mermaid Support"
   - Atau convert manual ke gambar dulu: https://mermaid.live/

---

## 🆘 Troubleshooting

### Browser tidak render markdown dengan baik
- Coba browser lain (Chrome/Edge biasanya support)
- Atau gunakan extension: "Markdown Viewer" untuk Chrome

### Pandoc error "pdf-engine not found"
```powershell
# Cek apakah pandoc terinstall
pandoc --version

# Cek apakah wkhtmltopdf terinstall
wkhtmltopdf --version

# Jika belum, install dulu
```

### PDF hasil terlalu besar
- Compress online: https://www.ilovepdf.com/compress_pdf
- Atau kurangi gambar/screenshot

### Tabel terpotong di PDF
- Gunakan landscape orientation
- Atau pecah tabel besar jadi beberapa tabel kecil

---

**Selamat mencoba! Pilih metode yang paling cocok untuk Anda.** 🎉
