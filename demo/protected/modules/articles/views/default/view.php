<?php
$pageContent = null;
$widgetImage = Data::getInstance()->getPageImage('articles', array($articleRecord['parent_img'], $articleRecord['page_img']), $articleRecord['sectionImage'], '');
//die($widgetImage);
$relatedArticles = array();
if (count($articlesRelated)) {
    foreach ($articlesRelated as $v) {
        $relatedArticles[] = array(
            'title' => $v['article_header'],
            'url' => array('/articles/default/view', 'id' => $v['article_id'], 'title' => $v['article_header']),
            'active' => ($v['article_id'] == $articleRecord['article_id']),
        );
    }
}
$appendContent = '';
$preHeader = $articleRecord['article_pri_header'];
$title = $articleRecord['article_header'];
if ($articleModule == "news") {
    if (isset(Yii::app()->params['custom']['front']['site']['news_title'])) {
        $appendContent .='<div class="page_title">' . Yii::app()->params['custom']['front']['site']['news_title'] . '</div>';
    }
    if (isset(Yii::app()->params['custom']['front']['site']['news_title_info'])) {
        $appendContent .='<div class="page_pre_title">' . Yii::app()->params['custom']['front']['site']['news_title_info'] . '</div>';
    }
}
else if($widgetImage){
    $pageContent .= '<div id="content_page_haeder" style="background:url(\''.$widgetImage .'\') no-repeat right;">
    <div class = "titles">
    <div class = "pre_title">' . $preHeader . '</div>
    <div class = "main_title">'. $title . '</div>
    </div>
    </div>';
    $preHeader = null;
}
if (count($articleRecord['titles'])) {
    $pageContent .= '<div class="content_sub_title">';
    foreach ($articleRecord['titles'] as $articleTitle) {
        $pageContent .="<h3  style='margin: 0px;'>{$articleTitle['title']}</h3>";
    }
    $pageContent .= '</div>';
}

if ($articleRecord['article_pri_header'] && $articleModule == "articles") {
    $pageTitle = $title;
} else if ($articleRecord['article_pri_header']) {
    $breadcrumbTitle = $preHeader;
    $pageTitle = $articleRecord['article_pri_header'] . " - " . $articleRecord['article_header'];
} else {
    $title = $articleRecord['article_header'];
    $pageTitle = $title;
}

if ($articleRecord['parentData']) {
    if ($articleRecord['parent_article']) {
        //$title = Html::link($articleRecord['parentData']['article_header'], array('/articles/default/view', 'id' => $articleRecord['parentData']['article_id'], 'title' => $articleRecord['parentData']['article_header'])) . ' » ' . $title;
        $title = Html::link($articleRecord['parentData']['article_header'], array('/articles/default/view', 'id' => $articleRecord['parentData']['article_id'], 'title' => $articleRecord['parentData']['article_header']));
    } else {
        $title = $articleRecord['parentData']['article_header'];
    }
}

$this->pageTitle = $pageTitle . ' - ' . $this->pageTitle;
if ($articleModule == "news") {
    $pageContent .='<div class="date">' . Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $articleRecord['publish_date']) . '</div>';

    if (file_exists($this->getModule()->appModule->mediaPaths['images']['path'] . DIRECTORY_SEPARATOR . $articleRecord["article_id"] . "." . $articleRecord["thumb"])) {
        $image = Yii::app()->baseUrl . "/" . $this->getModule()->appModule->mediaPaths['images']['path'] . "/" . $articleRecord["article_id"] . "." . $articleRecord["thumb"];
        Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . $image, "og:image");
        $imageSize = getimagesize("multimedia/articles/" . $articleRecord["article_id"] . "." . $articleRecord["thumb"]);
        $pageContent .= '<div class="content-img-container" style="width:' . $imageSize['0'] . 'px;">';
        $pageContent .= "<div><img class='content_img' src='{$image}' alt='" . CHtml::encode($articleRecord["article_header"]) . "' title='" . CHtml::encode($articleRecord["article_header"]) . "'/></div>";
        if ($articleRecord["image_description"]) {
            $pageContent .= "<h3 class='content_img_desc' style='width:{$imageSize['0']}px;'>" . $articleRecord["image_description"] . "</h3>";
        }
        $pageContent .= '</div>';
    } else {
        Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
    }
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
$appendBefore = array();
if ($articleModule == "news") {
    $appendBefore[AmcWm::t($msgsBase, "Articles")] = array('/articles/default/sections', 'module' => $articleModuleId);
}

if (!isset($breadcrumbs)) {
    $breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/articles/default/sections', 'id' => $articleRecord['section_id']), false, $appendBefore);
    if (!AmcWm::app()->request->getParam('menu'))
        $breadcrumbs[] = $title;
}
//die($articleModule);
//die($widgetImage);
$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $pageContent,
    'appendBefore' => $appendContent,
    'title' => $title,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'preHeader' => $preHeader,
    'subItems' => $relatedArticles,
));
