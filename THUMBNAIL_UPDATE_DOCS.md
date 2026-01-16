# Update Thumbnail Buku

Dokumentasi untuk update thumbnail/cover buku di database.

## Thumbnail untuk Kategori Fiksi

Script `update_fiksi_thumbnail.php` dipakai untuk set thumbnail semua buku kategori Fiksi.

### Cara Pakai

```bash
php update_fiksi_thumbnail.php
```

Script akan:
1. Cari semua buku dengan kategori "Fiksi"
2. Update kolom `thumbnail` dengan path gambar default
3. Pakai gambar: `/images/fiksi-cover.jpg`

### Edit Thumbnail Path

Buka file `update_fiksi_thumbnail.php`, cari baris ini:

```php
$thumbnailPath = '/images/fiksi-cover.jpg';
```

Ganti dengan path gambar yang mau dipake.

## Upload Thumbnail Baru

Kalau mau pakai gambar baru:

1. Simpan gambar di folder `web/images/`
2. Misal: `web/images/my-book-cover.jpg`
3. Update script atau database manual

### Update Manual via phpMyAdmin

```sql
UPDATE buku 
SET thumbnail = '/images/my-book-cover.jpg' 
WHERE kategoriID = 1;
```

Ganti `kategoriID` sesuai kategori:
- 1 = Fiksi
- 2 = Non-Fiksi
- 3 = Pendidikan
- 4 = Teknologi

## Thumbnail untuk Kategori Lain

Bisa bikin script serupa untuk kategori lain:

```php
<?php
require(__DIR__ . '/vendor/autoload.php');

$app = require(__DIR__ . '/config/web.php');
(new yii\web\Application($app));

// Update untuk Non-Fiksi
$buku = \app\models\Buku::find()
    ->where(['kategoriID' => 2])  // 2 = Non-Fiksi
    ->all();

foreach ($buku as $b) {
    $b->thumbnail = '/images/nonfiksi-cover.jpg';
    $b->save(false);
}

echo "Done!";
```

## Format Gambar

- Format: JPG, PNG, GIF
- Ukuran recommended: 300x400px atau 600x800px
- Simpan di `web/images/`
- Path relatif dari `web/` folder

## Troubleshooting

**Gambar tidak muncul**
- Cek path benar (relatif dari `/web`)
- Cek file exists di folder images
- Cek permission folder

**Error saat run script**
- Pastikan composer install sudah jalan
- Cek database connection di config
