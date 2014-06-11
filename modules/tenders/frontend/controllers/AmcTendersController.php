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

class AmcTendersController extends FrontendController {

    public function actionIndex() {
//        $keywords = Yii::app()->request->getParam('q');
//        $department = (int) Yii::app()->request->getParam('d');

        $cdataset = new TendersListData();
        $cdataset->useRecordIdAsKey(false);
        $cdataset->addWhere('t.tender_status < 3');
        $cpaging = new PagingDataset($cdataset, 10, (int) Yii::app()->request->getParam('page', 1));
        $currentTenders = new PagingDatasetProvider($cpaging, array());

        $pdataset = new TendersListData();
        $pdataset->useRecordIdAsKey(false);
        $pdataset->addWhere('t.tender_status >= 3');
        $ppaging = new PagingDataset($pdataset, 10, (int) Yii::app()->request->getParam('page', 1));
        $pastTenders = new PagingDatasetProvider($ppaging, array());        
        $this->render('tenders', array(
            'currentTenders' => $currentTenders,
            'pastTenders' => $pastTenders,
        ));
    }

    public function actionView() {
        $id = (int) Yii::app()->request->getParam('id');
        $tenderData = new TendersData($id);        
        $details = $tenderData->getItems();
        if(!$details['record']){
            throw new CHttpException(404, AmcWm::t('msgsbase.core', 'The requested tender does not exist.'));
        }
//        $details = $tenderData->getTender();

        $commentsModal = new Comments;
        $commentsModal->commentsOwners = new CommentsOwners;

        $pageSize = Yii::app()->params['pages']['comments'];
        $pageNo = (int) Yii::app()->request->getParam("page", 1);
        $allComments = $details['comments']['content'];
        $details['comments']['content'] = array_slice($allComments, ($pageNo - 1 ) * $pageSize, $pageSize);

        $this->render('view', array(
            'details' => $details,
            'commentsModal' => $commentsModal,
        ));
    }

    /**
     * Create a new comment
     * If update is successful, the browser will be redirected to the 'same' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionAddComment($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = new Comments;
            $model->commentsOwners = new CommentsOwners;
            $from = $this->module->appModule->options['default']['text']['email'];
            if ($this->saveComment($model)) {
                $ok = Yii::app()->db->createCommand(sprintf("insert into tenders_comments (comment_id, tender_id) values (%d, %d)", $model->comment_id, $id))->execute();
                $commentId = Yii::app()->db->lastInsertID;
                if ($ok) {
                    $queryArticle = sprintf("update tenders set comments = comments + 1 where tender_id = %d", $id);
                    Yii::app()->db->createCommand($queryArticle)->execute();
                }
                // Send comment notification by email
                $this->sendResult($model->commentsOwners->name, $model->commentsOwners->email, $id, $model->comment_id , $from);
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("comments", 'Comment has been added')));
            } else {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("comments", 'Comment cannot be added, please check the required values')));
            }
            
            $this->redirect(array('/tenders/default/view', 'id' => $id, 'lang' => Controller::getCurrentLanguage()));
        }else
            throw new CHttpException(400, 'Invalid request on create. Please do not repeat this request again.');
    }

    /**
     * Update comment
     * @param Comments $comment
     * @return boolean
     * @access protected
     */
    protected function saveComment(Comments $comment) {
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
     * Send tender comment notification by email
     */
    private function sendResult($name, $email, $id, $commentId, $from) {
        Yii::app()->mail->sender->Subject = AmcWm::t("app", "_TENDER_SUBJECT_");
        Yii::app()->mail->sender->AddAddress($from);
        Yii::app()->mail->sender->SetFrom($from);
        Yii::app()->mail->sender->AddReplyTo($email, $name);
        Yii::app()->mail->sender->IsHTML();
        Yii::app()->mail->sendView("application.views.email.tenders." . Controller::getCurrentLanguage() . ".tenders", array(
            'name' => $name,
            'link' => AmcWm::app()->request->getHostInfo() . Html::createUrl('/backend/tenders/questions/view', array('item' => $id, 'id' => $commentId))));
//        Yii::app()->mail->sender->Send();
//        Yii::app()->mail->sender->ClearAddresses();

    }

}
