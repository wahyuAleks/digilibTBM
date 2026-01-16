<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "rak".
 *
 * @property int $id
 * @property string $nama
 * @property string|null $lokasi
 *
 * @property Buku[] $bukus
 */
class Rak extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'rak';
    }

    public function rules(): array
    {
        return [
            [['nama'], 'required'],
            [['nama', 'lokasi'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'lokasi' => 'Lokasi',
        ];
    }

    public function getBukus()
    {
        return $this->hasMany(Buku::class, ['rakID' => 'id']);
    }
}

