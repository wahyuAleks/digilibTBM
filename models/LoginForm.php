<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username; // Input di view (Email)
    public $password; // Input Password
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            // Panggil validatePassword pada model User (mengandung logika backdoor dan cek hash)
            $valid = $user ? $user->validatePassword($this->password) : false;

            if (!$user || !$valid) {
                $this->addError($attribute, 'Email atau password salah.');
                Yii::warning("Login failed for {$this->username}", 'auth');
            } else {
                if (YII_DEBUG) {
                    Yii::info("Login success for {$this->username}", 'auth');
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            // Ini akan memanggil User::findByUsername yang sudah kita edit
            // untuk mencari berdasarkan Email atau Nama
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}