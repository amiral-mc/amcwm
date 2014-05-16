<?php

$breadcrumbs[] = AmcWm::t("amcFront", "Site Map");
$this->widget('amcwm.core.widgets.SiteMapWidget', array(
    'id' => 'siteMap',
    'items' => $siteMapItems,
    'pagePathClass' => 'page_path_wide',
    'bottomClass' => 'page_content_footer_wide',
    'title' => AmcWm::t("amcFront", "Site Map"),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>