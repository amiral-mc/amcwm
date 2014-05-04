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

class AmcDocCategoriesController extends BackendController {

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
     * @param DocsCategories $model
     * @access protected
     */
    protected function save(DocsCategoriesTranslation $contentModel) {
        if (isset($_POST['DocsCategories']) && isset($_POST["DocsCategoriesTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $oldImage = $model->image_ext;
            $model->attributes = $_POST['DocsCategories'];
            $contentModel->attributes = $_POST['DocsCategoriesTranslation'];
            
            $deleteFile = Yii::app()->request->getParam('deleteFile');
            $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile instanceof CUploadedFile) {
                $model->setAttribute('image_ext', $model->imageFile->getExtensionName());
            } else if ($deleteFile) {
                $model->setAttribute('image_ext', null);
            }
            
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $transaction->commit();
                            $this->saveImage($model, $oldImage);
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Category has been saved')));
                            $this->redirect(array('view', 'id' => $model->category_id));
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
        $model = new DocsCategories;
        $contentModel = new DocsCategoriesTranslation();
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
            if (isset($_POST["DocsCategoriesTranslation"])) {
                $translatedModel->attributes = $_POST['DocsCategoriesTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->category_id));
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
                $deleted = $model->delete();
                if($deleted){
                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Category "{Category}" has been deleted', array("{Category}" => $contentModel->category_name));
                }
                else{
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Cannot delete category "{category}"', array("{category}" => $contentModel->category_name));
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
        $model = new DocsCategories('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new DocsCategoriesTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['DocsCategories'])) {
            $model->attributes = $_GET['DocsCategories'];
        }
        if ($contentModel) {
            if (isset($_GET['DocsCategoriesTranslation'])) {
                $contentModel->attributes = $_GET['DocsCategoriesTranslation'];
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
     * @return DocsCategories
     */
    public function loadModel($id) {
        $model = DocsCategories::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param DocsCategories $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DocsCategoriesTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = DocsCategoriesTranslation::model()->findByPk(array("category_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new DocsCategoriesTranslation();
                $translatedModel->category_id = $model->category_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DocsCategoriesTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = DocsCategoriesTranslation::model()->findByPk(array("category_id" => $pk['id'], 'content_lang' => $pk['lang']));
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
    
    
    /**
     * Save slider image
     * @param ActiveRecord $article
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function saveImage(ActiveRecord $item, $oldImage) {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $imageInfo = $mediaSettings['categories'];
        $dirPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']);
        if(!is_dir($dirPath)){
            mkdir($dirPath, 0777, true);
        }
            
        if ($item->imageFile instanceof CUploadedFile) {
            $imageFile = $dirPath . "/" . $item->category_id . "." . $item->image_ext;
            $item->imageFile->saveAs($imageFile);
        }
        
        if ($oldImage != $item->image_ext && $oldImage) {
            $old = $dirPath . "/" . $item->category_id . "." . $oldImage;
            if (is_file($old)) {
                unlink($old);
            }
        }
    }

}