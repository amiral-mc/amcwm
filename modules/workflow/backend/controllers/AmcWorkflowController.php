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
class AmcWorkflowController extends BackendController {

    /**
     * Current module model
     * @var module
     */
    public $module = null;

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'modulesContext';
        return $filters;
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper gallery context
     */
    public function filterModulesContext($filterChain) {
        $moduleId = Yii::app()->request->getParam('mid');
        $this->loadModuleData($moduleId);
        $filterChain->run();
    }

    /**
     * Returns the Module model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @access public
     * @return Module data     
     */
    public function loadModuleData($id) {
        $this->module = Modules::model()->findByPk($id);
        if ($this->module === null) {
            throw new CHttpException(404, 'The requested module does not exist.');
        }
        return $this->module;
    }

    /**
     * Returns the module model instance
     * @access public
     * @return Module details
     */
    public function getModuleData() {
        return $this->module;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function checkWorkflowTasks($id) {
        $count = (int) Yii::app()->db->createCommand("select count(*) from workflow_tasks where step_id = " . (int) $id)->queryScalar();
        return $count;
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
     * Save model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function save(Workflow $model) {
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if (isset($_POST['Workflow'])) {
            $model->attributes = $_POST['Workflow'];
            $validate = $model->validate();

            if (!isset($_POST['WorkflowSteps']) || count($_POST['WorkflowSteps']) < 2) {
                $model->addError("msgsbase.core", AmcWm::t("msgsbase.core", "Please add at least two steps to this workflow"));
                $validate = false;
            } else if (count($_POST['WorkflowSteps']) >= 2) {
                $index = 0;
                foreach ($_POST['WorkflowSteps'] as $step) {
                    $flowStep = WorkflowSteps::model()->findByAttributes(array("step_id" => $step['step_id']));
                    if ($flowStep === null) {
                        $flowStep = new WorkflowSteps();
                        $flowStep->flow_id = $model->flow_id;
                    }
                    $flowStep->attributes = $step;
                    $validate &= $flowStep->validate();
                    $model->addRelatedRecord("workflowSteps", $flowStep, $index++);
                }
            }
            $success = false;
            $transaction = Yii::app()->db->beginTransaction();
            if ($validate) {
                try {
                    if ($model->save()) {
                        $success = true;
                        $step_id = null;
                        $index = 0;
                        foreach ($model->workflowSteps as $flowStep) {
                            $flowStep->setAttribute('flow_id', $model->flow_id);
                            if ($step_id) {
                                $flowStep->setAttribute('parent_step', $step_id);
                            }
                            $success &= $flowStep->save();
                            if ($success) {
                                $step_id = $flowStep->step_id;
                                if (isset($_POST['WorkflowActions'])) {
                                    if (isset($_POST['WorkflowActions'][$index])) {
                                        $flowStep->deleteActions();
                                        $q = 'INSERT INTO workflow_actions (action_id, step_id) VALUES ';
                                        $sepa = '';
                                        foreach ($_POST['WorkflowActions'][$index] as $action) {
                                            $q .= $sepa . "({$action}, $step_id)";
                                            $sepa = ', ';
                                        }
                                        Yii::app()->db->createCommand($q)->execute();
                                    }
                                }
                            }
                            $index++;
                        }
                        if ($success && !$model->isNewRecord && isset($_POST['WorkflowStepsRemoved'])) {
                            $removedSteps = array();
                            foreach ($_POST['WorkflowStepsRemoved'] as $removedId) {
                                if (!$this->checkWorkflowTasks($removedId)) {
                                    $removedSteps[] = (int) $removedId;
                                } else {
                                    $success = false;
                                }
                            }
                            if (count($removedSteps)) {
                                Yii::app()->db->createCommand("delete from workflow_steps where step_id in(" . implode(",", $removedSteps) . ")")->execute();
                            }
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
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Workflow has been saved')));
                    $this->redirect(array('view', 'id' => $model->flow_id, 'mid' => $model->module_id));
                } else {
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
            }
        } else if ($model->isNewRecord) {
            $model->addRelatedRecord("workflowSteps", new WorkflowSteps(), 0);
            $model->addRelatedRecord("workflowSteps", new WorkflowSteps(), 1);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Workflow();
        $model->module_id = $this->module->module_id;
        $this->save($model);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
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
        $model->module_id = $this->module->module_id;
        if ($model) {
            $this->save($model);
            $this->render('update', array(
                'model' => $model,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
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
                $model = $this->loadModel($id);
                $hasSteps = count($model->getSteps());
                if ($hasSteps) {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete workflow "{workflow}"', array("{workflow}" => $model->flow_title));
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Cannot delete started workflow');
                } else {
                    $model->delete();
                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Workflow "{workflow}" has been deleted', array("{workflow}" => $model->flow_title));
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
        $model = new Workflow('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Workflow']))
            $model->attributes = $_GET['Workflow'];

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
        $model = Workflow::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested workflow does not exist.');
        return $model;
    }

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
//            $dataset = new ModulesListData();

            foreach ($ids as $id) {
//                $ok = $dataset->enable2Workflow($id, $published);
//                $details = $dataset->getModuleDetails($id);
                $itemName = ""; //$details['title'];
//                if ($ok) {
//                    $messages['success'][] = AmcWm::t("amcBack", $okMessage, array("{displayTitle}" => $itemName));
//                } else {
                $messages['error'][] = AmcWm::t("amcBack", 'Can not publish item "{displayTitle}"', array("{displayTitle}" => $itemName));
//                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
        }
        $this->redirect(array("/backend/workflow/steps/index", 'mid' => $this->module->module_id));
    }

    public function actionAssign() {
        $flowId = Yii::app()->request->getParam('id');
        $criteria = new CDbCriteria;
        $criteria->order = "step_sort asc";
        $steps = WorkflowSteps::model()->findAllByAttributes(array('flow_id' => $flowId), $criteria);
        if ($steps === null) {
            throw new CHttpException(404, AmcWm::t('msgsbase.core', 'No steps found'));
        }

        $dataset = array();
        foreach ($steps as $step) {
            $dataset[$step['step_id']] = new StepsAssign($step['step_id']);
            $dataset[$step['step_id']]->useRecordIdAsKey(true);
        }

        if (isset($_POST['Users'])) {
            AmcWm::app()->clearGlobalState("acl");
            $this->saveAssign($dataset, $flowId);
        }

        $this->render('assign', array(
            'steps' => $steps,
            'dataset' => $dataset,
        ));
    }

    public function saveAssign($dataset, $flowId) {
        if (Yii::app()->request->isPostRequest) {
            $roles = array();
            if (isset($_POST['Users'])) {
                foreach ($_POST['Users'] as $stepId => $rolesData) {
                    $items = $dataset[$stepId]->getItems();
                    foreach ($rolesData as $role => $users) {
                        $roles[$stepId][$role]['all'] = count($items[$role]['usersList']);
                        $roles[$stepId][$role]['current'] = count($users);
                        $roles[$stepId][$role]['users'] = $users;
                    }
                }
            }

            if (count($roles)) {
                foreach ($roles as $stepId => $usersData) {
                    WorkflowSteps::model()->deleteOldAssigns($stepId);
                    foreach ($usersData as $id => $data) {
                        if (($data['all'] == $data['current']) || ($data['current'] == 0 && count($data['users']) == 0)) {
                            WorkflowSteps::model()->saveRole($id, $stepId);
                        } else if ($data['current']) {
                            WorkflowSteps::model()->saveUsers($data['users'], $stepId);
                        }
                    }
                }
            }

            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Workflow Steps has been assigned')));
            $url = array('assign', 'mid' => $this->module->module_id, 'id' => $flowId);
            $this->redirect($url);
        }
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === Yii::app()->params["pageSize"]) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}