<?php
define('YII_ENABLE_EXCEPTION_HANDLER', false);
define('YII_ENABLE_ERROR_HANDLER', false);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii/framework/yiilite.php';

Yii::$enableIncludePath = false;
Yii::setPathOfAlias('application', __DIR__);
Yii::import('application.*');
