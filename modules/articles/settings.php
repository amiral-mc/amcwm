<?php

return array(
    'tables' => array(
        't1' => array(
            'id' => 1,
            'name' => 'articles',
            'logMethods' => array(
                "select * from persons p inner join persons_translation on p.person_id = pt.person_id where person_id = %d'"
            ),
            'translation' => array(
                'id' => 8,
                'key' => 'article_id',
                'name' => 'articles_translation',
            ),
            'key' => 'article_id',
            'sorting' => array('sortField' => "article_sort", 'order' => 'asc'),
            'extendsTables' => array(
                'news' => 'news',
                'essays' => 'essays',
                'users_articles' => 'users_articles',
                'dir_companies_articles' => 'dir_companies_articles',
                'issues_articles' => 'issues_articles',
            ),
        ),
        't2' => array(
            'id' => 2,
            'name' => 'news',
            'key' => 'article_id',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
        't3' => array(
            'id' => 3,
            'name' => 'users_articles',
            'key' => 'article_id',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
        't4' => array(
            'id' => 4,
            'name' => 'dir_companies_articles',
            'key' => 'article_id',
            'sorting' => array('sortField' => "article_sort", 'order' => 'asc'),
            'wheres' => array(
                'companyId' => array('sql' => 'company_id = %d', 'type' => 'integer', 'ref' => 'companyId', 'operator' => 'and', 'inBackendOnly' => true),
            ),
        ),
        't5' => array(
            'id' => 5,
            'key' => 'article_id',
            'name' => 'articles_titles',
            'hasMany' => true,
        ),
        't6' => array(
            'id' => 6,
            'name' => 'issues_articles',
            'key' => 'article_id',
            'sorting' => array('sortField' => "article_sort", 'order' => 'asc'),
            'wheres' => array(
                'issueId' => array('sql' => 'issue_id = %d', 'type' => 'integer', 'ref' => 'issueId', 'operator' => 'and', 'inBackendOnly' => true),
            ),
        ),
        't7' => array(
            'id' => 7,
            'name' => 'essays',
            'key' => 'article_id',
            'sorting' => array('sortField' => "create_date", 'order' => 'desc'),
        ),
        't8' => array(
            'id' => 8,
            'name' => 'news_sources',
            'onRelations' => 'inner join news on news_sources.source_id = news.source_id',
            'onRelationsWhereKey' => 'article_id',
            'hasMany' => true,
            'translation' => array(
                'id' => 9,
                'key' => 'source_id',
                'onRelations' => 'left join news_sources on news_sources_translation.source_id = news_sources.source_id',
                'name' => 'news_sources_translation',
            ),
            'key' => 'source_id',
        ),
        't9' => array(
            'id' => 10,
            'name' => 'persons',
            'logNameKey' => 'writers',
            //'hasMany' => true,
            'onRelations' => 'inner join articles on persons.person_id = articles.writer_id',
            'translation' => array(
                'id' => 11,
                'onRelations' => 'left join persons on persons_translation.person_id = persons.person_id',
                'key' => 'article_id',
                'name' => 'persons_translation',
            ),
            'key' => 'article_id',
        ),
        't10' => array(
            'id' => 12,
            'name' => 'persons',
            'logNameKey' => 'news_editors',
            'hasMany' => true,
            'onRelations' => sprintf('inner join news_editors on persons.person_id = news_editors.editor_id'
                    . ' inner join persons_translation on persons_translation.person_id = persons.person_id and persons_translation.content_lang = %s', AmcWm::app()->db->quoteValue(Controller::getContentLanguage())),
            'logSelect' => 'email, name',
            'key' => 'article_id',
        ),
    ),
    'backend' => array(
        'log' => array(
            'use' => true,
            'useTableLog' => true,
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
        'install' => array(
            'controllers' => array(
                'default' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "publish" => array(
                            "perm" => 16,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "sort" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        'comments' => array(
                            "perm" => 64,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                            'forwardTo' => array(
                                'controller' => 'comments',
                                'action' => 'index',
                            ),
                        ),
                        'sources' => array(
                            "perm" => 128,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                            ),
                            'forwardTo' => array(
                                'controller' => 'sources',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
                'sources' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                    ),
                ),
                'comments' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "publish" => array(
                            "perm" => 16,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "hide" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        'replies' => array(
                            "perm" => 64,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                            'forwardTo' => array(
                                'controller' => 'replies',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
                'replies' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "publish" => array(
                            "perm" => 16,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                        "hide" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                            'roles4Virtual' => array(
                                'news' => array('admin'),
                                'breaking' => array('admin'),
                                'essays' => array('admin'),
                                'companyArticles' => array('admin'),
                                'usersArticles' => array('admin'),
                                'issueArticles' => array('admin'),
                            ),
                        ),
                    ),
                ),
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
            'breaking' => array(
                'route' => 'backend/breaking',
                'table' => 'news',
                'tableModel' => 'news',
                'module' => 'breaking',
                'views' => array(
                    'index' => "index",
                    'view' => "view",
                    '_form' => "_form",
                    'update' => "update",
                    'translate' => "translate",
                ),
                'customCriteria' => array(
                    'useRelatedModel' => true,
                    'conditionGeneration' => array('class' => 'amcwm.modules.articles.components.ManageBreakingCondition'),
                ),
            ),
            'essays' => array(
                'route' => 'backend/essays',
                'table' => 'essays',
                'tableModel' => 'essays',
                'module' => 'essays',
                'views' => array(
                    'index' => "index",
                    'view' => "view",
                    '_form' => "_form",
                    '_search' => "_search",
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
                'system' => 0,
                'enabled' => 0,
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
                'system' => 0,
                'enabled' => 0,
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
        'install' => array(
            'controllers' => array(
                'default' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('guest'),
                                'breaking' => array('guest'),
                                'essays' => array('guest'),
                                'companyArticles' => array('guest'),
                                'usersArticles' => array('guest'),
                                'issueArticles' => array('guest'),
                            ),
                        ),
                        "sections" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('guest'),
                                'breaking' => array('guest'),
                                'essays' => array('guest'),
                                'companyArticles' => array('guest'),
                                'usersArticles' => array('guest'),
                                'issueArticles' => array('guest'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('guest'),
                                'breaking' => array('guest'),
                                'essays' => array('guest'),
                                'companyArticles' => array('guest'),
                                'usersArticles' => array('guest'),
                                'issueArticles' => array('guest'),
                            ),
                        ),
                        'comments' => array(
                            "perm" => 32,
                            'roles' => array('guest'),
                            'forwardTo' => array(
                                'controller' => 'comments',
                                'action' => 'index',
                                'roles4Virtual' => array(
                                    'news' => array('guest'),
                                    'breaking' => array('guest'),
                                    'essays' => array('guest'),
                                    'companyArticles' => array('guest'),
                                    'usersArticles' => array('guest'),
                                    'issueArticles' => array('guest'),
                                ),
                            ),
                        ),
                    ),
                ),
                'manage' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('registered'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('registered'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('registered'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('registered'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('registered'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "publish" => array(
                            "perm" => 16,
                            'roles' => array('registered'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "sort" => array(
                            "perm" => 32,
                            'roles' => array('registered'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                    ),
                ),
                'comments' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('guest'),
                                'usersArticles' => array('guest'),
                                'issueArticles' => array('guest'),
                            ),
                        ),
                        'replies' => array(
                            "perm" => 64,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('guest'),
                                'breaking' => array('guest'),
                                'essays' => array('guest'),
                                'companyArticles' => array('guest'),
                                'usersArticles' => array('guest'),
                                'issueArticles' => array('guest'),
                            ),
                            'forwardTo' => array(
                                'controller' => 'replies',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
                'replies' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('guest'),
                            'roles4Virtual' => array(
                                'news' => array('registered'),
                                'breaking' => array('registered'),
                                'essays' => array('registered'),
                                'companyArticles' => array('registered'),
                                'usersArticles' => array('registered'),
                                'issueArticles' => array('registered'),
                            ),
                        ),
                    ),
                ),
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
                    'useRelatedModel' => true,
                    'conditionGeneration' => array(),
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
            'usersArticles' => array(
                'route' => 'usersArticles',
                'table' => 'users_articles',
                'tableModel' => 'usersArticles',
                'module' => 'usersArticles',
                'customCriteria' => array(
                    'useRelatedModel' => true,
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
            'essays' => array(
                'route' => 'essays',
                'table' => 'essays',
                'tableModel' => 'essays',
                'module' => 'essays',
                'customCriteria' => array(
                    'useRelatedModel' => true,
                    'conditionGeneration' => array(),
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
                'system' => 0,
                'enabled' => 0,
                'customCriteria' => array(
                    'useRelatedModel' => false,
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
            'issueArticles' => array(
                'route' => 'issueArticles',
                'table' => 'issue_articles',
                'tableModel' => 'issueArticles',
                'module' => 'issueArticles',
                'system' => 0,
                'enabled' => 0,
                'customCriteria' => array(
                    'useRelatedModel' => false,
                    'conditionGeneration' => array('class' => 'amcwm.modules.directory.components.ManageIssueArticlesCondition'),
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
                'seoImages' => true,
                'autoPost2social' => false,
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
                    'autoPost2social' => true,
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
        'breaking' => array(
            'postitions' => array(
//                'sisterPostition' => 4,
//                'sideColumn' => array(
//                    4, 1, 2
//                ),
            ),
            'default' => array(
                'check' => array(
                    'autoPost2social' => true,
                ),
                'integer' => array(
                    'breakingExpiredAfter' => 12 * 60 * 60,
                ),
            ),
        ),
        'essays' => array(
            'postitions' => array(
//                'sisterPostition' => 4,
//                'sideColumn' => array(
//                    4, 1, 2
//                ),
            ),
            'default' => array(
                'check' => array(
                    'autoPost2social' => true,
                ),
                'integer' => array(
                    'mainTopics' => 4,
                    'sticky' => 0,
                ),
                'post2social' => false,
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
        'articles' => array(
            'postitions' => array(
//                'sisterPostition' => 4,
//                'sideColumn' => array(
//                    4, 1, 2
//                ),
            ),
            'default' => array(
                'integer' => array('mainTopics' => 4),
                'post2social' => false,
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
