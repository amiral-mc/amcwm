<?php

Amcwm::import('amcwm.core.backend.models.Sections');

class AmcExchangeTradingController extends BackendController {

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
     * @param DirCompaniesBranches $model
     * @access protected
     */
    protected function save(ExchangeTrading $model) {
        if (isset($_POST['ExchangeTrading'])) {
            $model->attributes = $_POST['ExchangeTrading'];
            $validate = $model->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        Yii::app()->user->setFlash('success', array
                            ('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Record has been saved')));
                        $this->redirect(array('view', 'id' => $model->exchange_date));
                    }
                } catch (CDbException $e) {
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ExchangeTrading;
        $this->save($model);
        $this->render('create', array('model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $this->save($model);
        $this->render('update', array
            (
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new ExchangeTrading();
        $model->unsetAttributes();
        if (isset($_GET['ExchangeTrading'])) {
            $model->attributes = $_GET['ExchangeTrading'];
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids', array
                ());
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();

            foreach ($ids as $id) {
                $model = $this->loadModel($id);
                $hasChilds = $model->integrityCheck();
                if ($hasChilds) {
                    $deleted = false;
                } else {
                    $deleted = $model->delete();
                }
                if ($deleted) {
                    $messages['success'][] = AmcWm::t("amcTools", 'Record has been deleted');
                } else {
                    $messages['error'][] = AmcWm::t("amcTools", "Can't delete record");
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
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->publish($published, 'index', array(), 'loadModel');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $params = explode(',', $id);
        if(isset($params[1])){
            $id = $params[1];
        }
        else{
            $id = $params[0];
        }
        $model = ExchangeTrading::model()->findByAttributes(array('exchange_date' => $id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
}
