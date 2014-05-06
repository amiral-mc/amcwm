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

class AmcTendersController extends BackendController {

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
     * @param Tenders $model
     * @access protected
     */
    protected function save(TendersTranslation $contentModel) {
        if (isset($_POST['Tenders']) && isset($_POST["TendersTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $oldFile = $model->file_ext;

            $model->attributes = $_POST['Tenders'];
            $contentModel->attributes = $_POST['TendersTranslation'];

            $deleteFile = Yii::app()->request->getParam('deleteFile');
            $model->docFile = CUploadedFile::getInstance($model, 'docFile');
            if ($model->docFile instanceof CUploadedFile) {
                $model->setAttribute('file_ext', $model->docFile->getExtensionName());
            } else if ($deleteFile) {
                $model->setAttribute('file_ext', null);
            }

            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $transaction->commit();
                            $this->saveFile($model, $oldFile);
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Tender has been saved')));
                            $this->redirect(array('view', 'id' => $model->tender_id));
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
        $model = new Tenders;
        $contentModel = new TendersTranslation();
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
            if (isset($_POST["TendersTranslation"])) {
                $translatedModel->attributes = $_POST['TendersTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->tender_id));
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
        $mediaSettings = $this->module->appModule->mediaSettings;
        $ids = Yii::app()->request->getParam('ids', array());
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $deleted = $model->delete();
                if ($deleted) {
                    $file = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path'] . "/" . $model->tender_id . "." . $model->file_ext);
                    if ($model->file_ext && is_file($file)) {
                        unlink($file);
                    }
                }
                $messages['success'][] = AmcWm::t("msgsbase.core", 'Tender "{tender}" has been deleted', array("{tender}" => $contentModel->title));
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(array('index'));
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Tenders('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new TendersTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['Tenders'])) {
            $model->attributes = $_GET['Tenders'];
        }
        if ($contentModel) {
            if (isset($_GET['TendersTranslation'])) {
                $contentModel->attributes = $_GET['TendersTranslation'];
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
     * @return Tenders
     */
    public function loadModel($id) {
        $model = Tenders::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Tenders $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return TendersTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = TendersTranslation::model()->findByPk(array("tender_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new TendersTranslation();
                $translatedModel->tender_id = $model->tender_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return TendersTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = TendersTranslation::model()->findByPk(array("tender_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Save thumb images
     * @param ActiveRecord $section
     * @param string $oldFile
     * @return void
     * @access protected
     */
    protected function saveFile(ActiveRecord $model, $oldFile) {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $deleteFile = Yii::app()->request->getParam('deleteFile');
        $dir = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path']);
        if ($model->docFile instanceof CUploadedFile) {
            $tenderFile = $dir . DIRECTORY_SEPARATOR . $model->tender_id . "." . $model->file_ext;
            if ($oldFile != $model->file_ext && $oldFile) {
                unlink($dir . DIRECTORY_SEPARATOR . $model->tender_id . "." . $oldFile);
            }
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $model->docFile->saveAs($tenderFile);
        } else if ($deleteFile && $oldFile) {
            unlink($dir . DIRECTORY_SEPARATOR . $model->tender_id . "." . $oldFile);
        }
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

    
    /**
     * Performs the comments action
     * @param int $aid
     * @access public 
     * @return void
     */
    public function actionComments($item) {
        $this->forward('/backend/tenders/questions');
    }
}