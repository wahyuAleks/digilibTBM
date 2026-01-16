<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "item_peminjaman".
 *
 * @property int $id
 * @property int $peminjamanID
 * @property int $bukuID
 *
 * @property Peminjaman $peminjaman
 * @property Buku $buku
 */
class ItemPeminjaman extends ActiveRecord
{
    /**
     * Cache untuk mengecek apakah tabel ada
     */
    private static $_tableExists = null;
    
    /**
     * Cek apakah tabel item_peminjaman ada
     */
    public static function tableExists()
    {
        if (self::$_tableExists === null) {
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                self::$_tableExists = $tableSchema !== null;
            } catch (\Exception $e) {
                self::$_tableExists = false;
            }
        }
        return self::$_tableExists;
    }
    
    public static function tableName(): string
    {
        return 'item_peminjaman';
    }
    
    /**
     * Override find() untuk mencegah query jika tabel tidak ada
     */
    public static function find()
    {
        // Jika tabel tidak ada, return query yang tidak akan dieksekusi
        if (!self::tableExists()) {
            // Buat custom query yang tidak akan mengakses database
            $query = new ActiveQuery(get_called_class());
            // Set kondisi yang tidak mungkin terpenuhi
            $query->where('1 = 0');
            return $query;
        }
        
        return new ActiveQuery(get_called_class());
    }
    
    /**
     * Override untuk mencegah error saat save jika tabel tidak ada
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!self::tableExists()) {
            \Yii::$app->session->setFlash('error', 'Tabel item_peminjaman tidak ditemukan. Silakan buat tabel terlebih dahulu.');
            return false;
        }
        return parent::save($runValidation, $attributeNames);
    }

    public function rules(): array
    {
        return [
            [['peminjamanID', 'bukuID'], 'required'],
            [['peminjamanID', 'bukuID'], 'integer'],
        ];
    }

    /**
     * Relasi ke transaksi peminjaman.
     */
    public function getPeminjaman()
    {
        if (!self::tableExists()) {
            $query = new ActiveQuery(Peminjaman::class);
            $query->where('1 = 0');
            return $query;
        }
        
        $primaryKey = Peminjaman::primaryKey();
        $key = !empty($primaryKey) ? $primaryKey[0] : 'id';
        
        // Cek kolom yang ada di tabel item_peminjaman
        try {
            $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
            if ($tableSchema) {
                $columns = array_keys($tableSchema->columns);
                if (in_array('peminjamanID', $columns)) {
                    return $this->hasOne(Peminjaman::class, [$key => 'peminjamanID']);
                } elseif (in_array('peminjaman_id', $columns)) {
                    return $this->hasOne(Peminjaman::class, [$key => 'peminjaman_id']);
                }
            }
        } catch (\Exception $e) {
            // Jika error, gunakan default
        }
        
        return $this->hasOne(Peminjaman::class, [$key => 'peminjamanID']);
    }

    /**
     * Relasi ke buku yang dipinjam.
     */
    public function getBuku()
    {
        return $this->hasOne(Buku::class, ['bukuID' => 'bukuID']);
    }
}

