<?php

$extenstionAttributes = array('id' => 'sections_list');
$this->pageTitle = $data['pageSiteTitle'] . ' - ' . $this->pageTitle;
$virtualModule = $data['task']->getSettings()->currentVirtual;
$currentAppLang = Yii::app()->getLanguage();
$msgsBase = ($virtualModule == "articles") ? "msgsbase.core" : "msgsbase.{$virtualModule}";
$extension = "ContentTitlesList";
$removeLastItem = false;
$searchForm = '';

if ($virtualModule == "news" || $virtualModule == "essays") {
    //$extenstionAttributes['appendBefore'] = false;
    $extenstionAttributes['listingId'] = 'news_list';
    $searchForm = '<div id="news_search">';
    $searchForm .= '<div class="news_search_brief">' . AmcWm::t($msgsBase, "Articles Archive search") . '</div>';
    $searchForm .= '<form class="well form-search" id="NewssearchForm" method="get" action="' . Html::createUrl('/site/search', array('ct' => "news")) . '">';
    $searchForm .= '<div class="input-append">';
    $searchForm .= '<input name="q" style="width: 150px;" id="articles_q" type="text" placeholder="' . AmcWm::t('msgsbase.core', "Enter a Keyword") . '">';
    $searchForm .= '<span class="add-on">';
    $searchForm .= '<button type="submit" class="append-button">';
    $searchForm .= '<img src="' . Yii::app()->request->baseUrl . '/images/front/' . $currentAppLang . '/search_btn.png"></span></div>';
    $searchForm .= '</button>';
    $searchForm .= '</form>';
    $searchForm .= '</div>';
    $msgsBase = "msgsbase.news";
    $extension = "ArticlesLiListing";
    $params = $data['task']->getActionParams();
    $removeLastItem = !isset($params['id']);
    //$removeLastItem = !$data['sectionId'];    
}
else{
    $extenstionAttributes['descriptionLength'] = 600;
}

if (!$data['pageContentTitle']) {
    $data['pageContentTitle'] = AmcWm::t($msgsBase, "Articles");
}
if (!$data['widgetTitle']) {
    $data['widgetTitle'] = AmcWm::t($msgsBase, "Articles");
}


$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/articles/default/sections', 'id' => $data['sectionId']), $removeLastItem);
$msgsBase = "msgsbase.core";
if ($virtualModule != "articles") {
    $msgsBase = "msgsbase.{$virtualModule}";
}

if (!$breadcrumbs) {
    $breadcrumbs[] = AmcWm::t($msgsBase, "Articles");
}

if (isset($data['pageSiteTitle'])) {
    Yii::app()->clientScript->registerMetaTag($data['pageSiteTitle'], "description");
    Yii::app()->clientScript->registerMetaTag($data['pageSiteTitle'], "og:title");
}

if (isset($data['keywords'])) {
    Yii::app()->clientScript->registerMetaTag($data["keywords"]);
}

Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
$pageContentLabel = null;
$pageContentSloganLabel = $data['widgetTitle'];
if (count($breadcrumbs)) {
    $pageContentLabel = Data::getInstance()->getBreadcrumbsContentParentLabel($breadcrumbs);
}

if (!$pageContentLabel) {
    $pageContentLabel = $pageContentSloganLabel;
    $pageContentSloganLabel = null;
}

$widgetImage = Data::getInstance()->getPageImage('articles', $data['widgetImage'], null, '');
$extenstionAttributes['contentData'] = $searchForm;
$extenstionAttributes['pageContentDesc'] = $data['pageContent'];
$extenstionAttributes['pageContentPreTitle'] = $pageContentSloganLabel;

$extenstionAttributes['items'] = $data['itemsList'];
$extenstionAttributes['viewOptions'] = $data['viewOptions'];
//$extenstionAttributes['title'] =  $data['widgetTitle'];
$extenstionAttributes['image'] = $widgetImage;
$extenstionAttributes['breadcrumbs'] = $breadcrumbs;
$extenstionAttributes['descriptionKey'] = $data['descriptionKey'];
$extenstionAttributes['pageContentTitle'] = $pageContentLabel;
$this->widget("widgets.{$extension}", $extenstionAttributes);
?>