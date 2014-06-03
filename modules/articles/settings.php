<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'articles',
            'translation' => array(
                'id' => 6,
                'key' => 'article_id',
                'name' => 'articles_translation',
            ),
            'key' => 'article_id',
            'sorting' => array('sortField' => "article_sort", 'order' => 'asc'),
            'extendsTables' => array(
                'news' => 'news',
                'users_articles' => 'users_articles',
                'dir_companies_articles' => 'dir_companies_articles',
                'issues_articles' => 'issues_articles',
            ),
        ),
        array(
            'id' => 2,
            'name' => 'news',            
            'key' => 'article_id',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
        array(
            'id' => 3,
            'name' => 'users_articles',
            'key' => 'article_id',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
        array(
            'id' => 4,
            'name' => 'dir_companies_articles',
            'key' => 'article_id',
            'sorting' => array('sortField' => "article_sort", 'order' => 'asc'),
            'wheres' => array(
                'companyId' => array('sql' => 'company_id = %d', 'type' => 'integer', 'ref' => 'companyId', 'operator' => 'and', 'inBackendOnly' => true),
            ),
        ),
        array(
            'id' => 5,
            'key' => 'article_id',
            'name' => 'articles_titles',
            'hasMany' => true,
        ),
        array(
            'id' => 6,
            'name' => 'issues_articles',
            'key' => 'article_id',
            'sorting' => array('sortField' => "article_sort", 'order' => 'asc'),
            'wheres' => array(
                'issueId' => array('sql' => 'issue_id = %d', 'type' => 'integer', 'ref' => 'issueId', 'operator' => 'and', 'inBackendOnly' => true),
            ),
        ),
    ),
    'backend' => array(
        'log' => array(
            'use' => true,
            'title' => '$data["data"]["articles"]["db"]["translation"]["db"][$data["data"]["articles"]["db"]["translation"]["contentLang"]]["article_header"]',
        ),
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcArticlesController',
                'articleComments' => 'AmcCommentsController',
                'replies' => 'AmcRepliesController',
                'sources' => 'AmcSourcesController',                
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
            'issueArticles' => array(
                'route' => 'backend/issueArticles',
                'table' => 'issues_articles',
                'tableModel' => 'issuesArticles',
                'module' => 'issueArticles',
                'redirectParams' => array('issueId'),
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
                'default' => 'AmcArticlesController',
                'comments' => 'AmcCommentsController',
                'replies' => 'AmcRepliesController',
                'manage' => 'AmcManageArticlesController',
            ),
        ),
        'virtual' => array(
            'news' => array(
                'route' => 'news',
                'table' => 'news',
                'tableModel' => 'news',
                'module' => 'news',
                'customCriteria' => array(
                    'join' => 'inner join users_articles on p.article_id = users_articles.article_id',
                    'useRelaedModel' => true,
                    'conditionGeneration' => array('class' => 'amcwm.modules.articles.components.ManageUsersArticlesCondition'),
                ),
                'views' => array(
                    'index' => "index",
                    'view' => "view",
                    '_form' => "_form",
                    'create' => "create",
                    'update' => "update",
                    'translate' => "translate",
                ),
            ),
            'companyArticles' => array(
                'route' => 'companyArticles',
                'table' => 'dir_companies_articles',
                'tableModel' => 'dirCompaniesArticles',
                'module' => 'companyArticles',
                'customCriteria' => array(
                    'useRelaedModel' => false,
                    'conditionGeneration' => array('class' => 'amcwm.modules.directory.components.ManageCompaniesArticlesCondition'),
                ),
                'views' => array(
                    'index' => "index",
                    'view' => "view",
                    'create' => "create",
                    'update' => "update",
                    '_form' => "_form",
                    'translate' => "translate",
                ),
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'check' => array(
                'addToSlider' => true,
                'addToInfocus' => true,
                'allowPageImage' => true,
            ),
        ),
        'news' => array(
            'postitions' => array(
//                'sisterPostition' => 4,
//                'sideColumn' => array(
//                    4, 1, 2
//                ),
            ),
            'default' => array(
                'check' => array(
                    'addToBreaking' => true,
                ),
                'integer' => array(
                    'mainTopics' => 4,
                    'breakingExpiredAfter' => 12 * 60 * 60,
                ),                
                'topArticles' => 0,
                'showListingTitle' => false,
                'showPrimaryHeader' => true,
                'showDate' => true,
                'showSectionsList' => false,
                'showSectionName' => true,
                'showSource' => true,
                'showDefaultImage' => false,
                'noImageListing' => AmcWm::app()->request->baseUrl . '/images/front/news_default.jpg'
            ),
        ),
        'articles' => array(
            'postitions' => array(
//                'sisterPostition' => 4,
//                'sideColumn' => array(
//                    4, 1, 2
//                ),
            ),
            'default' => array(
                'integer' => array('mainTopics' => 4),
                'topArticles' => 3,
                'showListingTitle' => false,
                'showPrimaryHeader' => false,
                'showDate' => false,
                'showSectionsList' => true,
                'showSectionName' => true,
                'showSource' => true,
                'showDefaultImage' => false,
                'noImageListing' => AmcWm::app()->request->baseUrl . '/images/front/article_default.jpg'
            ),
        ),
    ),
    'media' => array(
        'info' => array(
//            'excludeMedia'=>array('news'=>array('images')),
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
                    'info' => array('width' => 100, 'height' => 100, 'exact' => false, 'allowedUploadRatio' => 8),
                )
            ),
            'pageImage' => array(
                'autoSave' => false,
                'path' => 'multimedia/articles/pageImage',
                'info' => array('width' => 800, 'height' => 600, 'exact' => false, 'allowedUploadRatio' => 1, 'crob' => false,),
            ),
        ),
    ),
);
