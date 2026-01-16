<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "kategori".
 *
 * @property int $kategoriID
 * @property string $nama
 *
 * @property Buku[] $bukus
 */
class Kategori extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'kategori';
    }

    public static function primaryKey(): array
    {
        return ['kategoriID'];
    }

    public function rules(): array
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'kategoriID' => 'ID',
            'nama' => 'Nama',
        ];
    }

    public function getBukus()
    {
        return $this->hasMany(Buku::class, ['kategoriID' => 'kategoriID']);
    }
}

