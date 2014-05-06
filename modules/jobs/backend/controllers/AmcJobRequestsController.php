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
class AmcJobRequestsController extends BackendController {    
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
     * Save model to database
     * @param Jobs $model
     * @access protected
     */
    protected function save(JobsRequests $model) {
        if (isset($_POST["JobsRequests"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model->attributes = $_POST['JobsRequests'];
            $validate = $model->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        $transaction->commit();
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Request has been saved')));
                        $this->redirect(array('view', 'id' => $model->request_id));
                    }
                } catch (CDbException $e) {
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
        $model = $this->loadModel($id);
        if ($model) {
            $this->save($model);
            $this->render('update', array(
                'model' => $model,
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
        parent::publish(1, "index");
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids', array());
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $model = $this->loadModel($id);
                $model->delete();
                $messages['success'][] = AmcWm::t("msgsbase.request", 'Request "{request_id}" has been deleted', array("{request_id}" => $model->request_id));
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
    public function actionShortlist($id) {
        
    }
    
    public function actionIndex() {
        //
        $model = new JobsRequests('search');
        $model->unsetAttributes();  // clear any default values
        if ($model) {

            $jid = Yii::app()->request->getParam('jid');
            $cVal = ChildTranslatedActiveRecord::getCompositeValues($jid);
            $job_id = $cVal['id'];
            if($job_id)
                $model->setAttribute('job_id', $job_id);
            
            if (isset($_GET['JobsRequests'])) {
                $model->attributes = $_GET['JobsRequests'];
            }

            $this->render('index', array(
                'model' => $model,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return Jobs
     */
    public function loadModel($id) {
        $model = JobsRequests::model()->findByPk($id);
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

}