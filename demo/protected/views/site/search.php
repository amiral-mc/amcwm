<?php
$breadcrumbs[] = AmcWm::t("amcFront", "Site Search");
$this->widget('ext.MySearchWidget', array(
    'items' => $searchData,
    'contentType' => $contentType,
    'advancedParams' => $advancedParams,
    'page' => $page,
    'keywords' => $keywords,
    'routers' => $routers,
    'htmlOptions' => array('style' => 'padding-top:5px;',),
    'breadcrumbs' => $breadcrumbs,
));
?>
