# Cara Convert Manual ke PDF

Dokumentasi ini untuk convert file `MANUAL_BOOK_DIGILIB_TBM.md` ke PDF.

## Cara Tercepat: Print to PDF dari Browser

Ini cara paling gampang:

1. Buka file manual di browser
   - Drag & drop file `MANUAL_BOOK_DIGILIB_TBM.md` ke browser
   - Atau pakai VS Code extension "Markdown Preview Enhanced"

2. Print halaman
   - Tekan `Ctrl + P`
   - Pilih "Save as PDF" di destination
   - Klik "Save"
   - Done!

## Cara 2: Pakai VS Code Extension

1. Install extension "Markdown PDF" di VS Code
2. Buka file `MANUAL_BOOK_DIGILIB_TBM.md`
3. Klik kanan → "Markdown PDF: Export (pdf)"
4. File PDF otomatis ke-generate di folder yang sama

## Cara 3: Pakai Pandoc (Advanced)

Kalau mau hasil lebih bagus, pakai Pandoc:

### Install Pandoc
Download dari: https://pandoc.org/installing.html

### Convert ke PDF
```bash
pandoc MANUAL_BOOK_DIGILIB_TBM.md -o MANUAL_BOOK_DIGILIB_TBM.pdf
```

### Dengan Custom Styling
```bash
pandoc MANUAL_BOOK_DIGILIB_TBM.md -o manual.pdf --pdf-engine=xelatex -V geometry:margin=1in
```

## Cara 4: Online Converter

Kalau ga mau install apa-apa:

1. Buka situs:
   - https://www.markdowntopdf.com/
   - https://cloudconvert.com/md-to-pdf
   
2. Upload file .md
3. Convert
4. Download PDF

## Cara 5: Pakai Microsoft Word

1. Buka file .md di VS Code
2. Copy semua isi
3. Paste ke Word
4. Format dikit kalau perlu
5. Save as PDF

## Tips

- Kalau pakai cara browser, pastikan formatting ok sebelum print
- Untuk hasil terbaik, pakai Pandoc
- Online converter cocok untuk cepet-cepetan
- VS Code extension paling praktis kalau udah install

## Troubleshooting

**Q: Gambar tidak muncul di PDF**
A: Pastikan path gambar absolute atau relatif-nya benar

**Q: Mermaid diagram tidak muncul**
A: Pakai Markdown Preview Enhanced di VS Code, atau convert diagram ke gambar dulu

**Q: Format berantakan**
A: Coba pakai Pandoc dengan custom CSS/styling
