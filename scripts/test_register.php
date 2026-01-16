<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/web.php';
$app = new yii\web\Application($config);

use app\models\RegistrasiForm;

$model = new RegistrasiForm();
$unique = 'testuser' . time();
$model->nama = 'Test User';
$model->username = $unique;
$model->email = $unique . '@example.com';
$model->password = '123456';
$model->password_repeat = '123456';

$res = $model->register();
echo "register() returned: " . ($res ? 'true' : 'false') . PHP_EOL;
if (!$res) {
    print_r($model->getErrors());
} else {
    echo "User created.\n";
}
