<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'persons',
        ), 
        array(
            'id' => 2,
            'name' => 'users',
        ), 
        array(
            'id' => 2,
            'name' => 'writers',
        ), 
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcPersonsController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcPersonsController',
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
                'autoSave'=>true,
                'path' => 'multimedia/persons',
                'info' => array('crob'=>false , 'width' => 150, 'height' => 150, 'exact' => false, 'allowedUploadRatio' => 4),
            ),
            'thumb' => array(
                'autoSave'=>true,
                'path' => 'multimedia/persons/thumbs',
                'info' => array('crob'=>true, 'width' => 60, 'height' => 60, 'exact' => false, 'allowedUploadRatio' => 4, 'size' => 1 * 1024 * 1024),
            )
        ),
    ),
);
