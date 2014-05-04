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

class FrontCommentsController extends FrontendController {

    /**
     * 
     * @var type 
     */
    protected $article = null;
    protected $images = null;
    protected $video = null;
    protected $gallery = null;

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

                $comment->save();
                $ownerParams = Yii::app()->request->getParam('CommentsOwners');
                $ownerParams["name"] = CHtml::encode($ownerParams["name"]);
                $comment->commentsOwners->attributes = $ownerParams;
                $comment->commentsOwners->setAttribute('comment_id', $comment->comment_id);

                if ($comment->commentsOwners->validate()) {
                    $comment->commentsOwners->save();
                    $ok = true;
                } else {
                    $ok = false;
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
        $comment->attributes = array("hide" => $hidden);
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
    public function getArticle() {
        return $this->article;
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

}