<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        
      /*'urlManager' => array(
      'enablePrettyUrl' => true,	
      'showScriptName' => false,
      'rules' => array(
        '' => 'site/index',
        'login' => 'site/login'
          ))
                ,*/
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'Anbud7!%lpc',
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
              'facebook' => [
                'class' => 'yii\authclient\clients\Facebook',
                'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                'clientId' => '546579682178714',
                'clientSecret' => '2e58f2cbfd3f2c1d35f493d2272410db',
              ],
            'twitter' => [
                              'class' => 'yii\authclient\clients\Twitter',
                              'consumerKey' => 'ax2LxeL5THDFQVP7tvgM18IbX',
                              'consumerSecret' => 'nzFHhD4Yu1WBwBzCzUQZQNL1lHUHchNNCHqume1Wywakqda03V',
                          ],
            ],
          ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        //'db' => require(__DIR__ . '/db.php'),
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://root:9892735aedg@104.131.35.197:27017/dudb',
            //'dsn' => 'mongodb://user1:root@localhost:27017/dudb',
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    /*$config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            'generators' => [
                'mongoDbModel' => [
                    'class' => 'yii\mongodb\gii\model\Generator'
                ]
            ],
        //'class' => 'yii\gii\Module',
    ];*/
}

return $config;
