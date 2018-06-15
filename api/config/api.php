<?php
$db     = require(__DIR__ . '/../../config/db.php');
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'api',
    'name' => 'Robopava API',
    'basePath' => dirname(__DIR__, 2),
    'bootstrap' => ['log'],
    'aliases' => [
        '@api' => dirname(__DIR__),
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'defaultRoute' => 'v1',
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\Module',
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'iV9cCoQkiLotM5YXhhfTgySuCw0Q_qpU',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'keyPrefix' => 'pava:cache:api:',
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'keyPrefix' => 'pava:session:api:',
            'timeout' => 2592000, // 30 días
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@api/runtime/logs/api.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@api/runtime/logs/mylog.log',
                    'logVars' => [],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'GET v1/?' => 'v1',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/user'],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/pava',
                    'extraPatterns' => [
                        'GET temperatura' => 'temperatura',
                        'OPTIONS calentar' => 'calentar',
                        'POST calentar' => 'calentar',
                    ],
                ],
            ],
        ],
        'db' => $db,
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '*'
        ],
    ],
];

return $config;
