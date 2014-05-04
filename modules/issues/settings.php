<?php

return array(
   'tables' => array(
        array(
            'id' => 1,
            'name' => 'issues',
        ), 
        array(
            'id' => 2,
            'name' => 'issues_articles',
        ), 
        array(
            'id' => 3,
            'name' => 'sections_issues',
        ), 
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcIssuesController',
                'sections' => 'AmcIssueSectionsController',
            ),
        ),
        'install' => array(
            'options' => array(
                'system' => 0,
                'workflow' => 0,
            ),
            'controllers' => array(
                'default' => array(
                    'options' => array(
                        'hidden' => 0,
                    ),
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                        "change" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('admin'),
                        ),
                        "publish" => array(
                            "perm" => 16,
                            'roles' => array('admin'),
                        ),
                        "issueArticles" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                        "issueSections" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                    ),
                ),
                'sections' => array(
                    'options' => array(
                        'hidden' => 0,
                    ),
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('admin'),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('admin'),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('admin'),
                        ),
                        "publish" => array(
                            "perm" => 16,
                            'roles' => array('admin'),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcIssuesController',
            ),
        ),
    ),  
);
