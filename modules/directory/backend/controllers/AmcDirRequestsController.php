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

class AmcDirRequestsController extends BackendController {

    /**
     *
     * @var event params 
     */
    protected $eventParams = array();

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id = "") {
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

            $validate = $model->validate();
            $validate &= $contentModel->validate();
            $contentModel->onValidateOthers = array($this, 'customValidate');
            $contentModel->onAfterSave = array($this, 'customSave');
            $this->eventParams = array('model' => $model, 'contentModel' => $contentModel);
            if ($validate) {
                try {
                    $settings = AmcWm::app()->appModule->options;
                    $password = null;                    
                    $userId = $model->user_id;
                    if($model->accepted ==  DirCompanies::ACCEPTED){
                        $model->published = 1;
                    }
                    else{
                        $model->published = 0;
                    }
                    if ($model->accepted == DirCompanies::ACCEPTED && $settings['default']['check']['allowUsersApply']) {
                        $password = $this->generateUser($model);
                    }
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            if ($model->accepted == DirCompanies::ACCEPTED && $settings['default']['check']['allowUsersApply'] && !$userId) {
                                Yii::app()->mail->sender->Subject = AmcWm::t("app", "_dir_company_accepted_subject_");
                                Yii::app()->mail->sender->AddAddress($settings['default']['text']['adminEmail']);
                                Yii::app()->mail->sender->SetFrom($settings['default']['text']['adminEmail']);
                                $ok = Yii::app()->mail->sendView("application.views.email.directory." . Controller::getCurrentLanguage() . ".companyAccepted", array(
                                    'username' => $model->user->username, 
                                    'password' => $password,
                                    'company'=>$contentModel->company_name,
                                    'link'=>Html::createUrl('/directory/default/update')));
                            }
                            $transaction->commit();
                            $this->saveThumb($model, $oldThumb);
                            $this->saveFile($model, $oldFile);
                            $this->saveMap($model, $mapsData, $oldMapImage);
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Company has been saved')));
                            $this->redirect(array('view', 'id' => $model->company_id));
                        }
                    }
                } catch (CDbException $e) {
//                    die($e->getMessage());
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    //$this->refresh();
                }
            }
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
    public function actionAccept() {
        $this->publish(1, "index");
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @param string $action action to redirect to it after publish / unpublish the content
     * @param array $params paramters to append to redirect route
     * @access public 
     * @return void
     */
    protected function publish($published, $action = "index", $params = array(), $loadMethod = "loadChildModel") {
        if (Yii::app()->request->isPostRequest && method_exists($this, $loadMethod)) {
            if ($published) {
                $okMessage = 'item "{displayTitle}" has been published';
            } else {
                $okMessage = 'item "{displayTitle}" has been unpublished';
            }
            $ids = Yii::app()->request->getParam('ids');
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();

            foreach ($ids as $id) {
                if ($loadMethod == "loadChildModel") {
                    $contentModel = $this->loadChildModel($id);
                    $model = $contentModel->getParentContent();
                    $itemName = $contentModel->displayTitle;
                } else {
                    $model = $this->$loadMethod($id);
                    $itemName = $model->displayTitle;
                }
                $model->published = 1;
                $model->accepted = 1;
                if ($model->save()) {
                    $messages['success'][] = AmcWm::t("amcBack", $okMessage, array("{displayTitle}" => $itemName));
                } else {
//                    print_r($model->getErrors());
//                    die();
                    $messages['error'][] = AmcWm::t("amcBack", 'Can not publish item "{displayTitle}"', array("{displayTitle}" => $itemName));
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
        }
        $url = array($action);
        if (count($params)) {
            foreach ($params as $key => $value) {
                $url[$key] = $value;
            }
        }
        $this->redirect($url);
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
                    if($user !== null){
                        $user->published = 0;
                        $user->save();
                    }
                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Company "{company}" has been deleted', array("{company}" => $contentModel->company_name));
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
        $model = new DirCompanies('request');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new DirCompaniesTranslation('request'), self::getContentLanguage());
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return DirCompanies
     */
    public function loadModel($id) {
        $model = DirCompanies::model()->findByPk($id);
        if ($model === null && !$model->registered)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
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
        } else if (!$model->getParentContent()->registered) {
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
                        $image->resizeCrop($imageInfo['info']['width'], $imageInfo['info']['height'], $imageFile);
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
        if ($model->attachFile instanceof CUploadedFile) {
            $attachFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']) . "/" . $model->company_id . "." . $model->file_ext;
            if ($oldFile != $model->file_ext && $oldFile) {
                unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']) . "/" . $model->company_id . "." . $oldFile);
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
     * Custom save in extended cobtroller
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

