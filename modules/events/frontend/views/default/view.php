<?php

$breadcrumbs = array();

$pageContent = $this->renderPartial('_view', array(
    'date' => $date,
    'event' => $event,
    'pastData' => $pastData,
        ), true);

if (isset($event['section_id'])) {
    $breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/events/default/index', 'id' => $event['section_id'], 'title' => $event["event_header"]));
} 
else {
    $breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/events/default/index', 'title' => $event["event_header"]));
}

$title = ($event['section_name']) ? $event['section_name'] : AmcWm::t("msgsbase.core", 'Events and Activities');
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");

$widgetImage = Data::getInstance()->getPageImage('events', null, $event['sectionImage'], null);

$this->widget('PageContentWidget', array(
    'id' => 'events_list',
    'contentData' => $pageContent,
    'title' => $event["event_header"],
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $title,
));
?>
