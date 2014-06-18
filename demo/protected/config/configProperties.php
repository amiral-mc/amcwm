<?php

return array(
    'configProperties' => array(
        array(
            'name' => 'title',
            'type' => 'textField',
            'htmlOptions' => array('id' => 'congfigTitle', 'required' => 'true'),
            'visible' => true,
        ),
        array(
            'name' => 'keywords',
            'type' => 'textarea',
            'htmlOptions' => array('id' => 'congfigKeywords', 'required' => 'true'),
            'visible' => true,
        ),
        array(
            'name' => 'description',
            'type' => 'textarea',
            'htmlOptions' => array('id' => 'congfigDescription', 'required' => 'true'),
            'visible' => true,
        ),
        array(
            'name' => 'news_title',
            'type' => 'textField',
            'htmlOptions' => array('id' => 'configNewsTitle', 'required' => 'true'),
            'visible' => true,
        ),
        array(
            'name' => 'news_title_info',
            'type' => 'textField',
            'htmlOptions' => array('id' => 'configNewsTitleInfo', 'required' => 'true'),
            'visible' => true,
        ),
    ),
    'limits' => array(
        'elements' => array("min" => 0, "max" => 50),
        'wordsCount' => array("min" => 1, "max" => 4),
        'delimiter' => PHP_EOL,
    ),
);
?>
