<?php

return array(
     'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'persons',
        ), 
        't2'=>array(
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
