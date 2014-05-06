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

class FrontRepliesArticlesCommentsController extends FrontRepliesCommentsController {

    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionCreate($id) {
        $cache = Yii::app()->getComponent('cache');
        if($cache !== null){
            $cache->delete('article_' . $id);
        }        
                
        if (Yii::app()->request->isPostRequest) {
                $model = new RepliesComments;
                $model->commentsOwners = new RepliesCommentsOwners;
                if ($this->createComment($model)) {
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => Yii::t("comments", 'Replay has been added')));
                    $params = array('default/view', 'id' => $id);
                    if(Yii::app()->request->getParam("page")){
                        $params['page'] = $params;
                    }
                    $params['lang'] =  Controller::getCurrentLanguage();
                    $params['#'] = "comments";
                }else{
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => Yii::t("comments", 'Replay cannot be added, please check the required values')));                    
                }
                $this->redirect($params);

            }else
                throw new CHttpException(400, 'Invalid request on create. Please do not repeat this request again.');
    }

    /**
     * 
     */
    public function actionIndex() {
        
    }
    
    public function actionReplies($id){
        $this->forward('replies/');
    }
    
}
