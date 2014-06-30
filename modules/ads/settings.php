<?php

return array(
    'name' => 'ads',   
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcAdsController',
                'servers' => 'AmcServersController',
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
                        'servers' => array(
                            "perm" => 16,
                            'roles' => array('editor'),
                            'forwardTo' => array(
                                'controller' => 'servers',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
                'servers' => array(
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
