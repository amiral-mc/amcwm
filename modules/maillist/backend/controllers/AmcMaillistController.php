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
class AmcMaillistController extends BackendController {

    protected function save(ActiveRecord $model) {
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getParam('Maillist');
            $model->maillistUsers->attributes = Yii::app()->request->getParam('MaillistUsers');
            $maillistValidate = $model->validate();
            $maillistValidate &= $model->maillistUsers->validate();

            if ($maillistValidate) {
                if ($model->save()) {
                    $model->maillistUsers->setAttribute('user_id', $model->id);
                    $model->maillistUsers->save();
                    $model->saveAllChannels();
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'E-mail has been saved')));
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Maillist;
        $model->maillistUsers = new MaillistUsers;
        $this->save($model);
        $model->setAttribute('status', '1');
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if ($model->person_id) {
            $_GET['maillist'] = $_GET['id'];
            $_GET['id'] = $model->person_id;
            $this->forward("/" . AmcWm::app()->backendName . "/users/default/update");
        } else {
            $this->save($model);
            $this->render('update', array(
                'model' => $model,
            ));
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
                $model = $this->loadModel($id);
                $email = $model->email;
                if ($model->delete()) {
                    $messages['success'][] = AmcWm::t("msgsbase.core", 'E-mail <span style="direction:ltr">"{email}"</span> has been deleted', array("{email}" => $email));
                } else {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete E-mail <span style="direction:ltr">"{email}"</span>', array("{email}" => $email));
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
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Maillist('search');
        $model->maillistUsers = new MaillistUsers('search');
        $model->unsetAttributes();  // clear any default values        
        $model->maillistUsers->unsetAttributes();  // clear any default values        
        if (isset($_GET['Maillist']))
            $model->attributes = $_GET['Maillist'];
        if (isset($_GET['MaillistUsers']))
            $model->maillistUsers->attributes = $_GET['MaillistUsers'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Maillist::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        if (Yii::app()->request->isPostRequest) {
            if ($published) {
                $okMessage = 'Email "{displayTitle}" has been activated';
            } else {
                $okMessage = 'Email "{displayTitle}" has been deactivated';
            }
            $ids = Yii::app()->request->getParam('ids');
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();

            foreach ($ids as $id) {
                $model = $this->loadModel($id);
                $itemName = $model->displayTitle;
                $model->status = (int) $published;
                if ($model->save()) {
                    $messages['success'][] = AmcWm::t("msgsbase.core", $okMessage, array("{displayTitle}" => $itemName));
                } else {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not activate email "{displayTitle}"', array("{displayTitle}" => $itemName));
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
        }
        $this->redirect(array('index'));
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

}
