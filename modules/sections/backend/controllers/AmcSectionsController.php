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
class AmcSectionsController extends BackendController {

    private $parentSection = null;

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
     * @param Sections $model
     * @access protected
     */
    protected function save(SectionsTranslation $contentModel) {
        if ($this->getParentId()) {
            $model->parent_section = $this->getParentId();
        }
        if (isset($_POST['Sections']) && isset($_POST["SectionsTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $model = $contentModel->getParentContent();
            $oldThumb = $model->image_ext;
            $model->attributes = $_POST['Sections'];

            $deleteImage = Yii::app()->request->getParam('deleteImage');
            $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile instanceof CUploadedFile) {
                $model->setAttribute('image_ext', $model->imageFile->getExtensionName());
            } else if ($deleteImage) {
                $model->setAttribute('image_ext', null);
            }

            if (isset($_POST["SectionsTranslation"]["tags"]) && is_array($_POST["SectionsTranslation"]["tags"])) {
                $tags = implode(PHP_EOL, $_POST["SectionsTranslation"]["tags"]);
            } else {
                $tags = null;
            }
            $_POST["SectionsTranslation"]["tags"] = $tags;
            $contentModel->attributes = $_POST['SectionsTranslation'];
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $transaction->commit();
                            $this->saveThumb($model, $oldThumb);
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Section has been saved')));
                            $this->redirect(array('view', 'id' => $model->section_id, 'sid' => $model->parent_section));
                        }
                    }
                } catch (CDbException $e) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    //$this->refresh();
                }
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Sections;
        $contentModel = new SectionsTranslation();
        if ($this->getParentId()) {
            $model->parent_section = $this->getParentId();
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
            if (isset($_POST["SectionsTranslation"])) {
                if (isset($_POST["SectionsTranslation"]["tags"]) && is_array($_POST["SectionsTranslation"]["tags"])) {
                    $tags = implode(PHP_EOL, $_POST["SectionsTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["SectionsTranslation"]["tags"] = $tags;
                $translatedModel->attributes = $_POST['SectionsTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->section_id, 'sid' => $contentModel->getParentContent()->parent_section));
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
        parent::publish($published, "index", array('sid' => $this->getParentId()));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $ids = Yii::app()->request->getParam('ids', array());
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $checkRelated = (count($model->articles) || count($model->events) || count($model->sections) || count($model->galleries) || count($model->infocuses));
                if ($checkRelated) {
                    $messages['error'][] = AmcWm::t("msgsbase.core", 'Can not delete section "{section}"', array("{section}" => $contentModel->section_name));
                } else {
                    if ($model->delete()) {
                        if ($model->image_ext) {
                            $imageSizesInfo = $this->getModule()->appModule->mediaPaths;
                            foreach ($imageSizesInfo as $imageInfo) {
                                $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $model->section_id . "." . $model->image_ext;
                                if (is_file($imageFile)) {
                                    unlink($imageFile);
                                }
                            }
                        }
                    }
                    $messages['success'][] = AmcWm::t("msgsbase.core", 'Section "{section}" has been deleted', array("{section}" => $contentModel->section_name));
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
                $this->redirect(array('index', 'sid' => $this->getParentId()));
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Sections('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new SectionsTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if ($this->getParentId()) {
            $_GET['Sections']['parent_section'] = $this->getParentId();
        }
        if (isset($_GET['Sections'])) {
            $model->attributes = $_GET['Sections'];
        }
        if ($contentModel) {
            if (isset($_GET['SectionsTranslation'])) {
                $contentModel->attributes = $_GET['SectionsTranslation'];
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
     * @return Sections
     */
    public function loadModel($id) {
        $model = Sections::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Sections $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = SectionsTranslation::model()->findByPk(array("section_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new SectionsTranslation();
                $translatedModel->section_id = $model->section_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = SectionsTranslation::model()->findByPk(array("section_id" => $pk['id'], 'content_lang' => $pk['lang']));
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
     * Supervisors action
     */
    public function actionSupervisors() {
        $this->forward("supervisors/");
    }

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'parentContext';
        return $filters;
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper gallery context
     */
    public function filterParentContext($filterChain) {
        //set the project identifier based on either the GET or POST input request variables, since we allow both types for our actions   
        $sectionId = null;
        if (isset($_GET['sid']))
            $sectionId = $_GET['sid'];
        else
        if (isset($_POST['sid']))
            $sectionId = $_POST['sid'];
        $this->loadParent($sectionId);
        //complete the running of other filters and execute the requested action
        $filterChain->run();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadParent($sectionId) {
        if ($sectionId) {
            $this->parentSection = Sections::model()->findByPk($sectionId);
        }
    }

    /**
     *
     * Get sections parent
     * @return ActiveModel 
     */
    public function getParentSection() {
        return $this->parentSection;
    }

    /**
     * Get parent id section
     * @return int
     */
    public function getParentId() {
        static $parentId = null;
        if ($parentId === null) {
            if ($this->getParentSection() !== NULL) {
                $parentId = $this->getParentSection()->section_id;
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
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Section "{section}" has been sorted', array("{section}" => $model->getCurrent()->section_name))));
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'sid' => $this->getParentId()));
    }

    /**
     * Save thumb images
     * @param ActiveRecord $section
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function saveThumb(ActiveRecord $section, $oldThumb) {
        $deleteImage = Yii::app()->request->getParam('deleteImage');
        if ($section->imageFile instanceof CUploadedFile) {
            $image = new Image($section->imageFile->getTempName());
            $imageSizesInfo = $this->getModule()->appModule->mediaPaths;
            foreach ($imageSizesInfo as $imageInfo) {
                $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $section->section_id . "." . $section->image_ext;
                if ($oldThumb != $section->image_ext && $oldThumb) {
                    unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $section->section_id . "." . $oldThumb);
                }
                if ($imageInfo['info']['crob']) {
                    $image->resizeCrop($imageInfo['info']['width'], $imageInfo['info']['height'], $imageFile);
                } else {
                    $image->resize($imageInfo['info']['width'], $imageInfo['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $imageFile);
                }
            }
        } else if ($deleteImage && $oldThumb) {
            $imageSizesInfo = $this->getModule()->appModule->mediaPaths;
            foreach ($imageSizesInfo as $imageInfo) {
                unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $section->section_id . "." . $oldThumb);
            }
        }
    }

}