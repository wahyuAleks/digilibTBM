<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "peminjaman".
 *
 * @property int $id
 * @property int $anggotaID
 * @property string|null $tanggalPinjam
 * @property string|null $tanggalKembali
 * @property string|null $tglJatuhTempo
 * @property string|null $status
 *
 * @property Anggota $anggota
 * @property ItemPeminjaman[] $itemPeminjamans
 */
class Peminjaman extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'peminjaman';
    }

    /**
     * {@inheritdoc}
     */
    public static function primaryKey(): array
    {
        // Cek kolom yang ada di tabel
        try {
            $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
            if ($tableSchema) {
                $columns = array_keys($tableSchema->columns);
                // Coba cari primary key yang umum
                if (in_array('peminjamanID', $columns)) {
                    return ['peminjamanID'];
                } elseif (in_array('id', $columns)) {
                    return ['id'];
                } elseif (in_array('peminjaman_id', $columns)) {
                    return ['peminjaman_id'];
                }
            }
        } catch (\Exception $e) {
            // Fallback
        }
        // Default fallback
        return ['id'];
    }

    /**
     * Mendapatkan ID peminjaman (primary key)
     */
    public function getId()
    {
        $primaryKey = static::primaryKey();
        if (!empty($primaryKey)) {
            $key = $primaryKey[0];
            return $this->$key ?? null;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['anggotaID'], 'required'],
            [['anggotaID'], 'integer'],
            [['tanggalPinjam', 'tanggalKembali', 'tglJatuhTempo', 'status'], 'safe'],
        ];
    }

    /**
     * Relasi ke model Anggota melalui FK anggotaID.
     */
    public function getAnggota()
    {
        return $this->hasOne(Anggota::class, ['anggotaID' => 'anggotaID']);
    }

    /**
     * Cache untuk mengecek apakah tabel item_peminjaman ada
     */
    private static $_itemPeminjamanTableExists = null;
    
    /**
     * Cek apakah tabel item_peminjaman ada
     */
    private static function checkItemPeminjamanTable()
    {
        if (self::$_itemPeminjamanTableExists === null) {
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                self::$_itemPeminjamanTableExists = $tableSchema !== null;
            } catch (\Exception $e) {
                self::$_itemPeminjamanTableExists = false;
            }
        }
        return self::$_itemPeminjamanTableExists;
    }
    
    /**
     * Relasi ke ItemPeminjaman.
     */
    public function getItemPeminjamans()
    {
        // Cek apakah tabel item_peminjaman ada sebelum membuat relasi
        if (!ItemPeminjaman::tableExists()) {
            // Jika tabel tidak ada, return query yang tidak akan dieksekusi
            $query = new \yii\db\ActiveQuery(ItemPeminjaman::class);
            $query->where('1 = 0');
            return $query;
        }
        
        // Gunakan primary key yang dinamis
        $primaryKey = static::primaryKey();
        $key = !empty($primaryKey) ? $primaryKey[0] : 'id';
        
        // Cek kolom yang ada di tabel item_peminjaman untuk foreign key
        try {
            $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
            if ($itemPeminjamanSchema) {
                $columns = array_keys($itemPeminjamanSchema->columns);
                // Cek kolom foreign key yang ada
                if (in_array('peminjamanID', $columns)) {
                    return $this->hasMany(ItemPeminjaman::class, ['peminjamanID' => $key]);
                } elseif (in_array('peminjaman_id', $columns)) {
                    return $this->hasMany(ItemPeminjaman::class, ['peminjaman_id' => $key]);
                } elseif (in_array('id', $columns)) {
                    return $this->hasMany(ItemPeminjaman::class, ['id' => $key]);
                }
            }
        } catch (\Exception $e) {
            // Jika error, gunakan default
        }
        
        // Default: gunakan peminjamanID
        return $this->hasMany(ItemPeminjaman::class, ['peminjamanID' => $key]);
    }
    
    /**
     * Override magic method untuk menangani itemPeminjamans jika tabel tidak ada
     * Ini akan mencegah query dieksekusi jika tabel tidak ada
     */
    public function __get($name)
    {
        if ($name === 'itemPeminjamans') {
            // Jika tabel tidak ada, langsung return empty array tanpa query
            if (!self::checkItemPeminjamanTable()) {
                return [];
            }
        }
        return parent::__get($name);
    }
    
    /**
     * Override untuk menangani hasAttribute jika tabel tidak ada
     */
    public function hasAttribute($name)
    {
        if ($name === 'itemPeminjamans') {
            return self::checkItemPeminjamanTable();
        }
        return parent::hasAttribute($name);
    }

    /**
     * Menghitung denda berdasarkan tanggal kembali dan tarif per hari.
     * 
     * @param string $tglKembali Tanggal kembali buku (format: Y-m-d atau Y-m-d H:i:s)
     * @param float $tarifPerHari Tarif denda per hari (default: 500)
     * @return int|null ID denda yang dibuat, atau null jika tidak ada denda
     */
    public function hitungDenda($tglKembali, $tarifPerHari = 500)
    {
        // Pastikan tglJatuhTempo ada
        if (empty($this->tglJatuhTempo)) {
            return null;
        }

        // Konversi tanggal ke timestamp untuk perbandingan
        $tglJatuhTempo = strtotime($this->tglJatuhTempo);
        $tglKembaliTimestamp = strtotime($tglKembali);

        // Jika tidak terlambat, tidak ada denda
        if ($tglKembaliTimestamp <= $tglJatuhTempo) {
            return null;
        }

        // Hitung hari terlambat
        $selisihHari = ($tglKembaliTimestamp - $tglJatuhTempo) / (60 * 60 * 24);
        $hariTerlambat = (int) ceil($selisihHari);

        // Hitung jumlah denda
        $jumlahDenda = $hariTerlambat * $tarifPerHari;

        // Buat record denda baru
        $denda = new Denda();
        $denda->peminjamanID = $this->getId();
        $denda->jumlah = $jumlahDenda;
        $denda->hariTerlambat = $hariTerlambat;
        $denda->tanggalDibuat = date('Y-m-d H:i:s');

        if ($denda->save()) {
            return $denda->id;
        }

        return null;
    }
}

