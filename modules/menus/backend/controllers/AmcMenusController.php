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
class AmcMenusController extends BackendController {

    private $menuData = null;
    private $menuItem = null;

    /**
     * setting generated from settings.php inside application module folder
     * @var array
     */
    private $_settings = array();

    public function init() {
        if (isset($this->getModule()->appModule)) {
            $this->_settings = $this->getModule()->appModule->settings;
        }
        parent::init();
    }

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
     * Save model to database
     * @param MenuItems $model
     * @access protected
     */
    protected function save(MenuItemTranslation $contentModel) {
        if (isset($_POST['MenuItems']) && isset($_POST["MenuItemTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $oldIcon = $model->icon;
            $model->attributes = $_POST['MenuItems'];
            $contentModel->attributes = $_POST['MenuItemTranslation'];

            $model->setAttribute('menu_id', $this->getMenuId());
            $model->setAttribute('paramsMenuItemsParams', Yii::app()->request->getParam('MenuItemsParams'));

            /**
             * Menu icon file
             */
            $deleteImage = Yii::app()->request->getParam('deleteImage');
            $model->iconImage = CUploadedFile::getInstance($model, 'iconImage');
            if ($model->iconImage instanceof CUploadedFile) {
                $model->setAttribute('icon', $model->iconImage->getExtensionName());
            } else if ($deleteImage) {
                $model->setAttribute('icon', null);
            }

            /**
             * Menu page image
             */
            if (isset($this->_settings['options']['default']['check']['allowPageImage']) && $this->_settings['options']['default']['check']['allowPageImage']) {
                $oldPageImage = $model->page_img;
                $deletePageImage = Yii::app()->request->getParam('deletePageImage');
                $model->pageImg = CUploadedFile::getInstance($model, 'pageImg');
                if ($model->pageImg instanceof CUploadedFile) {
                    $model->setAttribute('page_img', $model->pageImg->getExtensionName());
                } else if ($deletePageImage) {
                    $model->setAttribute('page_img', null);
                }
            }


            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if ($validate) {
                $success = false;
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $saved = $model->setItemParams();
                            if ($saved) {
                                $transaction->commit();
                                $success = true;
                            }
                        }
                    }
                } catch (CDbException $e) {
                    $transaction->rollback();
                    $success = false;
                }

                if ($success) {
                    $this->saveIcon($model, $oldIcon);

                    if (isset($this->_settings['options']['default']['check']['allowPageImage']) && $this->_settings['options']['default']['check']['allowPageImage']) {
                        $this->savePageImage($model, $oldPageImage);
                    }

                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Menu Item has been saved');
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
                    $this->redirect(array('items', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()));
                } else {
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
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
    protected function saveIcon(ActiveRecord $menuItem, $oldThumb) {
        $deleteImage = Yii::app()->request->getParam('deleteImage');
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $oldThumbFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']) . "/" . $menuItem->item_id . "." . $oldThumb;
        if ($menuItem->iconImage instanceof CUploadedFile) {
            $iconFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']) . "/" . $menuItem->item_id . "." . $menuItem->icon;

            if (!is_dir(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']))) {
                mkdir(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']), 0777, true);
            }

            if ($oldThumb != $menuItem->icon && $oldThumb && is_file($oldThumbFile)) {
                unlink($oldThumbFile);
            }
            $menuItem->iconImage->saveAs($iconFile);
        } else if ($deleteImage && $oldThumb) {
            if (is_file($oldThumbFile)) {
                unlink($oldThumbFile);
            }
        }
    }

    /**
     * Save page images
     * @param ActiveRecord $article
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function savePageImage(ActiveRecord $model, $oldPageImage) {
        $deleteImage = Yii::app()->request->getParam('deletePageImage');
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $dir = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['pageImage']['path']);
        if ($model->pageImg instanceof CUploadedFile) {
            $pageImage = $dir . DIRECTORY_SEPARATOR . $model->item_id . "." . $model->page_img;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if ($oldPageImage && $model->page_img != $oldPageImage && is_file($dir . "/" . $model->item_id . "." . $oldPageImage)) {
                @unlink($dir . "/" . $model->item_id . "." . $oldPageImage);
            }
            $model->pageImg->saveAs($pageImage);
        } else if ($deleteImage && $oldPageImage) {
            if (is_file($dir . "/" . $model->item_id . "." . $oldPageImage)) {
                @unlink($dir . "/" . $model->item_id . "." . $oldPageImage);
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new MenuItems;
        $model->menuItemsParams = new MenuItemsParams();
        $model->menu_id = $this->getMenuId();
        $contentModel = new MenuItemTranslation();
        if ($this->getParentId()) {
            $model->parent_item = $this->getParentId();
        }

        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $this->save($contentModel);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        $this->render('create', array(
            'contentModel' => $contentModel,
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
            $translatedModel = $this->loadTranslatedModel($contentModel->getParentContent(), $id);
            if (isset($_POST["MenuItemTranslation"])) {
                $translatedModel->attributes = $_POST['MenuItemTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->item_id, 'mid' => $contentModel->getParentContent()->menu_id));
                    }
                }
            }
            $this->render('translate', array(
                'contentModel' => $contentModel,
                'translatedModel' => $translatedModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
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

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        parent::publish($published, "items", array('pid' => $this->getParentId(), 'mid' => $this->getMenuId()));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids', array());
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;

        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $checkRelated = false; //count($model->menuItems);
                if ($checkRelated) {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete menu item "{label}"', array("{label}" => $contentModel->label));
                } else {
                    if ($model->delete()) {
                        $model->deleteItemParams($model->item_id);
                        $iconFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path']) . "/" . $model->item_id . "." . $model->icon;
                        if (is_file($iconFile)) {
                            unlink($iconFile);
                        }

                        $messages['success'][] = AmcWm::t("msgsbase.core", 'Menu Item "{label}" has been deleted', array("{label}" => $contentModel->label));
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
                $this->redirect(array('items', 'mid' => $this->getMenuId()));
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionItems() {
        $model = new MenuItems('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new MenuItemTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());

        if ($this->getParentId()) {
            $_GET['MenuItems']['parent_item'] = $this->getParentId();
        }

        if ($this->getMenuId()) {
            $_GET['MenuItems']['menu_id'] = $this->getMenuId();
        }

        if (isset($_GET['MenuItems'])) {
            $model->attributes = $_GET['MenuItems'];
        }
        if ($contentModel) {
            if (isset($_GET['MenuItemTranslation'])) {
                $contentModel->attributes = $_GET['MenuItemTranslation'];
            }

            $this->render('items', array(
                'model' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    public function actionIndex() {
        $model = new MenusModel('search');
        $model->unsetAttributes();  // clear any default values
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return MenuItems
     */
    public function loadModel($id) {
        $model = MenuItems::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param MenuItems $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return MenuItemTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = MenuItemTranslation::model()->findByPk(array("item_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new MenuItemTranslation();
                $translatedModel->item_id = $model->item_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return MenuItemTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = MenuItemTranslation::model()->findByPk(array("item_id" => $pk['id'], 'content_lang' => $pk['lang']));
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

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'parentContext';
        $filters[] = 'menuContext';
        return $filters;
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper gallery context
     */
    public function filterMenuContext($filterChain) {
        //set the project identifier based on either the GET or POST input request variables, since we allow both types for our actions   
        $menuId = Yii::app()->request->getParam('mid');
        if ($menuId)
            $this->menuData = MenusModel::model()->findByPk($menuId);
        //complete the running of other filters and execute the requested action
        $filterChain->run();
    }

    /**
     *
     * Get menu items parent
     * @return ActiveModel 
     */
    public function getMenuData() {
        return $this->menuData;
    }

    /**
     * Get parent id menu item
     * @return int
     */
    public function getMenuId() {
        static $menusId = null;
        if ($menusId === null) {
            if ($this->getMenuData() !== NULL) {
                $menusId = $this->getMenuData()->menu_id;
            } else {
                $menusId = 0;
            }
        }
        return $menusId;
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper gallery context
     */
    public function filterParentContext($filterChain) {
        //set the project identifier based on either the GET or POST input request variables, since we allow both types for our actions   
        $itemId = Yii::app()->request->getParam('pid');
        if ($itemId)
            $this->menuItem = MenuItems::model()->findByPk($itemId);
        //complete the running of other filters and execute the requested action
        $filterChain->run();
    }

    /**
     *
     * Get menu items parent
     * @return ActiveModel 
     */
    public function getParentItem() {
        return $this->menuItem;
    }

    /**
     * Get parent id menu item
     * @return int
     */
    public function getParentId() {
        static $parentId = null;
        if ($parentId === null) {
            if ($this->getParentItem() !== NULL) {
                $parentId = $this->getParentItem()->item_id;
            } else {
                $parentId = 0;
            }
        }
        return $parentId;
    }

    /**
     * Performs the sort action
     * @param  int $id the ID of the model to be sorted
     * @access public 
     * @return void
     */
    public function actionSort($id, $direction) {
        $model = $this->loadModel($id);
        $model->sort($direction);
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Menu Item "{label}" has been sorted', array("{label}" => $model->getCurrent()->label))));
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('items', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()));
    }

    public function ajaxComponentParam() {
        $this->layout = "amcwm.core.system.views.layouts.main";

        $searchFor = Yii::app()->request->getParam('q');
        $slctd = (int) Yii::app()->request->getParam('slctd', 0);
        $dialog = Yii::app()->request->getParam('dialog');
        $paramId = (int) Yii::app()->request->getParam('pid');
        $componentId = (int) Yii::app()->request->getParam('cid');

        $params = ParamsTaskManager::getParamData($paramId, $componentId);
        if (isset($params[0]))
            $param = $params[0];
        else
            $param = $params;

        $paramTaskObj = new ParamsTaskManager($searchFor, $param, $slctd);
        if ($paramTaskObj->hasItems()) {
            
            $cols = array();
            $cols[] = array(
                'class' => 'RadioBoxColumn',
                'checked' => '($data["id"] == ' . $slctd . ')?true:false',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            );

            $cols[] = array(
                'value' => '"<div id=\"title_{$data["id"]}\" style=\"padding-right:5px;\">" . $data["title"] . "</div>"',
                'type' => 'raw',
                'header' => AmcWm::t("msgsbase.core", 'Title')
            );
            
            $paramTaskObj->setGridColumns($cols);
            
            $this->render('componentParam', array(
                'myParamId' => 'MenuItemsParams_' . $componentId . '_' . $paramId,
                'dialog' => $dialog,
                'dataProvider' => $paramTaskObj->getDataProvider(),
                'columns' => $paramTaskObj->getGridColumns(),
            ));
        }
    }

}