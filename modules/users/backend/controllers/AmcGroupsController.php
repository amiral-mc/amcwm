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
class AmcGroupsController extends BackendController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function ajaxGetRolePermissions() {
        $roleId = Yii::app()->request->getParam('roleId');
        $model = new Roles();
        $model->role_id = $roleId;
        $this->widget('amcwm.core.widgets.ManageRolePermissions', array(
            'id' => 'manage-permissions-container',
            'model' => $model,
            'modules' => amcwm::app()->acl->getModules(),
        ));
    }

    /**
     * Save model
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function save(Roles $model) {
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Roles'])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model->attributes = $_POST['Roles'];
            $validate = $model->validate();
            $success = false;
            if ($validate) {
                try {
                    if ($model->save()) {
                        $saved = $model->setPermissions(Yii::app()->request->getParam('permissions'));
                        if ($saved) {
                            $transaction->commit();
                            $success = true;
                        }
                    }
                } catch (CDbException $e) {
                    $transaction->rollback();
                    $success = false;
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }

                if ($success) {
                    AmcWm::app()->clearGlobalState("acl");
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.roles", 'Role has been saved')));
                    $this->redirect(array('view', 'id' => $model->role_id));
                }
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Roles();
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
        if ($model && !$model->is_system) {
            $this->save($model);
            $this->render('update', array(
                'model' => $model,
            ));
        } else {
            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("msgsbase.roles", 'You can not change a system role')));
            $this->redirect(array('index'));
//            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids');
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();

            foreach ($ids as $id) {
                $model = $this->loadModel($id);
                $usersCount = count($model->users);
                $checkRelated = ($model->is_system && $usersCount != 0);
                if ($checkRelated) {
                    $messages['error'][] = AmcWm::t("msgsbase.roles", 'Can not delete role "{role}"', array("{group}" => $model->role));
                } else {
                    $deleted = $model->delete();
                    if ($deleted) {
                        $messages['success'][] = AmcWm::t("msgsbase.roles", 'Role "{role}" has been deleted', array("{group}" => $model->role));
                    }
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(array('index'));
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Roles'])) {
            $model->attributes = $_GET['Roles'];
        }

        if ($model) {
            $this->render('index', array(
                'model' => $model,
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
    public function loadModel($id) {
        $model = Roles::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
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
