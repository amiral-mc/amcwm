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
class AmcVotesController extends BackendController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'contentModel' => $this->loadChildModel($id),
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionPollResults($id) {
        $this->render('results', array(
            'contentModel' => $this->loadChildModel($id),
        ));
    }

    /**
     * translate a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionTranslate($id) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $model = $contentModel->getParentContent();
            $translatedModel = $this->loadTranslatedModel($model, $id);
            if (isset($_POST['VotesQuestionsTranslation'])) {
                $translatedModel->attributes = $_POST['VotesQuestionsTranslation'];
                $validate = $translatedModel->validate();
                $index = 0;
                foreach ($_POST['VotesOptions'] as $option) {
                    $voteOption = VotesOptions::model()->findByAttributes(array("option_id" => $option['option_id'], 'content_lang' => $translatedModel->content_lang));
                    if ($voteOption === null) {
                        $voteOption = new VotesOptions();
                        $voteOption->option_id = $option['option_id'];
                        $voteOption->content_lang = $translatedModel->content_lang;
                        $voteOption->ques_id = $translatedModel->ques_id;
                    }
                    $voteOption->attributes = $option;
                    $validate &= $voteOption->validate();
                    $translatedModel->addRelatedRecord("votesOptions", $voteOption, $index);
                    $index++;
                }
                $transaction = Yii::app()->db->beginTransaction();
                $success = false;
                if ($validate) {
                    try {
                        if ($translatedModel->save()) {
                            $success = true;
                            foreach ($translatedModel->votesOptions as $voteOption) {
                                $voteOption->setAttribute('ques_id', $model->ques_id);
                                $voteOption->setAttribute('content_lang', $translatedModel->content_lang);
                                $success &= $voteOption->save();
                            }
                            if ($success) {
                                $transaction->commit();
                            }
                        }
                    } catch (CDbException $e) {
                        $transaction->rollback();
                        $success = false;
                        Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                        //$this->refresh();
                    }
                    if ($success) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->ques_id));
                    }
                }
            } else {
                $index = count($translatedModel->votesOptions);
                foreach ($contentModel->votesOptions as $voteOption) {
                    $translatedVoteOption = VotesOptions::model()->findByAttributes(array("option_id" => $voteOption->option_id, 'content_lang' => $translatedModel->content_lang));
                    if ($translatedVoteOption === null) {
                        $translatedVoteOption = new VotesOptions();
                        $translatedVoteOption->content_lang = $translatedModel->content_lang;
                        $translatedVoteOption->option_id = $voteOption->option_id;
                        $translatedModel->addRelatedRecord('votesOptions', $translatedVoteOption, $index);
                        $index++;
                    }
                }
            }
            $this->render("translate", array(
                'contentModel' => $contentModel,
                'translatedModel' => $translatedModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Save model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function save(VotesQuestionsTranslation $contentModel) {
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['VotesQuestions']) && isset($_POST['VotesQuestionsTranslation'])) {
            $model = $contentModel->getParentContent();
            $model->attributes = $_POST['VotesQuestions'];
            $contentModel->attributes = $_POST['VotesQuestionsTranslation'];
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if (!isset($_POST['VotesOptions']) || count($_POST['VotesOptions']) < 2) {
                $contentModel->addError("ques", AmcWm::t("msgsbase.core", "Please add at least two answers to this vote"));
                $validate = false;
            } else if (count($_POST['VotesOptions']) >= 2) {
                $index = 0;
                foreach ($_POST['VotesOptions'] as $option) {
                    $voteOption = VotesOptions::model()->findByAttributes(array("option_id" => $option['option_id'], 'content_lang' => $contentModel->content_lang));
                    if ($voteOption === null) {
                        $voteOption = new VotesOptions();
                        $voteOption->content_lang = $contentModel->content_lang;
                        $voteOption->ques_id = $contentModel->ques_id;
                    }
                    $voteOption->attributes = $option;
                    $validate &= $voteOption->validate();
                    $contentModel->addRelatedRecord("votesOptions", $voteOption, $index++);
                }
            }
            $success = false;
            $transaction = Yii::app()->db->beginTransaction();
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $success = true;
                            foreach ($contentModel->votesOptions as $voteOption) {
                                $voteOption->setAttribute('ques_id', $contentModel->ques_id);
                                $voteOption->setAttribute('content_lang', $contentModel->content_lang);
                                $success &= $voteOption->save();
                            }
                            if ($success && !$contentModel->isNewRecord && isset($_POST['VotesOptionsRemoved'])) {
                                $removedAnswers = array();
                                foreach ($_POST['VotesOptionsRemoved'] as $removedId) {
                                    if (!$this->checkVoteOptionVoters($removedId)) {
                                        $removedAnswers[] = (int) $removedId;
                                    } else {
                                        $success = false;
                                    }
                                }
                                if (count($removedAnswers)) {
                                    Yii::app()->db->createCommand("delete from votes_options where option_id in(" . implode(",", $removedAnswers) . ")")->execute();
                                }
                            }
                            if ($success) {
                                $transaction->commit();
                            }
                        }
                    }
                } catch (CDbException $e) {
                    $transaction->rollback();
                    $success = false;
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    //$this->refresh();
                }
                if ($success) {
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Poll has been saved')));
                    $this->redirect(array('view', 'id' => $model->ques_id));
                } else {
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
            }
        } else if ($contentModel->isNewRecord) {
            $contentModel->addRelatedRecord("votesOptions", new VotesOptions(), 0);
            $contentModel->addRelatedRecord("votesOptions", new VotesOptions(), 1);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new VotesQuestions();
        $contentModel = new VotesQuestionsTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $this->save($contentModel);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $this->render('create', array(
            'contentModel' => $contentModel,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $this->save($contentModel);
            $this->render('update', array(
                'contentModel' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    public function getVoteAnswers(VotesQuestions $vote) {
        $answers = $vote->votesOptions;
        return $answers;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     */
    public function actionDelete() {
        if (Yii::app()->request->isPostRequest) {
            $ids = Yii::app()->request->getParam('ids');
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $alreadyVoted = $model->getVotersCount();
                if ($alreadyVoted) {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete poll "{poll}"', array("{poll}" => $contentModel->displayTitle));
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Cannot delete voted poll');
                } else {
                    $model->delete();
                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Poll "{poll}" has been deleted', array("{poll}" => $contentModel->displayTitle));
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
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
        $model = new VotesQuestions('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new VotesQuestionsTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if (isset($_GET['VotesQuestions']))
            $model->attributes = $_GET['VotesQuestions'];
        if ($contentModel) {
            if (isset($_GET['VotesQuestionsTranslation'])) {
                $contentModel->attributes = $_GET['VotesQuestionsTranslation'];
            }
            $this->render('index', array(
                'model' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadAnswers($id) {
        $answers = VotesOptions::model()->findAllByAttributes(array('ques_id' => $id));
        return $answers;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function checkVoteOptionVoters($id) {
        $count = (int) Yii::app()->db->createCommand("select count(*) from voters where option_id = " . (int) $id)->queryScalar();
        return $count;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = VotesQuestions::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = VotesQuestionsTranslation::model()->findByPk(array("ques_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Persons $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return PersonsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = VotesQuestionsTranslation::model()->findByPk(array("ques_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new VotesQuestionsTranslation();
                $translatedModel->ques_id = $model->ques_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'votes-questions-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
