<?php
$widgetImage = Data::getInstance()->getPageImage('maillist');    
$form = (AmcWm::app()->frontend['bootstrap']['use']) ? '_unsubscribeBootstrapForm' : '_unsubscribeForm';
$pageContent = $this->renderPartial($form, array("model" => $model), true);
$breadcrumbs[] = AmcWm::t("msgsbase.core", "Newsletter");
$this->widget('PageContentWidget', array(
    'id' => 'unsubscribe-maillist',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", "Unsubscribe"),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
));
?>
