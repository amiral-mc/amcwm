<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'menus',
        ),
        array(
            'id' => 2,
            'name' => 'menu_items',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcMenusController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'check' => array(
                'allowPageImage' => true,
            ),
            'integer' => array(
                'maxLevels' => 3,
                'maxSisterLevels'=>2,
            ),
            'json' => array(
                'generatedChilds' => array(
                    'count' => 10,
                    'id' => null,
                ),
            ),
        ),
        'params' => array(
            'views' => array(
                'default',
                'blocks',
                'cols',
                'links',
            ),
            'tasks' => array(
                'default',
                'sections',
                'mixed',
            ),
        ),
    ),
    'media' => array(
        'maxImageSize' => 1 * 1024 * 1024,
        'extensions' => 'jpg, gif, png',
        'url' => null,
        'info' => array('width' => 64, 'height' => 64, 'exact' => false, 'allowedUploadRatio' => 1, 'crob' => false,),
        'path' => 'multimedia/menu',
        'pageImage' => array(
            'path' => 'multimedia/menu/pageImage',
            'info' => array('width' => 800, 'height' => 600, 'exact' => false, 'allowedUploadRatio' => 1, 'crob' => false,),
        ),
    ),
);
