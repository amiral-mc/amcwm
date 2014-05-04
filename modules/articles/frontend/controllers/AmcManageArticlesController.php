<?php

Amcwm::import("amcwm.core.backend.models.*");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class AmcManageArticlesController extends FrontendController {

    /**
     *
     * @var ManageArticles
     */
    private $_manager = null;

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     */
    public function init() {
        parent::init();
        $this->_manager = new ManageArticles(false);
    }

    /**
     * Lists all models.
     */
    public function actionIndex($wajax = false) {
        $this->_manager->index($wajax);
    }

    /**
     * Performs the sort action
     * @param  int $id the ID of the model to be sorted
     * @access public 
     * @return void
     */
    public function actionSort($id, $direction) {
        $this->_manager->sort($id, $direction);
        $model = $this->loadModel($id);
        $model->sort($direction);
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Article "{article}" has been sorted', array("{article}" => $model->getCurrent()->article_header))));
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }  

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->_manager->create();
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->_manager->update($id);
    }

    /**
     * translate a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionTranslate($id) {
        $this->_manager->translate($id);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        return $this->_manager->loadChildModel($id);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Persons $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return PersonsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        return $this->_manager->loadTranslatedModel($model, $id);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return Sections
     */
    public function loadModel($id) {
        return $this->_manager->loadModel($id);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->_manager->view($id);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $this->_manager->delete();
    }

    /**
     * Get infocus list
     * @access public
     * @return array 
     */
    public function getInfocus() {
        return $this->_manager->getInfocus();
    }

    /**
     * Get infocus name for the given $id
     * @access public
     * @return array 
     */
    public function getInfocucName($id) {
        return $this->_manager->getInfocucName($id);
    }

    /**
     * required for ajax requests
     */
    public function ajaxFindArticle() {
        $this->_manager->findArticle(true);
    }

    /**
     * Get Socials networks list
     * @access public 
     * @return array     
     */
    public function getSocials() {
        return $this->_manager->getSocials();
    }

      /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->_manager->publish($published, 'index'); 
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

}