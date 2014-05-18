<?php
$pageContent =  '<p>' .AmcWm::t("app", '_JOBS_MODULE_DESC_'). '</p>';
$pageContent .= $this->renderPartial("jobsList", array(    
    'jobs' => $jobs,
), true);

$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/jobs/default/index'));
if(!$breadcrumbs){
    $breadcrumbs[AmcWm::t("msgsbase.core", 'Careers')] = array('/jobs/default/index');    
}

$widgetImage = Data::getInstance()->getPageImage('jobs', null, null);
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
