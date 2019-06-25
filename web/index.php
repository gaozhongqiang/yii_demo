<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

//自定义文件引用
defined('APPPATH') or define('APPPATH',__DIR__ .'/../');
require __DIR__ .'/../helpers'.DIRECTORY_SEPARATOR.'autoload.php';

(new yii\web\Application($config))->run();


