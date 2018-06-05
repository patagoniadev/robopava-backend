<?php

use app\models\User;

return [
    'user1' => [
        'username' => 'admin',
        'email' => 'admin@admin.com',
        'auth_key' => 'test100key',
        'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
        'status' => User::STATUS_ACTIVE
    ],
    'user2' => [
        'username' => 'demo',
        'email' => 'admin@admin.com',
        'auth_key' => 'K3nF70it7tzNsHddEiq0BZ0i-OU8S3xV',
        'password_hash' => Yii::$app->security->generatePasswordHash('demo'),
        'status' => User::STATUS_ACTIVE
    ]
];