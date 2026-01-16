<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "anggota".
 *
 * @property int $anggotaID
 * @property int $userID
 * @property string $nama
 *
 * @property User $user
 * @property Peminjaman[] $peminjamans
 */
class Anggota extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'anggota';
    }

    public static function primaryKey(): array
    {
        return ['anggotaID'];
    }

    public function rules(): array
    {
        return [
            // userID dan nama tidak ada di tabel anggota, hanya di tabel user
            // Hanya anggotaID yang ada di tabel anggota
        ];
    }

    /**
     * Relasi ke akun User.
     * Relasi menggunakan anggotaID = userid (anggotaID sama dengan userid)
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['userid' => 'anggotaID']);
    }

    /**
     * Relasi ke daftar peminjaman.
     */
    public function getPeminjamans()
    {
        return $this->hasMany(Peminjaman::class, ['anggotaID' => 'anggotaID']);
    }
}

