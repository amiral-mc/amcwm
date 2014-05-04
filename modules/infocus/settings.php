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
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcInfocusController',
            ),
        ),
    ),
    'options' => array(
        'system' => array(
            'check' => array(
                'enableInfocus' => true,
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
            'banners' => array(
                'autoSave' => true,
                'maxImageSize' => 900 * 1024 * 1024,
                'path' => 'multimedia/infocus/banners',
                'info' => array('crob' => false, 'width' => 990, 'height' => 90, 'exact' => true, 'allowedUploadRatio' => 1),
            ),
            'backgrounds' => array(
                'autoSave' => true,
                'maxImageSize' => 1024 * 1024 * 1024,
                'path' => 'multimedia/infocus/backgrounds',
                'info' => array('crob' => false, 'width' => 1500, 'height' => 800, 'exact' => false, 'allowedUploadRatio' => 2),
            ),
        ),
    ),
);
