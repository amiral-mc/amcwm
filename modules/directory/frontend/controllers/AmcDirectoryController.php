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

class AmcDirectoryController extends FrontendController {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'height' => 30,
                'padding' => 0,
                'width' => 90,
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    /**
     *
     * @var event params 
     */
    protected $eventParams = array();

    /**
     * get directory categories;
     * @return array
     */
    protected function getDirectoryCategories(&$directory) {
        $menu = Yii::app()->request->getParam('menu');
        $id = Yii::app()->request->getParam('id');
        if ($menu && $id) {
            $parentCategoriesData = $directory->parentCategoryData();
            $return['viewAll'] = $parentCategoriesData['category_name'];
            $return['dirCategories'] = $directory->getAllCategories($id);
            if (!$return['dirCategories']) {
                $return['dirCategories'][$id] = $parentCategoriesData['category_name'];
            }
//            $dirCategories = array();
//            $dirCategories[$id] = $parentCategoriesData['category_name'];
//            foreach ($dirCategoriesDataset as $catId=>$catName){                
//                $dirCategories[$catId] = $catName;
//            }
        } else {
            $return['viewAll'] = AmcWm::t("msgsbase.core", 'View all');
            $return['dirCategories'] = $directory->getAllCategories();
        }
        return $return;
    }

    public function actionIndex() {
        $keywords = AmcWm::app()->request->getParam('q');
        $category = (int) Yii::app()->request->getParam('c');
        $parentCategory = (int) Yii::app()->request->getParam('id');
        if (!$category) {
            $category = $parentCategory;
        }
        $directory = new DirectoryData($keywords, $category, 5);
        $directory->setAdvancedParam("selectedCategory", Yii::app()->request->getParam('c'));
        $directory->setAdvancedParam("parentCategory", $parentCategory);
        $directory->generate();
        $categories = $this->getDirectoryCategories($directory);

        $this->render('directory', array(
            'category' => $category,
            'page' => (int) Yii::app()->request->getParam('page', 1),
            'dirCategories' => $categories['dirCategories'],
            'viewAll' => $categories['viewAll'],
            'directoryData' => $directory->getResults(),
            'advancedParams' => $directory->getAdvancedParam(),
            'keywords' => $directory->getKeywords(),
            'alphabet' => $directory->getAlphabet()
        ));
    }

