<?php

use yii\web\UrlNormalizer;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
//    'defaultRoute' => 'site/login',
    'bootstrap' => ['log'],
    'modules' => [
        'rbac' => [
            'class' => 'backend\modules\rbac\Modulo',
        ],
        'facturas' => [
            'class' => 'backend\modules\facturas\Modulo',
        ],
        'cuentas' => [
            'class' => 'backend\modules\cuentas\Modulo',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY, // use temporary redirection instead of permanent
            ],
            'rules' => [
                //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',

                //modulo facturas
                '<modules:(facturas)>'=>'<module>/facturas/index',
                '<module:(facturas)>/<controller:\w+>'=>'<module>/facturas/index',
                '<module:(facturas)>/<action:\w+>/<id:\d+>'=>'<module>/facturas/<action>',

                //modulo cuentas
                '<modules:(cuentas)>'=>'<module>/usuarios/index',
                '<module:(cuentas)>/<controller:\w+>'=>'<module>/usuarios/index',
                '<module:(cuentas)>/<action:\w+>/<id:\d+>'=>'<module>/usuarios/<action>',
            ],
        ],
    ],
    'params' => $params,
];
