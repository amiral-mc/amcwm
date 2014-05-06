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

class AmcUsersController extends FrontendController {

    /**
     * Controller constrctors
     * @param string $id id of this controller
     * @param CWebModule $module the module that this controller belongs to. This parameter
     * @access public
     */
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
    }

    public function init() {
        parent::init();
    }

    /**
     * Action index , used for displaying member area to logged user.
     * @access public
     * @return void
     */
    public function actionIndex() {
        if (!Yii::app()->user->isGuest) {
            $userApps['user_profile'] = array(
                'id' => 'user_profile',
                'label' => AmcWm::t("msgsbase.core", "User Profile"),
                'url' => array('/users/default/profile'),
                'image_id' => 'users',
                'visible' => '1',
            );
            $customApps = AmcWm::app()->params['userApps'];
            if (count($customApps)) {
                foreach ($customApps as $customKey => $customApp) {
                    $userApps[$customKey] = $customApp;
                    $userApps[$customKey]['label'] = AmcWm::t("app", $customApp['label']);
                }
            }
            $this->render('index', array('userApps' => $userApps));
        }
    }

    /**
     * Send email with reset key to user     
     */
    public function sendResetKey($name, $username, $email, $key) {
        if (isset($this->module->appModule->options['default']['text']['forgotFrom'])) {
            $from = $this->module->appModule->options['default']['text']['forgotFrom'];
        } else {
            $from = Yii::app()->params['adminEmail'];
        }
        Yii::app()->mail->sender->Subject = AmcWm::t("app", "_FORGET_PASSWORD_SUBJECT_");
        Yii::app()->mail->sender->AddAddress($email);
        Yii::app()->mail->sender->SetFrom($from);
        Yii::app()->mail->sender->IsHTML();
        //Yii::app()->mail->sender->Body = "__";
        Yii::app()->mail->sendView("application.views.email.users." . Controller::getCurrentLanguage() . ".forgot", array(
            'username' => $username,
            'name' => $name,
            'key' => $key,
            'link' => AmcWm::app()->request->getHostInfo() . Html::createUrl('/users/default/reset', array('key' => $key))));

        Yii::app()->mail->sender->Send();
        Yii::app()->mail->sender->ClearAddresses();
    }

    /**
     * Action register , reset your password
     * @access public
     * @return void
     */
    public function actionReset() {
        if (Yii::app()->user->isGuest) {
            $resetForm = new ResetPasswordForm();
            $params['key'] =  AmcWm::app()->request->getParam('key');
            $resetForm->attributes = $params;
            $resetForm->validate(array('key'));
            if (Yii::app()->request->isPostRequest) {
                $params = AmcWm::app()->request->getParam('ResetPasswordForm');
                $params['key'] =  AmcWm::app()->request->getParam('key');
                $resetForm->attributes = $params;
                if ($resetForm->validate()) {
                    $resetForm->savePasswd();
                    if (AmcWm::app()->frontend['bootstrap']['use']) {
                        Yii::app()->user->setFlash('success', AmcWm::t("msgsbase.core", 'Your password has been sucessfully reseted. Try logging now'));
                    } else {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Your password has been sucessfully reseted. Try logging now')));
                    }
                    $this->redirect(array('/site/index', '#' => "message"));
                } else {
//                    if (AmcWm::app()->frontend['bootstrap']['use']) {
//                        Yii::app()->user->setFlash('error', Yii::t("msgsbase.core", "Check the errors below"));
//                    } else {
//                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => Yii::t("msgsbase.core", "Check the errors below")));
//                    }
                    $this->render('resetpasswd', array('model' => $resetForm,));
                }
            } else {
                $this->render('resetpasswd', array('model' => $resetForm));
            }
        } else {
            $this->redirect(array('index'));
        }
    }

    /**
     * Action forget , request reset key
     * @access public
     * @return void
     */
    public function actionForgot() {
        if (Yii::app()->user->isGuest) {
            $forgotForm = new ForgetPasswordForm();
            if (Yii::app()->request->isPostRequest) {
                $forgotForm->attributes = AmcWm::app()->request->getParam('ForgetPasswordForm');
                //if (isset($person->users->email)) {
                if ($forgotForm->validate()) {
                    $resetAttributes = $forgotForm->person->users->generateResetKey();
                    if ($resetAttributes) {
                        $this->sendResetKey($forgotForm->person->getCurrent()->name, $forgotForm->person->users->username, $forgotForm->email, $resetAttributes['reset_key']);
                        if (AmcWm::app()->frontend['bootstrap']['use']) {
                            Yii::app()->user->setFlash('success', AmcWm::t("msgsbase.core", 'An email has been sent. Follow the link provided to reset your password'));
                        } else {
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'An email has been sent. Follow the link provided to reset your password')));
                        }

                        $this->redirect(array('/site/index', '#' => "message"));
                    } else {
                        if (AmcWm::app()->frontend['bootstrap']['use']) {
                            Yii::app()->user->setFlash('error', AmcWm::t("msgsbase.core", "Error generation key. Please try again."));
                        } else {
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", "Error generation key. Please try again.")));
                        }
                    }
                } else {
                    $this->render('forgotpasswd', array('model' => $forgotForm));
                }
            } else {
                $this->render('forgotpasswd', array('model' => $forgotForm));
            }
        } else {
            $this->redirect(array('index'));
        }
    }

    /**
     * Action register , register a new user to the system.
     * @access public
     * @return void
     */
    public function actionRegister() {
        $maillistSettings = new Settings('maillist', 0);
        $maillistOptions = $maillistSettings->getOptions();
        $enableSubscribe = $maillistOptions['default']['check']['enableSubscribe'];
        if (Yii::app()->user->isGuest) {
            $model = new Persons('register');
            $contentModel = new PersonsTranslation();
            $model->users = new Users;
            $model->users->is_system = 0;
            $model->addTranslationChild($contentModel, self::getCurrentLanguage());
            $model->setScenario('register');
            $model->users->setScenario('register');
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
            if (isset($_POST['Users']) && isset($_POST['Persons']) && isset($_POST['PersonsTranslation'])) {

                $model->attributes = $_POST['Persons'];
                $contentModel->attributes = $_POST['PersonsTranslation'];
                $model->users->attributes = $_POST['Users'];                
                $contentModel->onValidateOthers = array($this, 'validateOthers');
                $model->users->onAfterSave = array($this, 'afterSave');
                $validate = $model->users->validate(array('passwdRepeat', 'verifyCode', 'username', 'passwd', 'role_id'));
                $validate &= $model->validate();
                $validate &= $contentModel->validate();
                $success = false;
                $saved = false;

                if ($validate) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        $saved = $model->save();
                        $saved &= $contentModel->save();
                        $model->users->published = ActiveRecord::PUBLISHED;
                        $model->users->role_id = amcwm::app()->acl->getRoleId(Acl::REGISTERED_ROLE);
                        $model->users->user_id = $model->person_id;
                        $saved &= $model->users->save();
                        if ($saved) {
                            $success = true;
                            $transaction->commit();
                        }
                    } catch (Exception $e) {
                        //echo $e->getMessage();
                        $transaction->rollback();
                        $success = false;
                    }

                    if ($success) {
                        if (AmcWm::app()->frontend['bootstrap']['use']) {
                            Yii::app()->user->setFlash('success', AmcWm::t("msgsbase.core", 'Registration has been completed successfully, you have to activate your account by clicking on the link that was sent to your e-mail address.'));
                        } else {
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Registration has been completed successfully, you have to activate your account by clicking on the link that was sent to your e-mail address.')));
                        }
                        $this->redirect(array('/site/index'));
                    } else {
                        if (AmcWm::app()->frontend['bootstrap']['use']) {
                            Yii::app()->user->setFlash('error', AmcWm::t("msgsbase.core", "Can't save record"));
                        } else {
                            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.core", "Can't save record")));
                        }
                    }
                }
            }

            $this->render('register', array(
                'enableSubscribe' => $enableSubscribe,
                'contentModel' => $contentModel,
            ));
        } else {
            $this->forward('index');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel() {
        $id = (int) Yii::app()->user->getId();
        $model = Persons::model()->findByPk($id);        
        if ($model === null || $model->users === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $contentModel = PersonsTranslation::model()->findByPk(array("person_id" => $id, 'content_lang' => AmcWm::app()->getLanguage()));            
            if ($contentModel == null) {
                $contentModel = new PersonsTranslation();
                $contentModel->person_id = $id;
                $model->addTranslationChild($contentModel, self::getContentLanguage());                
            }
            $this->addRelation($contentModel);
            return $contentModel;
        }
    }

    
    /**
     * Add custom relations
     * @param PersonsTranslation $contentModel
     */
    protected function addRelation(PersonsTranslation &$contentModel){
        
    }
    public function actionProfile() {
        $maillistSettings = new Settings('maillist', 0);
        $maillistOptions = $maillistSettings->getOptions();
        $enableSubscribe = $maillistOptions['default']['check']['enableSubscribe'];
        if (!Yii::app()->user->isGuest) {
            $contentModel = $this->loadModel();
            $model = $contentModel->getParentContent();
            // $this->performAjaxValidation($model);
            if (isset($_POST['Persons']) && isset($_POST['PersonsTranslation'])) {
                $model->attributes = $_POST['Persons'];
                $contentModel->attributes = $_POST['PersonsTranslation'];

                $model->personImage = CUploadedFile::getInstance($model, 'personImage');
                $oldThumb = $model->thumb;
                $deleteImageFile = Yii::app()->request->getParam('deleteImageFile');
                if ($model->personImage instanceof CUploadedFile) {
                    $model->setAttribute('thumb', $model->personImage->getExtensionName());
                    $deleteImageFile = false;
                } else if ($deleteImageFile) {
                    $model->setAttribute('thumb', null);
                }
                $contentModel->onValidateOthers = array($this, 'validateOthers');                
                $model->users->onAfterSave = array($this, 'afterSave');
                $validate = $model->validate();
                
                $validate &= $contentModel->validate();
                $success = false;
                if ($validate) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        if ($model->save()) {
                            if ($contentModel->save()) {
                                $model->users->attributes = array('user_id' => $model->person_id);
                                if (isset($_POST['Users'])) {
                                    $model->users->setAttribute('passwd', $_POST['Users']['passwd']);
                                }
                                if ($model->users->save()) {
                                    $transaction->commit();
                                    $model->saveImage($deleteImageFile);
                                    $success = true;
                                }
                            }
                        }
                    } catch (CDbException $e) {
                        $transaction->rollback();
                        $success = false;
                    }
                    if ($success) {
                        if (AmcWm::app()->frontend['bootstrap']['use']) {
                            Yii::app()->user->setFlash('success', Yii::t("msgsbase.core", 'Your profile data has been saved successfully'));
                        } else {
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => Yii::t("msgsbase.core", 'Your profile data has been saved successfully')));
                        }
                        $this->redirect(array('/users/default/index'));
                    } else {
                        if (AmcWm::app()->frontend['bootstrap']['use']) {
                            Yii::app()->user->setFlash('error', Yii::t("msgsbase.core", "Can't save record"));
                        } else {
                            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => Yii::t("msgsbase.core", "Can't save record")));
                        }
                    }
                }
            }

            $form = (AmcWm::app()->frontend['bootstrap']['use']) ? 'profileBootstrap' : 'profile';
            $this->render($form, array(
                'model' => $model,
                'contentModel' => $contentModel,
                'enableSubscribe' => $enableSubscribe,
            ));
        } else {
//            $this->forward('register');
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
     * before validation in extended cobtroller
     * @param CModelEvent $event
     */
    protected function validateOthers($event) {
        $event->isValid = true;
    }

    /**
     * Custom validation in extended cobtroller
     * @param CEvent $event
     */
    protected function afterSave($event) {      
        
    }

}
