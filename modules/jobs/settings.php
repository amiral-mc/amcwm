<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'jobs',
        ),
        array(
            'id' => 2,
            'name' => 'jobs_categories',
        ),
        array(
            'id' => 3,
            'name' => 'jobs_requests',
        ),
        array(
            'id' => 4,
            'name' => 'users_cv',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcJobHomeController',
                'requests' => 'AmcJobRequestsController',
                'usersCvs' => 'AmcUsersCvsController',
                'jobs' => 'AmcJobsController',
                'categories' => 'AmcJobsCategoriesController',
            ),
        ),
        /**
         * Virtual example
         */
//        'virtual' => array(
//            'cvs' => array(
//                'route' => 'cvs',
//                'table' => 'cvs',
//                'tableModel' => 'Cvs',
//                'module' => 'cvs',
//                'views' => array(
//                    'index' => "index",
//                    'view' => "view",
//                    '_form' => "_form",
//                    'create' => "create",
//                    'update' => "update",
//                    'translate' => "translate",
//                ),
//            ),
//        ),
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
                    ),
                ),
                'requests' => array(
                    'options' => array(
                        'hidden' => 0,
                    ),
                    "actions" => array(
                        "index" => array(
                            "perm" => 1,
                            /**
                             * Virtual example: No roles for cvs module 
                             */
//                            'roles4Virtual' => array(
//                                'cvs'=>array(),
//                            ),
                            'roles' => array('admin'),
                        ),
                        "view" => array(
                            "perm" => 1,
                            /**
                             * Virtual example: editor for cvs module 
                             */
//                            'roles4Virtual' => array(
//                                'cvs' => array('editor'),
//                            ),
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
                        "accept" => array(
                            "perm" => 16,
                            'roles' => array('admin'),
                        ),
                        "shortList" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                        ),
                    ),
                ),
                'usersCvs' => array(
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
                        "accept" => array(
                            "perm" => 16,
                            'roles' => array('admin'),
                        ),
                    ),
                ),
                'categories' => array(
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
                'jobs' => array(
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
                        "shortList" => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                        ),
                        'usersCvs' => array(
                            "perm" => 32,
                            'roles' => array('admin'),
                            'forwardTo' => array(
                                'controller' => 'usersCvs',
                                'action' => 'index',
                            ),
                        ),
                        "requests" => array(
                            "perm" => 64,
                            'roles' => array('admin'),
                            'forwardTo' => array(
                                'controller' => 'requests',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcJobsController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(            
            'integer' => array(
                'allowUsersApply' => 0,
                'allowJobs' => 1
            ),
            'widgetImage' => '/images/front/careers.jpg',
        ),
    ),
    'media' => array(
        'maxFileSize' => 1 * 1024 * 1024,
        'extensions' => 'doc, docx, pdf, pdfx, rtf',
        'url' => null,
        'path' => 'multimedia/jobs',
    ),
);
