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

class AmcGalleriesController extends MultimediaController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'contentModel' => $this->loadGallery($id),
        ));
    }

    /**
     * Save model to database
     * @param GalleriesTranslation $contentModel
     * @access protected
     */
    protected function save(GalleriesTranslation $contentModel) {
        if (isset($_POST['Galleries']) && isset($_POST["GalleriesTranslation"])) {
            $model = $contentModel->getParentContent();
            if (isset($_POST["GalleriesTranslation"]["tags"]) && is_array($_POST["GalleriesTranslation"]["tags"])) {
                $tags = implode(PHP_EOL, $_POST["GalleriesTranslation"]["tags"]);
            } else {
                $tags = null;
            }
            $_POST["GalleriesTranslation"]["tags"] = $tags;
            $model->attributes = $_POST['Galleries'];
            $contentModel->attributes = $_POST['GalleriesTranslation'];
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            $transaction = Yii::app()->db->beginTransaction();
            $success = false;
            $saved = false;
            if ($validate) {
                try {
                    $saved = $model->save();
                    $saved &= $contentModel->save();
                    if ($saved) {
                        $transaction->commit();
                        $success = true;
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    $success = false;
                }
                if ($success) {
                    $this->createGalleryDir($model);
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Gallery has been saved')));
                    $this->redirect(array('view', 'id' => $model->gallery_id));
                } else {
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
            }
        }
    }

    public function actionCreate() {
        $model = new Galleries;
        $contentModel = new GalleriesTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $this->save($contentModel);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
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
        $contentModel = $this->loadGallery($id);
        if ($contentModel) {
            $translatedModel = $this->loadTranslatedModel($contentModel->getParentContent(), $id);
            if (isset($_POST["GalleriesTranslation"])) {
                if (isset($_POST["GalleriesTranslation"]["tags"]) && is_array($_POST["GalleriesTranslation"]["tags"])) {
                    $tags = implode(PHP_EOL, $_POST["GalleriesTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["GalleriesTranslation"]["tags"] = $tags;
                $translatedModel->attributes = $_POST['GalleriesTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->gallery_id));
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
        $contentModel = $this->loadGallery($id);
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
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids', array());

        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $ids = Yii::app()->request->getParam('ids');
            foreach ($ids as $id) {
                $contentModel = $this->loadGallery($id);
                $model = $contentModel->getParentContent();
                $this->deleteGallery($model);
                $messages['success'][] = AmcWm::t("msgsbase.core", 'Gallery "{galleryname}" has been deleted', array("{galleryname}" => $contentModel->gallery_header));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    private function deleteGallery(Galleries $galleryModel) {
        $galleryFolder = $galleryModel->gallery_id;
        $mediaPaths = $this->getModule()->appModule->mediaPaths;
        $images = $galleryModel->images;
        $path = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['galleries']['path']) . DIRECTORY_SEPARATOR;
        $imgPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['images']['path']) . DIRECTORY_SEPARATOR;
        $imgPath = str_replace("{gallery_id}", $galleryFolder, $imgPath);
        $bgPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['backgrounds']['path']) . DIRECTORY_SEPARATOR;
        $bgPath = str_replace("{gallery_id}", $galleryFolder, $bgPath);
        $videoPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . DIRECTORY_SEPARATOR;
        $videoPath = str_replace("{gallery_id}", $galleryFolder, $videoPath);
        $videoThumbPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['thumb']['path']) . DIRECTORY_SEPARATOR;
        $videoThumbPath = str_replace("{gallery_id}", $galleryFolder, $videoThumbPath);
        $galleryPath = $path . $galleryFolder;
        /**
         * @todo check the directory when trying to delete the gallery that contains another images, and folder inside the main gallery folder
         * @todo getting the error [Directory not empty] when deleting gallery not empty.
         */
        if (is_dir($path)) {
            foreach ($images As $image) {
                if ($image['is_background']) {
                    $deleteFile = $bgPath . $image->image_id;
                } else {
                    $deleteFile = $imgPath . $image->image_id;
                }
                if (is_file($deleteFile)) {
                    unlink($deleteFile);
                }
                $image->delete();
            }
            if (is_dir($imgPath)) {
                rmdir($imgPath);
            }
            if (is_dir($bgPath)) {
                rmdir($bgPath);
            }            
            if (is_dir($videoThumbPath)) {
                rmdir($videoThumbPath);
            }
            if (is_dir($videoPath)) {
                rmdir($videoPath);
            }
            if (is_dir($galleryPath)) {
                rmdir($galleryPath);
            }
            foreach ($galleryModel->videos as $video) {
                $video->delete();
            }
        }
        $galleryModel->delete();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Galleries $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return GalleriesTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = GalleriesTranslation::model()->findByPk(array("gallery_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new GalleriesTranslation();
                $translatedModel->gallery_id = $model->gallery_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Galleries('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new GalleriesTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['Galleries'])) {
            $model->attributes = $_GET['Galleries'];
        }
        if ($contentModel) {
            if (isset($_GET['GalleriesTranslation'])) {
                $contentModel->attributes = $_GET['GalleriesTranslation'];
            }
            $this->render('index', array(
                'model' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
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

    public function actionImages($gid) {
        $this->forward("images/");
    }

    public function actionBackgrounds($gid) {
        $this->forward("backgrounds/");
    }

    public function actionVideos($gid) {
        $this->forward("videos/");
    }

    /**
     * Get Sections list
     * @access public
     * @return array 
     */
    public function getSections() {
        $sections = CHtml::listData(Sections::model()->findAll(), 'section_id', 'section_name');
        $sections[""] = "";
        return $sections;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Galleries::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested Gallery does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        return $this->loadGallery($id);
    }

}
