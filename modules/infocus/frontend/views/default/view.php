<?php
/**
 * @toda implment breadcrumbs same as articles
 */

$this->widget('widgets.infocus.InfocusWidget', array(
    'items' => $infocusResults,
    'infocusData'=>$infocusData,
    'contentType' => $contentType,
    'advancedParams' => $advancedParams,
    'page' => $page,
    'keywords' => $keywords,
    'routers' => $routers,
    'htmlOptions' => array('style' => 'padding-top:5px;',),
    'breadcrumbs' => array(),
));


//$this->widget('widgets.InfocusWidget', array(
//    'data' => $infocusItems,
//    'contentType' => $contentType,
//    'page' => $page,
//    'focusId' => $id,
//    'routers' => Yii::app()->params['routers'],
//    'htmlOptions' => array('style' => 'padding-top:5px;',),
//));
//?>