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

class FrontRepliesCommentsController extends FrontendController {

    /**
     * 
     * @var type 
     */
    protected $comment = null;
    protected $article = null;
    protected $images    = null;
    protected $video    = null;
    protected $gallery  = null;
    
     /**
     * Update comment
     * @param Comments $comment
     * @return boolean
     * @access protected
     */
    protected function createComment(Comments $comment) {
        $ok = false;
        if (Yii::app()->request->isPostRequest) {
            $ok = $this->validateComment($comment);
            if ($ok) {
                if (!Yii::app()->user->isGuest) {
                    $userData = Yii::app()->user->getInfo();
                    $comment->setAttribute('user_id', $userData['user_id']);
                }
                $comment->setAttribute('comment_review', Yii::app()->request->getParam('commentId'));
                $ok = $comment->save();
                if ($ok && Yii::app()->user->isGuest) {
                    $ownerParams = Yii::app()->request->getParam('RepliesCommentsOwners');
                    $ownerParams["name"] = CHtml::encode($ownerParams["name"]);
                    $comment->commentsOwners->attributes = $ownerParams;
                    $comment->commentsOwners->setAttribute('comment_id', $comment->comment_id);
                    $validate = $comment->commentsOwners->validate();
                    if ($validate) {
                        $ok = $comment->commentsOwners->save();
                    }
                }
            }
        }
        return $ok;
    }  

    /**
     * delete comment
     * @param ActiveRecord $comment
     * @return boolean
     * @access protected
     */
    protected function deleteComment(ActiveRecord $comment) {
        return $comment->delete();
    }
    
    /**
     * publish comment
     * @param ActiveRecord $comment
     * @param boolean $published
     * @return boolean
     * @access protected
     */
    protected function publishComment(ActiveRecord $comment, $published) {
        return $comment->publish($published);
    }
    
    /**
     * hide comment
     * @param ActiveRecord $comment
     * @param boolean $hidden
     * @return boolean
     * @access protected
     */
    protected function hideComment(ActiveRecord $comment, $hidden) {
        //return $comment->hide($hidden);        
        $comment->attributes = array("hide"=>$hidden);
        return $comment->save();
    }

    /**
     * validate comment
     * @param ActiveRecord $comment
     * @return boolean
     * @access protected
     */
    protected function validateComment(ActiveRecord $comment) {
        $commentsParam = Yii::app()->request->getParam('RepliesComments');
        $commentsParam["comment_header"] = CHtml::encode($commentsParam["comment_header"]);
        $commentsParam["comment"] = CHtml::encode($commentsParam["comment"]);
        $comment->attributes = $commentsParam;
        return $comment->validate();
    }      
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadArticle($id) {

        if ($this->article === null) {
            $this->article = Articles::model()->findByPk((int) $id);
            if ($this->article === null) {
                throw new CHttpException(404, 'The requested project does not exist.');
            }
        }
        return $this->article;
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadComment($id) {

        if ($this->comment === null) {
            $this->comment = RepliesComments::model()->findByPk((int) $id);
            if ($this->comment === null) {
                throw new CHttpException(404, 'The requested project does not exist.');
            }
        }
        return $this->comment;
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadGallery($id) {

        if ($this->gallery === null) {
            $this->gallery = Galleries::model()->findByPk((int) $id);
            if ($this->gallery === null) {
                throw new CHttpException(404, 'The requested gallery does not exist.');
            }
        }
        return $this->gallery;
    }
    
    public function loadImageData($id) {
        
        if ($this->images === null) {
            $this->images = Images::model()->findByPk((int) $id);
            if ($this->images === null) {
                throw new CHttpException(404, 'The requested image does not exist.');
            }
        }
        return $this->images;
    }
    
    public function loadVideoData($id) {

        if ($this->video === null) {
            $this->video = Videos::model()->findByPk((int) $id);
            if ($this->video === null) {
                throw new CHttpException(404, 'The requested project does not exist.');
            }
        }
        return $this->video;
    }
    
    
    /**
     * 	Returns the gallery model instance to which this images or videos belongs
     * @return
     */
    public function getComment() {
        return $this->comment;
    }
    
    public function getGalley() {
        return $this->gallery;
    }
    
    public function getImage() {
        return $this->images;
    }
    
    public function getVideo() {
        return $this->video;
    }
    
     /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function create($id, $cachePreFix) {
        $cache = Yii::app()->getComponent('cache');
        if ($cache !== null) {
            $cache->delete("{$cachePreFix}{$id}");
        }
        if (Yii::app()->request->isPostRequest) {
            $model = new RepliesComments;
            $model->commentsOwners = new RepliesCommentsOwners;
            if ($this->createComment($model)) {
                if (AmcWm::app()->frontend['bootstrap']['use']) {
                    $success = Yii::t("comments", 'Replay has been added');
                } else {
                    $success = array('class' => 'flash-error', 'content' => Yii::t("comments", 'Replay has been added'));
                }
                Yii::app()->user->setFlash('success', $success);
                $params = array('default/view', 'id' => $id);
                if (Yii::app()->request->getParam("page")) {
                    $params['page'] = $params;
                }
                $params['lang'] = Controller::getCurrentLanguage();
                $params['#'] = "comments";
                $this->redirect($params);
            } else {
                if (AmcWm::app()->frontend['bootstrap']['use']) {
                    $error = Yii::t("comments", 'Replay cannot be added, please check the required values');
                } else {
                    $error = array('class' => 'flash-error', 'content' => Yii::t("comments", 'Replay cannot be added, please check the required values'));
                }
                Yii::app()->user->setFlash('error', $error);
                //Html::printR($);
                $this->forward('default/view');
            }
        } else
            throw new CHttpException(400, 'Invalid request on create. Please do not repeat this request again.');
    }
    
}
