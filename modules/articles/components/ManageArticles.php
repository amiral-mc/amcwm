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
class ManageArticles extends ManageContent {

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
            $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
            if ($virtualModule == "articles") {
                $this->controller->redirect(AmcWm::app()->homeUrl);
            }
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
        $view = $this->controller->getModule()->appModule->getVirtualView("index");
        $model = new Articles('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new ArticlesTranslation('search'), Controller::getContentLanguage());
        $contentModel = $model->getTranslated(Controller::getContentLanguage());
        $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
//        $this->getStatistics($virtualModule);
        $msgsBase = "msgsbase.core";
        if ($virtualModule != "articles") {
            $msgsBase = "msgsbase.{$virtualModule}";
        }
        if (isset($_GET['Articles'])) {
            $model->attributes = $_GET['Articles'];
        }
        if ($contentModel) {
            if (isset($_GET['ArticlesTranslation'])) {
                $contentModel->attributes = $_GET['ArticlesTranslation'];
            }
            if ($wajax) {
                $this->controller->render('wajax', array(
                    'msgsBase' => $msgsBase,
                    'model' => $contentModel,
                ));
            } else {
                $this->controller->render($view, array(
                    'msgsBase' => $msgsBase,
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
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Article "{article}" has been sorted', array("{article}" => $model->getCurrent()->article_header))));
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function create() {
        $model = new Articles;
        $contentModel = new ArticlesTranslation();
        $model->addTranslationChild($contentModel, Controller::getContentLanguage());
        $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
        $options = $this->controller->module->appModule->options;
        $autoPost2social = false;
        if (isset($options[$virtualModule]['default']['check']['autoPost2social'])) {
            $autoPost2social = $options[$virtualModule]['default']['check']['autoPost2social'];
        } else if (isset($options['default']['check']['autoPost2social'])) {
            $autoPost2social = $options['default']['check']['autoPost2social'];
        }
        if (!isset($_POST['Articles']) && $autoPost2social) {
            $model->socialIds = array_keys($this->getSocials());
        }
        $msgsBase = "msgsbase.core";
        if ($virtualModule != "articles") {
            $msgsBase = "msgsbase.{$virtualModule}";
            $extraModel = $this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['tableModel'];
            $tableClass = ucfirst($extraModel);
            $model->$extraModel = new $tableClass();
            if ($model->$extraModel instanceof ParentTranslatedActiveRecord) {
                $tableTransClass = "{$tableClass}Translation";
                $model->$extraModel->addTranslationChild(new $tableTransClass(), Controller::getContentLanguage());
            }
        }
        $this->save($contentModel);
        $view = $this->controller->getModule()->appModule->getVirtualView("create");
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $this->controller->render($view, array(
            'contentModel' => $contentModel,
            'msgsBase' => $msgsBase
        ));
    }

    /**
     * Save article
     * @param ArticlesTranslation $article
     * @return boolean
     * @access protected
     */
    protected function save(ArticlesTranslation $contentModel) {
        $virtualModule = AmcWm::app()->appModule->getCurrentVirtual();
        $contentModel->attachBehavior("attachmentBehaviors", new AttachmentBehaviors($virtualModule, $contentModel, 1, $contentModel->article_id));            
        if (isset($_POST['Articles']) && isset($_POST["ArticlesTranslation"])) {
            $model = $contentModel->getParentContent();
            if (isset($_POST["ArticlesTranslation"]["tags"]) && is_array($_POST["ArticlesTranslation"]["tags"])) {
                $tags = implode(PHP_EOL, $_POST["ArticlesTranslation"]["tags"]);
            } else {
                $tags = null;
            }
            $_POST["ArticlesTranslation"]["tags"] = $tags;
            $oldThumb = $model->thumb;
            $oldSlider = $model->in_slider;
            $model->attributes = $_POST['Articles'];
            if (isset($_POST["remove_parent"]) && $_POST["remove_parent"]) {
                $model->parent_article = null;
            }
            $contentModel->attributes = $_POST['ArticlesTranslation'];
            $extraModel = null;

            $deleteImage = isset($_POST['Articles']['imageFile_deleteImage']) && $_POST['Articles']['imageFile_deleteImage'];
            $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile instanceof CUploadedFile) {
                $model->setAttribute('thumb', $model->imageFile->getExtensionName());
            } else if ($deleteImage) {
                $model->setAttribute('thumb', null);
            }

            /**
             * Article page image
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
            /**
             * Slider file
             */
            $model->sliderFile = CUploadedFile::getInstance($model, 'sliderFile');
            if ($model->sliderFile instanceof CUploadedFile) {
                $model->setAttribute('in_slider', $model->sliderFile->getExtensionName());
            } else if (!$model->in_slider) {
                $model->setAttribute('in_slider', null);
            }
            $currentFlow = array();
            if (AmcWm::app()->hasComponent("workflow")) {
                if (AmcWm::app()->workflow->module->hasUserSteps()) {
                    $currentFlow = AmcWm::app()->workflow->module->getFlowFromRoute($this->controller->getRoute());
                    if (isset($currentFlow['step_title']['ManageContent'])) {
                        $model->published = self::EDIT_PENDING;
                    }
                }
            }
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
            $useRelatedModel = true;
            if (isset($this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['customCriteria']['useRelatedModel']) && !$this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['customCriteria']['useRelatedModel']) {
                $useRelatedModel = false;
            }
            if ($virtualModule != "articles" && $useRelatedModel) {
                $extraModel = $this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['tableModel'];
                $tableClass = ucfirst($extraModel);
                if (isset($_POST[$tableClass])) {
                    $model->$extraModel->attributes = $_POST[$tableClass];
                    $validate &= $model->$extraModel->validate();
                }
                if (isset($_POST["{$tableClass}Translation"]) && $model->$extraModel instanceof ParentTranslatedActiveRecord) {
                    $model->$extraModel->getCurrent()->attributes = $_POST["{$tableClass}Translation"];
                    $validate &= $model->$extraModel->getCurrent()->validate();
                }
            }
            $titlesModels = array();
            if (isset($_POST['ArticlesTitles'])) {
                $index = 0;
                foreach ($_POST['ArticlesTitles'] as $title) {
                    $titleModel = ArticlesTitles::model()->findByPk($title['title_id']);
                    if ($titleModel === NULL) {
                        $titleModel = new ArticlesTitles();
                    }
                    $titleModel->attributes = $title;
                    $contentModel->addRelatedRecord('titles', $titleModel, $index);
                    $validate &= $titleModel->validate(array("title"));
                    $titlesModels[$index] = $titleModel;
                    $index++;
                }
            }
            if (isset($_POST['Essays']['sticky'])) {
                $count = Yii::app()->db->createCommand()
                        ->select('e.article_id')
                        ->from('essays e')
                        ->join('articles a', 'e.article_id = a.article_id')
                        ->where('e.sticky != 0')
                        ->order('a.update_date desc')
                        ->queryColumn();
                $stickyLimit = false;
                if ($this->_settings['options']['essays']['default']['integer']['sticky'] >= count($count) && !in_array($model->article_id, $count)) {
                    $stickyLimit = true;
                }
                $model->essays->sticky = $_POST['Essays']['sticky'];
            }
            $transaction = Yii::app()->db->beginTransaction();
            $success = false;
            $saved = false;
            if ($validate) {
                try {
                    if (isset($stickyLimit) && $stickyLimit && $count) {
                        $updateSticky = 'UPDATE essays SET sticky = 0 where article_id = ' . end($count);
                        Yii::app()->db->createCommand($updateSticky)->execute();
                    }
                    $isNew = $model->isNewRecord;
                    if (!$isNew) {
                        DbLogManager::logAction($model, DbLogManager::UPDATED);
                    }
                    $saved = $model->save();
                    $saved &= $contentModel->save();
                    if ($extraModel) {
                        $model->$extraModel->article_id = $model->article_id;
                        $saved &= $model->$extraModel->save();
                        if ($model->$extraModel instanceof ParentTranslatedActiveRecord) {
                            $saved &= $model->$extraModel->getCurrent()->save();
                        }
                    }
                    foreach ($titlesModels as $titleModel) {
                        $titleModel->article_id = $contentModel->article_id;
                        $titleModel->content_lang = $contentModel->content_lang;
                        $saved &= $titleModel->save();
                    }

                    if ($saved) {
                        if (isset($_POST['ArticlesTitlesRemoved'])) {
                            foreach ($_POST['ArticlesTitlesRemoved'] as $titleId) {
                                $removedTitles[] = (int) $titleId;
                            }
                            $deleteWhereTitles = implode(',', $removedTitles);
                            $query = "delete from articles_titles where title_id in($deleteWhereTitles)";
                            Yii::app()->db->createCommand($query)->execute();
                        }
                        if ($isNew) {
                            DbLogManager::logAction($model, DbLogManager::INSERT);
                        }
                        $redirectAction = 'view';
                        if (AmcWm::app()->hasComponent("workflow")) {
                            if (isset($currentFlow['step_title']['ManageContent'])) {
                                if (isset($_POST['save_finish']) && $_POST['save_finish']) {
                                    $redirectAction = 'view';
                                    AmcWm::app()->workflow->module->moveTaskToNextStep($contentModel->article_id);
                                } else {
                                    $redirectAction = 'update';
                                    AmcWm::app()->workflow->module->saveTaskStep($contentModel->article_id);
                                }
                            }
                        }
                        $transaction->commit();
                        $success = true;
                    }
                } catch (CDbException $e) {
//                    echo $e->getMessage();
                    $transaction->rollback();
                    $success = false;
                }
                if ($success) {
                    $this->saveThumb($model, $oldThumb);
                    $this->saveSlider($model, $oldSlider);

                    if (isset($this->_settings['options']['default']['check']['allowPageImage']) && $this->_settings['options']['default']['check']['allowPageImage']) {
                        $this->savePageImage($model, $oldPageImage);
                    }
                    Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Article has been saved')));
                    $this->redirect(array($redirectAction, 'id' => $model->article_id));
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
            $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
            $msgsBase = "msgsbase.core";
            if ($virtualModule != "articles") {
                $msgsBase = "msgsbase.{$virtualModule}";
            }

            $view = $this->controller->getModule()->appModule->getVirtualView("update");
            $this->save($contentModel);
            $this->controller->render($view, array(
                'contentModel' => $contentModel,
                'msgsBase' => $msgsBase,
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
        $view = $this->controller->getModule()->appModule->getVirtualView("translate");
        $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
        $msgsBase = "msgsbase.core";
        if ($virtualModule != "articles") {
            $msgsBase = "msgsbase.{$virtualModule}";
        }

        if ($contentModel) {
            $model = $contentModel->getParentContent();
            $translatedModel = $this->loadTranslatedModel($model, $id);
            $translatedModel->attachBehavior("attachmentBehaviors", new AttachmentBehaviors($virtualModule, $translatedModel, 1, $translatedModel->article_id));            
            if (isset($_POST["ArticlesTranslation"])) {
                if (isset($_POST["ArticlesTranslation"]["tags"]) && is_array($_POST["ArticlesTranslation"]["tags"])) {
                    $tags = implode(PHP_EOL, $_POST["ArticlesTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["ArticlesTranslation"]["tags"] = $tags;
                $translatedModel->attributes = $_POST['ArticlesTranslation'];
                $validate = $translatedModel->validate();
                $extraModel = null;
                if ($virtualModule != "articles") {
                    $extraModel = $this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['tableModel'];
                    $tableClass = ucfirst($extraModel);
                    if (isset($_POST["{$tableClass}Translation"]) && $model->$extraModel instanceof ParentTranslatedActiveRecord) {
                        $model->$extraModel->getTranslated($translatedModel->content_lang)->attributes = $_POST["{$tableClass}Translation"];
                        $validate &= $model->$extraModel->getTranslated($translatedModel->content_lang)->validate();
                    }
                }
                $titlesModels = array();
                if (isset($_POST['ArticlesTitles'])) {
                    $index = 0;
                    foreach ($_POST['ArticlesTitles'] as $title) {
                        $titleModel = ArticlesTitles::model()->findByPk($title['title_id']);
                        if ($titleModel === NULL) {
                            $titleModel = new ArticlesTitles();
                        }
                        $titleModel->attributes = $title;
                        $translatedModel->addRelatedRecord('titles', $titleModel, $index);
                        $validate &= $titleModel->validate(array("title"));
                        $titlesModels[$index] = $titleModel;
                        $index++;
                    }
                }
                $transaction = Yii::app()->db->beginTransaction();
                $success = false;
                $saved = false;
                if ($validate) {
                    try {
                        $isNew = $translatedModel->isNewRecord;
                        if (!$isNew) {
                            DbLogManager::logAction($model, DbLogManager::UPDATED);
                        }
                        $saved = $translatedModel->save();
                        if ($extraModel && $model->$extraModel instanceof ParentTranslatedActiveRecord) {
                            $saved &= $model->$extraModel->getTranslated($translatedModel->content_lang)->save();
                        }
                        foreach ($titlesModels as $titleModel) {
                            $titleModel->article_id = $translatedModel->article_id;
                            $titleModel->content_lang = $translatedModel->content_lang;
                            $saved &= $titleModel->save();
                        }
                        if ($saved) {
                            if (isset($_POST['ArticlesTitlesRemoved'])) {
                                foreach ($_POST['ArticlesTitlesRemoved'] as $titleId) {
                                    $removedTitles[] = (int) $titleId;
                                }
                                $deleteWhereTitles = implode(',', $removedTitles);
                                $query = "delete from articles_titles where title_id in($deleteWhereTitles)";
                                Yii::app()->db->createCommand($query)->execute();
                            }
                            $transaction->commit();
                            if ($isNew) {
                                DbLogManager::logAction($model, DbLogManager::INSERT);
                            }
                            $success = true;
                        }
                        $success = true;
                    } catch (CDbException $e) {
                        $transaction->rollback();
                        $success = false;
                    }
                    if ($success) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $contentModel->article_id));
                    } else {
                        Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    }
                }
            } else if ($translatedModel->isNewRecord) {
                $translatedTitlesCount = count($translatedModel->titles);
                $titlesDiffCount = count($contentModel->titles) - $translatedTitlesCount;
                if ($titlesDiffCount > 0) {
                    for ($index = 0; $index < $titlesDiffCount; $index++) {
                        //echo $index + $translatedTitlesCount , "p";
                        $titleModel = new ArticlesTitles();
                        $translatedModel->addRelatedRecord('titles', $titleModel, $index + $translatedTitlesCount);
                    }
                }
            }
            $this->controller->render($view, array(
                'contentModel' => $contentModel,
                'translatedModel' => $translatedModel,
                'msgsBase' => $msgsBase,
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
        $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
        if (isset($this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['redirectParams'])) {
            $redirectParams = $this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['redirectParams'];
            if (is_array($url)) {
                foreach ($redirectParams as $rparam) {
                    if (AmcWm::app()->request->getParam($rparam)) {
                        $url = array_merge($url, array($rparam => AmcWm::app()->request->getParam($rparam)));
                    }
                }
            }
        }
        $this->controller->redirect($url, $terminate, $statusCode);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $allow = true;
        if (AmcWm::app()->hasComponent("workflow")) {
            $allow = AmcWm::app()->workflow->module->checkTaskItem($id) || $this->controller->action->id == "view";
            if ($this->controller->action->id == "publish") {
                $currentFlow = AmcWm::app()->workflow->module->getFlowFromTaskItem($id);
                if (isset($currentFlow['step_title']['PublishContent'])) {
                    $allow = false;
                }
            }
        }
        if ($allow) {
            $ok = $this->controller->getModule()->appModule->checkArticleInTable($id);
            $model = null;
            if ($ok) {
                $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
                $model = ArticlesTranslation::model()->findByPk(array("article_id" => $pk['id'], 'content_lang' => $pk['lang']));
            }
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            return $model;
        } else {
            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcCore", 'You are not authorized to perform this action.')));
            $this->redirect(array('index'));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Persons $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return PersonsTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $ok = $this->controller->getModule()->appModule->checkArticleInTable($id);
        $translatedModel = null;
        if ($ok) {
            $translatedModel = null;
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
            } else {
                $langs = $this->controller->getTranslationLanguages();
                $translationLang = Yii::app()->request->getParam("tlang", key($langs));
                $translatedModel = ArticlesTranslation::model()->findByPk(array("article_id" => (int) $id, 'content_lang' => $translationLang));
                if ($translatedModel === null) {
                    $translatedModel = new ArticlesTranslation();
                    $translatedModel->article_id = $model->article_id;
                    $model->addTranslationChild($translatedModel, $translationLang);
                }
                $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
                if ($virtualModule != "articles") {
                    $extraModel = $this->_settings[$this->_settingsIndex]['virtual'][$virtualModule]['tableModel'];
                    $tableClass = ucfirst($extraModel);
                    if ($model->$extraModel instanceof ParentTranslatedActiveRecord) {
                        $tableExtraTransClass = "{$tableClass}Translation";
                        $translatedExtraModel = ChildTranslatedActiveRecord::model($tableExtraTransClass)->findByPk(array("article_id" => (int) $id, 'content_lang' => $translationLang));
                        if ($translatedExtraModel === null) {
                            $translatedExtraModel = new $tableExtraTransClass();
                            $translatedExtraModel->article_id = $model->article_id;
                            $model->$extraModel->addTranslationChild($translatedExtraModel, $translationLang);
                        }
                    }
                }
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
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
        $ok = $this->controller->getModule()->appModule->checkArticleInTable($id);
        $model = null;
        if ($ok) {
            $model = Articles::model()->findByPk($id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Save thumb images
     * @param ActiveRecord $article
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function saveThumb(ActiveRecord $article, $oldThumb) {
        $articlesParams = Yii::app()->request->getParam('Articles');
        $coords = isset($articlesParams['imageFile_coords']) ? CJSON::decode($articlesParams['imageFile_coords']) : array();
        $deleteImage = isset($articlesParams['imageFile_deleteImage']) && $articlesParams['imageFile_deleteImage'];
        $imageSizesInfo = $this->controller->getModule()->appModule->mediaPaths;
        if ($article->imageFile instanceof CUploadedFile) {
            $watermarkOptions = array();
            if (isset($articlesParams['imageFile_watermark']) && (isset(AmcWm::app()->params['watermark']['image']) || isset(AmcWm::app()->params['watermark']['text']))) {
                $watermarkOptions = AmcWm::app()->params['watermark'];
            }
            //if(isset(AmcWm::app()->params['watermark']['image']) || isset(AmcWm::app()->params['watermark']['text'])){
            $image = new Image($article->imageFile->getTempName());
            foreach ($imageSizesInfo as $imageInfo) {
                $path = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']);
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }
                $ok = false;
                if ($imageInfo['autoSave']) {
                    if ($coords) {
                        if ($imageInfo['info']['crob']) {
                            $ok = ($imageInfo['info']['width'] <= ($coords['x2'] - $coords['x']) || $imageInfo['info']['height'] <= ($coords['y2'] - $coords['y'])) ? true : false;
                        } else {
                            $ok = ($imageInfo['info']['width'] <= ($coords['x2'] - $coords['x'])) ? true : false;
                        }
                    } else {
                        $ok = true;
                    }
                }
                $imageFile = $path . DIRECTORY_SEPARATOR . $article->article_id . "." . $article->thumb;
                $oldThumbFile = $path . DIRECTORY_SEPARATOR . $article->article_id . "." . $oldThumb;
                if ($oldThumb && is_file($oldThumbFile) && $imageInfo['autoSave']) {
                    unlink($oldThumbFile);
                }
                if ($ok && $article->thumb) {

                    if ($imageInfo['info']['crob']) {
                        $image->resizeCrop($imageInfo['info']['width'], $imageInfo['info']['height'], $imageFile, $coords, $watermarkOptions);
                    } else {
                        $image->resize($imageInfo['info']['width'], $imageInfo['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $imageFile, $coords, $watermarkOptions);
                    }
                }
            }
        } else if ($deleteImage && $oldThumb) {
            foreach ($imageSizesInfo as $imageInfo) {
                $path = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']);
                if ($imageInfo['autoSave']) {
                    $oldThumbFile = $path . "/" . $article->article_id . DIRECTORY_SEPARATOR . $oldThumb;
                    if (is_file($oldThumbFile)) {
                        unlink($oldThumbFile);
                    }
                }
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
        $dir = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['pageImage']['path']);
        if ($model->pageImg instanceof CUploadedFile && $model->page_img) {
            $pageImage = $dir . DIRECTORY_SEPARATOR . $model->article_id . "." . $model->page_img;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if ($oldPageImage && $model->page_img != $oldPageImage && is_file($dir . "/" . $model->article_id . "." . $oldPageImage)) {
                @unlink($dir . "/" . $model->article_id . "." . $oldPageImage);
            }
            $model->pageImg->saveAs($pageImage);
        } else if ($deleteImage && $oldPageImage) {
            if (is_file($dir . "/" . $model->article_id . "." . $oldPageImage)) {
                @unlink($dir . "/" . $model->article_id . "." . $oldPageImage);
            }
        }
    }

    /**
     * Save slider image
     * @param ActiveRecord $article
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function saveSlider(ActiveRecord $item, $oldSlider) {
        $imageSizesInfo = $this->controller->getModule()->appModule->mediaPaths;
        $imageInfo = $imageSizesInfo['slider'];
        $articlesParams = Yii::app()->request->getParam('Articles');
        if ($item->sliderFile instanceof CUploadedFile && $item->in_slider) {
            $watermarkOptions = array();
            if (isset($articlesParams['sliderFile_watermark']) && (isset(AmcWm::app()->params['watermark']['image']) || isset(AmcWm::app()->params['watermark']['text']))) {
                $watermarkOptions = AmcWm::app()->params['watermark'];
            }
            $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $item->article_id . "." . $item->in_slider;
            $thumbFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['thumb']['path']) . "/" . $item->article_id . "." . $item->in_slider;
            $image = new Image($item->sliderFile->getTempName());
            $image->resize($imageInfo['info']['width'], $imageInfo['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $imageFile, array(), $watermarkOptions);
            $image->resize($imageInfo['thumb']['info']['width'], $imageInfo['thumb']['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $thumbFile, array(), $watermarkOptions);
        }
        if ($oldSlider != $item->in_slider && $oldSlider) {
            $old = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $item->article_id . "." . $oldSlider;
            $oldThumb = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['thumb']['path']) . "/" . $item->article_id . "." . $oldSlider;
            if (is_file($old)) {
                unlink($old);
            }
            if (is_file($oldThumb)) {
                unlink($oldThumb);
            }
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function view($id) {
        $view = $this->controller->getModule()->appModule->getVirtualView("view");
        $msgsBase = "msgsbase.core";
        $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
        if ($virtualModule != "articles") {
            $msgsBase = "msgsbase.{$virtualModule}";
        }
        $this->controller->render($view, array(
            'contentModel' => $this->loadChildModel($id),
            'msgsBase' => $msgsBase,
        ));
    }

    /**
     * 
     * @param integer $id
     * @param boolean $undo
     */
    public function contentApproval($id, $undo, $publishFlag) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $model = $contentModel->getParentContent();
            $msgsBase = "msgsbase.core";
            $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
            if ($virtualModule != "articles") {
                $msgsBase = "msgsbase.{$virtualModule}";
            }
            if (AmcWm::app()->hasComponent("workflow")) {
                $currentFlow = AmcWm::app()->workflow->module->getFlowFromRoute($this->controller->getRoute());
                if (isset($currentFlow['step_title']['DeleteApproval'])) {
                    if ($undo) {
                        AmcWm::app()->workflow->module->moveTaskToNextStep($contentModel->article_id, false, true);
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t($msgsBase, 'Article "{article}" has been recjected', array("{article}" => $contentModel->displayTitle))));
                    } else {
                        $model->published = $publishFlag;
                        $model->save();
                        AmcWm::app()->workflow->module->moveTaskToNextStep($contentModel->article_id);
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t($msgsBase, 'Article "{article}" has been approved', array("{article}" => $contentModel->displayTitle))));
                    }
                }
                $this->redirect(array('index'));
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * 
     * @param integer $id
     * @param boolean $undo
     */
    public function deleteApproval($id, $undo) {
        $contentModel = $this->loadChildModel($id);
        if ($contentModel) {
            $model = $contentModel->getParentContent();
            $msgsBase = "msgsbase.core";
            $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
            if ($virtualModule != "articles") {
                $msgsBase = "msgsbase.{$virtualModule}";
            }
            if (AmcWm::app()->hasComponent("workflow")) {
                $currentFlow = AmcWm::app()->workflow->module->getFlowFromRoute($this->controller->getRoute());
                if (isset($currentFlow['step_title']['DeleteApproval'])) {
                    if ($undo) {
                        $model->published = ActiveRecord::UNPUBLISHED;
                        $model->save();
                        AmcWm::app()->workflow->module->deleteTaskStep($contentModel->article_id);
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t($msgsBase, 'Article "{article}" has been restored', array("{article}" => $contentModel->displayTitle))));
                    } else {
                        AmcWm::app()->workflow->module->moveTaskToNextStep($contentModel->article_id);
                        $deleted = $model->delete();
                        if ($deleted) {
                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t($msgsBase, 'Article "{article}" has been deleted', array("{article}" => $contentModel->displayTitle))));
                            $this->deleteImages($model);
                        } else {
                            AmcWm::t($msgsBase, 'Can not delete article "{article}"', array("{article}" => $contentModel->displayTitle));
                            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => ''));
                        }
                    }
                }
                $this->redirect(array('index'));
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function delete() {
        $ids = Yii::app()->request->getParam('ids');
        $msgsBase = "msgsbase.core";
        $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
        if ($virtualModule != "articles") {
            $msgsBase = "msgsbase.{$virtualModule}";
        }
        $currentFlow = array();
        if (AmcWm::app()->hasComponent("workflow")) {
            $currentFlow = AmcWm::app()->workflow->module->getFlowFromRoute($this->controller->getRoute());
        }
        if (Yii::app()->request->isPostRequest && count($ids)) {
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $checkRelated = false;
                if ($checkRelated) {
                    $messages['error'][] = AmcWm::t($msgsBase, 'Can not delete article "{article}"', array("{article}" => $contentModel->displayTitle));
                } else {
                    DbLogManager::logAction($model, DbLogManager::DELETE);
                    if (isset($currentFlow['step_title']['DeleteContent'])) {
                        AmcWm::app()->workflow->module->moveTaskToNextStep($contentModel->article_id, true);
                        $model->published = self::DELETE_APPROVAL;
                        $model->save();
                        $deleted = true;
                    } else if (!AmcWm::app()->hasComponent("workflow")) {
                        $deleted = $model->delete();
                    }
                    if ($deleted) {
                        $this->deleteImages($model);
                        $messages['success'][] = AmcWm::t($msgsBase, 'Article "{article}" has been deleted', array("{article}" => $contentModel->displayTitle));
                    } else {
                        $messages['error'][] = AmcWm::t($msgsBase, 'Can not delete article "{article}"', array("{article}" => $contentModel->displayTitle));
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
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * delete article images
     * @param ActiveRecord $article
     * @return boolean
     * @access protected
     */
    protected function deleteImages(ActiveRecord $article) {
        $imageSizesInfo = $this->controller->getModule()->appModule->mediaPaths;
        if ($article->in_slider) {
            $imageInfo = $imageSizesInfo['slider'];
            $slider = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $article->article_id . "." . $article->in_slider;
            if (is_file($slider)) {
                unlink($slider);
            }
            $sliderThumb = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['thumb']['path']) . "/" . $article->article_id . "." . $article->in_slider;
            if (is_file($sliderThumb)) {
                unlink($sliderThumb);
            }
        }
        if ($article->thumb) {
            foreach ($imageSizesInfo as $imageInfo) {
                if ($imageInfo['autoSave']) {
                    $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $article->article_id . "." . $article->thumb;
                    if (is_file($imageFile)) {
                        unlink($imageFile);
                    }
                }
            }
        }
        return true;
    }

    /**
     * Get infocus list
     * @access public
     * @return array 
     */
    public function getInfocus() {
        $query = sprintf("
            select t.infocus_id, tt.header
            from infocus t
            inner join infocus_translation tt on t.infocus_id = tt.infocus_id
            where content_lang = %s", Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        $infocus = CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'infocus_id', 'header');
        $infocus[""] = Yii::t('zii', 'Not set');
        return $infocus;
    }

    /**
     * Get infocus name for the given $id
     * @access public
     * @return array 
     */
    public function getInfocucName($id) {
        $infocusName = null;
        if ($id) {
            $query = sprintf("
            select tt.header
            from infocus_translation tt 
            where tt.infocus_id = %d and content_lang = %s", $id, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
            $infocusName = Yii::app()->db->createCommand($query)->queryScalar();
        }
        return $infocusName;
    }

    /**
     * required for ajax requests
     * @param boolean $printResult
     */
    public function findArticle($printResult = true, $json = true) {
        $sectionId = (int) Yii::app()->request->getParam('sId');
        $artId = (int) Yii::app()->request->getParam('artId');
        $title = Yii::app()->request->getParam('q');
        $page = Yii::app()->request->getParam('page');
        $articles = Articles::getArticles($page, $title, $sectionId, false, $artId);
        if ($printResult) {
            header('Content-type: application/json');
            echo json_encode($articles);
        } else {
            if ($json) {
                $articles = json_encode($articles);
            }
            return $articles;
        }
    }

    /**
     * required for ajax requests
     * @param boolean $printResult
     */
    public function findWriters() {
        $writers = Writers::getWritersList(Yii::app()->request->getParam('q'), Yii::app()->request->getParam('page'), AmcWm::app()->request->getParam('prompt'));
        header('Content-type: application/json');
        echo CJSON::encode($writers);
    }

    /**
     * required for ajax requests
     */
    public function findEditors() {
        $editors = Writers::getEditorsList(Yii::app()->request->getParam('q'), Yii::app()->request->getParam('page'), AmcWm::app()->request->getParam('prompt'));
        header('Content-type: application/json');
        echo CJSON::encode($editors);
    }
    
    /**
     * required for ajax requests
     */
    public function findEditorsWriters() {
        $editors = Writers::getEditorsWritersList(Yii::app()->request->getParam('q'), Yii::app()->request->getParam('page'), AmcWm::app()->request->getParam('prompt'));
        header('Content-type: application/json');
        echo CJSON::encode($editors);
    }

    /**
     * required for ajax requests
     */
    public function findSources() {
        $list = NewsSources::getSourcesList(Yii::app()->request->getParam('q'), Yii::app()->request->getParam('page'), AmcWm::app()->request->getParam('prompt'));
        header('Content-type: application/json');
        echo CJSON::encode($list);
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

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @param string $action action to redirect to it after publish / unpublish the content
     * @param array $params paramters to append to redirect route
     * @access public 
     * @return void
     */
    public function publish($published, $action = "index", $params = array(), $loadMethod = "loadChildModel") {
        if (AmcWm::app()->hasComponent("workflow")) {
            if (!AmcWm::app()->workflow->module->hasUserSteps()) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcCore", 'You are not authorized to perform this action.')));
                $this->redirect(array('index'));
            }
        }
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
        $virtuals = AmcWm::app()->appModule->getVirtuals();
        if (isset($virtuals[$virtual]['redirectParams'])) {
            foreach ($virtuals[$virtual]['redirectParams'] as $p) {
                $params[$p] = AmcWm::app()->request->getParam($p);
            }
        }

        parent::publish($published, $action, $params, $loadMethod);
    }

    /**
     * @return boolean
     */
    public function allowApproved() {
        $baseRoute = AmcWm::app()->backendName . "/" . $virtualModule = $this->controller->getModule()->appModule->currentVirtual;
        $routes[] = $baseRoute . "/default/approvedContent";
        $routes[] = $baseRoute . "/default/approvedDelete";
        $routes[] = $baseRoute . "/default/publish";
        $allow = false;
        foreach ($routes as $route) {
            if (Yii::app()->user->checkRouteAccess(trim($route, "/"))) {
                $allow = true;
                break;
            }
        }
        return $allow;
    }
}
