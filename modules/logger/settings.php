<?php

return array(
     'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'log_data',
        ),
        't2'=>array(
            'id' => 2,
            'name' => 'users_log',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcLoggerController',
            ),
        ),
    ),
//    'frontend' => array(
//        'structure' => array(
//            'controllers' => array(
//                'default' => 'AmcMaillistController',
//            ),
//        ),
//    ),
    'options' => array(
        'default' => array(
            'text' => array(                
//                'subscriptoinRedirectUrl' => '/site/index',
            ),
        ),
    ),
    
);
