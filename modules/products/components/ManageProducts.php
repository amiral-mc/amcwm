<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ManageContent class, manage content
 * 
 * @package AmcWebManager
 * @subpackage Data
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ManageProducts extends ManageContent {

    const EDIT_PENDING = 3;
    const EDIT_APPROVAL = 2;
    const DELETE_APPROVAL = 4;

    private $_settingsIndex = "backend";

    /**
     * setting generated from settings.php inside application module folder
     * @var array
     */
    private $_settings = array();

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     */
    protected function init() {
        if ($this->isBackend) {
            $this->_settingsIndex = "backend";
        } else {
            $this->_settingsIndex = "frontend";
        }
        if (isset($this->controller->getModule()->appModule)) {
            $this->_settings = $this->controller->getModule()->appModule->settings;
        }
        parent::init();
    }

    /**
     * Lists all models.
     */
    public function index($wajax = false) {
        $model = new Products('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new ProductsTranslation('search'), Controller::getContentLanguage());
        $contentModel = $model->getTranslated(Controller::getContentLanguage());
        if (isset($_GET['Products'])) {
            $model->attributes = $_GET['Products'];
        }
        if ($contentModel) {
            if (isset($_GET['ProductsTranslation'])) {
                $contentModel->attributes = $_GET['ProductsTranslation'];
            }
            if ($wajax) {
                $this->controller->render('wajax', array(
                    'model' => $contentModel,
                ));
            } else {
                $this->controller->render('index', array(
                    'model' => $contentModel,
                ));
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Performs the sort action
     * @param  int $id the ID of the model to be sorted
     * @access public 
     * @return void
     */
    public function sort($id, $direction) {
        $model = $this->loadModel($id);
        $model->sort($direction);
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Product "{product}" has been sorted', array("{product}" => $model->getCurrent()->product_name))));
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function create() {
        $model = new Products;        
        $contentModel = new ProductsTranslation();
        $galleryContentModel = new GalleriesTranslation;
        $model->addTranslationChild($contentModel, Controller::getContentLanguage());        
        $model->gallery = new Galleries;                
        $model->gallery->addTranslationChild($galleryContentModel, Controller::getContentLanguage());                        
        $this->save($contentModel);
        $this->controller->render('create', array(
            'contentModel' => $contentModel,
        ));
    }

    /**
     * Save Product
     * @param ProductsTranslation $contentModel
     * @return boolean
     * @access protected
     */
    protected function save(ProductsTranslation $contentModel) {
        if (isset($_POST['Products']) && isset($_POST["ProductsTranslation"])) {
            $model = $contentModel->getParentContent();
            if (isset($_POST["ProductsTranslation"]["tags"]) && is_array($_POST["ProductsTranslation"]["tags"])) {
                $tags = implode(PHP_EOL, $_POST["ProductsTranslation"]["tags"]);
            } else {
                $tags = null;
            }
            $_POST["ProductsTranslation"]["tags"] = $tags;
            $model->attributes = $_POST['Products'];            
            $contentModel->attributes = $_POST['ProductsTranslation'];
            $galleryContentModel  = $model->gallery->getCurrent();            
            $galleryContentModel->gallery_header = $contentModel->product_name;
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            $transaction = Yii::app()->db->beginTransaction();
            $success = false;
            $saved = false;
            if ($validate) {
                try {
                    $saved = $model->gallery->save();
                    $model->gallery_id = $model->gallery->gallery_id;                    
                    $saved &= $galleryContentModel->save();
                    $saved &= $model->save();
                    $saved &= $contentModel->save();
                    if ($saved) {
                        $transaction->commit();
                        $success = true;
                    }
                } catch (CDbException $e) {
                    echo $e->getMessage();
                    $transaction->rollback();
                    $success = false;
                }
                if ($success) {
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Product has been saved')));
                    $this->redirect(array('view', 'id' => $model->product_id));
                } else {
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                }
            }
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function update($id) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $this->save($contentModel);
            $this->controller->render("update", array(
                'contentModel' => $contentModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * translate a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function translate($id) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $model = $contentModel->getParentContent();
            $translatedModel = $this->loadTranslatedModel($model, $id);
            if (isset($_POST["ProductsTranslation"])) {
                if (isset($_POST["ProductsTranslation"]["tags"]) && is_array($_POST["ProductsTranslation"]["tags"])) {
                    $tags = implode(PHP_EOL, $_POST["ProductsTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["ProductsTranslation"]["tags"] = $tags;
                $translatedModel->attributes = $_POST['ProductsTranslation'];
                $validate = $translatedModel->validate();
                $transaction = Yii::app()->db->beginTransaction();
                $success = false;
                $saved = false;
                if ($validate) {
                    try {
                        $saved = $translatedModel->save();
                        if ($saved) {
                            $transaction->commit();
                            $success = true;
                        }
                        $success = true;
                    } catch (CDbException $e) {
                        $transaction->rollback();
                        $success = false;
                    }
                    if ($success) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->product_id));
                    } else {
                        Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    }
                }
            }
            $this->controller->render('translation', array(
                'contentModel' => $contentModel,
                'translatedModel' => $translatedModel,
            ));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Redirects the browser to the specified URL or route (controller/action).
     * @param mixed $url the URL to be redirected to. If the parameter is an array,
     * the first element must be a route to a controller action and the rest
     * are GET parameters in name-value pairs.
     * @param boolean $terminate whether to terminate the current application after calling this method. Defaults to true.
     * @param integer $statusCode the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
     * for details about HTTP status code.
     */
    public function redirect($url, $terminate = true, $statusCode = 302) {
        $this->controller->redirect($url, $terminate, $statusCode);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = ProductsTranslation::model()->findByPk(array("product_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
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
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->controller->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = ProductsTranslation::model()->findByPk(array("product_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new ProductsTranslation();
                $translatedModel->product_id = $model->product_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return Sections
     */
    public function loadModel($id) {
        $model = Products::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function view($id) {
        $this->controller->render('view', array(
            'contentModel' => $this->loadChildModel($id),
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function delete() {
        $ids = Yii::app()->request->getParam('ids');
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $deleted = $model->delete();
                if ($deleted) {
                    $messages['success'][] = AmcWm::t('msgsbase.core', 'Product "{product}" has been deleted', array("{product}" => $contentModel->displayTitle));
                } else {
                    $messages['error'][] = AmcWm::t('msgsbase.core', 'Can not delete product "{product}"', array("{product}" => $contentModel->displayTitle));
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
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === Yii::app()->params["adminForm"]) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
