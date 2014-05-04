<?php

return array(
     'tables' => array(
        array(
            'id' => 1,
            'name' => 'votes_questions',
        ), 
        array(
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
