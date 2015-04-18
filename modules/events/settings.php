<?php

return array(
    'tables' => array(
        't1'=>array(
            'id' => 1,
            'name' => 'events',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcEventsController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcEventsController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'integer' => array(
                'topList' => 6,
            ),
            'text' => array(
                'sectionImage' => 'images/front/events.png',
            ),
        ),
    ),
);
