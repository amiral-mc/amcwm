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

class RepliesCommentsController extends BaseCommentsController {

    protected $isReply = true;
    /**
     * Comment parent record
     * @var Comments 
     */
    protected $comment = null;

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    protected function loadCommentsData($id) {
        parent::loadCommentsData($id);
        $itemId = Yii::app()->request->getParam('cid', null);
        $this->loadParentComment($itemId);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadParentComment($id) {

        if ($this->comment === null) {
            $this->comment = Comments::model()->findByPk((int) $id);
            if ($this->comment === null) {
                throw new CHttpException(404, 'The requested project does not exist.');
            }
        }
        return $this->comment;
    }

    /**
     * load comment
     * @param integet $id
     * @return boolean
     * @access protected
     */
    protected function loadComment($id) {
        return $this->loadModel($id);
    }

    /**
     * 	Returns the gallery model instance to which this images or videos belongs
     * @return
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionCreate() {
        $model = new Comments();
        $model->setAttribute('comment_review', $this->comment->comment_id);
        if ($this->updateComment($model)) {
            $viewRoute = array_merge(array('view', 'id' => $model->comment_id), $this->getParams());
            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcwm.core.backend.messages.comments", 'Comment has been saved')));
            $this->redirect($viewRoute);
        }

        $this->render("{$this->viewAlias}.replies.update", array(
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
        if ($this->updateComment($model)) {
            $viewRoute = array_merge(array('view', 'id' => $model->comment_id), $this->getParams());
            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcwm.core.backend.messages.comments", 'Comment has been saved')));
            $this->redirect($viewRoute);
        }

        $this->render("{$this->viewAlias}.replies.update", array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render("{$this->viewAlias}.replies.view", array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * 
     */
    public function actionIndex() {
        $model = new Comments('search');
        $model->unsetAttributes();  // clear any default values                        
        if (isset($_GET['Comments']))
            $model->attributes = $_GET['Comments'];
        $model->comment_review = $this->comment->comment_id;
        $this->render("{$this->viewAlias}.replies.index", array(
            'model' => $model,
        ));
    }

    /**
     * Gets params to be added to the tools
     * @access public
     * @return array
     */
    public function getParams() {
        $params = parent::getParams();
        $params['cid'] = $this->comment->comment_id;
        return $params;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Comments::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
