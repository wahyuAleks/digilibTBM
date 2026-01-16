<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Anggota;

/**
 * RegistrasiForm is the model behind the registration form.
 */
class RegistrasiForm extends Model
{
    public $nama;
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['nama', 'username', 'password', 'password_repeat'], 'required'],
            ['email', 'email'],
            ['email', 'default', 'value' => null],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => 'Username hanya boleh mengandung huruf, angka, dan underscore'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Password tidak cocok'],
            ['username', 'validateUsername'],
            ['email', 'validateEmail'],
        ];
    }

    /**
     * Validates username uniqueness.
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findByUsername($this->username);
            if ($user !== null) {
                $this->addError($attribute, 'Username sudah digunakan.');
            }
        }
    }

    /**
     * Validates email uniqueness.
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors() && !empty($this->email)) {
            $user = User::find()->where(['email' => $this->email])->one();
            if ($user !== null) {
                $this->addError($attribute, 'Email sudah terdaftar.');
            }
        }
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'nama' => 'Nama Lengkap',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Ulangi Password',
        ];
    }

    /**
     * Registers a new user and creates anggota record.
     * @return bool whether the registration is successful
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Buat user baru dan simpan nama
            $user = new User();
            $user->nama = $this->nama;
            $user->username = $this->username;
            $user->email = $this->email ?: null;
            $user->passwordHash = Yii::$app->security->generatePasswordHash($this->password);
            $user->authKey = Yii::$app->security->generateRandomString();
            $user->tipe_user = 'anggota';

            if (!$user->save()) {
                throw new \Exception('Gagal menyimpan data user.');
            }

            // Buat data anggota: gunakan anggotaID = userid (constraint FK di DB)
            $anggota = new Anggota();
            $anggota->anggotaID = $user->userid; // anggotaID sama dengan userid

            if (!$anggota->save()) {
                throw new \Exception('Gagal menyimpan data anggota.');
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('username', $e->getMessage());
            return false;
        }
    }
}

