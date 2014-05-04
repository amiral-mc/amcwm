<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcMaillistMessagesController extends BackendController {

    public $channel = null;

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'ChannelsContext';
        return $filters;
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper article context
     */
    public function filterChannelsContext($filterChain) {
        $channelId = Yii::app()->request->getParam('cid');
        $this->loadChannelData($channelId);
        $filterChain->run();
    }

    public function loadChannelData($channelId) {
        if ($this->channel === null) {
            $this->channel = MaillistChannels::model()->findByPk((int) $channelId);
//            if($this->channel && $this->channel->auto_generate){
//                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.channels", 'You cannot add messages to this "{channel}"', array("{channel}"=>$this->channel->channel))));
//                $this->redirect(array('/' . AmcWm::app()->backendName . '/maillist/channels/index'));
//            }
            if ($this->channel === null && $channelId) {
                throw new CHttpException(404, 'The requested channel does not exist.');
            }
        }
        return $this->channel;
    }

    /**
     * 	Returns the channel model instance
     * @return
     */
    public function getChannel() {
        return $this->channel;
    }

    protected function save(ActiveRecord $model) {
        $senario = ucfirst($model->getScenario());
        //update;
        //insert;
        if (($this->channel && !$this->channel->auto_generate) || $this->channel === null) {            
            $model->setScenario('allowEditingBody4' . $senario);
            $senario = ucfirst($model->getScenario());
        }
        if ($this->channel && $this->channel->auto_generate) {
            $model->setScenario('requireTemplate4' . $senario);
        }
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getParam('MaillistMessage');
            $channelId = null;
            if ($this->channel) {
                $channelId = $this->channel->id;
                $model->setAttribute('channel_id', $this->channel->id);
            }
            if ($model->validate()) {
                if($model->isNewRecord){
                    $model->setScenario('insert');
                }
                else{
                    $model->setScenario('update');
                }
                if ($model->save()) {
                    $model->saveSections();
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.mailing", 'Message has been saved')));
                    $this->redirect(array('view', 'id' => $model->id, 'cid' => $channelId));
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
        $model = new MaillistMessage;
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
                $subject = $model->subject;
                if ($model->delete()) {
                    $messages['success'][] = AmcWm::t("msgsbase.mailing", 'Message "{subject}" has been deleted', array("{subject}" => $subject));
                } else {
                    $messages['error'][] = AmcWm::t("msgsbase.mailing", 'Can not delete message "{subject}"', array("{subject}" => $subject));
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
                if ($this->channel) {
                    $url = array('index', 'cid' => $this->channel->id);
                } else {
                    $url = array('index',);
                }
                $this->redirect($url);
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new MaillistMessage('search');
        $model->unsetAttributes();  // clear any default values        
        if ($this->channel) {
            $model->channel_id = $this->channel->id;
        }

        if (isset($_GET['MaillistMessage']))
            $model->attributes = $_GET['MaillistMessage'];

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
        $model = MaillistMessage::model()->findByPk((int) $id);
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

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->publish($published, "index", array('cid' => AmcWm::app()->request->getParam('cid')), 'loadModel');
    }

}
