<?php

class AmcExchangeCompaniesController extends BackendController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $contentModel = $this->loadChildModel($id);
        $model = $contentModel->getParentContent();
        if ($contentModel) {
            $this->render('view', array(
                'contentModel' => $contentModel, 'model' => $model,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Save model to database
     * @param ExchangeCompaniesTranslation $model
     * @access protected
     */
    protected function save(ExchangeCompaniesTranslation $contentModel) {
        if (isset($_POST["ExchangeCompaniesTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $model->attributes = $_POST['ExchangeCompanies'];
            $model->exchange_id = (int) Yii::app()->request->getParam('eid');
            $contentModel->attributes = $_POST['ExchangeCompaniesTranslation'];
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $transaction->commit();
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Record has been saved')));
                            $this->redirect(array('view', 'id' => $model->exchange_companies_id, 'eid' => $model->exchange_id));
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
        $eid = (int) $_GET['eid'];
        $model = new ExchangeCompanies;
        $contentModel = new ExchangeCompaniesTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $this->save($contentModel);
        $this->render('create', array(
            'contentModel' => $contentModel,
            'eid' => $eid,
        ));

//        $model = new ExchangeCompanies;
//        $model->exchange_id = Yii::app()->request->getParam('eid');
//        $this->save($model);
//        $this->render('create', array(
//            'model' => $model,
//        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $eid = (int) $_GET['eid'];
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $this->save($contentModel);
            $this->render('update', array(
                'contentModel' => $contentModel,
                'eid' => $eid,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new ExchangeCompanies();
        $model->unsetAttributes();
        $model->unsetTranslationsAttributes();
        $model->addTranslationChild(new ExchangeCompaniesTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
//        if (isset($_GET['ExchangeCompanies'])) {
//            $model->attributes = $_GET['ExchangeCompanies'];
//        } 
//        else {
        if(isset($_GET['eid'])) {
            $contentModel->parentContent()->exchange_id = (int) $_GET['eid'];
        }
//        }
        if ($contentModel) {
            if (isset($_GET['ExchangeCompaniesTranslation'])) {
                $contentModel->attributes = $_GET['ExchangeCompaniesTranslation'];
            }
            $this->render('index', array(
                'model' => $contentModel
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Companies configuration
     */
    public function actionCompanies() {
        $this->forward("companies/");
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
                $model = $this->loadChildModel($id);
                $hasChilds = $model->integrityCheck();
                if ($hasChilds) {
                    $deleted = false;
                } else {
                    $deleted = $model->delete();
                    $deleted &= $model->parentContent()->delete();
                }
                if ($deleted) {
                    $messages['success'][] = AmcWm::t("amcTools", 'Record has been deleted');
                } else {
                    $messages['error'][] = AmcWm::t("amcTools", "Can't delete record");
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(array('index', 'eid' => (int) $_GET['eid']));
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->publish($published, 'index', array('eid' => (int) $_GET['eid']), 'loadChildModel');
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
            if (isset($_POST["ExchangeCompaniesTranslation"])) {
                $translatedModel->attributes = $_POST['ExchangeCompaniesTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->exchange_id));
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = ExchangeCompanies::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return NatureCargoTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = ExchangeCompaniesTranslation::model()->findByPk(array("exchange_companies_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param NatureCargo $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return NatureCargoTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = ExchangeCompaniesTranslation::model()->findByPk(array("exchange_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new ExchangeCompaniesTranslation();
                $translatedModel->type_id = $model->exchange_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

}
