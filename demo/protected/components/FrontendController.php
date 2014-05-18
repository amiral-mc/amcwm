<?php

class FrontendController extends AmcFrontendController {

    /**
     *
     * @var array Memberships & Joint ventures data
     */
    public $memberships = array();
    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     * @access public
     * @return $void
     */
    public function init() {
        $memberships = new SectionArticlesData("articles", AmcWm::app()->params['reservedContent']['membershipsJointVentures'], null);
        $articlesSetting = ArticlesListData::getSettings();        
        $memberships->setArticleMediaPath(Yii::app()->baseUrl . "/" . $articlesSetting->mediaPaths['images']['path'] . "/");
        $memberships->generate();
        $this->memberships['articles'] = array();
        if($memberships->getArticles()){
            $this->memberships['articles'] = $memberships->getArticles()->getItems();
        }
        $this->memberships['section'] = $memberships->getItems();
        parent::init();
    }
}
