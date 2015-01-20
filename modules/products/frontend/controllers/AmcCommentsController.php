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
        $product = new ProductData($id, false);
        if (Yii::app()->request->isPostRequest) {
            $model = new Comments;
            $model->commentsOwners = new CommentsOwners;
            if ($this->createComment($model)) {
                $ok = Yii::app()->db->createCommand(sprintf("insert into products_comments (product_comment_id, article_id) values (%d, %d)", $model->comment_id, $id))->execute();
                if ($ok) {
                    $updateQuery = sprintf("update products set comments = comments + 1 where product_id = %d", $id);
                    Yii::app()->db->createCommand($updateQuery)->execute();
                }
                $params = array('default/view', 'id' => $id, 'lang' => Controller::getCurrentLanguage(), '#' => "comments");
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => Yii::t("comments", 'Comment has been added')));
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
        $articleId = (int) Yii::app()->request->getParam('aid');
        $article = new ArticleData($articleId, false);
        $like = (intval(Yii::app()->request->getParam('like')) ? "good_imp" : "bad_imp");
        $id = (int) Yii::app()->request->getParam('id');
        if (Yii::app()->request->isPostRequest) {
            $cookieName = "liks_comments_{$id}";
            if (!isset(Yii::app()->request->cookies[$cookieName]->value)) {
                $cache = Yii::app()->getComponent('cache');
                if ($cache !== null) {
                    $cache->delete('article_' . $articleId);
                }
                $query = "update comments set $like=$like+1 where comment_id = {$id}";
                Yii::app()->db->createCommand($query)->execute();
                $cookie = new CHttpCookie($cookieName, $cookieName);
                $cookie->expire = time() + 900;
                Yii::app()->request->cookies[$cookieName] = $cookie;
            }
        }
        $query = sprintf("select %s from comments where comment_id=%d", $like, $id);
        $ret = Yii::app()->db->createCommand($query)->queryScalar();
        echo $ret;
        Yii::app()->end();
    }

}
