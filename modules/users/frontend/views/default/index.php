<?php

$userPageData = $this->widget('amcwm.core.widgets.modulesTools.ModulesTools', array(
    'id' => 'tools-grid',
    'items' => $userApps,
        ), true);


$breadcrumbs[] = AmcWm::t("msgsbase.core", "Member Area");
$this->widget('PageContentWidget', array(
    'id' => 'siteMap',
    'contentData' => $userPageData,
    'title' => AmcWm::t("msgsbase.core", 'Member Area'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>
