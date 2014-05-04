<?php

return array(
     'tables' => array(
        array(
            'id' => 1,
            'name' => 'glossary',
        ),
        array(
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
