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
class AmcVideosController extends AmcGalleriesController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render("view", array(
            'contentModel' => $this->loadChildModel($id),
        ));
    }

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'galleryContext';
        return $filters;
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper gallery context
     */
    public function filterGalleryContext($filterChain) {
        //set the project identifier based on either the GET or POST input request variables, since we allow both types for our actions   
        $galleryId = null;
        if (isset($_GET['gid']))
            $galleryId = $_GET['gid'];
        else
        if (isset($_POST['gid']))
            $galleryId = $_POST['gid'];

        $this->loadGallery($galleryId);
        //complete the running of other filters and execute the requested action
        $filterChain->run();
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Videos;
        $model->gallery_id = $this->gallery->getParentContent()->gallery_id;
        $contentModel = new VideosTranslation();
        $model->addTranslationChild($contentModel, self::getContentLanguage());
        $autoPost2social = false;
        $options = $this->module->appModule->options;
        if (isset($options['default']['check']['autoPostVideos2social'])) {
            $autoPost2social = $options['default']['check']['autoPostVideos2social'];
        }
        if (!isset($_POST['Videos']) && $autoPost2social) {
            $model->socialIds = array_keys($this->getSocials());
        }
        $this->save($contentModel);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $this->render('create', array(
            'contentModel' => $contentModel,
        ));
    }

    /**
     * Save model to database
     * @param VideosTranslation $contentModel
     * @access protected
     */
    protected function save(ChildTranslatedActiveRecord $contentModel) {

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['Videos']) && isset($_POST["VideosTranslation"])) {
                $model = $contentModel->getParentContent();
                $oldYoutube = $model->uploadedViaApi();
                $oldImageThumb = null;
                $oldVideoThumb = null;
                if ($model->internalVideos) {
                    $oldImageThumb = $model->internalVideos->img_ext;
                    $oldVideoThumb = $model->internalVideos->video_ext;
                } else {
                    $model->internalVideos = new InternalVideos;
                }
                if (!$model->externalVideos) {
                    $model->externalVideos = new ExternalVideos;
                }
                $currentVideoUrl = $model->videoURL;
                $tagsItems = array();
                if (isset($_POST["VideosTranslation"]["tags"]) && is_array($_POST["VideosTranslation"]["tags"])) {
                    $tagsItems = $_POST["VideosTranslation"]["tags"];
                    $tags = implode(PHP_EOL, $_POST["VideosTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["VideosTranslation"]["tags"] = $tags;
                $model->attributes = $_POST['Videos'];
                $contentModel->attributes = $_POST['VideosTranslation'];
                $currentAtributes = $contentModel->getOnlineAttributes();
                $model->videoFile = CUploadedFile::getInstance($model, 'videoFile');
                $model->videoThumb = CUploadedFile::getInstance($model, 'videoThumb');
                $model->youtubeFile = CUploadedFile::getInstance($model, 'youtubeFile');
                $validate = $model->validate();
                $validate &= $contentModel->validate();
                $youtubeTags = array();
                $transaction = Yii::app()->db->beginTransaction();
                $success = false;
                $saved = false;
                $youtubeUploadedError = false;
                $youtubeApi = null;
                $firstContentLang = $model->getFirstInsertedLang();
                $mediaPaths = $this->getModule()->appModule->mediaPaths;
                if ($validate) {
                    try {
                        $saved = $model->save();
                        $saved &= $contentModel->save();
                        if ($model->isEexternal() && $this->getModule()->appModule->youtubeApiIsEnabled() && (!$firstContentLang || $firstContentLang == $contentModel->content_lang)) {
                            $youtubeApi = VendorApiManager::getApi("youtube", "manage", array(), AmcWm::app()->params['proxy']);
                            foreach ($tagsItems as $tag) {
                                if (Html::utfStringLength($tag) < 15) {
                                    $youtubeTags[] = $tag;
                                }
                            }
                        }

                        if ($saved) {
                            switch ($model->videoType) {
                                case Videos::EXTERNAL:
                                    if ($youtubeApi) {
                                        if ($model->youtubeFile instanceof CUploadedFile) {
                                            if (!$model->externalVideos->isNewRecord && $model->externalVideos->uploaded_via_api) {
                                                $videoCode = Html::getVideoCode($model->videoURL);
                                                if ($videoCode) {
                                                    $youtubeApi->deleteVideo($videoCode);
                                                }
                                            } else {
                                                $model->externalVideos->uploaded_via_api = 1;
                                                $savedFile = $youtubeApi->getUploadPath() . DIRECTORY_SEPARATOR . basename($model->youtubeFile->getTempName()) . "." . strtolower($model->youtubeFile->getExtensionName());
                                                $model->youtubeFile->saveAs($savedFile);
                                                $video = $youtubeApi->browserUpload($contentModel->video_header, Html::utfSubstring($contentModel->description, 0, 200), $this->getModule()->appModule->options['youtubeApi']['text']['defaultCategory'], implode(",", $youtubeTags), $savedFile);
                                                if ($video instanceof stdClass && $video->htmlStatus == "200" && $video->code != null && !$video->errorContent && !$video->error) {
                                                    $model->videoURL = "http://www.youtube.com/watch?v=" . $video->code;
                                                } else {
                                                    $youtubeUploadedError = true;
                                                    $saved = false;
                                                    if (isset($video->errorContent)) {
                                                        $model->addError('youtubeFile', AmcWm::t("msgsbase.core", 'Error while trying sending data to Youtube api "{reason}", please try again later', array('{reason}' => $video->errorContent)));
                                                    }
                                                }
                                                unlink($savedFile);
                                            }
                                        }
                                    } else if (strtolower(trim($currentVideoUrl, "/")) != strtolower(trim($model->videoURL, "/"))) {
                                        $model->externalVideos->uploaded_via_api = 0;
                                    }
                                    $model->externalVideos->setAttribute('video_id', $model->video_id);
                                    $model->externalVideos->setAttribute('video', $model->videoURL);
                                    if ($saved) {
                                        $saved = $model->externalVideos->save();
                                    }
                                    if (!$model->internalVideos->isNewRecord) {
                                        $model->internalVideos->delete();
                                    }
                                    break;
                                case Videos::INTERNAL:
                                    if ($model->videoFile instanceof CUploadedFile) {
                                        $model->internalVideos->setAttribute('video_ext', strtolower($model->videoFile->getExtensionName()));
                                    }
                                    if ($model->videoThumb instanceof CUploadedFile) {
                                        $model->internalVideos->setAttribute('img_ext', strtolower($model->videoThumb->getExtensionName()));
                                    }
                                    $model->internalVideos->setAttribute('video_id', $model->video_id);
                                    $saved &= $model->internalVideos->save();
                                    if (!$model->externalVideos->isNewRecord) {
                                        $model->externalVideos->delete();
                                    }
                                    break;
                            }
                        }
                        if ($saved) {
                            $transaction->commit();
                            $success = true;
                        }
                    } catch (CDbException $e) {
//                        die($e->getMessage());
                        $transaction->rollback();
                        $success = false;
                    }
                    if ($success) {
                        switch ($model->videoType) {
                            case Videos::EXTERNAL:
                                if (!$model->internalVideos->isNewRecord) {
                                    $this->_deleteFiles("{$model->video_id}.{$oldImageThumb}", "{$model->video_id}.{$oldVideoThumb}");
                                }
                                if ($youtubeApi) {
                                    if ((!$model->youtubeFile instanceof CUploadedFile && !$model->externalVideos->isNewRecord) && $model->externalVideos->uploaded_via_api) {
                                        $videoCode = Html::getVideoCode($model->videoURL);
                                        $currentVideoCode = Html::getVideoCode($currentVideoUrl);
                                        if ($videoCode && $currentVideoCode == $videoCode && ($currentAtributes['video_header'] != $contentModel->video_header || $contentModel->description != $currentAtributes['description'])) {
                                            $youtubeApi->updateVideoData($contentModel->video_header, Html::utfSubstring($contentModel->description, 0, 200), $this->getModule()->appModule->options['youtubeApi']['text']['defaultCategory'], implode(",", $youtubeTags), $videoCode);
                                        }
                                    }
                                }
                                break;
                            case Videos::INTERNAL:
                                if ($this->getModule()->appModule->youtubeApiIsEnabled() && $oldYoutube) {
                                    $youtubeApi = VendorApiManager::getApi("youtube", "manage", array(), AmcWm::app()->params['proxy']);
                                    $videoCode = Html::getVideoCode($model->videoURL);
                                    if ($videoCode) {
                                        $youtubeApi->deleteVideo($videoCode);
                                    }
                                }
                                if ($model->videoFile instanceof CUploadedFile) {
                                    if ($oldVideoThumb != $model->internalVideos->video_ext) {
                                        $deletedVideo = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . "/{$model->video_id}.{$oldVideoThumb}";
                                        $deletedVideo = str_replace("{gallery_id}", $model->gallery_id, $deletedVideo);
                                        if (is_file($deletedVideo)) {
                                            unlink($deletedVideo);
                                        }
                                    }
                                    $videoFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . "/" . $model->video_id . "." . $model->internalVideos->video_ext;
                                    $videoFile = str_replace("{gallery_id}", $model->gallery_id, $videoFile);
                                    $model->videoFile->saveAs($videoFile);
                                }
                                if ($model->videoThumb instanceof CUploadedFile) {
                                    if ($oldImageThumb != $model->internalVideos->img_ext) {
                                        $deletedImage = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['thumb']['path']) . "/{$model->video_id}.{$oldImageThumb}";
                                        $deletedImage = str_replace("{gallery_id}", $model->gallery_id, $deletedImage);
                                        if (is_file($deletedImage)) {
                                            unlink($deletedImage);
                                        }
                                    }

                                    $thumbFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['thumb']['path']) . "/" . $model->video_id . "." . $model->internalVideos->img_ext;
                                    $thumbFile = str_replace("{gallery_id}", $model->gallery_id, $thumbFile);
                                    $image = new Image($model->videoThumb->getTempName());
                                    $watermarkOptions = array();
                                    $options = $this->module->appModule->options;
                                    if (isset($options['default']['watermark']['videos']['image'])) {
                                        $watermarkOptions = $options['default']['watermark']['videos'];
                                    }
                                    $image->resize($mediaPaths['videos']['thumb']['info']['width'], $mediaPaths['videos']['thumb']['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $thumbFile, array(), $watermarkOptions);
                                }
                                break;
                        }
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Video has been saved')));
                        $this->redirect(array('view', 'id' => $model->video_id, 'gid' => $model->gallery_id));
                    } else {
                        if (!$youtubeUploadedError) {
                            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                        }
                    }
                }
            }
        }
    }

    private function _deleteFiles($image, $video) {
        $mediaPaths = $this->getModule()->appModule->mediaPaths;
        if ($image) {
            $deletedImage = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['thumb']['path']) . "/{$image}";
            $deletedImage = str_replace("{gallery_id}", $this->gallery->gallery_id, $deletedImage);
            if (is_file($deletedImage)) {
                unlink($deletedImage);
            }
        }
        if ($video) {
            $deletedVideo = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . "/{$video}";
            $deletedVideo = str_replace("{gallery_id}", $this->gallery->gallery_id, $deletedVideo);
            if (is_file($deletedVideo)) {
                unlink($deletedVideo);
            }
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
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.     
     */
    public function actionDelete() {
        if (Yii::app()->request->isPostRequest) {
            $messages = array(
                'success' => array(),
                'errors' => array()
            );
            $ids = Yii::app()->request->getParam('ids');
            $mediaPaths = $this->getModule()->appModule->mediaPaths;
            foreach ($ids as $id) {
                $contentModel = $this->loadChildModel($id);
                $model = $contentModel->getParentContent();
                $messages['success'][] = AmcWm::t("msgsbase.core", 'Video "{video}" has been deleted', array("{video}" => $contentModel->video_header));
                $video = null;
                $thumb = null;
                $deleted = $model->delete();
                //$deleted = 1;
                if ($deleted) {
                    switch ($model->videoType) {
                        case Videos::EXTERNAL:
                            if ($model->uploadedViaApi() && $this->getModule()->appModule->youtubeApiIsEnabled()) {
                                $youtubeApi = VendorApiManager::getApi("youtube", "manage", array(), AmcWm::app()->params['proxy']);
                                $videoCode = Html::getVideoCode($model->videoURL);
                                if ($videoCode) {
                                    $youtubeApi->deleteVideo($videoCode);
                                }
                            }
                            break;
                        case Videos::INTERNAL:
                            $video = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . "/" . $model->video_id . "." . $model->internalVideos->video_ext;
                            $video = str_replace("{gallery_id}", $model->gallery_id, $video);
                            if (is_file($video)) {
                                unlink($video);
                            }
                            if (isset($model->internalVideos->img_ext)) {
                                $thumb = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['thumb']['path']) . "/" . $model->video_id . "." . $model->internalVideos->img_ext;
                                $thumb = str_replace("{gallery_id}", $model->gallery_id, $thumb);
                                if (is_file($thumb)) {
                                    unlink($thumb);
                                }
                            }
                            break;
                    }
                }
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index', 'gid' => $this->gallery->gallery_id));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Videos('search');
        $model->unsetAttributes();  // clear any default values
        $model->unsetTranslationsAttributes();  // clear any default values
        $model->addTranslationChild(new VideosTranslation('search'), self::getContentLanguage());
        $contentModel = $model->getTranslated(self::getContentLanguage());
        if ($contentModel) {
            if (isset($_GET['Videos'])) {
                $model->attributes = $_GET['Videos'];
            }
            if (isset($_GET['VideosTranslation'])) {
                $contentModel->attributes = $_GET['VideosTranslation'];
            }
            $model->gallery_id = $contentModel->gallery_id = $this->gallery->getParentContent()->gallery_id;

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
    public function loadModel($id) {
        $model = Videos::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'videos-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
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
        $this->publish($published, "index", array('gid' => $this->gallery->getParentContent()->gallery_id));
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
        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Item "{item}" has been sorted', array("{item}" => $model->getCurrent()->video_header))));
        $this->redirect(array('index', 'gid' => $model->gallery_id));
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
            if (isset($_POST["VideosTranslation"])) {
                if (isset($_POST["VideosTranslation"]["tags"]) && is_array($_POST["VideosTranslation"]["tags"])) {
                    $tags = implode(PHP_EOL, $_POST["VideosTranslation"]["tags"]);
                } else {
                    $tags = null;
                }
                $_POST["VideosTranslation"]["tags"] = $tags;
                $translatedModel->attributes = $_POST['VideosTranslation'];
                $validate = $translatedModel->validate();
                if ($validate) {
                    if ($translatedModel->save()) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("amcTools", 'Content has been translated')));
                        $this->redirect(array('view', 'id' => $model->video_id, 'gid' => $model->gallery_id));
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Galleries $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return GalleriesTranslation
     */
    public function loadTranslatedSheetModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = DopeSheetTranslation::model()->findByPk(array("video_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new DopeSheetTranslation();
                $translatedModel->video_id = $model->video_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param Galleries $model parent content model
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return GalleriesTranslation
     */
    public function loadTranslatedModel($model, $id) {
        $translatedModel = null;
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $langs = $this->getTranslationLanguages();
            $translationLang = Yii::app()->request->getParam("tlang", key($langs));
            $translatedModel = VideosTranslation::model()->findByPk(array("video_id" => (int) $id, 'content_lang' => $translationLang));
            if ($translatedModel === null) {
                $translatedModel = new VideosTranslation();
                $translatedModel->video_id = $model->video_id;
                $model->addTranslationChild($translatedModel, $translationLang);
            }
        }
        return $translatedModel;
    }

    /**
     * Performs the comments action
     * @param int $imId
     * @access public 
     * @return void
     */
    public function actionComments($item) {
        $this->forward('videosComments/');
    }

    public function actionDopeSheet($mmId) {
        $contentModel = $this->loadChildModel($mmId);
        $sheetContentModel = $contentModel->initDopeSheet();
        $sheetModel = $sheetContentModel->getParentContent();

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['DopeSheet']) && isset($_POST['DopeSheetTranslation'])) {
                $sheetModel->attributes = $_POST['DopeSheet'];
                $sheetContentModel->attributes = $_POST['DopeSheetTranslation'];
                $validate = $sheetModel->validate();
                $validate &= $sheetContentModel->validate();
                $index = 0;
                foreach ($_POST['DopeSheetShots'] as $optionIndex => $option) {
                    if (isset($_POST['DopeSheetShotsTranslation'][$optionIndex])) {
                        $shot = DopeSheetShots::model()->findByPk($option['shot_id']);
                        if ($shot === null) {
                            $shot = new DopeSheetShots();
                            $shot->video_id = $sheetContentModel->video_id;
                        }
                        $shotContent = DopeSheetShotsTranslation::model()->findByPk(array("shot_id" => $option['shot_id'], 'content_lang' => $sheetContentModel->content_lang));
                        if ($shotContent === null) {
                            $shotContent = new DopeSheetShotsTranslation();
                            $shot->addTranslationChild($shotContent, $sheetContentModel->content_lang);
                        }
                        $shot->attributes = $option;
                        $validate &= $shot->validate();
                        $shotContent->attributes = $_POST['DopeSheetShotsTranslation'][$optionIndex];
                        $validate &= $shotContent->validate();
                        $sheetModel->addRelatedRecord("shots", $shot, $index++);
                    }
                }
                if (!$index) {
                    $validate = false;
                    $sheetModel->addError("reporter", AmcWm::t("msgsbase.core", "Error, please enter dope sheet shots"));
                }
                $transaction = Yii::app()->db->beginTransaction();
                $success = false;
                if ($validate) {
                    try {
                        $success = $sheetModel->save();
                        $success &= $sheetContentModel->save();
                        if ($success) {
                            foreach ($sheetModel->shots as $shotModel) {
                                $success &= $shotModel->save();
                                $success &= $shotModel->getTranslated($sheetContentModel->content_lang)->save();
                            }
                            if ($success && !$sheetModel->isNewRecord && isset($_POST['DopeSheetShotsRemoved'])) {
                                $removedShots = array();
                                foreach ($_POST['DopeSheetShotsRemoved'] as $removedId) {
                                    $removedShots[] = (int) $removedId;
                                }
                                if (count($removedShots)) {
                                    Yii::app()->db->createCommand("delete from dope_sheet_shots where shot_id in(" . implode(",", $removedShots) . ")")->execute();
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
                    }
                    if ($success) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Dope Sheet has been saved')));
                        $this->redirect(array('index', 'mmId' => $contentModel->video_id, 'gid' => $this->gallery->gallery_id));
                    }
                }
            }
        } else {
            foreach ($sheetModel->shots as $shot) {
                if (!$shot->isNewRecord) {
                    $shotContent = DopeSheetShotsTranslation::model()->findByPk(array("shot_id" => $shot->shot_id, 'content_lang' => $sheetContentModel->content_lang));
                    if ($shotContent === null) {
                        $shotContent = new DopeSheetShotsTranslation();
                        $shotContent->shot_id = $shot->shot_id;
                        $shot->addTranslationChild($shotContent, $sheetContentModel->content_lang);
                    }
                }
            }
        }
        $this->render('dopeSheet', array(
            'contentModel' => $contentModel,
            'sheetContentModel' => $sheetContentModel,
        ));
    }

    public function actionDopeSheetTranslate($mmId) {
        $contentModel = $this->loadChildModel($mmId);
        $sheetContentModel = $contentModel->initDopeSheet();
        $sheetModel = $sheetContentModel->getParentContent();
        $sheetTranslatedModel = $this->loadTranslatedSheetModel($sheetModel, $mmId);
        $shotsTranslated = array();
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['DopeSheetTranslation'])) {
                $sheetTranslatedModel->attributes = $_POST['DopeSheetTranslation'];
                $validate = $sheetTranslatedModel->validate();
                $index = 0;
                foreach ($sheetModel->shots as $shot) {
                    if (isset($_POST['DopeSheetShotsTranslation'][$shot->shot_id])) {
                        $shotTranslated = DopeSheetShotsTranslation::model()->findByPk(array("shot_id" => $shot->shot_id, 'content_lang' => $sheetTranslatedModel->content_lang));
                        if ($shotTranslated === null) {
                            $shotTranslated = new DopeSheetShotsTranslation();
                            $shotTranslated->shot_id = $shot->shot_id;
                        }
                        $shotTranslated->attributes = $_POST['DopeSheetShotsTranslation'][$shot->shot_id];
                        $shotTranslated->content_lang = $sheetTranslatedModel->content_lang;
                        $validate &= $shotTranslated->validate();
                        $shot->addTranslationChild($shotTranslated, $sheetTranslatedModel->content_lang);
                        $shotsTranslated[$shot->shot_id] = $shotTranslated;
                        $index++;
                    }
                }
                if (!$index) {
                    $validate = false;
                    $sheetTranslatedModel->addError("reporter", AmcWm::t("msgsbase.core", "Error, please enter dope sheet shots"));
                }
                $transaction = Yii::app()->db->beginTransaction();
                $success = false;
                if ($validate) {
                    try {
                        $success = $sheetTranslatedModel->save();
                        if ($success) {
                            foreach ($shotsTranslated as $shotModel) {
                                $success &= $shotModel->save();
                            }
                            if ($success) {
                                $transaction->commit();
                            }
                        }
                    } catch (CDbException $e) {
                        $transaction->rollback();
                        $success = false;
                        Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    }
                    if ($success) {
                        Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("msgsbase.core", 'Dope Sheet has been saved')));
                        $this->redirect(array('index', 'mmId' => $contentModel->video_id, 'gid' => $this->gallery->gallery_id));
                    }
                }
            }
        } else {
            foreach ($sheetModel->shots as $shot) {
                $shotTranslated = DopeSheetShotsTranslation::model()->findByPk(array("shot_id" => $shot->shot_id, 'content_lang' => $sheetTranslatedModel->content_lang));
                if ($shotTranslated === null) {
                    $shotTranslated = new DopeSheetShotsTranslation();
                    $shotTranslated->content_lang = $sheetTranslatedModel->content_lang;
                    $shotTranslated->shot_id = $shot->shot_id;
                }
                $shot->addTranslationChild($shotTranslated, $sheetTranslatedModel->content_lang);
            }
        }
        $this->render('translatedDopeSheet', array(
            'contentModel' => $contentModel,
            'sheetTranslatedModel' => $sheetTranslatedModel,
            'sheetContentModel' => $sheetContentModel,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param string the ID of the model to be loaded , Id send as $id = pk1, pk2
     * @return SectionsTranslation
     */
    public function loadChildModel($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $model = VideosTranslation::model()->findByPk(array("video_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

}
