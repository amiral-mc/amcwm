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
class HomeFrontendController extends FrontendController {

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
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->isHomePage = true;
        Yii::app()->clientScript->registerMetaTag(Yii::app()->params['custom']['front']['site']['description'], "description");
        Yii::app()->clientScript->registerMetaTag(Data::getInstance()->generatHomeKeywords(), "keywords");
        if (Html::isFacebook()) {
            Yii::app()->clientScript->registerMetaTag(Yii::app()->params['custom']['front']['site']['title'], "og:title");
            Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
        }
        $this->render('index');
//        print_r(Yii::app()->db->createCommand('SHOW PROFILES;')->queryAll());
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Log hits
     */
    public function actionLogHits() {
        $tables = array("article_id" => "articles", "image_id" => "images", "video_id" => "videos");
        if (isset($tables[$_POST['t']]) && isset($_POST['id'])) {
            $id = $_POST['id'];
            $table = $tables[$_POST['t']];
            $table_id = str_replace("'", "", Yii::app()->db->quoteValue($_POST['t']));
            $cookieName = "{$table}_hits_{$id}";
            $updated = 0;
            if (!isset(Yii::app()->request->cookies[$cookieName]->value)) {
                $updated = Yii::app()->db->createCommand("update {$table} set hits=hits+1 where {$table_id}=:id")->execute(array(":id" => $id));
                $cookie = new CHttpCookie($cookieName, $cookieName);
                $cookie->expire = time() + 3600;
                $cookie->httpOnly = true;
                Yii::app()->request->cookies[$cookieName] = $cookie;
            }
        }
        header("Content-type:application/json");
        echo CJSON::encode(array('updated'=>$updated));
        Yii::app()->end();
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        $class = (AmcWm::app()->frontend['bootstrap']['use'] || AmcWm::app()->frontend['bootstrap']['customUse']) ? "success" : "contact";
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}" . "\r\n";
                if ($model->contact) {
                    $contactData = $model->getContactData($model->contact);
                    $toEmail = $contactData['email'];
//                  // $headers .= 'Cc: ' . $ccEmail . "\r\n";
                } else {
                    $toEmail = Yii::app()->params['adminEmail'];
                }
                mail($toEmail, $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash($class, Yii::t('contact', 'Thank you for contacting us. We will respond to you as soon as possible.'));
                $this->refresh();
            }
        } else {
            $model->contact = AmcWm::app()->request->getParam('dep');
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (Yii::app()->user->isGuest) {
            $model = $this->loginModel();
            // if it is ajax validation request

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($this->loginModel());
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];

                // validate user input and redirect to the previous page if valid
                if ($this->loginModel()->validate() && $this->loginModel()->login()) {
                    $this->redirect(Yii::app()->user->returnUrl);
                }
            } elseif (!Yii::app()->user->allowLoginView) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-error', 'content' => AmcWm::t("amcFront", 'You are not authorized to view this page, please login first.')));
            }

            if (Yii::app()->user->allowLoginView) {
                $this->render('login', array('model' => $model));
            } else {
                $this->render('index');
            }
        } else {
            $this->forward('index');
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionVote($view = 0) {
        if ($view) {
            Votes::getInstance($this)->viewResults();
        } else {
            Votes::getInstance($this)->save();
        }
    }

    public function actionVoteResults() {
        Votes::getInstance($this)->viewResults(true);
    }

    public function ajaxServices() {
        $city = AmcWm::app()->request->getParam("city", 1);
        $query4Services = sprintf("
            
        select currency_code        
        from countries
        inner join services_cities cities on countries.code = cities.country_code 
        where city_id=%d limit 0, 1", $city);
        $code = Yii::app()->db->createCommand($query4Services)->queryScalar();
        header('Content-type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';

        $weather = new WeatherInfoData($city);
        $curruncies = new CurrunciesInfo($code);
        $weather->generate();
        $curruncies->generate();
        echo '<services>';
        echo '<weather>';
        echo '<![CDATA[';
        echo $weather->draw();
        echo ']]>';
        echo '</weather>';
        echo '<curruncies>';
        echo '<![CDATA[';
        echo $curruncies->generate();
        echo ']]>';
        echo '</curruncies>';
        echo '</services>';

        Yii::app()->end();
    }

    public function actionSearch($q) {
        $contentType = Yii::app()->request->getParam('ct', 'news');
        $keywords = Yii::app()->request->getParam('q');
        $search = new SearchData($keywords, $contentType, 10);
        $search->setAdvancedParam('contentType', array('news' => 1, 'articles' => 1, 'essays' => 1, 'videos' => 1, 'images' => 1));
        $search->generate();
        $this->render('search', array(
            'page' => (int) Yii::app()->request->getParam('page', 1),
            'contentType' => $search->getContentType(),
            'searchData' => $search->getResults(),
            'advancedParams' => $search->getAdvancedParam(),
            'keywords' => $keywords,
            'routers' => Yii::app()->params['routers'],
        ));
    }

    public function actionStockData() {
        //$uaeStockData = new StockInfo;
        //echo $uaeStockData->generate();
        Yii::app()->end();
    }

    public function actionDownload($f) {
        $ds = DIRECTORY_SEPARATOR;
        $f = trim(str_replace('\\', '/', $f), "/");
        $webroot = Yii::getPathOfAlias("webroot");
        $paths = array(
            'files',
            'multimedia'
        );
        foreach ($paths as $path) {
            $file = strstr($f, "{$path}/");
            $downloadPath = "{$webroot}{$ds}{$path}";
            if ($file) {
                break;
            }
        }
        if (!$file) {
            $file = "files/{$f}";
            $downloadPath = "{$webroot}{$ds}files";
        }
        $fileRealPath = realpath(Yii::app()->basePath . "{$ds}..{$ds}{$file}");
        if (strpos($fileRealPath, $downloadPath) === 0 && is_file($fileRealPath)) {
            Yii::app()->request->sendFile(basename($fileRealPath), file_get_contents($fileRealPath));
        } else {
            throw new CHttpException(404, AmcWm::t('amcFront', 'The requested file is not exist'));
        }
    }

    public function actionSiteMap() {
        $this->layout = null;
        $this->render('siteMap', array('siteMapItems' => SiteMap::getInstance()->getItems()));
    }

    public function actionGetSideItemsList() {
        $sisterOptions = Yii::app()->request->getParam("params");
        $sister = new SistersRelatedManager($sisterOptions['type'], $sisterOptions['id']);
        if ($sister->hasItems()) {
            $this->widget('amcwm.widgets.ItemsSideList', array(
                'id' => "sister_widget_{$sisterOptions['type']}_{$sisterOptions['id']}",
                'items' => $sister->getItems(),
                'params' => $sisterOptions,
                'contentOnly' => true,
            ));
        }
    }

    /**
     * ajaxWeather retrun the weather forcasting 
     * to the ajax requests when change the cities
     */
    public function ajaxWeather() {
        echo WeatherInfo::execute("widgets.WeatherWidget", array('defaultCity' => 1), array('contentOnly' => true), true);
        Yii::app()->end();
    }

}
