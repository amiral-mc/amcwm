<?php

return array(
    'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'persons',
        ),
        't2'=>array(
            'id' => 2,
            'name' => 'users',
        ),
        't3'=>array(
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
