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

class BaseCommentsController extends BackendController {

    /**
     * Comments component name
     * @var string 
     */
    protected $componentName = "";

    /**
     * Check if the curreny countroller is reply controller or not
     * @var boolean 
     */
    protected $isReply = false;

    /**
     * Comments type object ,
     * @var ChildTranslatedActiveRecord 
     */
    protected $commentsType = null;

    /**
     * model instance ,
     * @var ActiveRecord 
     */
    protected $model = null;
    
    
    /**
     * View alias
     * @var string
     */
    protected $viewAlias = null;

    /**
     * The route of the main component
     * @var string 
     */
    protected $backRoute;
    
    /**
     * the route for makeing a new reply
     * @var string
     */
    protected $createRoute = null;
    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     * @access public
     * @return $void
     */
    public function init() {
        if(isset(AmcWm::app()->backend['viewsInProject']) && AmcWm::app()->backend['viewsInProject']){
            $this->viewAlias = "application.modules.backend.views.comments";
        }
        else{
            $this->viewAlias = "amcwm.core.backend.views.comments";
        }
        parent::init();
    }

    /**
     * @var string 
     * @access public
     * @return string
     */
    public function getBackRoute() {
        return $this->backRoute;
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->publish($published, "index", $this->getParams(), "loadComment");
    }

    /**
     * Performs the hide action
     * @see ManageComments::hide($ids, $hidden)
     * @param int $hidden
     * @access public 
     * @return void
     */
    public function actionHide($hidden) {
        if (Yii::app()->request->isPostRequest) {
            if ($hidden) {
                $okMessage = 'item "{displayTitle}" has been hidden';
            } else {
                $okMessage = 'item "{displayTitle}" has been unhidden';
            }
            $ids = Yii::app()->request->getParam('ids');
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $model = $this->loadComment($id);
                if ($this->hideComment($model, $hidden)) {
                    $messages['success'][] = AmcWm::t("amcBack", $okMessage, array("{displayTitle}" => $model->displayTitle));
                } else {
                    $messages['error'][] = AmcWm::t("amcBack", 'Can not hide item "{displayTitle}"', array("{displayTitle}" => $model->displayTitle));
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
        }
        $indexRoute = array_merge(array('index'), $this->getParams());
        $this->redirect($indexRoute);
    }

    /**
     * 
     */
    public function actionDelete() {
        if (Yii::app()->request->isPostRequest) {
            $typePk = $this->commentsType->tableSchema->primaryKey;
            if (is_array($typePk)) {
                $typePkId = $typePk[0];
            } else {
                $typePkId = $typePk;
            }
            $table = $this->commentsType->getParentContent()->tableName();
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();

            // we only allow deletion via POST request
            $ids = Yii::app()->request->getParam('ids');
            $deletedComments = 0;
            foreach ($ids as $id) {
                $model = $this->loadComment($id);
                if ($this->deleteComment($model)) {
                    $deletedComments++;
                    $messages['success'][] = AmcWm::t("amcBack", 'item "{displayTitle}" has been deleted', array("{displayTitle}" => $model->displayTitle));
                } else {
                    $messages['error'][] = AmcWm::t("amcBack", 'Can not delete item "{displayTitle}"', array("{displayTitle}" => $model->displayTitle));
                }
            }
            if (!$this->isReply) {
                $query = sprintf("update {$table} set comments = comments - {$deletedComments} where $typePkId = %d", $this->commentsType->$typePkId);
                Yii::app()->db->createCommand($query)->execute();
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
            $indexRoute = array_merge(array('index'), $this->getParams());
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect($indexRoute);
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * @create new model
     * @access protected
     * @return ActiveRecord
     */
    protected function newModel() {
        
    }

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'commentsTypeContext';
        return $filters;
    }

    /**
     * Gets the comments type id
     * @access public
     * @return integer
     */
    public function getItemId() {
        
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper article context
     */
    public function filterCommentsTypeContext($filterChain) {
        //set the project identifier based on either 
        //the GET or POST input request variables, 
        //since we allow both types for our actions   
        $itemId = Yii::app()->request->getParam('item', null);
        $this->loadCommentsData($itemId);
        //complete the running of other filters and execute the requested action
        $filterChain->run();
    }

    /**
     * Gets the comments type instance
     * @access public 
     * @return ChildTranslatedActiveRecord
     */
    public function getCommentsType() {
        return $this->commentsType;
    }

    /**
     * Update comment
     * @param Comments $comment
     * @return boolean
     * @access protected
     */
    protected function updateComment(Comments $comment) {
        $ok = false;
        if (Yii::app()->request->isPostRequest) {
            $ok = $this->validateComment($comment);
            if ($ok) {
                $comment->save();
            }
        }
        return $ok;
    }

    /**
     * delete comment
     * @param Comments $comment
     * @return boolean
     * @access protected
     */
    protected function deleteComment(Comments $comment) {
        return $comment->delete();
    }

    /**
     * load comment
     * @param integet $id
     * @return boolean
     * @access protected
     */
    protected function loadComment($id) {
        $model = $this->loadModel($id);
        return $model->comments;
    }

    /**
     * hide comment
     * @param ActiveRecord $comment
     * @param boolean $hidden
     * @return boolean
     * @access protected
     */
    protected function hideComment(Comments $comment, $hidden) {
        //return $comment->hide($hidden);        
        $comment->attributes = array("hide" => $hidden);
        $comment->save();
        return $comment->save();
    }

    /**
     * validate comment
     * @param ActiveRecord $comment
     * @return boolean
     * @access protected
     */
    protected function validateComment(Comments $comment) {
        $commentsParam = Yii::app()->request->getParam('Comments');
        $comment->attributes = $commentsParam;
        return $comment->validate();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    protected function loadCommentsData($id) {
        
    }

    /**
     * Gets params to be added to the tools
     * @access public
     * @return array
     */
    public function getParams() {
        $params = array();
        $params['item'] = $this->getItemId();
        
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
        $virtuals = AmcWm::app()->appModule->getVirtuals();
        if (isset($virtuals[$virtual]['redirectParams'])) {
            foreach ($virtuals[$virtual]['redirectParams'] as $p) {
                $params[$p] = AmcWm::app()->request->getParam($p);
            }
        }
//        return array('item' => $this->getItemId());
        return $params;
    }

}