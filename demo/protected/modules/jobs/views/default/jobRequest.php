<?php
$pageContent =  '<p>' .AmcWm::t("app", '_JOBS_MODULE_INFO_'). '</p>';
$pageContent .=  '<p>' .AmcWm::t("app", '_JOBS_MODULE_DESC_'). '</p>';
$pageContent .= $this->renderPartial("jobForm", array(
    'model' => $model,
), true);
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/jobs/default/index'));
if(!$breadcrumbs){
    $breadcrumbs[AmcWm::t("msgsbase.core", 'Careers')] = array('/jobs/default/index');    
}
$breadcrumbs[] = AmcWm::t("msgsbase.request", 'Job Request');

$widgetImage = Data::getInstance()->getPageImage('jobs', null, null , null, '/jobs/default/index');


$this->widget('PageContentWidget', array(
    'id' => 'jobsList',
    'pageContentDesc' => AmcWm::t("app", '_JOBS_MODULE_INFO_'),
    'contentData' => $pageContent,
    'pageContentDescLength'=> 0,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Careers'),
));
?>