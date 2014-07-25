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
            $eid = (int) Yii::app()->request->getParam('eid');
            $model->attributes = $_POST['ExchangeTrading'];
            $tradingsModelSave = array();
            $validate = false;
            if (isset($_POST['ExchangeTradingCompanies'])) {
                ExchangeTradingCompanies::model()->deleteAll('exchange_trading_exchange_id = ' . $eid);
                foreach ($_POST['ExchangeTradingCompanies'] as $key => $value) {
                    $tradingsModel = new ExchangeTradingCompanies;
                    $tradingsModel->opening_value = $value['opening_value'];
                    $tradingsModel->closing_value = $value['closing_value'];
                    $tradingsModel->difference_percentage = $value['difference_percentage'];
                    $tradingsModel->exchange_companies_exchange_companies_id = $value['exchange_companies_exchange_companies_id'];
                    $tradingsModel->exchange_trading_exchange_id = $eid;
                    $tradingsModel->exchange_trading_exchange_date = $_POST['ExchangeTrading']['exchange_date'];
                    $validate = $tradingsModel->validate();
                    $tradingsModelSave[] = $tradingsModel;
                }
            }
            $validate &= $model->validate();
            if ($validate) {
                try {
                    if ($model->save() && $tradingsModelSave) {
                        foreach ($tradingsModelSave as $record) {
                            $record->save();
                        }
                        Yii::app()->user->setFlash('success', array
                            ('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Record has been saved')));
                        $this->redirect(array('view', 'id' => $model->exchange_date, 'eid' => $model->exchange_id));
                    }
                } catch (CDbException $e) {
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
            } else {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.tradings", "Please fill in at least 1 company and missing company's data")));
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $eid = (int) $_GET['eid'];
        $model = new ExchangeTrading;
        $tradingsModel = new ExchangeTradingCompanies;
        $companies = Yii::app()->db->createCommand(''
                        . 'SELECT * FROM exchange_companies e '
                        . 'INNER JOIN exchange_companies_translation et on e.exchange_companies_id = et.exchange_companies_id '
                        . 'WHERE exchange_id = ' . $eid)->queryAll();
        $this->save($model);
        $this->render('create', array(
            'model' => $model,
            'companies' => $companies,
            'tradingsModel' => $tradingsModel,
            'eid' => $eid,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $eid = (int) $_GET['eid'];
        $model = $this->loadModel($id);
        $childModel = $this->loadChildModel($eid);
        $tradingsModel = new ExchangeTradingCompanies;
        $companies = Yii::app()->db->createCommand(''
                        . 'SELECT * FROM exchange_companies e '
                        . 'INNER JOIN exchange_companies_translation et on e.exchange_companies_id = et.exchange_companies_id '
                        . 'WHERE exchange_id = ' . $eid)->queryAll();
        $this->save($model);
        $this->render('update', array(
            'model' => $model,
            'companies' => $companies,
            'tradingsModel' => $tradingsModel,
            'childModel' => $childModel,
            'eid' => $eid,
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
        } else {
            $model->exchange_id = (int) $_GET['eid'];
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
                    ExchangeTradingCompanies::model()->deleteAll('exchange_trading_exchange_id = ' . $model->exchange_id);
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
                $this->redirect(array('index', 'eid' => (int) $_GET['eid']));
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
        if (isset($params[1])) {
            $id = $params[1];
        } else {
            $id = $params[0];
        }
        $model = ExchangeTrading::model()->findByAttributes(array('exchange_date' => $id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return NatureCargoTranslation
     */
    public function loadChildModel($eid) {
        $model = ExchangeTradingCompanies::model()->findAll("exchange_trading_exchange_id = " . $eid);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
