<?php

return array(
    'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'tenders',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
        't2'=>array(
            'id' => 2,
            'name' => 'tenders_department',
            'sorting' => array('sortField' => "department_id", 'order' => 'desc'),
        ),
        't3'=>array(
            'id' => 3,
            'name' => 'tenders_activities',
            'sorting' => array('sortField' => "activity_id", 'order' => 'desc'),
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcTendersController',
                'departments' => 'AmcTendersDepartmentsController',
                'activities' => 'AmcTendersActivitiesController',
                'questions' => 'AmcQuestionsController',
                'repliesTenders' => 'AmcQuestionsRepliesController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcTendersController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'text' => array(
                'homeRoute' => '/tenders/default/index',
            ),
            'currency'=>array(
                'EGP'=>'EGP',
                'EUR'=>'EUR',
                'USD'=>'USD',
            )
        ),
    ),
    'media' => array(
        'info' => array(
            'maxFileSize' => 50 * 1024 * 1024,
            'extensions' => 'doc, docx, txt, xls, xlsx, pdf, pdfx, rtf, odt, ppt, pptx',
            'url' => null,
        ),
        'paths' => array(
            'files' => array(
                'autoSave' => true,
                'path' => 'multimedia/tenders',
            ),
        ),
    ),
);