    /**
     * Save model to database
     * @param DirCompanies $model
     * @access protected
     * @todo {'location':[{'lng':-31.00019509,'lat':-29.000879376,'zoom':15}], 'image':'jpg'}
     */
    protected function save(DirCompaniesTranslation $contentModel) {
        $model = $contentModel->getParentContent();
        $contentModel->attachBehavior("extendableBehaviors", new ExtendableAttributesBehaviors());
        /*
         * inisialize maps data
         */
        $mapsData = array('location' => array('lng' => '', 'lat' => '', 'zoom' => '', 'enabled' => false), 'image' => '');
        if (isset($_POST['DirCompanies']) && isset($_POST["DirCompaniesTranslation"])) {
            $transaction = Yii::app()->db->beginTransaction();
            $oldThumb = $model->image_ext;
            $oldFile = $model->file_ext;
//            $contentModel->oldAttributes
            $oldMapImage = null;
            $mapsData = CJSON::decode($model->maps);
            if (isset($mapsData['image']) && $mapsData['image'] != '')
                $oldMapImage = $mapsData['image'];
            $_POST['DirCompaniesTranslation']['description'] = strip_tags($_POST['DirCompaniesTranslation']['description'], "<div>, <p>, <i>, <span>, <strong>,<b>");
            $model->attributes = $_POST['DirCompanies'];
            $contentModel->attributes = $_POST['DirCompaniesTranslation'];
            $deleteImage = Yii::app()->request->getParam('deleteImage');
            $model->imageFile = CUploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile instanceof CUploadedFile) {
                $model->setAttribute('image_ext', $model->imageFile->getExtensionName());
            } else if ($deleteImage) {
                $model->setAttribute('image_ext', null);
            }
            $deleteFile = Yii::app()->request->getParam('deleteFile');
            $model->attachFile = CUploadedFile::getInstance($model, 'attachFile');
            if ($model->attachFile instanceof CUploadedFile) {
                $model->setAttribute('file_ext', $model->attachFile->getExtensionName());
            } else if ($deleteFile) {
                $model->setAttribute('file_ext', null);
            }

            /*             * *********** */
            $deleteMap = Yii::app()->request->getParam('deleteMap');
            $model->mapFile = CUploadedFile::getInstance($model, 'mapFile');
            if ($model->mapFile instanceof CUploadedFile) {
                $mapsData['image'] = $model->mapFile->getExtensionName();
            } else if ($deleteMap) {
                $mapsData['image'] = '';
            }

            if (isset($_POST['lat']) && isset($_POST["lng"]) && isset($_POST['zoom']) && $_POST['zoom'] != 1) {
                $mapsData['location']['lat'] = $_POST['lat'];
                $mapsData['location']['lng'] = $_POST['lng'];
                $mapsData['location']['zoom'] = $_POST['zoom'];
            }

            if (isset($_POST['enabled'])) {
                $mapsData['location']['enabled'] = true;
            } else {
                $mapsData['location']['enabled'] = false;
            }
//            die(CJSON::encode($mapsData));
            $model->setAttribute('maps', CJSON::encode($mapsData));
            /*             * ********************** */

            $contentModel->onValidateOthers = array($this, 'customValidate');
            $contentModel->onAfterSave = array($this, 'customSave');
            $this->eventParams = array('model' => $model, 'contentModel' => $contentModel);
            $validate = $model->validate();
            $validate &= $contentModel->validate();
            $model->published = 0;
            $model->accepted = 0;
            $model->in_ticker = 0;
            $model->registered = 1;
            if ($validate) {
                try {
                    if ($model->save()) {
                        if ($contentModel->save()) {
                            $userInfo = $model->getUserInfo();
                            if(isset($userInfo['user_id'])){
                                $model->user_id = $userInfo['user_id'];
                            }
                            $transaction->commit();
                            $this->saveThumb($model, $oldThumb);
                            $this->saveFile($model, $oldFile);
                            $this->saveMap($model, $mapsData, $oldMapImage);
                            if (isset($this->module->appModule->options['default']['text']['subscriptoinRedirectUrl'])) {
                                $redirect = $this->module->appModule->options['default']['text']['subscriptoinRedirectUrl'];
                            } else {
                                $redirect = "/" . AmcWm::app()->defaultController . "/index";
                            }
                            Yii::app()->mail->sender->ClearAllRecipients();
                            Yii::app()->mail->sender->Subject = AmcWm::t("msgsbase.core", "New Company Registration");
                            Yii::app()->mail->sender->AddAddress(AmcWm::t("msgsbase.core", "New Company Registration"));
                            if (isset($this->module->appModule->options['default']['text']['adminEmail'])) {
                                $to = $this->module->appModule->options['default']['text']['adminEmail'];
                            } else {
                                $to = AmcWm::app()->params['adminEmail'];
                            }
                            Yii::app()->mail->sender->SetFrom($to);
                            $link = Html::createUrl('/backend/directory/requests/view', array('id' => $model->company_id));
                            $ok = Yii::app()->mail->sendView("application.views.email.directory.{$contentModel->content_lang}.companyCreated", array('company' => $contentModel->company_name, 'link' => $link));

                            Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => AmcWm::t("app", '_directory_register_thanks_')));
                            $this->redirect(array($redirect, '#' => "message"));
                        }
                    }
                } catch (CDbException $e) {
                    //die($e->getMessage());
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcTools", "Can't save record")));
                    //$this->refresh();
                }
            }
        }
        else{       
            $userInfo = $model->getUserInfo();
            $model->email = (isset($userInfo['email'])) ? $userInfo['email'] : "";
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionRequest() {
        $allOptions = $this->module->appModule->options;
        if (!Yii::app()->user->isGuest) {
            $id = (int) Yii::app()->user->getId();
            $dir = DirCompanies::model()->findByAttributes(array('user_id' => $id));
            if($dir !== null){
                $this->redirect(array('/users/default/index'));
            }
        }
        if ($allOptions['system']['check']['requestsEnable']) {
            $model = new DirCompanies;
            $model->setScenario('subscribe');
            $contentModel = new DirCompaniesTranslation();
            $contentModel->setScenario('subscribe');
            $model->addTranslationChild($contentModel, self::getContentLanguage());
            $this->save($contentModel);
            $this->render('request', array(
                'contentModel' => $contentModel,
            ));
        } else {
            $this->redirect(array("index"));
        }
    }

    /**
     * action list all articles related to the givin directory Id
     * @param int $id
     */
    public function actionView($id) {
        $directory = new DirectoryItemData($id, true);
        if ($directory->getCount()) {
            $this->sisterOptions = array('type' => 'directory', 'id' => (int) $id);
            $this->render('view', array(
                'articles' => $directory->getArticles(),
//            'page' => (int) Yii::app()->request->getParam('page', 1),
                'directoryData' => $directory->getDirectory()
            ));
        } else {
            throw new CHttpException(404, AmcWm::t('amcCore', 'The requested page does not exist.'));
        }
    }

    /**
     * action list all articles related to the givin directory Id
     * @param int $id
     */
    public function actionCountryList() {
        $directory = $directoryData = $countryData = null;
        $countryCode = AmcWm::app()->request->getParam('code');
        if ($countryCode) {
            $directory = new DirectoryData();
            $directory->setAdvancedParam('nationality', AmcWm::app()->db->quoteValue($countryCode));
            $directory->generate();
            $directoryData = $directory->getResults();
            $this->sisterOptions = array('type' => 'directoryCountries', 'id' => $countryCode);
        } else {
            $directory = new DirectoryCountriesData();
            $directory->generate();
            $countryData = $directory->getItems();
        }

        $this->render('countryList', array(
            'page' => (int) Yii::app()->request->getParam('page', 1),
            'directoryData' => $directoryData,
            'countryData' => $countryData
        ));
    }

    /**
     * action list all articles related to the givin directory Id
     * @param int $id
     */
    public function actionViewArticle($id, $dir) {

        $directory = new DirectoryItemData($dir, true);
        $this->sisterOptions = array('type' => 'directory', 'id' => (int) $id);
        $directoryArticles = $directory->getArticles();
        $directoryData = $directory->getDirectory();

        $article = new ArticleData($id);
        $virtualCommentsRoute = $article->getModuleName() . "/comments/index";
        if (!$article->recordIsFound()) {
            Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => AmcWm::t("amcFront", 'Sorry, the requested content is not found, or has been deleted, please check another page.')));
            $this->redirect(array('/site/index'), true, 301);
        }

        /**
         * Render the design params
         */
        $articleRecord = $article->getArticle();
        $articlesRelated = $directoryArticles;
        $articleModule = $article->getModuleName();
