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
class AmcIssuesController extends BackendController {    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $issueData = Issue::getInstance()->getIssue();
        $model = new Issues;
        if (!$issueData['lastNotActive']['issue_id']) {
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (Yii::app()->request->isPostRequest) {
                //$model->attributes = $_POST['Issues'];
                if ($model->addNewIssue()) {
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'New issue has been added')));
                    $this->redirect(array('index', 'issue' => $model->issue_id, 'id' => $model->issue_id));
                }
            }

            $this->render('create', array(
                'model' => $model,
            ));
        }
        else{
            $this->redirect(array('index'));
        }
        
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $model = new Issues('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Issues']))
            $model->attributes = $_GET['Issues'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($pissue) {
        $id = $pissue;
        $model = $this->loadModel($id);
        $issueData = Issue::getInstance()->getIssue();
        if ($model->publish(true)) {
            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'issue : "{issue}" has been published', array("{issue}" => $id))));
        } else {
            Yii::app()->user->setFlash('success', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.core", 'Can not publish issue : "{issue}"', array("{issue}" => $id))));
        }
        $this->redirect(array('index'));
    }

    /**
     * Change issue number
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionChange($issue) {
        Issue::getInstance()->changeIssue();
        $this->redirect(array('/site/index', 'issue' => $issue));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Issues::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'issues-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    
    public function actionIssueArticles($issueId) {
        $settings = new Settings("articles", true);
        $virtualId = $settings->getVirtualId($this->getAction()->getId());
        $_GET['module'] = $virtualId;
        $_GET['issueId'] = $issueId;
        $this->forward('/backend/articles/default/index');
    }
    
    
    public function actionIssueSections() {
        $this->forward('/backend/issues/sections');
    }

}