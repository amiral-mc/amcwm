<?php

return array(
    'name' => 'exchange',
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcExchangeController',
                'companies' => 'AmcExchangeCompaniesController',
                'trading' => 'AmcExchangeTradingController',
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
                            'roles' => array('editor'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('editor'),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('editor'),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('editor'),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                        'companies' => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                            'forwardTo' => array(
                                'controller' => 'companies',
                                'action' => 'index',
                            ),
                        ),
                        'trading' => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                            'forwardTo' => array(
                                'controller' => 'trading',
                                'action' => 'index',
                            ),
                        ),
                        "publish" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                        
                    ),
                ),
                'companies' => array(
                    'options' => array(
                        'hidden' => 0,
                    ),
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('editor'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('editor'),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('editor'),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('editor'),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                        "companies" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                        "translate" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                        "publish" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                    ),
                ),
                'trading' => array(
                    'options' => array(
                        'hidden' => 0,
                    ),
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('editor'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('editor'),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('editor'),
                        ),
                        "update" => array(
                            "perm" => 4,
                            'roles' => array('editor'),
                        ),
                        "delete" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                        "publish" => array(
                            "perm" => 8,
                            'roles' => array('editor'),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcStockController',
            ),
        ),
        'install' => array(
            'controllers' => array(
                'default' => array(
//                    'options' => array(
//                        'hidden' => 0,
//                    ),
                    "actions" => array(
                        "stock" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        "stockDetails" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                    ),
                ),
            ),
        ),
        'options' => array(
            'tickerLimit' => 4,
            'companiesGridLimit' => 5,
            'graphDaysLimit' => 5,
            'graphLabelsLimit' => 7,
        ),
    ),
);
