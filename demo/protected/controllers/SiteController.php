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

    public function actionWater() {
        $imageLayer = AmcWm::app()->imageworkshop->initFromPath(AmcWm::app()->basePath . '/../resources/images/sample1.jpg');
        $watermarkLayer = AmcWm::app()->imageworkshop->initFromPath(AmcWm::app()->basePath . '/../resources/images/w.png');
        $watermarkLayer->opacity(40);
        $imageLayer->addLayerOnTop($watermarkLayer, 12, 12, "LB");
        $image = $imageLayer->getResult();
        header('Content-type: image/jpeg');
        imagejpeg($image, null, 95); // We chose to show a JPG with a quality of 95%
        exit;
    }

}
