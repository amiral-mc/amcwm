<?php

return array(
    'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'docs',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
        't2'=>array(
            'id' => 2,
            'name' => 'docs_categories',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcDocumentsController',
                'categories' => 'AmcDocCategoriesController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcDocumentsController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'widgetImage' => '/images/front/documents.png',
            'text' => array(
                'homeRoute' => '/documents/default/index',
            ),            
        ),
    ),
    'media' => array(
        'info' => array(
            'maxFileSize' => 50 * 1024 * 1024,
            'extensions' => 'doc, docx, txt, xls, xlsx, pdf, pdfx, rtf, odt, ppt, pptx',
            'url' => null,
        ),
        'categories' => array(
            'maxImageSize' => 1 * 1024 * 1024,
            'extensions' => 'jpg, gif, png',
            'path' => 'multimedia/docs/categories',
            'info' => array('width' => 635, 'height' => 230, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => false,),
        ),
        'paths' => array(
            'files' => array(
                'autoSave' => true,
                'path' => 'multimedia/docs',
            ),
        ),
    ),
    'languages' => array(        
    ),
);
