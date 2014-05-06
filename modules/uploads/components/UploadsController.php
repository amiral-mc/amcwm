<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * RTE Manager
 * @package AmcWm.core.controllers
 * @author Amiral Management Corporation
 * @version 1.0
 */
class UploadsController extends Controller {

    public function init() {        
        parent::init();
        if (AmcWm::app()->user->isGuest) {
            $this->redirect(AmcWm::app()->homeUrl);
        }
        $this->layout = "amcwm.core.system.views.layouts.main";
    }

    /**
     * Default action
     */
    public function actionIndex($op, $dialog = null) {
        $this->renderFileManager($op, $dialog);
    }

    /**
     * Render file manager
     * @param boolean $isBackend
     */
    protected function renderFileManager($op, $dialog, $isBackend = true) {
        $url = CHtml::asset(Yii::getPathOfAlias('system.web.widgets.pagers.pager') . '.css');
        Yii::app()->getClientScript()->registerCssFile($url);
        if ($op == "rte") {
            $baseUrl = Yii::app()->getAssetManager()->getPublishedUrl(Yii::getPathOfAlias('amcwm.core.widgets.tinymce.assets'), true);
            $cs = AmcWm::app()->getClientScript();
            $cs->registerCoreScript('jquery');
//            $cs->registerCssFile("{$baseUrl}/tiny_mce/themes/advanced/skins/default/dialog.css");
            $cs->registerScriptFile("{$baseUrl}/tiny_mce/tiny_mce_popup.js");
        }
        $component = AmcWm::app()->request->getParam("component", "uploadsFiles");
        $this->render("amcwm.core.system.views.fileManager.index", array('attachmentInfo' => AttachmentInfo::getInstance($isBackend)->getInfo(), "openerType" => $op, 'dialog' => $dialog, 'defaultComponent' => $component));
    }

    /**
     * Draw uploads list
     */
    public function drawList($msg = null, $goToFirst = false) {
        $route = str_replace("ajax", "manageFiles", $route = $this->getRoute());
        $access = AmcWm::app()->user->checkRouteAccess($route);
        if ($access) {
            $assets = AmcWm::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'assets');
            //$this->renderPartial("amcwm.modules.uploads.views.list", array("msg" => $msg, "iconsPath" => $assets . "/images"));            
            $list = new UploadsList();
            $page = AmcWm::app()->request->getParam("page");
            if ($goToFirst) {
                $page = null;
            }
            $pagingDataset = new PagingDataset($list, 10, $page);
            $assets = AmcWm::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'assets');
            $this->renderPartial("amcwm.modules.uploads.views.list", array("msg" => $msg, "iconsPath" => $assets . "/images", 'list' => $pagingDataset->getData()));
        }
    }

    /**
     * delete files
     */
    public function delete() {
        $route = str_replace("ajax", "manageFiles", $route = $this->getRoute());
        $access = AmcWm::app()->user->checkRouteAccess($route);
        $deleted = false;
        $files = AmcWm::app()->request->getParam("file_select");

        if ($files && $access) {
            $list = new UploadsList(0, 0);
            $fileIds = array();
            foreach ($files as $fileId) {
                $fileIds[] = (int) $fileId;
            }
            $filesIn = "file_id in(" . implode(",", $fileIds) . ")";
            $list->addWhere($filesIn);
            $list->generate();
            $records = $list->getItems();
            if (count($records)) {
                foreach ($records as $row) {
                    if (file_exists(AmcWm::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . $row['url'])) {
                        $deleted = true;
                        unlink(AmcWm::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . $row['url']);
                    }
                }
                if (count($records)) {
                    $deleted = AmcWm::app()->db->createCommand("delete from files where $filesIn")->execute();
                }
            }
        }
        return $deleted;
    }

    /**
     * Manage folders action
     */
    public function actionManageFolders() {
        
    }

    /**
     * Manage files action
     */
    public function actionManageFiles() {
        $action = AmcWm::app()->request->getParam("action", "list");
        switch ($action) {
            case "upload":
                $this->uploadForm();
                break;
        }
    }

    /**
     * upload file
     */
    public function uploadForm() {
        if (!AmcWm::app()->user->isGuest) {
            $model = new UploadForm;
            $uploadedFileInfo = array();
            if (Yii::app()->request->isPostRequest) {
                $model->file = CUploadedFile::getInstance($model, 'file');
                $validate = $model->validate();
                if ($validate) {
                    $ok = $model->saveFile();
                    if ($ok) {
                        $uploadedFileInfo = $model->getuploadedFileInfo();
                        AmcWm::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", '_file_has_been_uploaded_')));
                    }
                }
            }
            $assets = AmcWm::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'assets');
            $this->render("amcwm.modules.uploads.views.form", array("iconsPath" => $assets . "/images", "model" => $model, "uploadedFileInfo" => $uploadedFileInfo));
        }
    }

    /**
     * upload file
     */
    public function upload() {
        if (!AmcWm::app()->user->isGuest) {
            $route = str_replace("ajax", "manageFiles", $route = $this->getRoute());
            $access = AmcWm::app()->user->checkRouteAccess($route);
            if ($access) {
                $model = new UploadForm;
                $assets = AmcWm::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'assets');
                $this->renderPartial("amcwm.modules.uploads.views.upload", array("iconsPath" => $assets . "/images", "model" => $model));
            }
        }
    }

    /**
     * Ajax method 
     */
    public function ajaxAttachment() {
        $action = AmcWm::app()->request->getParam("action", "list");
        $msg = null;
        switch ($action) {
            case "upload":
                $this->upload();
                break;
            case "list":
                $this->drawList();
                break;
            case "delete":
                $ok = $this->delete();
                if ($ok) {
                    $msg = '<div class="flash-success" style="width:50%"><p>' . AmcWm::t("msgsbase.core", "_files_has_been_deleted_") . '</p></div>';
                } else {
                    $msg = '<div class="errorSummary" style="width:50%"><p>' . AmcWm::t("msgsbase.core", "_cannot_delete_files_") . '</p></div>';
                }
                $this->drawList($msg, true);
                break;
        }
    }

}