<?php

$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
return CMap::mergeArray(
                require($path . 'config/common.php'), array(
            'backend' => array(
//                'viewsInProject' => true,
            ),
            'language' => 'en',
            'backendLang' => "ar",
            'frontend' => array(
                'bootstrap' => array(
                    'use' => true,
                    'useResponsive' => true,
                ),
//        'viewsInProject'=> true,
                'layout' => 'layouts.column2',
                'positions' => array(
                    "sideColumn" => array(
                        array('id' => 1, 'data' => null, 'options' => array()),
                        array('id' => 2, 'data' => null, 'options' => array()),
                        array('id' => 3, 'data' => null, 'options' => array()),
                        array('id' => 4, 'data' => null, 'options' => array()),
                    )),
                'menus' => array(
                    array('id' => 1, 'is_main' => true),
                    array('id' => 2, 'is_main' => false),
                    array('id' => 3, 'is_main' => false),
                ),
            ),
            'applicationModules' => array(
                'multimedia' => array(
                    'useDopeSheet' => false,
                ),
                'sections' => array(
                    'useSupervisor' => false,
                ),
                'events' => array(
                    'sendEmailAfterInsert' => false
                ),
            ),
//            'theme' => 'mobile',
            //'onBeginRequest'=> array('DeviceDetection', 'BeginRequest'),
            'modules' => array(
                'gii' => array(
                    'class' => 'system.gii.GiiModule',
                    'password' => '123456',
                    // If removed, Gii defaults to localhost only. Edit carefully to taste.
                    'ipFilters' => array('127.0.0.1', '::1'),
                ),
            ),
            'params' =>
            CMap::mergeArray(
                    require($path . 'config/contacts.php'), require_once($path . 'config/configProperties.php'), array(
                'pageSize' => 30,
                'menuMoreAsText' => array(
                    'ar' => 40,
                    'en' => 40,
                ),
                'adminForm' => 'adminForm',
                'watermark' => array(
                    'image' => "resources/images/watermark.png",
                    'position' => 'LB',
                    'opacity' => "40",
                ),
                'reservedContent' => array(
                    'cruiseSectionId' => 1,
                    'membershipsJointVentures' => 2,
                    'contactUs' => 1,
                    'articles' => array(
                        'whatWeDo' => 8,
                        'eightReasons' => 10,
                        'whereWeOperate' => 11,
                    ),
                ),
                'pages' => array(
                    'comments' => 10,
                ),
                'userApps' => array(
                ),
                'facebookLink' => array(
                    'ar' => '#',
                    'en' => '#',
                ),
                'twitterLink' => array(
                    'ar' => '#',
                    'en' => '#',
                ),
                'linkedInLink' => array(
                    'ar' => '#',
                    'en' => '#',
                ),
                'youtubeLink' => 'http://www.youtube.com/user/',
                'rssLink' => array("/rss/default/index"),
                'mobileLink' => array("/mobile/index"),
                'alertLink' => '#',
                'newslettertLink' => array("/maillist/default/subscribe"),
                'googleAdClient' => "ca-pub-4497825201096868",
                'routers' => array(
                    'news' => array(
                        'list' => '/articles/default/index',
                        'sections' => '/articles/default/sections',
                        'view' => '/articles/default/view',
                    ),
                    'articles' => array(
                        'list' => '/articles/default/index',
                        'sections' => '/articles/default/sections',
                        'view' => '/articles/default/view',
                    ),
                    'essays' => array(
                        'list' => '/articles/default/index',
                        'sections' => '/articles/default/sections',
                        'view' => '/articles/default/view',
                    ),
                    'infocus' => array(
                        'list' => '/infocus/default/index',
                        'view' => '/infocus/default/view',
                    ),
                    'videos' => array(
                        'list' => '/multimedia/videos/default/index',
                        'view' => '/multimedia/videos/view',
                        'sections' => '/multimedia/videos/sections',
                    ),
                    'images' => array(
                        'list' => '/multimedia/images/default/index',
                        'view' => '/multimedia/images/view',
                        'sections' => '/multimedia/images/sections',
                    ),
                ),
            )),
                )
);
