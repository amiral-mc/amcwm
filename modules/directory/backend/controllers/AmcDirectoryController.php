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

class AmcDirectoryController extends BackendController {

    /**
     *
     * @var event params 
     */
    protected $eventParams = array();   

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $contentModel = $this->loadChildModel($id);
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
                            $this->redirect(array('view', 'id' => $model->company_id));
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new DirCompanies;
        $contentModel = new DirCompaniesTranslation();
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
                            $this->redirect(array('view', 'id' => $contentModel->company_id));
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
                $contentModel->attachBehavior("extendableBehaviors", new ExtendableAttributesBehaviors());
                $model = $contentModel->getParentContent();
                $this->eventParams = array('model' => $model, 'contentModel' => $contentModel);
                $model->onAfterDelete = array($this, 'customDelete');
                $user = $model->user;
                $error = $this->checkRelated($id, $contentModel);
                if (!$error) {
                    $deleted = $model->delete();
                    if ($deleted) {
                        $file = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path'] . "/" . $model->company_id . "." . $model->file_ext);
                        $image = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['images']['path'] . "/" . $model->company_id . "." . $model->image_ext);
                        if ($model->file_ext && is_file($file)) {
                            unlink($file);
                        }
                        if ($model->image_ext && is_file($image)) {
                            unlink($image);
                        }
                        if ($user !== null) {
                            $user->published = 0;
                            $user->save();
                        }
                        $messages['success'][] = AmcWm::t("msgsbase.core", 'Company "{company}" has been deleted', array("{company}" => $contentModel->company_name));
                    }
                } else {
                    if(isset($error['message'])){
                        $messages['error'][] = $error['message'];
                    }
                    else{
                        $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete "{company}"', array("{company}" => $contentModel->company_name));
                    }
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
     * Check related 
     * @param integer $id company id
     */
    protected function checkRelated($id, $contentModel) {
        return array();
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new DirCompanies('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new DirCompaniesTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['DirCompanies'])) {
            $model->attributes = $_GET['DirCompanies'];
        }
        if ($contentModel) {
            if (isset($_GET['DirCompaniesTranslation'])) {
                $contentModel->attributes = $_GET['DirCompaniesTranslation'];
            }

            $this->render('index', array(
                'model' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * 
     */
    public function actionGenerateUser() {
        $settings = AmcWm::app()->appModule->options;
        $id = (int) Yii::app()->request->getParam('id');
//        $ids = Yii::app()->request->getParam('ids', array());
//        if ($settings['default']['check']['allowUsersApply'] && Yii::app()->request->isPostRequest && count($ids)) {
        if ($settings['default']['check']['allowUsersApply'] && $id) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
//            foreach ($ids as $id) {
            $contentModel = $this->loadChildModel($id);
            $model = $contentModel->getParentContent();
            $user = $model->user;
            if ($user === NULL && $model->email) {
                $password = $this->generateUser($model);
                $model->published = 1;
                $model->save();
                if ($model->published && $model->accepted == DirCompanies::ACCEPTED) {
                    Yii::app()->mail->sender->Subject = AmcWm::t("app", "_dir_company_accepted_subject_");
                    Yii::app()->mail->sender->AddAddress($settings['default']['text']['adminEmail']);
                    Yii::app()->mail->sender->SetFrom($settings['default']['text']['adminEmail']);
                    Yii::app()->mail->sendView("application.views.email.directory." . Controller::getCurrentLanguage() . ".companyAccepted", array(
                        'username' => $model->user->username,
                        'password' => $password,
                        'company' => $contentModel->company_name,
                        'link' => Html::createUrl('/directory/default/update')));

                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Company "{company}" has been accepted', array("{company}" => $contentModel->company_name));
                }
            } else {
                if (!$model->email) {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Company "{company}" must have a valid email address', array("{company}" => $contentModel->company_name));
                } else if ($user !== NULL) {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Company "{company}" already has a user access information', array("{company}" => $contentModel->company_name));
                } else {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Company "{company}" has no affected changes', array("{company}" => $contentModel->company_name));
                }
            }
//            }

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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return DirCompanies
     */
    public function loadModel($id) {
        $model = DirCompanies::model()->findByPk($id);
        if ($model === null || $model->accepted != DirCompanies::ACCEPTED)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param DirCompanies $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DirCompaniesTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null || $model->accepted != DirCompanies::ACCEPTED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = DirCompaniesTranslation::model()->findByPk(array("company_id" => (int) $id, 'content_lang' => $translationLang));
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
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return DirCompaniesTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = DirCompaniesTranslation::model()->findByPk(array("company_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else if ($model->getParentContent()->accepted != DirCompanies::ACCEPTED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
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
        $this->forward('/backend/directory/branches');
    }

    public function actionCompanyArticles($companyId) {
        $settings = new Settings("articles", true);
        $virtualId = $settings->getVirtualId($this->getAction()->getId());
        $_GET['module'] = $virtualId;
        $_GET['companyId'] = $companyId;
        $this->forward('/backend/articles/default/index');
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

    /**
     * Custom delete in extended cobtroller
     * @param CEvent $event
     */
    public function customDelete($event) {
        
    }

    /**
     * Generate user for the current company if the given company have not user id
     * @param DirCompanies $dirCompany
     * @return string password generated
     */
    protected function generateUser($dirCompany) {
        $password = null;
        if (!$dirCompany->user_id) {
            $person = new Persons();
            $person->email = $dirCompany->email;
            $person->country_code = $dirCompany->nationality;
            $person->sex = 'm';
            $person->validate();
            $person->users = new Users;
            $person->users->username = $person->email;
            $person->users->published = 1;
            $person->users->role_id = amcwm::app()->acl->getRoleId(Acl::REGISTERED_ROLE);
            $password = $this->generatePassword();
            $person->users->passwd = md5($password);
            $person->save();
            $person->users->user_id = $person->person_id;
            $person->users->save();
            $user = $person->users;
            $dirCompany->user_id = $person->users->user_id;
            $dirCompany->user = $person->users;
        }
        return $password;
    }

    /**
     * Generate password for company user
     */
    protected function generatePassword($length = 8) {
        $password = '';
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, $count - 1)];
        }
        return $password;
    }

}