<?php

$videoMaxSize = 20 * 1024 * 1024;
$iniSize = ((int) ini_get('upload_max_filesize') ) * 1024 * 1024;
if ($videoMaxSize > $iniSize) {
    $videoMaxSize = $iniSize;
}
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'AMC Web Mananger ver 1',
    'language' => 'en',
    'contentLang' => 'en',
    'timeZone' => 'Africa/Cairo',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.votes.*',
    ),
    // application components
    'components' => array(
        'ecountdown' => array(
            'class' => 'ext.ecountdown.ECountDown'
        ),
//        'mail' => array(
//            'mailerAttributes' => array(
//                'Mailer' => 'smtp',
//                'Host' => 'amicaiexch.amiral.com.eg',
//                //'SMTPAuth' => true,
//                'Username' => 'abdullah.samir@amiral.com',
//            )
//        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                'backend' => 'backend/default/index',
                'backend/<module:\w+>/<controller:\w+>/ajax/<do:\w+>' => '<module>/<controller>/ajax',
                'backend/<module:\w+>/<controller:\w+>/<action:\w+>/<id>' => 'backend/<module>/<controller>/<action>',
                'backend/<module:\w+>/<controller:\w+>/<action:\w+>' => 'backend/<module>/<controller>/<action>',
                'backend/<controller:\w+>/<action:\w+>' => 'backend/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/captcha' => '<module>/<controller>/captcha',
                '<controller:\w+>/captcha' => '<controller>/captcha',
                '<lang:\w+>directory/default/view/<id>/<v>' => 'directory/default/view',
                '<lang:\w+>directory/default/viewArticle/<id>/<dir:\d+>' => 'directory/default/viewArticle',
                '<lang:\w+>/content/<id>/<menu>' => 'articles/default/view',
                '<lang:\w+>/content/<id>' => 'articles/default/view',
                '<lang:\w+>/presentations' => 'multimedia/default/presentations',
                '<lang:\w+>/images/<id>/<ajax:\d+>' => 'multimedia/images/view',
                '<lang:\w+>/images/<id>' => 'multimedia/images/view',
                '<lang:\w+>/images' => 'multimedia/images/index',
                '<lang:\w+>/videos/<id>/<ajax:\d+>' => 'multimedia/videos/view',
                '<lang:\w+>/videos/<id>' => 'multimedia/videos/view',
                '<lang:\w+>/videos' => 'multimedia/videos/index',
                '<lang:\w+>' => 'site/index',
                '<lang:\w+>/sections/<id>/<module:\d+>/<menu>' => '/articles/default/sections',
                '<lang:\w+>/sections/<menu>/<module:\d+>' => '/articles/default/sections',
                '<lang:\w+>/sections/<id>/<menu>' => '/articles/default/sections',
                '<lang:\w+>/sections/<id>' => '/articles/default/sections',
                '<lang:\w+>/sections/<menu>' => '/articles/default/sections',
                '<lang:\w+>/sections' => '/articles/default/sections',
                '<lang:\w+>/<module:\w+>/<controller:\w+>/ajax/<do:\w+>' => '<module>/<controller>/ajax',
                '<lang:\w+>/<module:\w+>/<controller:\w+>/<action:\w+>/<id>/<menu>' => '<module>/<controller>/<action>',
                '<lang:\w+>/<module:\w+>/<controller:\w+>/<action:\w+>/<id>' => '<module>/<controller>/<action>',
                '<lang:\w+>/<module:\w+>/<controller:\w+>/<action:\w+>/<menu>' => '<module>/<controller>/<action>',
                '<lang:\w+>/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<lang:\w+>/<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<lang:\w+>/<controller:\w+>/<action:\w+>/<id\d+>' => '<controller>/<action>',
                '<lang:\w+>/<controller:\w+>/<action:\w+>/<menu>' => '<controller>/<action>',
                '<lang:\w+>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'enableParamLogging' => true,
            'enableProfiling' => true,
            'connectionString' => 'mysql:host=localhost;dbname=amcwm',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '1',
            'charset' => 'utf8',
            'useCache' => true,
            'schemaCachingDuration' => 24 * 60 * 60 * 30,
            'queryCachingDuration' => 86400,
//            'persistent'=>true,
        ),
//        'cache' => array(
        //    'class' => 'DbCache',
        //'cacheDbFile' => '',
        //'cacheFile' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'cache',
//            'class' => 'system.caching.CMemCache',
//            'servers' => array(
//                array(
//                    'host' => '127.0.0.1',
//                    'port' => 11211,
//                    'weight' => 60,
//                ),
//            ),
//        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => '/site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    'enabled' => true,
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'cacheDuration' => array("static" => 24 * 60 * 60 * 300, 'comments' => 24 * 60 * 60, 'article' => 30 * 24 * 60 * 60, 'multimedia' => 30 * 24 * 60 * 60),
        'socialPost' => false,
        // this is used in contact page
        'adminEmail' => 'info@localhost',
        'marketerEmail' => 'info@localhost',
        'editor' => 'tinymce',
        'languages' => array('en' => 'English', 'ar' => 'العربية'),
        'pageSize' => 10,
        'message' => NULL,
//        "proxy" => array(
//            'host' => '127.0.0.0',
//            'port' => '0000',
//        ),
        'defaultMedia' => array(
            'rte' => array(
                'path' => array('root' => 'multimedia', 'folder' => 'editor'),
                'info' => array('thumbSize' => '100', 'allowedExtensions' => 'swf,txt,htm,html,zip,gz,rar,cab,tar,7z,mp3,ogg,mid,avi,mpg,flv,mpeg,pdf,ttf',),
            ),
        ),
        // facebook page access taken generate from 
        //https://graph.facebook.com/me/accounts?access_token=[user access taken]
        // see http://www.yorkstreetlabs.com/blog/Publish-to-Your-Facebook-Pages-Wall-with-PHP
        'facebook' => array(
            'en' => array(
                'apiId' => '',
                'appSecret' => '',
                'pageId' => '',
                'accessToken' => '',
                'pageAccessToken' => '',
            ),
            'ar' => array(
                'apiId' => '',
                'appSecret' => '',
                'pageId' => '',
                'accessToken' => '',
                'pageAccessToken' => '',
            ),
        ),
        'twitter' => array(
            'en' => array(
                'consumerKey' => '',
                'consumerSecret' => '',
                'oAuthToken' => '',
                'oAuthSecret' => '',
            ),
            'ar' => array(
                'consumerKey' => '',
                'consumerSecret' => '',
                'oAuthToken' => '',
                'oAuthSecret' => '',
            ),
        ),
        'weather' => array(
            'par' => '1211906641',
            'key' => '82b67b9d72b40220',
        ),
        'siteUrl' => 'http://localhost',
    ),
);
