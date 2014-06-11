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

class AmcArticlesController extends BackendController {   

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     */
    public function init() {
        parent::init();
        $this->manager = new ManageArticles(true);
    }

    /**
     * Lists all models.
     */
    public function actionIndex($wajax = false) {
        $this->manager->index($wajax);
    }

    /**
     * Performs the sort action
     * @param  int $id the ID of the model to be sorted
     * @access public 
     * @return void
     */
    public function actionSort($id, $direction) {
        $this->manager->sort($id, $direction);
        $model = $this->loadModel($id);
        $model->sort($direction);
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Article "{article}" has been sorted', array("{article}" => $model->getCurrent()->article_header))));
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Performs the comments action
     * @param int $aid
     * @access public 
     * @return void
     */
    public function actionComments($item) {
        $this->forward('articleComments/');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->manager->create();
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->manager->update($id);
    }

    /**
     * translate a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionTranslate($id) {
        $this->manager->translate($id);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        return $this->manager->loadChildModel($id);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Persons $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return PersonsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        return $this->manager->loadTranslatedModel($model, $id);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return Sections
     */
    public function loadModel($id) {
        return $this->manager->loadModel($id);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->manager->view($id);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $this->manager->delete();
    }
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDeleteApproval($id, $undo = 0) {
        $this->manager->deleteApproval($id, $undo);
    }
    
    
     /**
     * approve a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionContentApproval($id, $undo = 0) {
        $this->manager->contentApproval($id, $undo, ManageArticles::EDIT_APPROVAL);
    }
    
      /**
     * approve a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionPublishApproval($id, $undo = 0) {
        $this->manager->contentApproval($id, $undo, ActiveRecord::PUBLISHED);
    }
    
    

    

    /**
     * Get infocus list
     * @access public
     * @return array 
     */
    public function getInfocus() {
        return $this->manager->getInfocus();
    }

    /**
     * Get infocus name for the given $id
     * @access public
     * @return array 
     */
    public function getInfocucName($id) {
        return $this->manager->getInfocucName($id);
    }

    /**
     * required for ajax requests
     */
    public function ajaxFindArticle() {
        $this->manager->findArticle(true);
    }
    
    /**
     * required for ajax requests
     */
    public function ajaxFindWriters() {
        $this->manager->findWriters();
    }    
    
    /**
     * required for ajax requests
     */
    public function ajaxFindEditors() {
        $this->manager->findEditors();
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
    
    /**
     * Manage news sources
     */
    public function actionSources(){
        $this->forward('sources/');       
    }
    
    /**
     * Manage news sources
     */
    public function actionMore(){
        $settings = new Settings("articles", true);
        $virtualId = $settings->getVirtualId('news');
//        $_GET['module'] = $virtualId;                
        $action = AmcWm::app()->request->getParam('action', 'update');
        $id = AmcWm::app()->request->getParam('id');        
        //$this->forward("/backend/articles/default/{$action}");
        $this->redirect(array("/backend/articles/default/{$action}", 'id'=>$id, 'module'=>$virtualId));
    }

}