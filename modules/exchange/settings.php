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
                            "perm" => 16,
                            'roles' => array('editor'),
                            'forwardTo' => array(
                                'controller' => 'companies',
                                'action' => 'index',
                            ),
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
                    ),
                ),
            ),
        ),
    ),
);
