<?php

return array(
     'tables' => array(
        array(
            'id' => 1,
            'name' => 'persons',
        ), 
        array(
            'id' => 2,
            'name' => 'writers',
        ), 
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcWritersController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcWritersController',
            ),
        ),
    ),  
);
