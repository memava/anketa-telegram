<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"] ?: $_SERVER['REMOTE_ADDR'];

$config = [
	'id' => 'basic',
	'name' => 'BotManager',
	'language' => "ru",
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'unmvPHiA0K4zbRnS2XPnfXekAo6E0vaG',
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
		'db' => $db,
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
			],
		],
		'qiwi' => [
			'class' => 'Qiwi\Api\BillPayments',
			'key' => ""
		],
		'formatter' => [
			"language" => "ru",
			"timeZone" => "Europe/Kiev",
			"datetimeFormat" => "dd.M.yyyy H:i:s"
		]
	],
	'container' => [
		'definitions' => [
			\yii\widgets\LinkPager::class => \yii\bootstrap4\LinkPager::class,
			'yii\bootstrap4\LinkPager' => [
				'firstPageLabel' => 'Первая',
				'lastPageLabel'  => 'Последняя'
			]
		],
	],
	'params' => $params,
];
if (YII_DEBUG) {

	$config['bootstrap'][] = 'debug';

	$config['modules']['debug'] = [
		'class'      => 'yii\debug\Module',
		'allowedIPs' => ['127.0.0.1', '*'],
	];

}
if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        'allowedIPs' => ['127.0.0.1', '45.88.3.51'],
//    ];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		'allowedIPs' => ['127.0.0.1', '*'],
	];
}

return $config;
