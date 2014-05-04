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

class AmcDirBranchesController extends BackendController {

    protected $company = null;

    public function filters() {
        $filters = parent::filters();
        $filters[] = 'companyTypeContext';
        return $filters;
    }
    
    public function filterCompanyTypeContext($filterChain) {
        $cid = Yii::app()->request->getParam('cid');
        if(!$cid && isset($_POST['DirCompaniesBranches']['company_id'])){
            $cid = $_POST['DirCompaniesBranches']['company_id'];
        }
        if(!$cid){
            $cid = $_GET['id'];
        }
        $this->loadCompanyData($cid);
        $filterChain->run();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadCompanyData($id) {
        if ($this->company === null) {
            $this->company = DirCompanies::model()->findByPk((int) $id);
            if ($this->company === null) {
                throw new CHttpException(404, 'The requested project does not exist.');
            }
        }
        return $this->company;
    }
    
    /**
     * Get parent id section
     * @return int
     */
    public function getCompanyId() {
        static $companyId = null;
        if ($companyId === null) {
            if (Yii::app()->request->getParam('cid')) {
                $companyId = Yii::app()->request->getParam('cid');
            } else {
                $companyId = $this->company->company_id;
            }
        }
        return $companyId;
    }
    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'contentModel' => $this->loadChildModel($id),
        ));
    }

    /**
     * Save model to database
     * @param DirCompaniesBranches $model
     * @access protected
     */
    protected function save(DirCompaniesBranchesTranslation $contentModel) {
        if (isset($_POST['DirCompaniesBranches']) && isset($_POST["DirCompaniesBranchesTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $model->attributes = $_POST['DirCompaniesBranches'];
            $contentModel->attributes = $_POST['DirCompaniesBranchesTranslation'];
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $transaction->commit();
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Branch has been saved')));
                            $this->redirect(array('view', 'id' => $model->branch_id, 'cid'=>  $this->getCompanyId()));
                        }
                    }
                } catch (CDbException $e) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    //$this->refresh();
                }
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new DirCompaniesBranches;
        $model->company_id = $this->company->company_id;
        $contentModel = new DirCompaniesBranchesTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $this->save($contentModel);
        $this->render('create', array(
            'contentModel' => $contentModel,
        ));
    }

    /**
     * translate a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionTranslate($id) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $translatedModel = $this->loadTranslatedModel($contentModel->getParentContent(), $id);
            if (isset($_POST["DirCompaniesBranchesTranslation"])) {
                $translatedModel->attributes = $_POST['DirCompaniesBranchesTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->branch_id, 'cid'=>  $this->getCompanyId()));
                    }
                }
            }
            $this->render('translate', array(
                'contentModel' => $contentModel,
                'translatedModel' => $translatedModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $this->save($contentModel);
            $this->render('update', array(
                'contentModel' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        parent::publish($published, "index");
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids', array());
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $model->delete();
                $messages['success'][] = AmcWm::t("msgsbase.core", 'Branch "{Branch}" has been deleted', array("{Branch}" => $contentModel->branch_name));
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(array('index', 'cid'=>  $this->getCompanyId()));
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new DirCompaniesBranches('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->company_id = $this->company->company_id;
        $model->addTranslationChild(new DirCompaniesBranchesTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['DirCompaniesBranches'])) {
            $model->attributes = $_GET['DirCompaniesBranches'];
        }
        if ($contentModel) {
            if (isset($_GET['DirCompaniesBranchesTranslation'])) {
                $contentModel->attributes = $_GET['DirCompaniesBranchesTranslation'];
            }

            $this->render('index', array(
                'model' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return DirCompaniesBranches
     */
    public function loadModel($id) {
        $model = DirCompaniesBranches::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param DirCompaniesBranches $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DirCompaniesBranchesTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = DirCompaniesBranchesTranslation::model()->findByPk(array("branch_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new DirCompaniesBranchesTranslation();
                $translatedModel->branch_id = $model->branch_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DirCompaniesBranchesTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = DirCompaniesBranchesTranslation::model()->findByPk(array("branch_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === Yii::app()->params["pageSize"]) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}