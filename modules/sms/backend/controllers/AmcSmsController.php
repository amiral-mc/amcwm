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

class AmcSmsController extends BackendController {   

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
     * Save uploaded video file to path
     * @param SmsVideos $video
     * @param string $oldExt 
     */
    protected function saveVideo(SmsVideos $video, $oldExt) {                
        if ($video->videoFile instanceof CUploadedFile) {
            $videoPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params["multimedia"]['smsVideos']['path']) . "/";
            $videoFile = $videoPath . $video->getVideoName();
            
            if ($oldExt != $video->ext && $oldExt) {
                unlink($videoPath . $video->getVideoName($oldExt));
            }
            $video->videoFile->saveAs($videoFile);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
//        set_time_limit(0);
        $model = new SmsVideos;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['SmsVideos'])) {
                $oldExt = $model->ext;
                $model->attributes = $_POST['SmsVideos'];
                $model->videoFile = CUploadedFile::getInstance($model, 'videoFile');
                if ($model->validate()) {
                    if ($model->videoFile instanceof CUploadedFile) {
                        $model->setAttribute('ext', $model->videoFile->getExtensionName());
                    }
                    if ($model->save()) {
                        $this->saveVideo($model, $oldExt);
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Video has been saved')));
                        $this->redirect(array('view', 'id' => $model->video_id));
                    }
                }
            }
        }

        $this->render('create', array(
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['SmsVideos'])) {
                $oldExt = $model->ext;
                $model->attributes = $_POST['SmsVideos'];
                $model->videoFile = CUploadedFile::getInstance($model, 'videoFile');
                if ($model->validate()) {
                    if ($model->videoFile instanceof CUploadedFile) {
                        $model->setAttribute('ext', $model->videoFile->getExtensionName());
                    }
                    if ($model->save()) {
                        $this->saveVideo($model, $oldExt);
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Video has been saved')));
                        $this->redirect(array('view', 'id' => $model->video_id));
                    }
                }
            }
        }


        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.     
     */
    public function actionDelete() {
        if (Yii::app()->request->isPostRequest) {
            $ids = Yii::app()->request->getParam('ids');            
            foreach ($ids as $id) {
                $model = $this->loadModel($id);
                $messages['success'][] = Yii::t("sms", 'Video "{video}" has been deleted', array("{video}" => $model->video_header));
                $videoPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params["multimedia"]['smsVideos']['path']) . "/";
                $videoFile = $videoPath . $model->getVideoName();
                if ($model->delete()) {
                    if (is_file($videoFile)) {
                        unlink($videoFile);
                    }
                }
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new SmsVideos('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SmsVideos']))
            $model->attributes = $_GET['SmsVideos'];
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = SmsVideos::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'videos-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        if (Yii::app()->request->isPostRequest) {
            if ($published) {
                $okMessage = 'item "{displayTitle}" has been published';
            } else {
                $okMessage = 'item "{displayTitle}" has been unpublished';
            }
            $ids = Yii::app()->request->getParam('ids');
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $model = $this->loadModel($id);
                if ($model->publish($published)) {
                    $messages['success'][] = AmcWm::t("amcBack", $okMessage, array("{displayTitle}" => $model->displayTitle));
                } else {
                    $messages['error'][] = AmcWm::t("amcBack", 'Can not publish item "{displayTitle}"', array("{displayTitle}" => $model->displayTitle));
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
        }
        $this->redirect(array('index', 'gid' => $this->gallery->gallery_id));
    }

    /**
     * Performs the sort action
     * @param  int $id the ID of the model to be sorted
     * @access public 
     * @return void
     */
    public function actionSort($id, $direction) {
        if ($this->sortOrder == 'desc') {
            if ($direction == 'down') {
                $direction = 'up';
            } else if ($direction == 'up') {
                $direction = 'down';
            }
        }
        $model = $this->loadModel($id);
        $model->sortVideo($direction);
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => Yii::t("sms", 'Item "{item}" has been sorted', array("{item}" => $model->video_header))));
        $this->redirect(array('videos/index/gid/' . $model->gallery_id));
    }

    /**
     * Performs the comments action
     * @param int $imId
     * @access public 
     * @return void
     */
    public function actionComments($mmId) {
        $this->forward('videosComments/');
    }

    public function actionViewDopeSheet($mmId) {
        $model = $this->loadModel($mmId);
        $this->render('viewDopeSheet', array(
            'model' => $model,
        ));
    }

    public function actionDopeSheet($mmId) {
        $model = $this->loadModel($mmId);
        //$itemsAnswers = array();
        $shotsModels = array();
        $dopeSheetsRemoved = array();
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['DopeSheet'])) {
                $model->getDopeSheet()->attributes = $_POST['DopeSheet'];
                $valid = $model->getDopeSheet()->validate();
                if (isset($_POST['DopeSheetShots'])) {
                    foreach ($_POST['DopeSheetShots'] as $shot) {
                        $shotModel = DopeSheetShots::model()->findByPk($shot['id']);
                        if ($shotModel === NULL) {
                            $shotModel = new DopeSheetShots();
                        }
                        $shotModel->attributes = $shot;
                        $valid = $shotModel->validate(array('description', 'sound', 'length_minutes', 'length_seconds', 'type_id')) && $valid;
                        $shotsModels[] = $shotModel;
                    }
                }
                if (!count($shotsModels)) {
                    $valid = false;
                    $model->getDopeSheet()->addError("video_id", AmcWm::t("msgsbase.core", "Error, please enter dope sheet shots"));
                }
                if (isset($_POST['DopeSheetShotsRemoved'])) {
                    $dopeSheetsRemoved = $_POST['DopeSheetShotsRemoved'];
                }
                if ($valid) {
                    if ($model->getDopeSheet()->save()) {
                        foreach ($shotsModels as $i => $item) {
                            $item->setAttribute('video_id', $model->video_id);
                            $item->save();
                        }
                        if (count($dopeSheetsRemoved)) {
                            foreach ($dopeSheetsRemoved as $removedId) {
                                $deletedShotModel = DopeSheetShots::model()->findByPk($removedId);
                                if ($deletedShotModel !== NULL) {
                                    $deletedShotModel->delete();
                                }
                            }
                        }
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => Yii::t("sms", 'Dope Sheet has been saved')));
                        $this->redirect(array('viewDopeSheet', 'mmId' => $model->video_id, 'gid' => $model->gallery_id));
                    }
                } else {
                    $model->getDopeSheet()->dopeSheetShots = $shotsModels;
                }
            }
        } else if (!count($model->getDopeSheet()->dopeSheetShots) && $model->getDopeSheet()->isNewRecord) {
            $shotModel = new DopeSheetShots();
            $shotModel->video_id = $mmId;
            $shotsModels[] = $shotModel;
            $shotModel = new DopeSheetShots();
            $shotModel->video_id = $mmId;
            $shotsModels[] = $shotModel;
            $model->getDopeSheet()->dopeSheetShots = $shotsModels;
        }
        $this->render('dopeSheet', array(
            'model' => $model,
            'dopeSheetsRemoved' => $dopeSheetsRemoved,
        ));
    }

}
