<?php

return array(
    'name' => 'directory',
    'extraAttributes' => array(
        'attributesMaps' => array(
            'dir_companies_translation' => array(
                'address' => 'company_address',
            ),
            'dir_companies' => array(
            ),
            'dir_companies_branches_translation' => array(
                'address' => 'branch_address',
            ),
            'dir_companies_branches' => array(
            ),
        ),
        'required' => array(
        ),
        'tables' => array(
            'dir_companies'=>'dir_companies_attributes',
            'dir_companies_branches'=>'dir_companies_branches_attributes',
        ),
        'enable' => true
    ),
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
                'default' => 'AmcDirectoryController',
                'branches' => 'AmcDirBranchesController',
                'categories' => 'AmcDirCategoriesController',
                'requests' => 'AmcDirRequestsController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcDirectoryController',
                'members' => 'AmcMembersController',
                'branches' => 'AmcDirBranchesController',
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
            ),
        ),
        'default' => array(
            'text' => array(
                'subscriptoinRedirectUrl' => '/site/index',
                'homeDirectoryRoute' => '/directory/default/index',
            ),
            'check' => array(
                'attachEnable' => true,
                'imageEnable' => true,
                'useTicker' => true,
                'mapEnable' => true,
                'allowUsersApply' => true,
            ),
            'frontend' => array(
                'searchEnable' => true,
                'categoriesFilterEnable' => true,
                'showArticleLink' => false,
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
                'info' => array('isImage' => true, 'width' => 90, 'height' => 90, 'exact' => false, 'allowedUploadRatio' => 1, 'crob' => false,),
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
