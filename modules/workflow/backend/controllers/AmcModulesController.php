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
class AmcModulesController extends BackendController {

    /**
     * Displays a particular model.
     * Current enabled Modules list
     * @param integer $id the ID of the model to be displayed
     */
    public function actionIndex() {
        $dataset = new ModulesListData();
        $dataset->addWhere('m.workflow_enabled = 1');
        $dataset->useRecordIdAsKey(false);
        $paging = new PagingDataset($dataset, 50, (int) Yii::app()->request->getParam('page', 1));
        $module = new PagingDatasetProvider($paging, array());

        $this->render('index', array(
            'module' => $module
        ));
    }

    /**
     * enable the given module
     * @param integer $id the ID of the model to be displayed
     */
    public function actionEnable() {
        $dataset = new ModulesListData();
        $dataset->useRecordIdAsKey(false);
        $paging = new PagingDataset($dataset, 50, (int) Yii::app()->request->getParam('page', 1));
        $module = new PagingDatasetProvider($paging, array());

        $this->render('enable', array(
            'module' => $module
        ));
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
            $dataset = new ModulesListData();
            foreach ($ids as $id) {
                $ok = $dataset->enable2Workflow($id, $published);
                $details = $dataset->getModuleDetails($id);
                $itemName = $details['title'];
                if ($ok) {
                    $messages['success'][] = AmcWm::t("amcBack", $okMessage, array("{displayTitle}" => $itemName));
                } else {
                    $messages['error'][] = AmcWm::t("amcBack", 'Can not publish item "{displayTitle}"', array("{displayTitle}" => $itemName));
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
        }
        $this->redirect(array("/backend/workflow/default/index"));
    }

    /**
     * Assign selected users or rules to selected module     
     */
    public function actionAssign() {
        
    }

    /**
     * work flow steps
     */
    public function actionSteps() {
        $this->forward('/backend/workflow/steps/index');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return Jobs
     */
    public function loadModel($id) {
        
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