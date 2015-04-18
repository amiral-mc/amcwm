<?php

return array(
     'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'votes_questions',
        ), 
        't2'=>array(
            'id' => 2,
            'name' => 'votes_options',
        ), 
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcVotesController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcVotesController',
            ),
        ),
    ),  
);
