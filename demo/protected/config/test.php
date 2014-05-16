<?php

$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
return CMap::mergeArray(
                require($path . 'config/common.php'), array(
            'components' => array(
                'fixture' => array(
                    'class' => 'system.test.CDbFixtureManager',
                ),
            ),
            'backend' => array(
//                'viewsInProject' => true,
            ),
            'frontend' => array(
                'bootstrap' => array(
                    'use' => true,
                    'useResponsive' => false,
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
                    array('id' => 4, 'is_main' => false),
                ),
            ),
            'applicationModules' => array(
                'multimedia' => array(
                    'useDopeSheet' => false,
//            'useInfocus' => false,
//            'useKeywords' => false,
//            'useSocials' => false,
                ),
                'sections' => array(
                    'useSupervisor' => false,
                ),
                'events' => array(
                    'sendEmailAfterInsert' => false
                ),
                'transits' => array(),
            ),
            'language' => 'ar',
//            'theme' => 'mobile',
            //'onBeginRequest'=> array('DeviceDetection', 'BeginRequest'),
            'modules' => array(
                'gii' => array(
                    'class' => 'system.gii.GiiModule',
                    'password' => '123456',
                    // If removed, Gii defaults to localhost only. Edit carefully to taste.
                    'ipFilters' => array('127.0.0.1', '::1'),
                ),
                'backend' => array(
                    'modules' => array(
                        'editorManager' => array(),
                        'usersArticles' => array(),
                        'agencyServices' => array(),
                    ),
                ),
            ),
            'params' =>
            CMap::mergeArray(
                    require($path . 'config/configProperties.php'), array(
                'pageSize' => 30,
                'adminForm' => 'adminForm',
                'pages' => array(
                    'comments' => 10,
                ),
                'userApps' => array(
                    'manage_directory_company' => array(
                        'id' => 'manage_directory_company',
                        'label' => "_manage_company_",
                        'url' => array('/directory/members/view'),
                        'image_id' => 'directory',
                        'visible' => '1',
                    ),
                ),
                'facebookLink' => array(
                    'ar' => 'http://www.facebook.com/',
                    'en' => 'http://www.facebook.com/',
                ),
                'twitterLink' => array(
                    'ar' => 'http://twitter.com/#!/',
                    'en' => 'http://twitter.com/#!/',
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
                    'infocus' => array(
                        'list' => '/infocus/default/index',
                        'view' => '/infocus/default/view',
                    ),
                    'videos' => array(
                        'list' => '/multimedia/videos/default/index',
                        'view' => '/multimedia/videos/index',
                        'sections' => '/multimedia/videos/sections',
                    ),
                ),
            )),
                )
);
