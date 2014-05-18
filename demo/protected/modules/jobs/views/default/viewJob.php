<?php
$pageContent =  '<p>' .AmcWm::t("app", '_JOBS_MODULE_INFO_'). '</p>';
$pageContent .=  '<p>' .AmcWm::t("app", '_JOBS_MODULE_DESC_'). '</p>';
$pageContent .= "<div class='job_details'>";
$pageContent .= "  <h2>" . $jobDetails['name'] . "</h2>";
$pageContent .= "  <div class='date'>" . AmcWm::t('msgsbase.core', 'Publish Date') . ": {$jobDetails['publish_date']}</div>";
if ($jobDetails['expire_date']) {
    $pageContent .= "  <div class='date'>" . AmcWm::t('msgsbase.core', 'Expire Date') . ": {$jobDetails['expire_date']}</div>";
}
$pageContent .= "  <div class='applybtn'>" . CHtml::button(AmcWm::t('msgsbase.core', 'Apply Now'), array('onclick' => "document.location.href= '" . Html::createUrl('/jobs/default/request', array('id' => $jobDetails['job_id'], 'title' => CHtml::encode($jobDetails['name']))) . "'")) . "</div>";
$pageContent .= "  <div>{$jobDetails['description']}</div>";
if(trim($jobDetails['description'])){
    $pageContent .= "<div class='applybtn'>" . CHtml::button(AmcWm::t('msgsbase.core', 'Apply Now'), array('onclick' => "document.location.href= '" . Html::createUrl('/jobs/default/request', array('id' => $jobDetails['job_id'], 'title' => CHtml::encode($jobDetails['name']))) . "'")) . "</div>";
}

$pageContent .= "</div>";
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/jobs/default/index'));
if(!$breadcrumbs){
    $breadcrumbs[AmcWm::t("msgsbase.core", 'Careers')] = array('/jobs/default/index');    
}
$breadcrumbs[] = AmcWm::t("msgsbase.core", 'View Job');
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