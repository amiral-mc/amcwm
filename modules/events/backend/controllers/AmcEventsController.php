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
class AmcEventsController extends BackendController {

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
     * @param EventsTranslation $contentModel
     * @access protected
     */
    protected function save(EventsTranslation $contentModel) {
        $contentModel->attachBehavior("attachmentBehaviors", new AttachmentBehaviors("events", $contentModel, 1, $contentModel->event_id));            
        if (isset($_POST['Events']) && isset($_POST["EventsTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();            
            $model = $contentModel->getParentContent();
            $model->attributes = $_POST['Events'];
            $contentModel->attributes = $_POST['EventsTranslation'];
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if ($validate) {
//                print_r($this-)
                try {
                    $isNew = $model->isNewRecord;
                    if ($model->save()) {
                        $saved = $contentModel->save();
                        if ($saved) {
                            $transaction->commit();
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Event has been saved')));
                            if ($isNew && $this->getModule()->appModule->sendEmailAfterInsert) {
                                //Yii::app()->mail->sender->ClearAllRecipients();                                
                                Yii::app()->mail->sender->Subject = AmcWm::t("msgsbase.core", "New agenda item has been inserted");
                                Yii::app()->mail->sender->AddAddress(Yii::app()->params['marketerEmail']);
                                Yii::app()->mail->sender->SetFrom(Yii::app()->params['adminEmail']);
                                $ok = Yii::app()->mail->sendView("application.views.email.eventCreated", array('eventHeader' => $_POST['EventsTranslation']['event_header'], 'id' => $model->event_id));
                            } 
                            $this->redirect(array('view', 'id' => $model->event_id));
                        }
                    }
                } catch (CDbException $e) {                    
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record" . $e->getMessage())));
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
        $model = new Events;
        $contentModel = new EventsTranslation();
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
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $contentModel->attachBehavior("attachmentBehaviors", new AttachmentBehaviors("events", $contentModel, 1, $contentModel->event_id));                
                $model = $contentModel->getParentContent();
                if ($model->delete()) {
                    $contentModel->deleteAttachment();
                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Event "{event}" has been deleted', array("{event}" => $contentModel->displayTitle));
                } else {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete event "{event}"', array("{event}" => $contentModel->displayTitle));
                }
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
            $translatedModel->attachBehavior("attachmentBehaviors", new AttachmentBehaviors("events", $translatedModel, 1, $translatedModel->event_id));            
            if (isset($_POST["EventsTranslation"])) {
                $translatedModel->attributes = $_POST['EventsTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->event_id));
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
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Events('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new EventsTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['Events'])) {
            $model->attributes = $_GET['Events'];
        }
        if ($contentModel) {
            if (isset($_GET['EventsTranslation'])) {
                $contentModel->attributes = $_GET['EventsTranslation'];
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
     */
    public function loadModel($id) {
        $model = Events::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'events-questions-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = EventsTranslation::model()->findByPk(array("event_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Ecents $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return EventsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = EventsTranslation::model()->findByPk(array("event_id" => (int) $id, 'content_lang' => $translationLang));                        
            if ($translatedModel === null) {
                $translatedModel = new EventsTranslation();
                $translatedModel->event_id = $model->event_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }            
        }
        return $translatedModel;
    }

}
