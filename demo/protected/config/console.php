<?php

return CMap::mergeArray(
        require(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'config/common.php'), array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'AMC Web Mananger ver 1',
    'import' => array(
        'application.components.*',
        'application.commands.components.*',
    ),    
            
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in services update
        'pageSize' => 30,
        // this is used in contact page
        'adminForm' => 'adminForm',
        // this is used for the email list
        'maillistSender' => 'webmaster@anaonline.net',
        'maillistUsersLimit' => '100',
    ),
        )
);