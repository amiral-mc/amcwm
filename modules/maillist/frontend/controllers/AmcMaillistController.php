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
class AmcMaillistController extends FrontendController {

    protected $msgCategory = 'amcFront';

    /**
     * Controller constrctors
     * @param string $id id of this controller
     * @param CWebModule $module the module that this controller belongs to. This parameter
     * @access public
     */
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
    }

    public function actionIndex() {
        $this->forward("subscribe");
    }

    /**
     * @todo correct the maillist log
     * @param type $id
     * @param type $email
     * @param type $channel 
     */
    public function actionArticle($id) {
        $emailId = (int) AmcWm::app()->request->getParam('e');
        $msgId = (int) AmcWm::app()->request->getParam('m');
        $articleId = (int) $id;
        $ip = AmcWm::app()->request->getUserHostAddress();
        $date = date("Y-m-d H:i:s");
        if ($articleId && $emailId && $msgId) {
            $query = sprintf("select l.article_id 
                from maillist_articles_log l
                inner join maillist_message m on l.message_id = m.id
                where l.subscriber_id = %d
                    and l.message_id = %d
                    and l.article_id = %d", $emailId, $msgId, $articleId);
            $found = Yii::app()->db->createCommand($query)->queryScalar();
            if (!$found) {
                $query = sprintf("insert into maillist_articles_log(article_id, message_id, subscriber_id, ip, log_date) values(%d, %d, %d , %s, %s)"
                        , $articleId
                        , $msgId
                        , $emailId
                        , Yii::app()->db->quoteValue($ip)
                        , Yii::app()->db->quoteValue($date)
                );
                Yii::app()->db->createCommand($query)->execute();
            }
            $this->redirect(array("/articles/default/view", 'id' => $articleId));
        }
    }

    public function actionView($list, $email, $channel) {
        $filename = Yii::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "multimedia" . DIRECTORY_SEPARATOR . "newsletter" . DIRECTORY_SEPARATOR . "{$list}.html";
        $body = null;
        if (file_exists($filename)) {
            $body = file_get_contents($filename);
            $body = str_replace(array("__log__", "__user__", "__channel__", "__link__"), array("", $email, $channel, ""), $body);
        }
        echo $body;
        Yii::app()->end();
    }

    public function actionLog() {
        $emailId = (int) AmcWm::app()->request->getParam('e');
        $msgId = (int) AmcWm::app()->request->getParam('m');
        $ip = Yii::app()->request->getUserHostAddress();
        $date = date("Y-m-d H:i:s");
        $logDate = date("Y-m-d");
        if ($emailId && $msgId) {
            $query = sprintf("select subscriber_id 
                from maillist_log 
                where subscriber_id = %d 
                    and message_id = %d 
                    and date(log_date)= %s", $emailId, $msgId, Yii::app()->db->quoteValue($logDate));
            $found = Yii::app()->db->createCommand($query)->queryScalar();
            if (!$found) {
                $query = sprintf("insert into maillist_log(message_id, subscriber_id, ip, log_date) values(%d, %d , %s, %s)"
                        , $msgId
                        , $emailId
                        , Yii::app()->db->quoteValue($ip)
                        , Yii::app()->db->quoteValue($date)
                );
                Yii::app()->db->createCommand($query)->execute();
            }
        }
        $header = "Content-type: image/gif";
        header("Content-Type:{$header}");
        echo file_get_contents(AmcWm::getPathOfAlias("amcwm.modules.maillist.source") . DIRECTORY_SEPARATOR . "1x1.gif");
        Yii::app()->end();
    }

    public function actionSubscribe() {
        $model = new Maillist();
        $model->maillistUsers = new MaillistUsers;

        if (isset($this->module->appModule->options['default']['text']['subscriptoinRedirectUrl'])) {
            $redirect = $this->module->appModule->options['default']['text']['subscriptoinRedirectUrl'];
        } else {
            $redirect = "subscribe";
        }
        $channels = null;
        $showChannels = $this->module->appModule->options['default']['check']['showChannels'];
        if ($showChannels) {
            $channels = MaillistChannels::model()->findAllByAttributes(array('content_lang' => Controller::getCurrentLanguage()));
        }

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getParam('Maillist');
            $model->maillistUsers->attributes = Yii::app()->request->getParam('MaillistUsers');
            $transaction = Yii::app()->db->beginTransaction();
            if ($model->validate() && $model->maillistUsers->validate()) {
                try {
                    $success = $model->save();
                    if ($success) {
                        $model->maillistUsers->setAttribute('user_id', $model->id);
                        $model->maillistUsers->save();
                        $saveAllChannels = $this->module->appModule->options['default']['check']['saveAllChannels'];
                        if ($saveAllChannels) {
                            $success &= $model->saveAllChannels();
                        } else {
                            $success &= $model->saveSelectedChannels();
                        }
                        if ($success) {
                            $model->sendActivationLink();
                            $transaction->commit();
                            if (AmcWm::app()->frontend['bootstrap']['use']) {
                                Yii::app()->user->setFlash('success', AmcWm::t($this->msgCategory, 'Thank you for subscribing with us, please check your email for the activation URL'));
                            } else {
                                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t($this->msgCategory, 'Thank you for subscribing with us, please check your email for the activation URL')));
                            }
                            $this->redirect(array($redirect, '#' => "message", 'msg' => AmcWm::t($this->msgCategory, 'Thank you for subscribing with us, please check your email for the activation URL')));
                        } else {
                            if (AmcWm::app()->frontend['bootstrap']['use']) {
                                Yii::app()->user->setFlash('errors', AmcWm::t("msgsbase.core", 'Cant initialize the email service, email didnot sent'));
                            } else {
                                Yii::app()->user->setFlash('errors', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.core", 'Cant initialize the email service, email didnot sent')));
                            }
                        }
                    }
                } catch (CException $e) {
                    if (AmcWm::app()->frontend['bootstrap']['use']) {
                        Yii::app()->user->setFlash('errors', AmcWm::t("msgsbase.core", 'Cant initialize the email service, email didnot sent'));
                    } else {
                        Yii::app()->user->setFlash('errors', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.core", 'Cant initialize the email service, email didnot sent')));
                    }
                    $transaction->rollback();
                }
            } else {
                $errors = null;
//                foreach ($model->getErrors() as $error) {
//                    $errors.=implode("<br />", $error);
//                }
//                Yii::app()->user->setFlash('errors', array('class' => 'flash-error', 'content' => $errors));
                //$this->redirect(array($redirect, '#' => "message", 'msg'=>$errors));
            }
        }
        $this->render('subscribe', array(
            "model" => $model,
            "channels" => $channels,
        ));
    }

    public function actionActivate() {
        $email2chk = Yii::app()->request->getParam('m');
        $aKey = Yii::app()->request->getParam('k');
        $ok = false;
        $cmdQuery = sprintf("select mu.user_id, mu.email 
            from maillist_users mu
            inner join maillist m on m.id = mu.user_id
            where md5(concat(email, '|', user_id , '|', ip)) = %s 
            and m.status = 0", Yii::app()->db->quoteValue($aKey));
        $emailData = Yii::app()->db->createCommand($cmdQuery)->queryRow();
        if ($emailData && $emailData['email'] == $email2chk) {
            $query = sprintf("update maillist set status = 1 where id=%d", $emailData['user_id']);
            Yii::app()->db->createCommand($query)->execute();
            if (AmcWm::app()->frontend['bootstrap']['use']) {
                Yii::app()->user->setFlash('success', AmcWm::t($this->msgCategory, 'This email has been activated, Thank you for subscribing'));
            } else {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t($this->msgCategory, 'This email has been activated, Thank you for subscribing')));
            }
            $this->redirect(Yii::app()->homeUrl);
        } else {
            $this->redirect(Yii::app()->homeUrl);
        }
    }

    /**
     * @todo add option to unsubscribe from specified channel
     */
    public function actionUnsubscribe() {
        $model = new Maillist;
        $model->maillistUsers = new MaillistUsers();

        $model->setScenario('unsubscribe');
        $model->maillistUsers->setScenario('unsubscribe');
        $showChannels = $this->module->appModule->options['default']['check']['showChannels'];
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getParam('Maillist');
            $model->maillistUsers->attributes = Yii::app()->request->getParam('MaillistUsers');
            if ($model->maillistUsers->validate()) {
                $findModel = MaillistUsers::model()->findByAttributes(array("email" => $model->maillistUsers->email));
                if ($findModel === NULL) {
                    if (AmcWm::app()->frontend['bootstrap']['use']) {
                        Yii::app()->user->setFlash('errors', AmcWm::t($this->msgCategory, 'This email does not exist, Please check your email'));
                    } else {
                        Yii::app()->user->setFlash('errors', array('class' => 'flash-error', 'content' => AmcWm::t($this->msgCategory, 'This email does not exist, Please check your email')));
                    }
                } else {
                    $queryUsersData = sprintf("delete from maillist_users where user_id=%d;", $findModel->user_id);
                    $queryUsers = sprintf("delete from maillist where id=%d;", $findModel->user_id);
                    $queryUsersChannels = sprintf("delete from maillist_channels_subscribe where subscriber_id=%d;", $findModel->user_id);
                    $queryLogs = sprintf("delete from maillist_log where subscriber_id=%d;", $findModel->user_id);
                    $queryArticlesLog = sprintf("delete from maillist_articles_log where subscriber_id=%d;", $findModel->user_id);
                    Yii::app()->db->createCommand($queryUsersData)->execute();
                    Yii::app()->db->createCommand($queryUsers)->execute();
                    Yii::app()->db->createCommand($queryUsersChannels)->execute();
                    Yii::app()->db->createCommand($queryLogs)->execute();
                    Yii::app()->db->createCommand($queryArticlesLog)->execute();
                    if (AmcWm::app()->frontend['bootstrap']['use']) {
                        Yii::app()->user->setFlash('success', AmcWm::t($this->msgCategory, 'You have successfully unsubscribed our newsletter'));
                    } else {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t($this->msgCategory, 'You have successfully unsubscribed our newsletter')));
                    }
                    $this->redirect(Yii::app()->homeUrl);
                }
            }
        }
        $this->render('unsubscribe', array("model" => $model));
    }

}
