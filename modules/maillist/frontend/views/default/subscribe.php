<?php
$widgetImage = Data::getInstance()->getPageImage('maillist');    
$msg = AmcWm::app()->request->getParam('msg');
if (!$msg) {
    $form = (AmcWm::app()->frontend['bootstrap']['use']) ? '_subscribeBootstrapForm' : '_subscribeForm';
    $pageContent = $this->renderPartial($form, array("model" => $model, 'channels' => $channels), true);
} else {
    $pageContent = $msg;
}
$breadcrumbs[] = AmcWm::t("msgsbase.core", "Newsletter");
$this->widget('PageContentWidget', array(
    'id' => 'subsrcibe_maillist',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", "Subscribe to our newsletter"),
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Subscribe to our newsletter'),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
));
?>
