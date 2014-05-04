<?php

if ($past) {
    $title = AmcWm::t("msgsbase.core", 'Events and Activities');
    $eventIcon = "calendar_date.png";
} else {
    if ($viewNext) {
        $title = AmcWm::t("msgsbase.core", 'Events and Activities');
    } else {
        $title = AmcWm::t("msgsbase.core", 'Agenda Reports for {day}', array('{day}' => Yii::app()->dateFormatter->format('EEEE dd-MM-yyyy', $date)));
    }
    $eventIcon = "event_icon.png";
}
$pageContent = $this->renderPartial('_index', array(
    'eventData' => $eventData,
    'date' => $date,
    'past' => $past,
    'viewNext' => $viewNext,
    'pastData' => $pastData,
    'eventIcon' => $eventIcon,
        ), true);
$breadcrumbs = array();
if (isset($section['section_id'])) {
    $breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/events/default/index', 'id' => $section['section_id']));
}

Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");

$widgetImage = Data::getInstance()->getPageImage('events', null, $section['sectionImage'], null);

$this->widget('PageContentWidget', array(
    'id' => 'events_list',
    'contentData' => $pageContent,
    'title' => $title,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $section['section_name'],
));
?>
