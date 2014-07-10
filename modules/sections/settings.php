<?php

return array(
    'tables' => array(
        array(
            'id'=>1,
            'name' => 'sections',
            'sorting' => array('sortField' => "section_sort", 'order' => 'asc'),
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcSectionsController',
                'supervisors' => 'AmcSectionsSupervisorsController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'radio' => array(
                'applyArticlesViewLinks' => false,
                'showSubSections' => false,
                'applySubSectionViewLinks' => false,
                'showMixed' => false,
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
                'path' => 'multimedia/sections',
                'info' => array('width' => 635, 'height' => 229, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => false,),
            ),
            'topContent' => array(
                'path' => 'multimedia/sections/topContent',
                'info' => array('width' => 730, 'height' => 229, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => false,),
            ),
            'blocks' => array(
                'path' => 'multimedia/sections/blocks',
                'info' => array('width' => 293, 'height' => 98, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => true
                ,),
            ),
            'list' => array(
                'autoSave' => true,
                'path' => 'multimedia/sections/list',
                'info' => array('width' => 82, 'height' => 63, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => true,),
            ),
        ),
    ),
);
