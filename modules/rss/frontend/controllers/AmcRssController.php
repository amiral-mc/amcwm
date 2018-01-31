<?php
AmcWm::import("amcwm.modules.rss.frontend.components.feed.*");
/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcRssController extends FrontendController {

    private function _draw($rssItems, $limit = 100, $start = 0, $sectionId = null) {
        $view = "rss";
        if (!$this->__getUserAccess()) {
            $rssItems = array();
            $rssItems[0]['title'] = AmcWm::t("amcFront", 'You are not authorized to view this page');
            $rssItems[0]['link'] = Yii::app()->request->baseUrl;
            $rssItems[0]['publish_date'] = date("Y-m-d H:i:s");
            $view = "rssHeading";
        }
        $type = Yii::app()->request->getParam("type", "rss");
        $callback = Yii::app()->request->getParam("callback");
        switch ($type) {
            case 'rss':
                $this->renderPartial($view, array('rssItems' => $rssItems));
                break;
            case 'json':
                $output = new stdClass();
                $output->errors = 0;
                $output->news = $rssItems;
                $jsonEncode = CJavaScript::jsonEncode($output);
                if ($callback) {
                    echo $callback . '(' . $jsonEncode . ')';
                } else {
                    echo $jsonEncode;
                }
                break;
        }
        Yii::app()->end();
    }

    private function _rssList() {
        $this->render('index', array('sections' => Data::getInstance()->getSectionsTree()));
    }

    private function __getUserAccess() {
        /**
         * 0 no access
         * 1 Guest Access
         * 2 User access
         */
        $actionsPermission['index'] = array(
            RssSiteData::SHORT_STORY => 1,
            RssSiteData::FULL_STORY => 2,
            RssSiteData::HEADING_STORY => 1,
        );
        $actionsPermission['breaking'] = array(
            RssSiteData::SHORT_STORY => 1,
            RssSiteData::FULL_STORY => 2,
            RssSiteData::HEADING_STORY => 1,
        );
        $action = $this->getAction()->getId();
        $storyType = Yii::app()->request->getParam("st", RssSiteData::SHORT_STORY);
        $allow = false;
        $perm = 0;
        if (array_key_exists($action, $actionsPermission)) {
            $perm = $actionsPermission[$action][$storyType];
            switch ($perm) {
                case 0:
                    $allow = false;
                    break;
                case 1:
                    $allow = true;
                    break;
                case 2:
                    $accessTokenCheck = true;
                    $allow = $accessTokenCheck;
            }
        }
        return $allow;
    }

    public function actionIndex($limit = 100, $start = 0, $list = TRUE, $sectionId = null) {
        if (!$list) {
            $storyType = Yii::app()->request->getParam("st", RssSiteData::SHORT_STORY);
            $table = Yii::app()->request->getParam("tb", 'news');
            $rssData = new ArticlesRssData(array($table), 0, $limit, $sectionId);
            $rssData->setUseCount(false);
            if($table == 'news'){
                $rssData->forceUseIndex = '';
                $rssData->addOrder('publish_date desc');
            }
            $rssData->setStoryType($storyType);
            $rssData->generate();
            $this->_draw($rssData->getItems(), $limit, $start, $sectionId);
        } else {
            $this->_rssList();
        }
    }

    public function actionBreaking($limit = 100, $start = 0, $sectionId = null) {
        $storyType = Yii::app()->request->getParam("st", RssSiteData::SHORT_STORY);
        $rssData = new ArticlesRssData(array("news"), 0, $limit, $sectionId);
        $rssData->setUseCount(false);
        $rssData->setStoryType(RssSiteData::SHORT_STORY);
        $rssData->setIsBreaking(true);
        $rssData->generate();
        $rssItems = $rssData->getItems();
        if (!count($rssItems)) {
            $rssData = new ArticlesRssData(array("news"), 0, $limit, $sectionId);
            $rssData->setStoryType(RssSiteData::SHORT_STORY);
            $rssData->generate();
        }
        $this->_draw($rssData->getItems(), $limit, $start, $sectionId);
    }

}