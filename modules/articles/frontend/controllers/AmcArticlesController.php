<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class AmcArticlesController extends FrontendController {

    /**
     * allow the given $positions ids and unset the other positions
     * @param array $positions
     * @access protected
     * @return void
     */
    protected function allowedPositions($positions = array(), $category = "sideColumn") {
        $module = $this->module->appModule->currentVirtual;
        $options = $this->module->appModule->options;
        $positions = null;
        if (isset($options[$module]['postitions']['sisterPostition'])) {
            $sisterPosition = $options[$module]['postitions']['sisterPostition'];
            if (isset($this->positions[$category]) && isset($options[$module]['postitions'][$category])) {
                $positions = $options[$module]['postitions'][$category];
            }
        }
        parent::allowedPositions($positions, $category);
        if (isset($sisterPosition)) {
            $this->setSisterPositionData($sisterPosition, $this->sisterOptions, $category);
        }
    }

    public function actionIndex() {
        
    }

    public function actionSections($id = null) {

        //sisterPostition
        $menuId = Yii::app()->request->getParam("menu");
        if ($menuId) {
            $this->sisterOptions = array('type' => 'menu', 'id' => $menuId);
        } else {
            $this->sisterOptions = array('type' => 'section', 'id' => $id);
        }
        $this->allowedPositions();
        $params = $this->generateTaskParams();
        $ok = $this->runTask($params);
        if (!$ok) {
            throw new CHttpException(404, AmcWm::t('msgsbase.core', 'The requested page does not exist'));
        }
    }

    protected function generateTaskParams() {
        $sectionId = Yii::app()->request->getParam("id");
        $settings = SectionsData::getSectionSettings($sectionId);
        $params = array();
        if ($settings) {
            foreach ($settings['radio'] as $k => $option) {
                if ($k == 'applyArticlesViewLinks' && $option)
                    $params['defaultView'] = 'links';
                
                if ($k == 'applySubSectionViewLinks' && $option){
                    $params['className'] = 'ArticlesSectionsSectionsTask';
                    $params['defaultView'] = 'list';
                }
                
                if ($k == 'showSubSections' && $option)
                    $params['className'] = 'ArticlesSectionsSectionsTask';

                if ($k == 'showMixed' && $option)
                    $params['className'] = 'ArticlesSectionsMixedTask';
            }
        }
        return $params;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $article = new ArticleData($id);
        $module = null;
        if ($article->getModuleName() != "articles") {
            $forwardModules = amcwm::app()->acl->getForwardModules();            
            foreach ($forwardModules as $forwardId => $forward) {
                if (isset($forward[$article->getModuleName()])) {
                    $module = $forwardId;
                    $_GET['module'] = $module;
                    break;
                }
            }
        }        
        $virtualCommentsRoute = $article->getModuleName() . "/comments/index";
        if (!$article->recordIsFound()) {
            //Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcFront", 'Sorry, the requested content is not found, or has been deleted, please check another page.')));
            //$this->redirect(array('/site/index'), true, 301);
            throw new CHttpException(404, AmcWm::t('msgsbase.core', 'The requested page does not exist'));
        }
        /**
         * model for new comments form 
         */
        $commentsModel = new Comments;
        $commentsModel->commentsOwners = new CommentsOwners;
        $repliesModel = new RepliesComments;
        $repliesModel->commentsOwners = new RepliesCommentsOwners;
        /**
         * Render the design params
         */
        $articleRecord = $article->getArticle();
        $articlesRelated = $article->getArticleSubs();
        $articleModule = $article->getModuleName();
//        $this->swapPosition(4, 1);
//        $this->unsetPosition(3);
        $menuId = Yii::app()->request->getParam("menu");
        if ($menuId) {
            $this->sisterOptions = array('type' => 'menu', 'id' => $menuId);
        } else {
            $this->sisterOptions = array('type' => 'article', 'id' => $articleRecord['section_id']);
        }
        $this->allowedPositions();
        $this->render('view', array(
            'articleRecord' => $articleRecord,
            'articlesRelated' => $articlesRelated,
            'articleModule' => $articleModule,
            'articleModuleId' => $module,
            'commentsModel' => $commentsModel,
            'repliesModel' => $repliesModel,
            'viewComments' => Yii::app()->user->checkRouteAccess($virtualCommentsRoute),
        ));
    }

    /**
     * Performs the comments action
     * @param int $aid
     * @access public 
     * @return void
     */
    public function actionComments($id) {
        $this->forward('comments');
    }

}
