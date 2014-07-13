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

class AmcImagesController extends AmcGalleriesController {

    protected $isBackground = 0;
    protected $imageInfo = array();

    public function getIsBackground() {
        return $this->isBackground;
    }

    public function getImageInfo() {
        return $this->imageInfo;
    }

    public function init() {
        $mediaPaths = $this->getModule()->appModule->mediaPaths;
        $this->isBackground = 0;
        $this->imageInfo = $mediaPaths['images'];
        $this->imageInfo['errorMessage']['exact'] = AmcWm::t("amcFront", 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"');
        $this->imageInfo['errorMessage']['notexact'] = AmcWm::t("amcFront", 'Image width must be less than {width}, Image height must be less than {height}');
        parent::init();
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render(AmcWm::app()->appModule->getViewPathAlias() . ".images.view", array(
            'contentModel' => $this->loadChildModel($id),
        ));
    }

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'galleryContext';
        return $filters;
    }

    private function makeImage(ParentTranslatedActiveRecord $model, $oldExt = null) {
        $galleryFolder = $model->gallery_id;
        $this->createGalleryDir($model->gallery);
        $path = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $this->imageInfo['path']) . DIRECTORY_SEPARATOR;
        $path = str_replace("{gallery_id}", $galleryFolder, $path);
        if ($this->imageInfo['info']['exact'] && $this->imageInfo['info']['allowedUploadRatio'] == 1) {
            $model->imageFile->saveAs($path . $model->image_id . "." . $model->ext);
            chmod($path . $model->image_id . "." . $model->ext, 0777);
        } else {
            $image = new Image($model->imageFile->getTempName());
            $image->resize($this->imageInfo['info']['width'], $this->imageInfo['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $path . $model->image_id . "." . $model->ext);
        }
        $image = new Image($path . $model->image_id . "." . $model->ext);
        $image->resizeCrop($this->imageInfo['info']['thumbSize']['width'], $this->imageInfo['info']['thumbSize']['height'], $path . DIRECTORY_SEPARATOR . $model->image_id . "-th." . $model->ext);
        if ($oldExt) {
            if ($oldExt && $oldExt != $model->ext) {
                if (is_file($path . DIRECTORY_SEPARATOR . $model->image_id . "." . $oldExt)) {
                    unlink($path . DIRECTORY_SEPARATOR . $model->image_id . "." . $oldExt);
                }
                if (is_file($path . DIRECTORY_SEPARATOR . $model->image_id . "-th." . $oldExt)) {
                    unlink($path . DIRECTORY_SEPARATOR . $model->image_id . "-th." . $oldExt);
                }
            }
        }
    }

    /**
     * Save model to database
     * @param ImagesTranslation $contentModel
     * @access protected
     */
    protected function save(ChildTranslatedActiveRecord $contentModel) {
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST["Images"]) && isset($_POST["ImagesTranslation"])) {
                $model = $contentModel->getParentContent();
                if (isset($_POST["ImagesTranslation"]["tags"]) && is_array($_POST["ImagesTranslation"]["tags"])) {
                    $tags = implode(PHP_EOL, $_POST["ImagesTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["ImagesTranslation"]["tags"] = $tags;
                $oldImageExt = ($model->isNewRecord) ? null : $model->ext;
                $model->attributes = $_POST["Images"];
                $contentModel->attributes = $_POST['ImagesTranslation'];
                $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
                $validate = $model->validate();
                $validate &= $contentModel->validate();
                if ($model->imageFile instanceof CUploadedFile) {
                    $model->ext = strtolower($model->imageFile->getExtensionName());
                }
                $model->is_background = $this->isBackground;
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
                    } catch (CDbException $e) {
                        $transaction->rollback();
                        $success = false;
                    }
                    if ($success) {
                        if ($model->imageFile instanceof CUploadedFile) {
                            $this->makeImage($model, $oldImageExt);
                        }
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Image has been saved')));
                        $this->redirect(array('view', 'id' => $model->image_id, 'gid' => $model->gallery_id));
                    } else {
                        Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    }
                }
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Images;
        $model->gallery_id = $this->gallery->getParentContent()->gallery_id;
        $contentModel = new ImagesTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $options = $this->module->appModule->options;
        $autoPost2social = false;        
        if(isset($options['default']['check']['autoPostImages2social'])){
            $autoPost2social = $options['default']['check']['autoPostImages2social'];
        }
        if(!isset($_POST['Images']) && $autoPost2social){
            $model->socialIds = array_keys($this->getSocials());
        }
        $this->save($contentModel);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $this->render(AmcWm::app()->appModule->getViewPathAlias() . '.images.create', array(
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
            $model = $contentModel->getParentContent();
            $translatedModel = $this->loadTranslatedModel($model, $id);
            if (isset($_POST["ImagesTranslation"])) {
                if (isset($_POST["ImagesTranslation"]["tags"]) && is_array($_POST["ImagesTranslation"]["tags"])) {
                    $tags = implode(PHP_EOL, $_POST["ImagesTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["ImagesTranslation"]["tags"] = $tags;
                $translatedModel->attributes = $_POST['ImagesTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $model->image_id, 'gid' => $model->gallery_id));
                    }
                }
            }
            $this->render(AmcWm::app()->appModule->getViewPathAlias() . ".images.translate", array(
                "contentModel" => $contentModel,
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
            $this->render(AmcWm::app()->appModule->getViewPathAlias() . '.images.update', array(
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
            $messages = array(
                'success' => array(),
                'errors' => array()
            );
            $ids = Yii::app()->request->getParam('ids');
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $messages['success'][] = AmcWm::t("msgsbase.core", 'Image "{imagename}" has been deleted', array("{imagename}" => $contentModel->image_header));
                $deleted = $model->delete();
                if ($deleted) {
                    $galleryFolder = $model->gallery_id;
                    $imgPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $this->imageInfo['path']);
                    $imgPath = str_replace("{gallery_id}", $galleryFolder, $imgPath);
                    $deleteImage = $imgPath . DIRECTORY_SEPARATOR . $model->image_id . "." . "{$model->ext}";
                    $deleteThumbImage = $imgPath . DIRECTORY_SEPARATOR . $model->image_id . "-th." . "{$model->ext}";
                    if (is_file($deleteThumbImage)) {
                        unlink($deleteThumbImage);
                    }
                    if (is_file($deleteImage)) {
                        unlink($deleteImage);
                    }
                }
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index', 'gid' => $this->gallery->gallery_id));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper gallery context
     */
    public function filterGalleryContext($filterChain) {
        //set the project identifier based on either the GET or POST input request variables, since we allow both types for our actions   
        $galleryId = null;
        if (isset($_GET['gid']))
            $galleryId = $_GET['gid'];
        else
        if (isset($_POST['gid']))
            $galleryId = $_POST['gid'];
        $this->loadGallery($galleryId);
        //complete the running of other filters and execute the requested action
        $filterChain->run();
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Images('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new ImagesTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if ($contentModel) {
            if (isset($_GET['Images'])) {
                $model->attributes = $_GET['Images'];
            }
            if (isset($_GET['ImagesTranslation'])) {
                $contentModel->attributes = $_GET['ImagesTranslation'];
            }
            $model->gallery_id = $contentModel->gallery_id = $this->gallery->getParentContent()->gallery_id;

            $this->render(AmcWm::app()->appModule->getViewPathAlias() . '.images.index', array(
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
     */
    public function loadModel($id) {
        $model = Images::model()->findByAttributes(array("image_id" => (int) $id, "is_background" => $this->isBackground));
        if ($model === null)
            throw new CHttpException(404, 'The requested image does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'images-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the sort action
     * @param  int $id the ID of the model to be sorted
     * @access public 
     * @return void
     */
    public function actionSort($id, $direction) {
        $model = $this->loadModel($id);
        $model->sort($direction);
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Item "{item}" has been sorted', array("{item}" => $model->getCurrent()->image_header))));
        $this->redirect(array('index', 'gid' => $model->gallery_id));
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->publish($published, "index", array('gid' => $this->gallery->getParentContent()->gallery_id));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $contentModel = ImagesTranslation::model()->findByPk(array("image_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($contentModel === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else if ($contentModel->getParentContent()->is_background != $this->isBackground) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $contentModel;
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
        } else if ($model->is_background != $this->isBackground) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {

            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = ImagesTranslation::model()->findByPk(array("image_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new ImagesTranslation();
                $translatedModel->image_id = $model->image_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Performs the comments action
     * @param int $imId
     * @access public 
     * @return void
     */
    public function actionComments($item) {
        $this->forward($this->getId() . 'Comments/');
    }

}
