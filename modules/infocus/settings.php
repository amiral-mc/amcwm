<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'infocus',
            'sorting' => array('sortField' => "publish_date", 'order' => 'asc'),
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcInfocusController',
            ),
        ),
        'install' => array(
            'controllers' => array(
                'default' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            "create" => array(
                                "perm" => 2,
                                'roles' => array('admin'),
                            ),
                            "update" => array(
                                "perm" => 4,
                                'roles' => array('admin'),
                            ),
                            "delete" => array(
                                "perm" => 8,
                                'roles' => array('admin'),
                            ),
                            "publish" => array(
                                "perm" => 16,
                                'roles' => array('admin'),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcInfocusController',
            ),
        ),
        'install' => array(
            'controllers' => array(
                'default' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'options' => array(
        'system' => array(
            'check' => array(
                'useBanner' => true,
                'useBackground' => true,
                'useBackgroundColor'=>true,
            ),
        ),
        'default' => array(
            'integer' => array(
                'topImages' => 0,
                'topVideos' => 0,
                'topText' => 0,
            ),
        ),
    ),
    'media' => array(
        'info' => array(
            'maxImageSize' => 70 * 1024 * 1024,
            'extensions' => 'jpg, gif, png',
            'url' => null,
        ),
        'paths' => array(
            'images' => array(
                'autoSave' => true,
                'path' => 'multimedia/infocus',
                'info' => array('crob' => false, 'width' => 396, 'height' => 232, 'exact' => false, 'allowedUploadRatio' => 8),
            ),
            'list' => array(
                'autoSave' => true,
                'path' => 'multimedia/infocus/list',
                'info' => array('width' => 78, 'height' => 59, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => true,),
            ),
            'banners' => array(
                'autoSave' => false,
                'maxImageSize' => 900 * 1024 * 1024,
                'path' => 'multimedia/infocus/banners',
                'info' => array('crob' => false, 'width' => 990, 'height' => 90, 'exact' => true, 'allowedUploadRatio' => 1),
            ),
            'backgrounds' => array(
                'autoSave' => false,
                'maxImageSize' => 1024 * 1024 * 1024,
                'path' => 'multimedia/infocus/backgrounds',
                'info' => array('crob' => false, 'width' => 1500, 'height' => 800, 'exact' => false, 'allowedUploadRatio' => 2),
            ),
        ),
    ),
);
