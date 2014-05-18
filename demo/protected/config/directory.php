<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'dir_companies',
            'sorting' => array('sortField' => "create_date", 'order' => 'asc'),
        ),
        array(
            'id' => 2,
            'name' => 'dir_categories',
        ),
        array(
            'id' => 3,
            'name' => 'dir_companies_branches',
        ),
    ),
    'backend' => array(
//        'messageBase'=> "application.messages",
        'structure' => array(
            'controllers' => array(
                'default' => null,
                'branches' => 'AmcDirBranchesController',
                'categories' => 'AmcDirCategoriesController',
                'requests' => 'AmcDirRequestsController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => null,
            ),
        ),
    ),
    'options' => array(
        'system' => array(
            'integer' => array(
                'regionFilter' => 0,
            ),
            'check' => array(
                'generatePdfAfterRequest' => false,
                'requestsEnable' => true,
                'categoriesEnable' => true,
                'branchesEnable' => true,                
                'articlesEnable' => true,
                'requiredCategory' => false,
                'useTicker' => false,
            ),
        ),
        'default' => array(
            'text' => array(
                'subscriptoinRedirectUrl' => '/site/index',
                'adminEmail'=>'ashraf.akl@amiral.com',
            ),
            'check' => array(
                'attachEnable' => true,
                'imageEnable' => true,
                'useTicker' => true,
                'mapEnable' => true,
                'allowUsersApply'=>false,
            ),
            'frontend' => array(
                'searchEnable' => true,
                'categoriesFilterEnable' => true,
                'showArticleLink' => true,
            ),
        ),
    ),
    'media' => array(
        'info' => array(
            'maxImageSize' => 7 * 1024 * 1024,
            'extensions' => 'jpg, gif, png',
            'url' => null,
        ),
        'paths' => array(
            'images' => array(
                'path' => 'multimedia/directory',
                'info' => array('isImage' => true, 'width' => 150, 'height' => 75, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => false,),
            ),
            'maps' => array(
                'extensions' => 'jpg, gif, png',
                'path' => 'multimedia/directory/maps',
                'info' => array('isImage' => true, 'width' => 500, 'height' => 500, 'exact' => false, 'allowedUploadRatio' => 1, 'crob' => false,),
            ),
            'attach' => array(
                'path' => 'multimedia/directory/attach',
                'info' => array('isImage' => false, 'extensions' => 'pdf, doc, docx, xls, xlsx', 'maxSize' => 15 * 1024 * 1024,),
            ),
        ),
    ),
);
