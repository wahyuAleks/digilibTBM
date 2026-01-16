<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "buku".
 *
 * @property int $bukuID
 * @property int $kategoriID
 * @property string $judul
 * @property string|null $penulis
 * @property string|null $isbn
 * @property int|null $stok
 * @property string|null $thumbnail
 *
 * @property Kategori $kategori
 * @property Rak $rak
 */
class Buku extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'buku';
    }

    public static function primaryKey(): array
    {
        return ['bukuID'];
    }

    public function rules(): array
    {
        return [
            [['kategoriID', 'judul'], 'required'],
            [['kategoriID', 'stok'], 'integer'],
            [['judul', 'penulis', 'thumbnail'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'bukuID' => 'ID',
            'kategoriID' => 'Kategori',
            'judul' => 'Judul',
            'penulis' => 'Penulis',
            'isbn' => 'ISBN',
            'stok' => 'Stok',
            'thumbnail' => 'Thumbnail',
        ];
    }

    /**
     * Relasi ke Kategori buku.
     */
    public function getKategori()
    {
        return $this->hasOne(Kategori::class, ['kategoriID' => 'kategoriID']);
    }

    /**
     * Relasi ke Rak penyimpanan.
     */
    public function getRak()
    {
        return $this->hasOne(Rak::class, ['id' => 'rakID']);
    }
}

