<?php
error_reporting(E_ALL);
// change the following paths if necessary
$yii=dirname(__FILE__).'/../amc.php';
$config=dirname(__FILE__).'/protected/config/main.php';
<h1>teeest</t>
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
AmcWm::createWebApplication($config)->run();
