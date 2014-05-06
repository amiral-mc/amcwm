<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class AmcCommentsController extends CommentsController {

    protected $componentName = "";

    /**
     * Gets the comments type id
     * @access public
     * @return integer
     */
    public function getItemId() {
        $id = 0;
        if ($this->commentsType != null) {
            $id = $this->commentsType->article_id;
        }
        return $id;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    protected function loadCommentsData($id) {
        parent::loadCommentsData($id);
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $this->commentsType = ArticlesTranslation::model()->findByPk(array("article_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($this->commentsType == null) {
            throw new CHttpException(404, 'The requested type does not exist.');
        } else {
            $this->breadcrumbs = array(
                AmcWm::t("msgsbase.core", "Articles") => array("/backend/articles/default/index"),
                $this->commentsType->displayTitle => array("/backend/articles/default/view", 'id' => $this->getItemId()),
            );

            $backUrl = array("/backend/articles/default/index");
            $virtual = AmcWm::app()->appModule->getCurrentVirtual();
            $virtuals = AmcWm::app()->appModule->getVirtuals();
            if (isset($virtuals[$virtual]['redirectParams'])) {
                foreach ($virtuals[$virtual]['redirectParams'] as $p) {
                    $backUrl[$p] = AmcWm::app()->request->getParam($p);
                }
            }
            $this->backRoute = $backUrl;
        }
    }

    /**
     * @create new model
     * @access protected
     * @return ActiveRecord
     */
    protected function newModel() {
        $model = new ArticlesComments('search');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {

        $model = ArticlesComments::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === Yii::app()->params["adminForm"]) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionReplies($item, $cid) {
        $this->forward('replies/');
    }

}
