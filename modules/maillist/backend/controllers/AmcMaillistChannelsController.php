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
class AmcMaillistChannelsController extends BackendController {

    protected function save(MaillistChannels $model) {
//        print_r($this->getMonthsList());
//        print_r();
//        die();
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getParam('MaillistChannels');
            if ($model->validate()) {
                if ($model->save()) {                    
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.channels", 'Channel has been saved')));
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
        $model = new MaillistChannels;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $this->save($model);
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
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $this->save($model);
        $this->render('update', array(
            'model' => $model,
        ));
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
                $check = count($model->maillistMessages) || count($model->maillists);
                $channel = $model->channel;
                if (!$check) {
                    if ($model->delete()) {
                        $messages['success'][] = AmcWm::t("msgsbase.channels", 'Channel "{channel}" has been deleted', array("{channel}" => $channel));
                    } else {
                        $messages['error'][] = AmcWm::t("msgsbase.channels", 'Can not delete channel "{channel}"', array("{channel}" => $channel));
                    }
                } else {
                    $messages['error'][] = AmcWm::t("msgsbase.channels", 'Can not delete channel "{channel}"', array("{channel}" => $channel));
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
        $model = new MaillistChannels('search');
        $model->unsetAttributes();  // clear any default values        
        if (isset($_GET['MaillistChannels']))
            $model->attributes = $_GET['MaillistChannels'];

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
        $model = MaillistChannels::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
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

    public function actionMessages($cid) {
        $this->forward('messages/');
    }
    
    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->publish($published, "index", array(), 'loadModel');
    }


}
