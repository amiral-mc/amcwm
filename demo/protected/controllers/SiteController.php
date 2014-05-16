<?php

class SiteController extends HomeFrontendController {

    public function beforeRenderLogin(&$view, &$data) {
        $this->layout = '//layouts/fullPage';
    }
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function beforeRenderIndex(&$view, &$data) {
        //$this->usePostitions = false;
        $this->layout = '//layouts/home';      
    }
    
    public function actionSiteMap() {
        $this->layout = '//layouts/fullPage';
        $this->render('siteMap', array('siteMapItems' => SiteMap::getInstance()->getItems()));
    }

}
