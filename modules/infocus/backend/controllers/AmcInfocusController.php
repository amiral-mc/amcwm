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

class AmcInfocusController extends BackendController {

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
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Infocus('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new InfocusTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['Infocus'])) {
            $model->attributes = $_GET['Infocus'];
        }
        if ($contentModel) {
            if (isset($_GET['InfocusTranslation'])) {
                $contentModel->attributes = $_GET['InfocusTranslation'];
            }

            $this->render('index', array(
                'contentModel' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Infocus;
        $contentModel = new InfocusTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $this->save($contentModel);
        $this->render('create', array(
            'contentModel' => $contentModel,
        ));
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
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     */
    public function actionDelete() {
        if (Yii::app()->request->isPostRequest) {
            $ids = Yii::app()->request->getParam('ids');
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $deleted = $model->delete();
                if ($deleted) {
                    $image = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params['multimedia']['infocus']['list']['path']) . "/" . $model->infocus_id . "." . $model->thumb;
                    if (is_file($image)) {
                        unlink($image);
                    }
                    $banner = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['banners']['path']) . "/" . $model->infocus_id . "." . $model->banner;
                    if (is_file($banner)) {
                        unlink($banner);
                    }
                    $background = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['backgrounds']['path']) . "/" . $model->infocus_id . "." . $model->background;
                    if (is_file($background)) {
                        unlink($background);
                    }
                }
                //------------------------------------------------------
                $messages['success'][] = AmcWm::t("msgsbase.core", 'Infocus "{article}" has been deleted', array("{article}" => $model->displayTitle));
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
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

    /**
     * translate a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionTranslate($id) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $translatedModel = $this->loadTranslatedModel($contentModel->getParentContent(), $id);
            if (isset($_POST["InfocusTranslation"])) {
                $translatedModel->attributes = $_POST['InfocusTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->infocus_id));
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
     * Save model to database
     * @param Infocus $model
     * @access protected
     */
    protected function save(InfocusTranslation $contentModel) {
        if (isset($_POST['Infocus']) && isset($_POST["InfocusTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();

            if ($_POST['Infocus']['expire_date'] == '')
                unset($_POST['Infocus']['expire_date']);

            $model->attributes = $_POST['Infocus'];
            $contentModel->attributes = $_POST['InfocusTranslation'];

            if (isset($_POST["InfocusTranslation"]["tags"]) && is_array($_POST["InfocusTranslation"]["tags"])) {
                $tags = array_filter($_POST["InfocusTranslation"]["tags"]);
                $contentModel->setAttribute("tags", implode(PHP_EOL, $tags));
            }

            $oldImages = array(
                "thumb" => $model->thumb,
                "banner" => $model->banner,
                "background" => $model->background,
            );
            $this->prepareFiles($model);

            $validate = $this->validateFile($model);
            $validate &= $contentModel->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $transaction->commit();
                            $this->saveFiles($model, $oldImages);
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Article has been saved')));
                            $this->redirect(array('view', 'id' => $model->infocus_id));
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

    protected function prepareFiles(Infocus $model) {
        $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
        if ($model->imageFile instanceof CUploadedFile) {
            $model->setAttribute('thumb', $model->imageFile->getExtensionName());
        }

        $model->bannerFile = CUploadedFile::getInstance($model, 'bannerFile');
        if ($model->bannerFile instanceof CUploadedFile) {
            $model->setAttribute('banner', $model->bannerFile->getExtensionName());
        }

        $model->backgroundFile = CUploadedFile::getInstance($model, 'backgroundFile');
        if ($model->backgroundFile instanceof CUploadedFile) {
            $model->setAttribute('background', $model->backgroundFile->getExtensionName());
        }
    }

    /**
     * save the images
     * @param Infocus $model
     * @param array $oldImages
     */
    protected function saveFiles(Infocus $model, $oldImages = array()) {
        $deleteImage = Yii::app()->request->getParam('deleteImageFile');
        $deleteBanner = Yii::app()->request->getParam('deleteBnrFile');
        $deleteBg = Yii::app()->request->getParam('deleteBgFile');
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;

        if ($model->imageFile instanceof CUploadedFile) {
            $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['images']['path']) . "/" . $model->infocus_id . "." . $model->thumb;
            if ($oldImages['thumb'] != $model->thumb && $oldImages['thumb']) {
                @unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['images']['path']) . "/" . $model->infocus_id . "." . $oldImages['thumb']);
            }
            $image = new Image($model->imageFile->getTempName());
            $image->resize($mediaSettings['paths']['images']['info']['width'], $mediaSettings['paths']['images']['info']['height'], Image::RESIZE_BASED_ON_HEIGHT, $imageFile);
        } else if ($deleteImage) {
            if ($model->thumb) {
                @unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['images']['path']) . "/" . $model->infocus_id . "." . $model->thumb);
                $model->setAttribute('thumb', '');
            }
        }

        if ($model->bannerFile instanceof CUploadedFile) {
            $bannerFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['banners']['path']) . "/" . $model->infocus_id . "." . $model->banner;
            if ($oldImages['banner'] != $model->banner && $oldImages['banner']) {
                unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['banners']['path']) . "/" . $model->infocus_id . "." . $oldImages['banner']);
            }
            $model->bannerFile->saveAs($bannerFile);
        } else if ($deleteBanner) {
            if ($model->banner) {
                @unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['banners']['path']) . "/" . $model->infocus_id . "." . $model->banner);
                $model->setAttribute('banner', '');
            }
        }

        if ($model->backgroundFile instanceof CUploadedFile) {
            $backgroundFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['backgrounds']['path']) . "/" . $model->infocus_id . "." . $model->background;
            if ($oldImages['background'] != $model->background && $oldImages['background']) {
                unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['backgrounds']['path']) . "/" . $model->infocus_id . "." . $oldImages['background']);
            }
            $model->backgroundFile->saveAs($backgroundFile);
        } else if ($deleteBg) {
            if ($model->background) {
                @unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['backgrounds']['path']) . "/" . $model->infocus_id . "." . $model->background);
                $model->setAttribute('background', '');
            }
        }
    }

    /**
     * validate article
     * @param ActiveRecord $file
     * @return boolean
     * @access protected
     */
    protected function validateFile(ActiveRecord $model) {
        $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
        $model->bannerFile = CUploadedFile::getInstance($model, 'bannerFile');
        $model->backgroundFile = CUploadedFile::getInstance($model, 'backgroundFile');

        $ok = $model->validate();
        return $ok;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Infocus $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return InfocusTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = InfocusTranslation::model()->findByPk(array("infocus_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new InfocusTranslation();
                $translatedModel->infocus_id = $model->infocus_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return InfocusTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = InfocusTranslation::model()->findByPk(array("infocus_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Infocus::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
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

}
