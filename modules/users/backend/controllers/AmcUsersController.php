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
class AmcUsersController extends BackendController {

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
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionProfile() {
        $this->render('profile', array(
            'contentModel' => $this->loadChildModel(AmcWm::app()->user->getId()),
        ));
    }

    /**
     * Save model
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function save(PersonsTranslation $contentModel) {
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Persons']) && isset($_POST['PersonsTranslation']) && isset($_POST['Users'])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $model->attributes = $_POST['Persons'];
            $contentModel->attributes = $_POST['PersonsTranslation'];
            $model->users->attributes = $_POST['Users'];
            $model->personImage = CUploadedFile::getInstance($model, 'personImage');
            $oldThumb = $model->thumb;
            $deleteImageFile = Yii::app()->request->getParam('deleteImageFile');
            if ($model->personImage instanceof CUploadedFile) {
                $model->setAttribute('thumb', $model->personImage->getExtensionName());
                $deleteImageFile = false;
            } else if ($deleteImageFile) {
                $model->setAttribute('thumb', null);
            }
            $validate = $model->validate();
            $validate &= $model->users->validate();
            $validate &= $contentModel->validate();
            $success = false;
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $model->users->attributes = array('user_id' => $model->person_id);
                            if ($model->users->save()) {
                                $transaction->commit();
                                $success = true;
                            }
                        }
                    }
                } catch (CDbException $e) {
                    $transaction->rollback();
                    $success = false;
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    //$this->refresh();
                }
                if ($success) {                    
                    $model->saveImage($deleteImageFile);
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'User has been saved')));
                    $this->redirect(array('view', 'id' => $model->person_id));
                }
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Persons('search');
        $model->users = new Users;
        $model->users->person = $model;
        $contentModel = new PersonsTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $this->save($contentModel);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

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
        $model = $contentModel->getParentContent();
        $user = $model->users;
        if ($contentModel && $user) {
            $this->save($contentModel);
            $this->render('update', array(
                'contentModel' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
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
            if (isset($_POST["PersonsTranslation"])) {
                $translatedModel->attributes = $_POST['PersonsTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->person_id));
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
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids');
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();

            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $user = $model->users;
                $writer = $model->writers;
                $articlesCount = 0;
                $userInfo = yii::app()->user->getInfo();
                if ($writer) {
                    $articlesCount = count($model->writers->articles);
                }
                $checkRelated = ($articlesCount || count($model->sections) || count($user->usersLogs) || $user->is_system || $userInfo['username'] == $user->username);
                if ($checkRelated) {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete user "{username}"', array("{username}" => $user->username));
                } else {
                    $deleted = $user->delete();
                    if ($deleted && $writer) {
                        $deleted = $writer->delete();
                    }
                    if ($deleted) {
                        $deleted = $model->delete();
                        if ($deleted) {
                            $model->deleteImage();
                            $messages['success'][] = AmcWm::t("msgsbase.core", 'User "{username}" has been deleted', array("{username}" => $user->username));
                        }
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
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Persons('search');
        $model->users = new Users('search');
        $model->users->person = $model;
        $model->users->unsetAttributes();  // clear any default values
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new PersonsTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['Persons'])) {
            $model->attributes = $_GET['Persons'];
        }

        if ($contentModel) {
            if (isset($_GET['PersonsTranslation'])) {
                $contentModel->attributes = $_GET['PersonsTranslation'];
            }
            if (isset($_GET['Users'])) {
                $model->users->attributes = $_GET['Users'];
            }

            $this->render('index', array(
                'contentModel' => $contentModel,
                'model' => $model->users,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Persons $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return PersonsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        $user = $model->users;
        if ($model === null || $user === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = PersonsTranslation::model()->findByPk(array("person_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new PersonsTranslation();
                $translatedModel->person_id = $model->person_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Users::model()->findByPk((int) $id);
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

    public function actionPublish($published) {
        $this->publish($published, 'index', array(), 'loadModel');
    }

    /**
     * Manage permession
     * @access public
     */
    public function actionPermissions($id) {
        $model = $this->loadModel($id);
        $userInfo = yii::app()->user->getInfo();
        if ($model->is_system || $userInfo['username'] == $model->username) {
            $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not change user permissions "{username}"', array("{username}" => $model->username));
            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            $this->redirect(array('index'));
        }
        if (Yii::app()->request->isPostRequest) {
            $transaction = Yii::app()->db->beginTransaction();
            $success = false;
            try {
                $saved = $model->setPermissions(Yii::app()->request->getParam('permissions'));
                if ($saved) {
                    $transaction->commit();
                    $success = true;
                }
            } catch (CDbException $e) {
                $transaction->rollback();
                $success = false;
            }
            if ($success) {
                $messages['success'][] = AmcWm::t("msgsbase.core", 'User "{username}" permissions has been changed', array("{username}" => $model->username));
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
            }
        }
        $this->render('permissions', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $id = (int) $id;
        $user = Users::model()->findByPk($id);
        if ($user === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
            $contentModel = PersonsTranslation::model()->findByPk(array("person_id" => $pk['id'], 'content_lang' => $pk['lang']));
            if ($contentModel == null) {
                $contentModel = new PersonsTranslation();
                $contentModel->person_id = $id;
                $user->person->addTranslationChild($contentModel, self::getContentLanguage());
            }
            return $contentModel;
        }        
    }

}
