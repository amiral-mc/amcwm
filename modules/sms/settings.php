<?php

$videoMaxSize = 20 * 1024 * 1024;
$iniSize = ((int) ini_get('upload_max_filesize') ) * 1024 * 1024;
if ($videoMaxSize > $iniSize) {
    $videoMaxSize = $iniSize;
}
return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'sms_videos',
        ),
    ),
    'options' => array(
        'default' => array(
            'savedId' => 'creation_date',
            'allowed' => array('from' => 8, 'to' => 21),
        )
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcSmsController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcSmsController',
            ),
        ),
    ),
    'media' => array(
        'info' => array(
            'maxImageSize' => 70 * 1024 * 1024,
            'extensions' => '3gp',
            'url' => null,
        ),
        'paths' => array(
            'sms' => array(
                'path' => 'multimedia/sms',
                'size' => $videoMaxSize,
            ),
        ),
    ),
);
