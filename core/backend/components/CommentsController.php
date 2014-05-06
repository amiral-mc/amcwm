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

class CommentsController extends BaseCommentsController {

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if ($this->updateComment($model->comments)) {
            $viewRoute = array_merge(array('view', 'id' => $model->comments->comment_id), $this->getParams());
            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcwm.core.backend.messages.comments", 'Comment has been saved')));
            $this->redirect($viewRoute);
        }

        $this->render("{$this->viewAlias}.update", array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render("{$this->viewAlias}.view", array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * 
     */
    public function actionIndex() {
        $model = $this->newModel();
        $model->unsetAttributes();  // clear any default values
        $model->comments = new Comments('search');
        $model->comments->unsetAttributes();  // clear any default values                        
        $typePk = $this->commentsType->tableSchema->primaryKey;
        if (is_array($typePk)) {
            $model->$typePk[0] = $this->commentsType->$typePk[0];
        } else {
            $model->$typePk = $this->commentsType->$typePk;
        }
        if (isset($_GET['Comments']))
            $model->comments->attributes = $_GET['Comments'];
        $this->render("{$this->viewAlias}.index", array(
            'model' => $model,
        ));
    }

    public function actionReplies($item, $cid) {
        $componentName = null;
        if($this->componentName){
            $componentName = ucfirst($this->componentName);
        }
        $this->forward("replies{$componentName}/");
    }

}