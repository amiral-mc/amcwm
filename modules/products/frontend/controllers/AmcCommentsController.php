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
class AmcCommentsController extends FrontCommentsController {

    public function actionIndex($id) {
        /**
         * @todo add the ajax list for the comments;
         */
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
     * Create a new comment
     * If update is successful, the browser will be redirected to the 'same' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionCreate($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = new Comments;
            $model->commentsOwners = new CommentsOwners;
            if ($this->createComment($model)) {
                $ok = Yii::app()->db->createCommand(sprintf("insert into products_comments (product_comment_id, product_id) values (%d, %d)", $model->comment_id, $id))->execute();
                if ($ok) {
                    if (AmcWm::app()->frontend['bootstrap']['use']) {
                        $success = Yii::t("comments", 'Comment has been added');
                    } else {
                        $success = array('class' => 'flash-error', 'content' => Yii::t("comments", 'Comment has been added'));
                    }
                    $updateQuery = sprintf("update products set comments = comments + 1 where product_id = %d", $id);
                    Yii::app()->db->createCommand($updateQuery)->execute();
                }
                $params = array('default/view', 'id' => $id, 'lang' => Controller::getCurrentLanguage(), '#' => "comments");
                Yii::app()->user->setFlash('success', $success);
                $this->redirect($params);
            } else {
                if (AmcWm::app()->frontend['bootstrap']['use']) {
                    $error = Yii::t("comments", 'Comment cannot be added, please check the required values');
                } else {
                    $error = array('class' => 'flash-error', 'content' => Yii::t("comments", 'Comment cannot be added, please check the required values'));
                }
                Yii::app()->user->setFlash('error', $error);
                $this->forward('default/view');
            }
        } else
            throw new CHttpException(400, 'Invalid request on create. Please do not repeat this request again.');
    }

    public function actionLike() {
        $table = "products_comments";
        $key = "product_id";
        $commentKey = "product_comment_id";
        $this->like($table, 'product_' , $key, $commentKey);
    }

}
