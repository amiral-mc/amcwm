<?php

return array(
     'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'glossary',
        ),
        't2'=>array(
            'id' => 2,
            'name' => 'glossary_categories',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcGlossaryController',
                'categories' => 'AmcGlossaryCategoriesController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcGlossaryController',
            ),
        ),
    ),  
);
