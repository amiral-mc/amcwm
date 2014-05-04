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

class AmcMembersController extends FrontendController {

    /**
     *
     * @var event params 
     */
    protected $eventParams = array();

    /**
     * Displays a particular model.
     */
    public function actionView() {
        $contentModel = $this->loadChildModel();
        $contentModel->attachBehavior("extendableBehaviors", new ExtendableAttributesBehaviors());
        $this->render('view', array(
            'contentModel' => $contentModel,
        ));
    }

    /**
     * Save model to database
     * @param DirCompanies $model
     * @access protected
     * @todo {'location':[{'lng':-31.00019509,'lat':-29.000879376,'zoom':15}], 'image':'jpg'}
     */
    protected function save(DirCompaniesTranslation $contentModel) {
        $model = $contentModel->getParentContent();
        $contentModel->attachBehavior("extendableBehaviors", new ExtendableAttributesBehaviors());

        /*
         * inisialize maps data
         */
        $mapsData = array('location' => array('lng' => '', 'lat' => '', 'zoom' => '', 'enabled' => false), 'image' => '');

        if (isset($_POST['DirCompanies']) && isset($_POST["DirCompaniesTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $oldThumb = $model->image_ext;
            $oldFile = $model->file_ext;
//            $contentModel->oldAttributes
            $oldMapImage = null;
            $mapsData = CJSON::decode($model->maps);
            if (isset($mapsData['image']) && $mapsData['image'] != '')
                $oldMapImage = $mapsData['image'];

            $model->attributes = $_POST['DirCompanies'];
            $contentModel->attributes = $_POST['DirCompaniesTranslation'];
            $deleteImage = Yii::app()->request->getParam('deleteImage');
            $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile instanceof CUploadedFile) {
                $model->setAttribute('image_ext', $model->imageFile->getExtensionName());
            } else if ($deleteImage) {
                $model->setAttribute('image_ext', null);
            }
            $deleteFile = Yii::app()->request->getParam('deleteFile');
            $model->attachFile = CUploadedFile::getInstance($model, 'attachFile');
            if ($model->attachFile instanceof CUploadedFile) {
                $model->setAttribute('file_ext', $model->attachFile->getExtensionName());
            } else if ($deleteFile) {
                $model->setAttribute('file_ext', null);
            }

            /*             * *********** */
            $deleteMap = Yii::app()->request->getParam('deleteMap');
            $model->mapFile = CUploadedFile::getInstance($model, 'mapFile');
            if ($model->mapFile instanceof CUploadedFile) {
                $mapsData['image'] = $model->mapFile->getExtensionName();
            } else if ($deleteMap) {
                $mapsData['image'] = '';
            }

            if (isset($_POST['lat']) && isset($_POST["lng"]) && isset($_POST['zoom']) && $_POST['zoom'] != 1) {
                $mapsData['location']['lat'] = $_POST['lat'];
                $mapsData['location']['lng'] = $_POST['lng'];
                $mapsData['location']['zoom'] = $_POST['zoom'];
            }

            if (isset($_POST['enabled'])) {
                $mapsData['location']['enabled'] = true;
            } else {
                $mapsData['location']['enabled'] = false;
            }
//            die(CJSON::encode($mapsData));
            $model->setAttribute('maps', CJSON::encode($mapsData));
            /*             * ********************** */

            $contentModel->onValidateOthers = array($this, 'customValidate');
            $contentModel->onAfterSave = array($this, 'customSave');
            $this->eventParams = array('model' => $model, 'contentModel' => $contentModel);
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            //echo $contentModel->company_address;
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            //die("");
                            $transaction->commit();
                            $this->saveThumb($model, $oldThumb);
                            $this->saveFile($model, $oldFile);
                            $this->saveMap($model, $mapsData, $oldMapImage);
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Company has been saved')));
                            $this->redirect(array('view'));
                        }
                    }
                } catch (CDbException $e) {
                    //die($e->getMessage());
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    //$this->refresh();
                }
            }
        }
    }

    /**
     * translate a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionTranslate() {
        $contentModel = $this->loadChildModel();
        if ($contentModel) {
            $translatedModel = $this->loadTranslatedModel($contentModel->getParentContent());
            $translatedModel->attachBehavior("extendableBehaviors", new ExtendableAttributesBehaviors());
            if (isset($_POST["DirCompaniesTranslation"])) {
                $translatedModel->attributes = $_POST['DirCompaniesTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        if ($translatedModel->save()) {
//                            die();
                            $transaction->commit();
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                            $this->redirect(array('view'));
                        }
                    } catch (CDbException $e) {
                        //die($e->getMessage());
                        $transaction->rollback();
                        Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                        //$this->refresh();
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
    public function actionUpdate() {
        $contentModel = $this->loadChildModel();
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
     * Lists all models.
     */
    public function actionIndex() {
        $this->forward("view");
    }
 
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param DirCompanies $model parent content model
     * @return DirCompaniesTranslation
     */
    public function loadTranslatedModel($model) {
        $translatedModel = null;
        if ($model === null || $model->accepted != DirCompanies::ACCEPTED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = DirCompaniesTranslation::model()->findByPk(array("company_id" => $model->company_id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new DirCompaniesTranslation();
                $translatedModel->company_id = $model->company_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @return DirCompaniesTranslation
     */
    public function loadChildModel() {
        $userId = (int) Yii::app()->user->getId();
        $dirModels = DirCompanies::model()->findAllByAttributes(array('user_id' => $userId));
        if (isset($dirModels[0])) {
            $contentModel = DirCompaniesTranslation::model()->findByPk(array("company_id" => $dirModels[0]->company_id, 'content_lang' => Controller::getContentLanguage()));
            if ($contentModel === null) {
                $contentModel = new DirCompaniesTranslation();
                $dirModels[0]->addTranslationChild($contentModel, self::getContentLanguage());
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $contentModel;
    }

    /**
     * Save thumb images
     * @param ActiveRecord $section
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function saveThumb(ActiveRecord $directory, $oldThumb) {
        $imageSizesInfo = $this->getModule()->appModule->mediaPaths;
        $deleteImage = Yii::app()->request->getParam('deleteImage');
        if ($directory->imageFile instanceof CUploadedFile) {
            $image = new Image($directory->imageFile->getTempName());
            foreach ($imageSizesInfo as $imageInfo) {
                if ($imageInfo['info']['isImage']) {
                    $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $directory->company_id . "." . $directory->image_ext;
                    if ($oldThumb != $directory->image_ext && $oldThumb) {
                        unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $directory->company_id . "." . $oldThumb);
                    }
                    if ($imageInfo['info']['crob']) {
                        $image->resizeCrob($imageInfo['info']['width'], $imageInfo['info']['height'], $imageFile);
                    } else {
                        $image->resize($imageInfo['info']['width'], $imageInfo['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $imageFile);
                    }
                }
            }
        } else if ($deleteImage && $oldThumb) {
            foreach ($imageSizesInfo as $imageInfo) {
                if ($imageInfo['info']['isImage']) {
                    unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $directory->company_id . "." . $oldThumb);
                }
            }
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

    public function actionBranches() {
        $this->forward('/directory/branches');
    }

    public function actionArticles() {
        $settings = new Settings("articles", false);
        $virtualId = $settings->getVirtualId('companyArticles');
        $_GET['module'] = $virtualId;        
        $this->forward('/articles/manage/index');
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
        $dir = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']);
        if ($model->attachFile instanceof CUploadedFile) {
            $attachFile = $dir . DIRECTORY_SEPARATOR . $model->company_id . "." . $model->file_ext;
            if ($oldFile != $model->file_ext && $oldFile) {
                unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']) . "/" . $model->company_id . "." . $oldFile);
            }
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $model->attachFile->saveAs($attachFile);
        } else if ($deleteFile && $oldFile) {
            @unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']) . "/" . $model->company_id . "." . $oldFile);
        }
    }

    /**
     * Save map image
     * @param ActiveRecord $section
     * @param string $oldFile
     * @return void
     * @access protected
     */
    protected function saveMap(ActiveRecord $model, $mapsData, $oldMapImage) {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $deleteMap = Yii::app()->request->getParam('deleteMap');
        $dir = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['maps']['path']);
        if ($model->mapFile instanceof CUploadedFile) {
            $attachMap = $dir . DIRECTORY_SEPARATOR . $model->company_id . "." . $mapsData['image'];
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if ($oldMapImage && $mapsData['image'] != $oldMapImage && is_file($dir . "/" . $model->company_id . "." . $oldMapImage)) {
                @unlink($dir . "/" . $model->company_id . "." . $oldMapImage);
            }
            $model->mapFile->saveAs($attachMap);
        } else if ($deleteMap && $oldMapImage) {
            if (is_file($dir . "/" . $model->company_id . "." . $oldMapImage)) {
                @unlink($dir . "/" . $model->company_id . "." . $oldMapImage);
            }
        }
    }

    /**
     * Custom validation in extended cobtroller
     * @param CModelEvent $event
     */
    public function customValidate($event) {
        $event->isValid = true;
    }

    /**
     * Custom delete in extended cobtroller
     * @param CEvent $event
     */
    public function customSave($event) {
        
    }   

}