//        $this->swapPosition(4, 1);
//        $this->unsetPosition(3);
        $this->sisterOptions = array('type' => 'directoryArticles', 'id' => $id);

//        $breadcrumbs = null;
        $breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/directory/default/countryList', 'id' => '2'), false);

        $this->allowedPositions();
        $this->render('viewArticle', array(
            'articleRecord' => $articleRecord,
            'articlesRelated' => $articlesRelated,
            'articleModule' => $articleModule,
            'commentsModel' => null,
            'repliesModel' => null,
            'breadcrumbs' => $breadcrumbs,
            'viewComments' => Yii::app()->user->checkRouteAccess($virtualCommentsRoute),
            'directoryArticles' => $directoryArticles,
            'directoryData' => $directoryData,
        ));
    }

    /**
     * Save thumb images
     * @param ActiveRecord $section
     * @param string $oldFile
     * @return void
     * @access protected
     */
    protected function saveFile(ActiveRecord $model, $oldFile) {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $deleteFile = Yii::app()->request->getParam('deleteFile');
        $dir = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']);
        if ($model->attachFile instanceof CUploadedFile) {
            $attachFile = $dir . DIRECTORY_SEPARATOR . $model->company_id . "." . $model->file_ext;
            if ($oldFile != $model->file_ext && $oldFile) {
                unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']) . "/" . $model->company_id . "." . $oldFile);
            }
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $model->attachFile->saveAs($attachFile);
        } else if ($deleteFile && $oldFile) {
            @unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path']) . "/" . $model->company_id . "." . $oldFile);
        }
    }

    /**
     * Save thumb images
     * @param ActiveRecord $section
     * @param string $oldThumb
     * @return void
     * @access protected
     */
    protected function saveThumb(ActiveRecord $directory, $oldThumb) {
        $imageSizesInfo = $this->getModule()->appModule->mediaPaths;
        $deleteImage = Yii::app()->request->getParam('deleteImage');
        if ($directory->imageFile instanceof CUploadedFile) {
            $image = new Image($directory->imageFile->getTempName());
            foreach ($imageSizesInfo as $imageInfo) {
                if ($imageInfo['info']['isImage']) {
                    $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $directory->company_id . "." . $directory->image_ext;
                    if ($oldThumb != $directory->image_ext && $oldThumb) {
                        unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $directory->company_id . "." . $oldThumb);
                    }
                    if ($imageInfo['info']['crob']) {
                        $image->resizeCrop($imageInfo['info']['width'], $imageInfo['info']['height'], $imageFile);
                    } else {
                        $image->resize($imageInfo['info']['width'], $imageInfo['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $imageFile);
                    }
                }
            }
        } else if ($deleteImage && $oldThumb) {
            foreach ($imageSizesInfo as $imageInfo) {
                if ($imageInfo['info']['isImage']) {
                    unlink(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $directory->company_id . "." . $oldThumb);
                }
            }
        }
    }

    /**
     * Save map image
     * @param ActiveRecord $section
     * @param string $oldFile
     * @return void
     * @access protected
     */
    protected function saveMap(ActiveRecord $model, $mapsData, $oldMapImage) {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $deleteMap = Yii::app()->request->getParam('deleteMap');
        $dir = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['maps']['path']);
        if ($model->mapFile instanceof CUploadedFile) {
            $attachMap = $dir . DIRECTORY_SEPARATOR . $model->company_id . "." . $mapsData['image'];
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if ($oldMapImage && $mapsData['image'] != $oldMapImage && is_file($dir . "/" . $model->company_id . "." . $oldMapImage)) {
                @unlink($dir . "/" . $model->company_id . "." . $oldMapImage);
            }
            $model->mapFile->saveAs($attachMap);
        } else if ($deleteMap && $oldMapImage) {
            if (is_file($dir . "/" . $model->company_id . "." . $oldMapImage)) {
                @unlink($dir . "/" . $model->company_id . "." . $oldMapImage);
            }
        }
    }

    /**
     * Draw extended output values
     * @param array $data
     * @param string $field
     * @param boolean $inBlock
     * @param string $label
     * @param string $extraData      
     * @return string
     */
    public function drawExtended($data, $filed, $inBlock = true, $label = null, $extraData = null) {
        $output = null;


        if (isset($data[$filed])) {
            $blank = !is_numeric($data[$filed]) && empty($data[$filed]);
            if (!$blank) {
                if ($label) {
                    $label = "{$label}:&nbsp;";
                }
                if (!$inBlock) {
                    $output .= "<div>{$label}{$data[$filed]}{$extraData}</div>";
                    if (isset($data['extended'][$filed]['belong'])) {
                        foreach ($data['extended'][$filed]['belong'] as $belongData) {
                            $output .= "<div>{$belongData['value']}</div>";
                        }
                    }
                    if (isset($data['extended'][$filed]['new']) && count($data['extended'][$filed]['new'])) {
                        foreach ($data['extended'][$filed]['new'] as $newMetaData) {
                            foreach ($newMetaData['data'] as $newData) {
                                $output .= "<div>{$newData['value']}</div>";
                            }
                        }
                    }
                } else {
                    $output .= "<table cellspacing='1' style='border-collapse: collapse;'>";
                    $output .= "<tr>";
                    $output .= "<td>{$label}</td>";
                    $output .= "<td>{$data[$filed]}{$extraData}</td>";
                    $output .= "</tr>";
                    if (isset($data['extended'][$filed]['belong'])) {
                        foreach ($data['extended'][$filed]['belong'] as $belongData) {
                            $output .= "<tr>";
                            $output .= "<td>&nbsp;</td>";
                            $output .= "<td>{$belongData['value']}</td>";
                            $output .= "</tr>";
                        }
                    }
                    $output .= "</table>";
                    if (isset($data['extended'][$filed]['new']) && count($data['extended'][$filed]['new'])) {
                        foreach ($data['extended'][$filed]['new'] as $newMetaData) {
                            $output .= "<table cellspacing='1' style='border-collapse: collapse;'>";
                            $output .= "<tr>";
                            $output .= "<td>" . $newMetaData['label'] . ":&nbsp;</td>";
                            $first = array_shift($newMetaData['data']);
                            if (isset($first['value'])) {
                                $output .= "<td>" . $first['value'] . "</td>";
                            } else {
                                $output .= "<td>&nbsp;</td>";
                            }
                            $output .= "</tr>";
                            foreach ($newMetaData['data'] as $newData) {
                                $output .= "<tr>";
                                $output .= "<td>&nbsp;</td>";
                                $output .= "<td>" . $newData['value'] . "</td>";
                                $output .= "</tr>";
                            }
                            $output .= "</table>";
                        }
                    }
                }
            }
        }
        return $output;
    }

    /**
     * Custom validation in extended cobtroller
     * @param CModelEvent $event
     */
    public function customValidate($event) {
        $event->isValid = true;
    }

    /**
     * Custom validation in extended cobtroller
     * @param CEvent $event
     */
    public function customSave($event) {
        
    }

}
