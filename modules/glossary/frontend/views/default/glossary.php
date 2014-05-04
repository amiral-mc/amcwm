<?php

$pageContent = $this->renderPartial("_glossary", array(
    'alphabet' => $alphabet,
    'glossaryData' => $glossaryData,
    'categoryId' => $categoryId,
    'categoriesList' => $categoriesList
), true);

$breadcrumbs = array(AmcWm::t("msgsbase.core", 'Glossary Data'));

$widgetImage = Data::getInstance()->getPageImage('glossary', null, null, Yii::app()->request->baseUrl . '/images/front/glossary.png');

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", 'Glossary Data'),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Glossary Data'),
));
?>