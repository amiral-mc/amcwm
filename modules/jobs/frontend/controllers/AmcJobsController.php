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
class AmcJobsController extends FrontendController {

    public function actionIndex() {
        $options = $this->module->appModule->options;
        if ($options['default']['integer']['allowJobs']) {
            $jobs = new JobsList();
            $this->render('jobs', array('jobs' => $jobs->getItems()));
        } else {
            $this->forward('request');
        }
    }

    public function actionView($id) {
        $jobDetails = JobsList::getJob($id);
        $this->render('viewJob', array('jobDetails' => $jobDetails));
    }

    public function actionApply() {
        if (Yii::app()->user->isGuest) {
            $this->forward('/site/login');
            exit;
        }
        $this->render('apply', array());
    }

    public function actionRequest() {
        $job_id = (int) AmcWm::app()->request->getParam('id');
        $options = $this->module->appModule->options;
        $jobModel = Jobs::model()->findByPk($job_id);        
        if ($jobModel->allow_request) {
            if ($options['default']['integer']['allowUsersApply']) {
                if (Yii::app()->user->isGuest) {
                    $this->forward('/site/login');
                } else {
                    $this->forward('apply');
                }
            } else {
                $model = new JobsRequests;
                $model->setAttribute('job_id', $job_id);
                $this->save($model);
                $this->render('jobRequest', array('model' => $model, 'jobModel'=>$jobModel));
            }
        }
        else{
            throw new CHttpException(404, AmcWm::t('amcCore', 'The requested page does not exist.'));
        }
    }

    protected function save(ActiveRecord $model) {
        $model->setScenario('request');

        if (isset($this->module->appModule->options['default']['text']['sendJobRequstRedirectUrl'])) {
            $redirect = array($this->module->appModule->options['default']['text']['sendJobRequstRedirectUrl'], '#' => "message");
        } else {
            $redirect = array("/jobs/default/view", 'id' => $model->job_id, '#' => "message");
        }

        if (isset($_POST['JobsRequests'])) {

            $model->content_lang = Controller::getCurrentLanguage();
            $model->attributes = $_POST['JobsRequests'];

            $model->attachedFile = CUploadedFile::getInstance($model, 'attachedFile');
            if ($model->attachedFile instanceof CUploadedFile) {
                $model->setAttribute('attach_ext', $model->attachedFile->getExtensionName());
            }

            if ($model->validate()) {
//                die(print_r($model->attributes));
                $model->save();
                $this->saveAttachment($model);

                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], 'New Job Request', '', $headers);
                if (AmcWm::app()->frontend['bootstrap']['use'] || AmcWm::app()->frontend['bootstrap']['customUse']) {
                    Yii::app()->user->setFlash('success', AmcWm::t('msgsbase.request', 'Thank you, your request has been sent, and we will respond if you has been accepted'));
                } else {
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t('msgsbase.request', 'Thank you, your request has been sent, and we will respond if you has been accepted')));
                }
                $this->redirect($redirect);
            }
        }
    }

    /**
     * Save Icon images
     * @param ActiveRecord $menuItem
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function saveAttachment(ActiveRecord $model) {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        if ($model->attachedFile instanceof CUploadedFile) {
            $attachedFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']) . "/" . $model->request_id . "." . $model->attach_ext;
//            die($attachedFile);
            if (!is_dir(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']))) {
                mkdir(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']), 0777, true);
            }

            $model->attachedFile->saveAs($attachedFile);
        }
    }

    protected function getJobsList() {
        $jobs = CHtml::listData(Yii::app()->db->createCommand(
                                sprintf("select t.job_id, t.job 
                    from jobs_translation t 
                    inner join jobs j on j.job_id=t.job_id 
                    where j.published = 1 and t.content_lang=%s order by job ", Yii::app()->db->quoteValue(Controller::getContentLanguage()))
                        )->queryAll(), 'job_id', "job");
        return $jobs;
    }

}
