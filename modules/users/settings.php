<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'persons',
        ),
        array(
            'id' => 2,
            'name' => 'users',
        ),
        array(
            'id' => 3,
            'name' => 'roles',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcUsersController',
                'groups' => 'AmcGroupsController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcUsersController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(            
            'text' => array(
                'forgotFrom' => 'admin@localhost',
            ),
        ),
    ),
);
