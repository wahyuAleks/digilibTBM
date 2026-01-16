<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $userid
 * @property string $nama
 * @property string $email
 * @property string $passwordHash
 * @property string $status
 * @property string $tipe_user
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password; // Variabel penampung input password form
    public $authKey;  // Variabel sementara

    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'username', 'passwordHash'], 'required'],
            ['email', 'email'],
            ['email', 'default', 'value' => null],
            ['email', 'unique', 'message' => 'Email sudah terdaftar.', 'skipOnEmpty' => true],
            [['nama', 'username'], 'string', 'max' => 255],
            ['username', 'unique', 'message' => 'Username sudah digunakan.'],
            ['tipe_user', 'string'],
            ['tipe_user', 'default', 'value' => 'anggota'],
        ];
    }

    // --- LOGIKA LOGIN ---

    /**
     * Mencari user berdasarkan USERNAME, EMAIL, atau NAMA.
     */
    public static function findByUsername($username)
    {
        $query = static::find()->where(['username' => $username]);
        
        // Hanya cari berdasarkan email jika username bukan string kosong
        if (!empty($username)) {
            $query->orWhere(['and', ['email' => $username], ['is not', 'email', null]]);
        }
        
        // Atau cek berdasarkan nama
        $query->orWhere(['nama' => $username]);
        
        return $query->one();
    }

    /**
     * Validasi Password (ANTI ERROR VERSION)
     */
    public function validatePassword($password)
    {
        // 1. BACKDOOR (Jalur Khusus)
        // Kalau ketik '12345', langsung lolos (untuk testing).
        if ($password === '12345') {
            return true; 
        }

        // 2. CEK PLAIN TEXT
        // Jika password di database belum di-hash (masih teks biasa "12345")
        if ($password === $this->passwordHash) {
            return true;
        }

        // 3. CEK HASH (Dengan Pengaman)
        // Hash yang valid biasanya panjang (lebih dari 50 karakter).
        // Kalau isi database cuma "12345" (5 karakter), JANGAN panggil security, nanti error.
        if (strlen($this->passwordHash) < 50) {
            return false; // Database isinya bukan hash valid, dan password tidak cocok (langkah 2 gagal)
        }

        // Cek Hash Bawaan Yii2
        try {
            return Yii::$app->security->validatePassword($password, $this->passwordHash);
        } catch (\Exception $e) {
            // Jika hash rusak, return false (jangan crash)
            return false;
        }
    }

    // --- IDENTITY INTERFACE IMPLEMENTATION ---

    public static function findIdentity($id)
    {
        return static::findOne(['userid' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; 
    }

    public function getId()
    {
        return $this->userid;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    // --- HELPER ---

    public function getUsername()
    {
        return $this->email; 
    }

    public function getRole()
    {
        return $this->tipe_user;
    }
}