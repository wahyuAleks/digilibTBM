<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "denda".
 *
 * @property int $id
 * @property int $peminjamanID
 * @property float $jumlah
 * @property int $hariTerlambat
 * @property string $tanggalDibuat
 *
 * @property Peminjaman $peminjaman
 */
class Denda extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'denda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['peminjamanID', 'jumlah', 'hariTerlambat'], 'required'],
            [['peminjamanID', 'hariTerlambat'], 'integer'],
            [['jumlah'], 'number'],
            [['tanggalDibuat'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'peminjamanID' => 'Peminjaman ID',
            'jumlah' => 'Jumlah Denda',
            'hariTerlambat' => 'Hari Terlambat',
            'tanggalDibuat' => 'Tanggal Dibuat',
        ];
    }

    /**
     * Relasi ke model Peminjaman.
     */
    public function getPeminjaman()
    {
        $primaryKey = Peminjaman::primaryKey();
        $key = !empty($primaryKey) ? $primaryKey[0] : 'id';
        return $this->hasOne(Peminjaman::class, [$key => 'peminjamanID']);
    }
}

