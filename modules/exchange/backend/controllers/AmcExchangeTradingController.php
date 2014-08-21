<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AmcExchangeTradingController
 * @author Amiral Management Corporation
 * @version 1.0
 */
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
     * @param ExchangeTrading $model
     * @access protected
     */
    protected function save(ExchangeTrading $model) {
        if (isset($_POST['ExchangeTrading'])) {
            $model->tradingCompanies = array();
            $model->attributes = $_POST['ExchangeTrading'];
            $tradingsModelSave = array();
            $validate = $model->validate();
            if (isset($_POST['ExchangeTradingCompanies'])) {
                foreach ($_POST['ExchangeTradingCompanies'] as $key => $value) {
                    $tradingValues['exchange_trading_exchange_id'] = $model->exchange_id;
                    $tradingValues['exchange_trading_exchange_date'] = $_POST['ExchangeTrading']['exchange_date'];
                    $tradingValues['exchange_companies_exchange_companies_id'] = $value['exchange_companies_exchange_companies_id'];
                    $tradingValues['opening_value'] = $value['opening_value'];
                    $tradingValues['closing_value'] = $value['closing_value'];
                    $tradingValues['difference_percentage'] = $value['difference_percentage'];

                    if (trim($value['opening_value']) != null || trim($value['closing_value']) != null || trim($value['difference_percentage']) != null) {
                        $tradingsModel = ExchangeTradingCompanies::model()->findByPk(array('exchange_trading_exchange_id' => $model->exchange_id, 'exchange_trading_exchange_date' => $_POST['ExchangeTrading']['exchange_date'], 'exchange_companies_exchange_companies_id' => $value['exchange_companies_exchange_companies_id']));
                        if ($tradingsModel === null) {
                            $tradingsModel = new ExchangeTradingCompanies;
                        }
                        $tradingsModel->attributes = $tradingValues;
                        $validate &= $tradingsModel->validate();
                        $tradingsModelSave[] = $tradingsModel;
                    } else {
                        $tradingsModel = new ExchangeTradingCompanies;
                        $tradingsModel->attributes = $tradingValues;
                    }
                    $model->addRelatedRecord("tradingCompanies", $tradingsModel, $key);
                }
            }

            if ($validate) {
                try {
                    if ($model->save()) {
                        $saved = array();
                        if ($tradingsModelSave) {
                            foreach ($tradingsModelSave as $record) {
                                $saved[] = $record->exchange_companies_exchange_companies_id;
                                $record->save();
                            }
                        }
                        $deleteQuery = "DELETE FROM exchange_trading_companies WHERE exchange_trading_exchange_id = " . $model->exchange_id .
                                " AND exchange_trading_exchange_date = " . AmcWm::app()->db->quoteValue($model->exchange_date);
                        if ($saved) {
                            $deleteQuery .= " AND exchange_companies_exchange_companies_id not in (" . implode(",", $saved) . ") ";
                        }
                        Yii::app()->db->createCommand($deleteQuery)->execute();
                        Yii::app()->user->setFlash('success', array
                            ('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Record has been saved')));
                        $this->redirect(array('view', 'id' => $model->exchange_date, 'eid' => $model->exchange_id));
                    }
                } catch (CDbException $e) {
                    //echo $e->getMessage();
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
            } else {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.tradings", "Please fill in at least 1 company and missing company's data")));
            }
        } else {
            $companies = Yii::app()->db->createCommand(''
                            . 'SELECT * FROM exchange_companies e '
                            . 'INNER JOIN exchange_companies_translation et on e.exchange_companies_id = et.exchange_companies_id '
                            . 'LEFT JOIN exchange_trading_companies etc on e.exchange_companies_id = etc.exchange_companies_exchange_companies_id '
                            . 'WHERE exchange_id = ' . $model->exchange_id . " AND etc.exchange_companies_exchange_companies_id IS NULL")->queryAll();
            $count = count($model->tradingCompanies);
            foreach ($companies as $key => $company) {
                $tradingsModel = new ExchangeTradingCompanies;
                $tradingsModel->exchange_companies_exchange_companies_id = $company['exchange_companies_id'];
                $tradingsModel->exchange_trading_exchange_id = $model->exchange_id;
                $model->addRelatedRecord("tradingCompanies", $tradingsModel, $key + $count);
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
        $model->exchange_id = $eid;
        $companies = Yii::app()->db->createCommand(''
                        . 'SELECT * FROM exchange_companies e '
                        . 'INNER JOIN exchange_companies_translation et on e.exchange_companies_id = et.exchange_companies_id '
                        . 'INNER JOIN exchange_trading_companies etc on e.exchange_companies_id = etc.exchange_companies_exchange_companies_id '
                        . 'WHERE exchange_id = ' . $model->exchange_id)->queryAll();
        $count = count($model->tradingCompanies);
        foreach ($companies as $key => $company) {
            $tradingsModel = new ExchangeTradingCompanies;
            $tradingsModel->exchange_companies_exchange_companies_id = $company['exchange_companies_id'];
            $tradingsModel->exchange_trading_exchange_id = $model->exchange_id;
            $model->addRelatedRecord("tradingCompanies", $tradingsModel, $key + $count);
        }
        $this->save($model);
        $this->render('create', array(
            'model' => $model,
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
        $this->save($model);
        $this->render('update', array(
            'model' => $model,
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
    
    public function ajaxCompaniesList(){        
        $list = ExchangeCompanies::getCompaniesList(AmcWm::app()->request->getParam('q'), AmcWm::app()->request->getParam('page', 1), AmcWm::app()->request->getParam('prompt'), AmcWm::app()->request->getParam('eid'));
        header('Content-type: application/json');
        echo CJSON::encode($list);
    }

}
