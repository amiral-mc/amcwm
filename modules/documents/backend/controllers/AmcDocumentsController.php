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

class AmcDocumentsController extends BackendController {
   
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
     * @param Docs $model
     * @access protected
     */
    protected function save(DocsTranslation $contentModel) {
        if (isset($_POST['Docs']) && isset($_POST["DocsTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $oldFile = $model->file_ext;
            if ($_POST['Docs']['end_date'] == '')
                unset($_POST['Docs']['end_date']);

            $model->attributes = $_POST['Docs'];
            $contentModel->attributes = $_POST['DocsTranslation'];

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
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Document has been saved')));
                            $this->redirect(array('view', 'id' => $model->doc_id));
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
        $model = new Docs;
        $contentModel = new DocsTranslation();
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
            if (isset($_POST["DocsTranslation"])) {
                $translatedModel->attributes = $_POST['DocsTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->doc_id));
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
                    $file = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path'] . "/" . $model->doc_id . "." . $model->file_ext);
                    if ($model->file_ext && is_file($file)) {
                       unlink($file) ;
                    }
                }
                $messages['success'][] = AmcWm::t("msgsbase.core", 'Docuement "{docuement}" has been deleted', array("{docuement}" => $contentModel->title));
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
        $model = new Docs('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new DocsTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['Docs'])) {
            $model->attributes = $_GET['Docs'];
        }
        if ($contentModel) {
            if (isset($_GET['DocsTranslation'])) {
                $contentModel->attributes = $_GET['DocsTranslation'];
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
     * @return Docs
     */
    public function loadModel($id) {
        $model = Docs::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Docs $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DocsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = DocsTranslation::model()->findByPk(array("doc_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new DocsTranslation();
                $translatedModel->doc_id = $model->doc_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DocsTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = DocsTranslation::model()->findByPk(array("doc_id" => $pk['id'], 'content_lang' => $pk['lang']));
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
    protected function saveFile(ActiveRecord $document, $oldFile) {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $deleteFile = Yii::app()->request->getParam('deleteFile');
        if ($document->docFile instanceof CUploadedFile) {
            $docFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path']) . "/" . $document->doc_id . "." . $document->file_ext;
            if ($oldFile != $document->file_ext && $oldFile) {
                unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path']) . "/" . $document->doc_id . "." . $oldFile);
            }
            $document->docFile->saveAs($docFile);
        } else if ($deleteFile && $oldFile) {
            unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path']) . "/" . $document->doc_id . "." . $oldFile);
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

}