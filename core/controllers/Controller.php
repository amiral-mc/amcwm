<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * FrontendController, Controller is the base controller class.
 * All controller classes for this application should extend from this base class.
 * @package AmcWm.core.controllers
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Controller extends CController
{

    /**
     * @var boolean default views in project , if wqual true then the current controller action view 
     * render the view  from controller views folder other wise the controller render the view from core folder
     */
    protected $forceViewsInProject = false;

    /**
     * @var string the layout language
     */
    protected static $currentLang = '';

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     * @access public
     * @return $void
     */
    public function init()
    {
        //        static $mysqlIni = false;
//        if (!$mysqlIni) {
//            $mysqlIni = true;
//            //Yii::app()->db->createCommand("set global tx_isolation='READ-COMMITTED'")->execute();
//        }

        AmcWm::setPathOfAlias('layouts', AmcWm::app()->getLayoutPath());
        $module = $this->getModule();
        $lang = null;
        if ($module !== null) {
            $path = $module->getModulePath();
            if ($module) {
                $parentModule = AmcWm::app()->getModuleRootName($module->id);
                if ($parentModule == AmcWm::app()->backendName) {
                    $lang = AmcWm::app()->backendLang;
                }
            }
            $path = substr($path, 0, strripos($path, "modules")) . "views";
        } else {
            $path = AmcWm::app()->basePath . DIRECTORY_SEPARATOR . "views";
        }
        if ($this->forceViewsInProject) {
            $this->setViewPath($path);
        }
        self::setCurrentLanguage($lang);
        $this->setConfig();
        $windowAjax = AmcWm::app()->request->getParam(AmcWm::WINDOW_AJAX);
        if ($windowAjax) {
            $this->layout = 'layouts.simple';
        }
        parent::init();
    }

    protected function beforeAction($action)
    {
        header("X-XSS-Protection: 1;mode=block");
        header("X-Frame-Options: SAMEORIGIN");
        header("X-Content-Type-Options: nosniff");
        return parent::beforeAction($action);
    }

    /**
     * @todo need to review the algorithm and check forward module outside the backend or module is a virtual module
     * @access public
     * set current forward module
     * 
     */
    public function getForwardModule()
    {
        $moduleId = Data::getForwardModParam();
        $forwardModules = amcwm::app()->acl->getForwardModules();
        $controllerModule = $this->getModule();
        $forward = array();
        if (isset($forwardModules[$moduleId]) && $controllerModule) {
            $moduleName = $controllerModule->getId();
            $forwardFrom = key($forwardModules[$moduleId]);
            $forwardTo = $forwardModules[$moduleId][$forwardFrom];
            if ($forwardTo == $moduleName) {
                $forward[0] = $forwardFrom;
                $forward[1] = $forwardTo;
            }
        }
        return $forward;
    }

    /**
     * Set custom config 
     * @access private 
     */
    private function setConfig()
    {
        try {
            $cache = Yii::app()->getComponent('cache');
            $config = null;
            if ($cache !== NULL) {
                    $config = unserialize($cache->get("configuration"));
                    if ($config == null) {
                        $encodedConfig = Yii::app()->db->createCommand(sprintf("select config from configuration where content_lang = %s", Yii::app()->db->quoteValue(self::getCurrentLanguage())))->queryScalar();    
                        $config = unserialize(base64_decode($encodedConfig));                        
                        $cache->set('configuration', serialize($config), Yii::app()->params["cacheDuration"]["static"]);
                    } 
            }
            else {
                $encodedConfig = Yii::app()->db->createCommand(sprintf("select config from configuration where content_lang = %s", Yii::app()->db->quoteValue(self::getCurrentLanguage())))->queryScalar();    
                $config = unserialize(base64_decode($encodedConfig));
            }                       
            if (is_array($config)) {
                Yii::app()->setParams($config);
            }
        } catch (CException $e) {
            echo 'Website is down, please try again later.';
            Yii::app()->end();
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
    public function redirect($url, $terminate = true, $statusCode = 302)
    {
        $redirect = $url;
        if (is_array($url)) {
            $route = $url[0];
            $forwardModule = Data::getForwardModParam();
            if (!isset($url['lang'])) {
                $url['lang'] = Controller::getCurrentLanguage();
            }
            if ($forwardModule && !isset($url['module'])) {
                $url['module'] = $forwardModule;
            }
            $bookmark = null;
            if (isset($url["#"])) {
                $bookmark = "#{$url["#"]}";
                unset($url["#"]);
            }
            if ($route === '')
                $route = $this->getId() . '/' . $this->getAction()->getId();
            else if (strpos($route, '/') === false)
                $route = $this->getId() . '/' . $route;
            if ($route[0] !== '/' && ($module = $this->getModule()) !== null)
                $route = $module->getId() . '/' . $route;
            $redirect = Html::createUrl($route, array_splice($url, 1)) . "$bookmark";
        } else if (strpos($url, '?') === false) {
            $redirect = $url;
            $redirect .= '?';
            $redirect .= '&lang=' . Controller::getCurrentLanguage();
        }
        parent::redirect($redirect, $terminate, $statusCode);
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @todo add translate to action tables after correcting the transation concept for any acl tables
     * @return array access control rules
     */
    public function accessRules()
    {
        $forward = $this->getForwardModule();
        $route = $this->getRoute();
        if ($this->getAction()->getId() == "translate") {
            $langsCount = count(AmcWm::app()->params->languages);
            if ($langsCount > 1) {
                $route = str_replace("translate", "update", $route);
            } else {
                $rules = array(array('deny', 'users' => array('*')));
                return $rules;
            }
        }
        if ($forward) {
            $route = str_replace($forward[1], $forward[0], $route);
        }
        $rules = Yii::app()->user->getRouteRules($route);
        return $rules;
    }

    /**
     * Get Languages used in this application
     * @access public
     * @return array      
     */
    public function getLanguages()
    {
        // $lang[''] = '';
        //array_merge($lang, Yii::app()->params['languages']);
        $langs = Yii::app()->params['languages'];

        return $langs;
    }

    /**
     * Get translation languages used in this application
     * @access public
     * @return array      
     */
    public function getTranslationLanguages()
    {
        // $lang[''] = '';
        //array_merge($lang, Yii::app()->params['languages']);
        $langs = Yii::app()->params['languages'];
        unset($langs[self::getContentLanguage()]);
        return $langs;
    }

    /**
     * Get Countries list
     * @access public
     * @return array      
     */
    public function getCountries($addEmpty = false, $code = NULL, $contentLang = null)
    {
        static $countries = NULL;
        if ($contentLang === null) {
            $contentLang = Controller::getContentLanguage();
        }
        if ($code) {
            $country = null;
            $code = strtoupper($code);
            if ($countries === NULL) {
                $country = Yii::app()->db->createCommand(sprintf("select country from countries_translation where content_lang=%s AND code = %s", Yii::app()->db->quoteValue($contentLang), Yii::app()->db->quoteValue($code)))->queryScalar();
            } else if (isset($countries[$code])) {
                $country = $countries[$code];
            }
            return $country;
        } else if ($countries === NULL) {
            $countries = CHtml::listData(Yii::app()->db->createCommand(sprintf("select code, country from countries_translation where content_lang=%s order by country ", Yii::app()->db->quoteValue($contentLang)))->queryAll(), 'code', "country");
            if ($addEmpty) {
                $countries[""] = "";
            }
        }
        return $countries;
    }

    protected static function setCurrentLanguage($currentLang = null)
    {
        $langParam = ($currentLang) ? $currentLang : Yii::app()->request->getParam('lang', $currentLang);
        if ($langParam) {
            self::$currentLang = $langParam;
            if (!isset(Yii::app()->request->cookies['lang']->value)) {
                $cookie = new CHttpCookie("lang", self::$currentLang);
                $cookie->expire = 0;
                $cookie->httpOnly = true;
                Yii::app()->request->cookies['lang'] = $cookie;
            } else if (Yii::app()->request->cookies['lang']->value != $langParam) {
                $cookie = new CHttpCookie("lang", self::$currentLang);
                $cookie->expire = 0;
                $cookie->httpOnly = true;
                Yii::app()->request->cookies['lang'] = $cookie;
            }
        } else {
            if (isset(Yii::app()->request->cookies['lang']->value)) {
                self::$currentLang = Yii::app()->request->cookies['lang']->value;
            } else {
                self::$currentLang = Yii::app()->getLanguage();
            }
        }
        $languages = Yii::app()->params['languages'];
        $url = parse_url(AmcWm::app()->getRequest()->requestUri);
        if (isset($url['query'])) {
            parse_str($url['query'], $query);
            if (isset($query['r'])) {
                $route = $query['r'];
            } else {
                $route = $url['path'];
            }
        } else {
            $route = $url['path'];
        }
        $moduleRoot = AmcWm::app()->getModuleRootName(trim(str_replace(Yii::app()->request->baseUrl, "", $route), "/"));
        if ($moduleRoot == AmcWm::app()->backendName && !isset($languages[AmcWm::app()->backendLang])) {
            $languages[AmcWm::app()->backendLang] = AmcWm::app()->backendLang;
        }
        if (!array_key_exists(self::$currentLang, $languages)) {
            self::$currentLang = Yii::app()->getLanguage();
        }
        self::$currentLang = Html::escapeString(self::$currentLang);
        Yii::app()->setLanguage(self::$currentLang);
    }

    public static function getCurrentLanguage()
    {
        if (!self::$currentLang) {
            self::setCurrentLanguage();
        }
        return self::$currentLang;
    }

    public static function getContentLanguage()
    {
        $contentLang = null;
        if (Yii::app()->hasComponent('user')) {
            $contentLang = Yii::app()->user->getState('contentLang');
        }
        if (!$contentLang) {
            $contentLang = (AmcWm::app()->contentLang) ? AmcWm::app()->contentLang : self::$currentLang;
        }
        return $contentLang;
    }

    /**
     * @param string $path the root directory of view files.
     * @throws CException if the directory does not exist.
     * @access public
     */
    public function setViewPath($path)
    {
        $module = $this->getModule();
        if ($module === null) {
            Yii::app()->setViewPath($path);
        } else {
            $module->setViewPath($path);
        }
    }

    public function getFlashMsg($container = array(), $jsAnimate = array('options' => array('opacity' => 1.0), 'duration' => 10000, 'animationMethod' => 'fadeOut', 'compelet' => null, 'beforeAnimate' => null), $jsPosition = CClientScript::POS_READY)
    {
        $msg = '';
        if ($flashes = AmcWm::app()->user->getFlashes()) {
            $script = '';
            if (isset($container['tag'])) {
                $htmlOptions = array();
                if (isset($container['htmlOptions'])) {
                    $htmlOptions = $container['htmlOptions'];
                }
                if ($jsAnimate['beforeAnimate']) {
                    $script .= $jsAnimate['beforeAnimate'] . PHP_EOL;
                }
                $msg .= CHtml::openTag($container['tag'], $htmlOptions);
                if (isset($htmlOptions['id'])) {
                    $jsAnimate['compelet'] .= "$('#{$htmlOptions['id']}').hide();";
                }
            }
            $compelet = '';
            if ($jsAnimate['compelet']) {
                $compelet .= ", function(){{$jsAnimate['compelet']}}";
            }
            $jsMethod = "animate(" . CJSON::encode($jsAnimate['options']) . ", {$jsAnimate['duration']}$compelet).{$jsAnimate['animationMethod']}()";
            foreach ($flashes as $userMessageKey => $userMessage) {
                $msg .= '<div class="' . $userMessage['class'] . '">';
                $msg .= $userMessage['content'];
                $msg .= '</div>';
                if ($jsMethod) {
                    $script .= '$(".' . $userMessage['class'] . '").' . $jsMethod . PHP_EOL;
                }
            }
            AmcWm::app()->clientScript->registerScript(
                    'messageEffect', $script, $jsPosition
            );
            if (isset($container['tag'])) {
                $msg .= CHtml::closeTag($container['tag']);
            }
        }
        return $msg;
    }

    /**
     * run ajax
     * @param string $do
     * @access public
     * @return void
     */
    public function actionAjax($do)
    {
        $methodName = "ajax{$do}";
        if (method_exists($this, $methodName)) {
            $this->$methodName();
        }
    }

    /**
     * Renders a view with a layout.
     *
     * This method first calls {@link renderPartial} to render the view (called content view).
     * It then renders the layout view which may embed the content view at appropriate place.
     * In the layout view, the content view rendering result can be accessed via variable
     * <code>$content</code>. At the end, it calls {@link processOutput} to insert scripts
     * and dynamic contents if they are available.
     *
     * By default, the layout view script is "protected/views/layouts/main.php".
     * This may be customized by changing {@link layout}.
     *
     * @param string $view name of the view to be rendered. See {@link getViewFile} for details
     * about how the view script is resolved.
     * @param array $data data to be extracted into PHP variables and made available to the view script
     * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
     * @return string the rendering result. Null if the rendering result is not required.
     * @see renderPartial
     * @see getLayoutFile
     */
    public function render($view, $data = null, $return = false)
    {
        //$this->beforeRenderMethod($view, $data);
        $beforeMethod = "beforeRender" . ucfirst($this->getAction()->id);
        if (method_exists($this, $beforeMethod)) {
            $this->$beforeMethod($view, $data);
        }
        return parent::render($view, $data, $return);
    }

//    protected function beforeRenderMethod() {
//        if ($this->hasEventHandler('onBeforeRenderMethod')) {
//            $event = new CEvent(new ControllerEvent());
//            $this->onBeforeRenderMethod($event);
//        }
//    }
//
//    public function onBeforeRenderMethod($event) {
//        $this->raiseEvent('onBeforeRenderMethod', $event);
//    }

    /**
     * Get Days numbers list
     * @access public
     * @return void
     */
    public function daysNumbersList()
    {
        $days = array();
        for ($i = 1; $i <= 31; $i++) {
            if ($i <= 9) {
                $i = "0{$i}";
            }
            $days[$i] = $i;
        }
        return $days;
    }

    /**
     * Get Hours numbers list
     * @access public
     * @return void
     */
    public function hoursList()
    {
        $list = array();
        for ($i = 0; $i <= 23; $i++) {
            if ($i <= 9) {
                $i = "0{$i}";
            }
            $list[$i] = $i;
        }
        return $list;
    }

    /**
     * Ge min numbers list
     * @access public
     * @return void
     */
    public function minsList()
    {
        $list = array();
        for ($i = 0; $i <= 59; $i++) {
            if ($i <= 9) {
                $i = "0{$i}";
            }
            $list[$i] = $i;
        }
        return $list;
    }

    /**
     * Get Days list
     * @static
     * @access public
     * @return void
     */
    public function getDaysList()
    {
        $rows = array(
            array('id' => '6'),
            array('id' => '0'),
            array('id' => '1'),
            array('id' => '2'),
            array('id' => '3'),
            array('id' => '4'),
            array('id' => '5'),
        );
        $localDays = Yii::app()->getLocale()->getWeekDayNames();
        $days = array();
        foreach ($rows as $row) {
            $days[$row['id']] = $localDays[$row['id']];
        }
        return $days;
    }

    /**
     * Get years list
     * @access public
     * @return array
     */
    public function getYearsList($from = 1960)
    {
        $years = array();
        $currentYear = date('Y');
        for ($year = $from; $year <= $currentYear; $year++) {
            $years[$year] = $year;
        }
        return $years;
    }

    /**
     * Get months list
     * @access public
     * @return void
     */
    public function getMonthsList()
    {
        return Yii::app()->getLocale()->getMonthNames();
    }

    /**
     * Get months list
     * @access public
     * @return void
     */
    public function getCurrencies()
    {
        $countries = CHtml::listData(Yii::app()->db->createCommand("select currency_code from currency")->queryAll(), 'currency_code', "currency_code");
        return $countries;
    }

}
