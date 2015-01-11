<?php

return array(
    'tables' => array(
        't1' => array(
            'id' => 1,
            'name' => 'products',
            'translation' => array(
                'id' => 8,
                'key' => 'product_id',
                'name' => 'products_translation',
            ),
            'key' => 'product_id',
            'sorting' => array('sortField' => "product_sort", 'order' => 'asc'),
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcProductsController',
                'replies' => 'AmcRepliesController',
                'productComments' => 'AmcCommentsController',
                'gallery' => 'AmcGalleryController',
            ),
        ),
        'install' => array(
            'controllers' => array(
                'default' => array(
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
                        "sort" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                        ),
                        'comments' => array(
                            "perm" => 64,
                            'roles' => array('admin'),
                            'forwardTo' => array(
                                'controller' => 'comments',
                                'action' => 'index',
                            ),
                        ),
                        'gallery' => array(
                            "perm" => 128,
                            'roles' => array('admin'),
                            'forwardTo' => array(
                                'controller' => 'gallery',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
                'comments' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('admin'),
                        ),
                        "view" => array(
                            "perm" => 1,
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
                        "hide" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                        ),
                        'replies' => array(
                            "perm" => 64,
                            'roles' => array('admin'),
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
                        ),
                        "view" => array(
                            "perm" => 1,
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
                        "hide" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'integer' => array(
                'mainSection' => 1,
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcProductsCategoriesController',
                'comments' => 'AmcCommentsController',
                'replies' => 'AmcRepliesController',
            ),
        ),
        'install' => array(
            'controllers' => array(
                'default' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        "category" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        'comments' => array(
                            "perm" => 32,
                            'roles' => array('guest'),
                            'forwardTo' => array(
                                'controller' => 'comments',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
                'comments' => array(
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('guest'),
                        ),
                        'replies' => array(
                            "perm" => 64,
                            'roles' => array('guest'),
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
                        ),
                        "view" => array(
                            "perm" => 1,
                            'roles' => array('guest'),
                        ),
                        "create" => array(
                            "perm" => 2,
                            'roles' => array('guest'),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
