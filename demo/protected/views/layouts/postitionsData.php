<?php
$currentAppLang = Yii::app()->getLanguage();
if (!$this->positionHasData(1, "sideColumn")) {
    $news = new ArticlesListData(array('news'), 0, 6);
    $news->addColumn('publish_date');
    $news->addOrder('publish_date desc');
    $news->generate();
    $newsList = $news->getItems();
    $this->setPositionData(1, $this->widget('widgets.NewsSideList', array('items' => $newsList, "title" => AmcWm::t("amcFront", 'News Center')), true), "sideColumn");
}

if (!$this->positionHasData(2, "sideColumn")) {

    $newsletterWidget = AmcWm::app()->executeWidget(
           "amcwm.modules.maillist.frontend.components.ExecuteSubscribe", 
            array('widget' => "ext.NewsletterWidget"),
            array('id' => 'newsletter_frm', 'title'=>AmcWm::t("app", 'Subscribe in Newsletter')),
            true
    );
    $this->setPositionData(2, $newsletterWidget, "sideColumn");
}