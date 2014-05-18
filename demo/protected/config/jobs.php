<?php

return array(     
    'frontend' => array(
        
        'structure' => array(            
            'controllers' => array(
                'default' => null,
            ),
        ),
    ),
    'options' => array(
        'default' => array(            
            'integer' => array(
                'allowUsersApply' => 0,
                'allowJobs' => 1
            ),
            'widgetImage' => '/images/front/careers.jpg',
        ),
    ),
    'media' => array(
        'maxFileSize' => 1 * 1024 * 1024,
        'extensions' => 'doc, docx, pdf, pdfx, rtf',
        'url' => null,
        'path' => 'multimedia/jobs',
    ),
);
