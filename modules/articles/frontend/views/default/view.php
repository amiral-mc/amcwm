<?php

$pageContent = null;

$widgetImage = Data::getInstance()->getPageImage('articles', array($articleRecord['parent_img'], $articleRecord['page_img']), $articleRecord['sectionImage'], '');

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


if (count($articleRecord['titles'])) {
    $pageContent .= '<div class="content_sub_title">';
    foreach ($articleRecord['titles'] as $articleTitle) {
        $pageContent .="<h3  style='margin: 0px;'>{$articleTitle['title']}</h3>";
    }
    $pageContent .= '</div>';
}

if ($articleRecord['article_pri_header'] && $articleModule == "articles") {
    $title = $articleRecord['article_pri_header'] . " - " . $articleRecord['article_header'];
    $pageTitle = $title;
} else if ($articleRecord['article_pri_header']) {
    $title = $articleRecord['article_pri_header'] . " <br /> " . $articleRecord['article_header'];
    $pageTitle = $articleRecord['article_pri_header'] . " - " . $articleRecord['article_header'];
} 
else {
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

if (file_exists($this->getModule()->appModule->mediaPaths['images']['path'] . DIRECTORY_SEPARATOR . $articleRecord["article_id"] . "." . $articleRecord["thumb"]) && $articleModule == "news") {
    $options = $this->getModule()->appModule->options;        
    $useSeoImages = isset($options['default']['check']['seoImages']) && $options['default']['check']['seoImages'] ? $options['default']['check']['seoImages'] : false ;
    $seoTitle = ($useSeoImages) ? Html::seoTitle($articleRecord["article_header"]) . "." : "";
    $image = Yii::app()->baseUrl . "/" . $this->getModule()->appModule->mediaPaths['images']['path'] . "/{$seoTitle}" . $articleRecord["article_id"] . "." . $articleRecord["thumb"];
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
    $appendBefore[AmcWm::t($msgsBase, "Articles")] = array('/articles/default/sections', 'module' => $articleModuleId);
}

if(!isset($breadcrumbs))
    $breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/articles/default/sections', 'id' => $articleRecord['section_id']), false, $appendBefore);

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $pageContent,
    'title' => $title,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $pageContentTitle,
    'subItems' => $relatedArticles,
));