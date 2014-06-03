<?php

return array(   
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcArticlesController',
                'articleComments' => 'AmcCommentsController',
                'replies' => 'AmcRepliesController',
            ),
        ),
        'virtual' => array(
            'news' => array(
                'route' => 'backend/news',
                'table' => 'news',
                'tableModel' => 'news',
                'module' => 'news',
                'views' => array(
                    'index' => "index",
                    'view' => "view",
                    '_form' => "_form",
                    'translate' => "translate",
                ),
            ),
            'usersArticles' => array(
                'route' => 'backend/usersArticles',
                'table' => 'users_articles',
                'tableModel' => 'usersArticles',
                'module' => 'usersArticles',
                'views' => array(
                    'index' => "index",
                    'wajax' => 'wajax',
                    'view' => "view",
                    '_form' => "_form",
                    'translate' => "translate",
                ),
            ),
            'companyArticles' => array(
                'route' => 'backend/companyArticles',
                'table' => 'dir_companies_articles',
                'tableModel' => 'dirCompaniesArticles',
                'module' => 'companyArticles',
                'redirectParams' => array('companyId'),
                'saveMethod' => 'saveRelatedVirtual',
                'views' => array(
                    'index' => "index",
                    'wajax' => 'wajax',
                    'view' => "view",
                    '_form' => "_form",
                    'create' => "create",
                    'update' => "update",
                    'translate' => "translate",
                ),
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => null,
                'comments' => 'AmcCommentsController',
                'replies' => 'AmcRepliesController',
            ),
        ),
        'virtual' => array(
            'news' => array(
                'route' => 'news',
                'table' => 'news',
                'tableModel' => 'news',
                'module' => 'news',
                'views' => array(),
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'check' => array(
                'addToSlider' => true,
                'addToInfocus' => false,
            ),
        ),
        'news' => array(
            'postitions' => array(
                'sisterPostition' => 4,
                'sideColumn' => array(
                    4, 2, 1
                ),
            ),
            'default' => array(
                'check' => array(
                    'addToBreaking' => true,
                ),
                'listingRowOrders' => array('infoBar' => 'infoBar','header' => 'header', 'details' => 'details'),
                'topArticles' => 0,
                'showListingTitle' => false,
                'showPrimaryHeader' => true,
                'showDate' => true,
                'showSectionName' => true,
                'showSource' => true,
                'showSectionsList' => false,
            ),
        ),
        'articles' => array(
            'postitions' => array(
                'sisterPostition' => 4,
                'sideColumn' => array(
                    4, 2, 1
                ),
            ),
            'default' => array(
                'listingRowOrders' => array('infoBar' => 'infoBar','header' => 'header', 'image' => 'image', 'details' => 'details'),
                'topArticles' => 0,
                'showListingTitle' => false,
                'showPrimaryHeader' => false,
                'showDate' => false,
                'showSectionsList' => true,
                'showSectionName' => true,
                'showSource' => true,
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
                'path' => 'multimedia/articles',
                'info' => array('width' => 396, 'height' => 232, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => false,),
            ),
            'sections' => array(
                'autoSave' => true,
                'path' => 'multimedia/articles/sections',
                'info' => array('width' => 292, 'height' => 232, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => false,),
            ),
            'mostread' => array(
                'autoSave' => true,
                'path' => 'multimedia/articles/mostread',
                'info' => array('width' => 133, 'height' => 75, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => true,),
            ),
            'newsList' => array(
                'autoSave' => true,
                'path' => 'multimedia/articles/list',
                'info' => array('width' => 145, 'height' => 70, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => true,),
            ),
            'list' => array(
                'autoSave' => true,
                'path' => 'multimedia/articles/list',
                'info' => array('width' => 78, 'height' => 59, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => true,),
            ),
            'blocks' => array(
                'autoSave' => true,
                'path' => 'multimedia/articles/blocks',
                'info' => array('width' => 293, 'height' => 98, 'exact' => false, 'allowedUploadRatio' => 8, 'crob' => true,),
            ),
            'slider' => array(
                'autoSave' => false,
                'path' => 'multimedia/slider/articles',
                'info' => array('width' => 375, 'height' => 300, 'exact' => false, 'allowedUploadRatio' => 3),
                'thumb' => array(
                    'path' => 'multimedia/slider/articles/thumb',
                    'info' => array('width' => 108, 'height' => 107, 'exact' => false, 'allowedUploadRatio' => 8),
                )
            ),
        ),
    ),
);
