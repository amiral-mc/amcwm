<?php

$pageContent = null;

$widgetImage = Data::getInstance()->getPageImage('directory', $articleRecord['sectionImage'], null, Yii::app()->request->baseUrl . '/images/front/company_dir.png');

$view = AmcWm::app()->request->getParam('v');
$relatedArticles = array();
$relatedArticles[] = array(
    'title' => AmcWm::t("msgsbase.core", 'Description'),
    'url' => array('/directory/default/view', 'id' => $directoryData['company_id'], 'v'=>'desc'),
    'active' => ($view == 'desc'),
);

if (count($directoryArticles)) {
    foreach ($directoryArticles as $v) {
        $relatedArticles[] = array(
            'title' => $v['title'],
            'url' => $v['link'],
            'active' => ($v['id'] == $articleRecord['article_id']),
        );
    }
}

$relatedArticles[] = array(
    'title' => AmcWm::t("msgsbase.core", 'Maps'),
    'url' => array('/directory/default/view', 'id' => $directoryData['company_id'], 'v' => 'maps'),
    'active' => ($view == 'maps'),
);

$relatedArticles[] = array(
    'title' => AmcWm::t("msgsbase.core", 'Contact'),
    'url' => array('/directory/default/view', 'id' => $directoryData['company_id'], 'v'=>'contact'),
    'active' => ($view == 'contact'),
);


if (count($articleRecord['titles'])) {
    $pageContent .= '<div class="content_sub_title">';
    foreach ($articleRecord['titles'] as $title) {
        $pageContent .="<h3  style='margin: 0px;'>{$title['title']}</h3>";
    }
    $pageContent .= '</div>';
}

if ($articleRecord['article_pri_header'] && $articleModule == "articles") {
    $title = $articleRecord['article_pri_header'] . " - " . $articleRecord['article_header'];
    $pageTitle = $title;
} else if ($articleRecord['article_pri_header']) {
    $title = $articleRecord['article_pri_header'] . " <br /> " . $articleRecord['article_header'];
    $pageTitle = $articleRecord['article_pri_header'] . " - " . $articleRecord['article_header'];
} else {
    $title = $articleRecord['article_header'];
    $pageTitle = $title;
}

if ($articleRecord['parentData']) {
    if ($articleRecord['parent_article']) {
        $title = Html::link($articleRecord['parentData']['article_header'], array('/articles/default/view', 'id' => $articleRecord['parentData']['article_id'], 'title' => $articleRecord['parentData']['article_header'])) . ' » ' . $title;
    } else {
        $title = $articleRecord['parentData']['article_header'];
    }
}

$this->pageTitle = $pageTitle . ' - ' . $this->pageTitle;

if (file_exists($this->getModule()->appModule->mediaPaths['images']['path'] . DIRECTORY_SEPARATOR . $articleRecord["article_id"] . "." . $articleRecord["thumb"]) && $articleModule == "news") {
    $image = Yii::app()->baseUrl . "/" . $this->getModule()->appModule->mediaPaths['images']['path'] . "/" . $articleRecord["article_id"] . "." . $articleRecord["thumb"];
    Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . $image, "og:image");
    $imageSize = getimagesize("multimedia/articles/" . $articleRecord["article_id"] . "." . $articleRecord["thumb"]);
    
    $pageContent .= '<div style="margin:5px auto; width:' . $imageSize['0'] . 'px;">';
    $pageContent .= "<div><img class='content_img' src='{$image}' alt='" . CHtml::encode($articleRecord["article_header"]) . "' title='" . CHtml::encode($articleRecord["article_header"]) . "'/></div>";
    if ($articleRecord["image_description"]) {
        $pageContent .= "<h3 class='content_img_desc' style='width:{$imageSize['0']}px;'>" . $articleRecord["image_description"] . "</h3>";
    }
    $pageContent .= '</div>';
} else {
    Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
}

if (trim($articleRecord["source"]) != '') {
    $pageContent .= '<div class="content_detail_source">';
    $pageContent .= $articleRecord["source"];
    $pageContent .= '</div>';
}

$pageContent .= $articleRecord["article_detail"];
//$params = $data['task']->getActionParams();
//echo $articleRecord['section_id'];
$msgsBase = ($articleModule == "articles") ? "msgsbase.core" : "msgsbase.{$articleModule}";
$pageContentTitle = $articleRecord['section_name'];

if (!$articleRecord['section_id']) {
    $pageContentTitle = AmcWm::t($msgsBase, "Articles");
}

$appendBefore = array();
if ($articleModule == "news") {
    $appendBefore[AmcWm::t($msgsBase, "Articles")] = array('/articles/default/sections', 'module' => 22);
}


//$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/articles/default/sections', 'id' => $articleRecord['section_id']), false, $appendBefore);
$breadcrumbs = array(
    AmcWm::t("msgsbase.core", 'Companies Directory') => array('/directory/default/countryList'),
    $directoryData['company_name']
);

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $pageContent,
    'title' => $directoryData['company_name'],
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $pageContentTitle,
    'subItems' => $relatedArticles,
));