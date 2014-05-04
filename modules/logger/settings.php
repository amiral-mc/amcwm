<?php

return array(
     'tables' => array(
        array(
            'id' => 1,
            'name' => 'log_data',
        ),
        array(
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
