<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * FrontendController, Controller is the base frontend controller class.
 * All Frontend controller classes for this application should extend from this base class.
 * @package AmcWm.core.frontend.controllers
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
class AmcFrontendController extends Controller {

    /**
     * Widgits positions
     * @var array 
     */
    public $positions = array();
    /**
     *
     * @var boolean if the current route is home page or not 
     */
    public $isHomePage = false;
    
    /**
     *
     * @var boolean if false then we dont generate postions 
     */
    protected $usePostitions = true;

    /**
     * sister options data needed to draw sister related widget
     * @var array
     */
    protected $sisterOptions = array();
    
    /**
     *
     * @var string content language  is not avaliable
     */
    protected $contentNotAvailable = null;

    /**
     * @var mixed the views layout alias path used in error that is shared by the controllers inside this module.
     */
    public $viewsBaseAlias = null;

    /**
     *
     * @var LoginForm login form model used in all controllers actions 
     */
    private $loginModel;

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     * @access public
     * @return $void
     */
    public function init() {
        //require_once 'MobileDetect.php';
//        $module = $this->getModule();
//        $moduleId = null;
//        if ($module) {
//            $moduleId = $this->getModule()->getId();
//        }
//        if ($moduleId <> 'rss') {
//            $detect = new MobileDetect();
//            if ($detect->isMobile()) {
//                if ($this->getId() != 'mobile') {
//                    $router = '/mobile/index';
//                    $extraUrl = array();
//                    $articleId = Yii::app()->request->getParam("id");
//                    if (isset($articleId)) {
//                        $router = '/mobile/details';
//                    }
//                    $this->forward($router);
//                }
//            }
//        }       

        AmcWm::app()->setIsBackend(false);
        if (isset(AmcWm::app()->frontend['layout'])) {
            $this->layout = AmcWm::app()->frontend['layout'];
        }
        //$this->layout = $this->viewsBaseAlias . ".layouts.main";
        AmcWm::import("amcwm.models.*");
//        AmcWm::import("amcwm.core.frontend.controllers.*");
        AmcWm::import("amcwm.core.frontend.components.*");
        AmcWm::import("amcwm.core.frontend.models.*");
        if (!isset(AmcWm::app()->frontend['menus'])) {
            AmcWm::app()->frontend['menus'] = array(
                array('id' => 1, 'is_main' => true));
        }
        Menus::setMenus(AmcWm::app()->frontend['menus']);
        if (isset(AmcWm::app()->frontend['positions'])) {
            $this->positions = AmcWm::app()->frontend['positions'];
        }
        
        parent::init();        
        $this->checkContentLang();
        if (AmcWm::app()->frontend['bootstrap']['use']) {
            if (isset(AmcWm::app()->frontend['bootstrap']['useResponsive']) && AmcWm::app()->frontend['bootstrap']['useResponsive']) {
                Yii::app()->bootstrap->useResponsive = true;
            } else {
                Yii::app()->bootstrap->useResponsive = false;
            }
            Yii::app()->bootstrap->register();
        }
        $this->pageTitle = (!empty(Yii::app()->params['custom']['front']['site']['title']) ? Yii::app()->params['custom']['front']['site']['title'] : Yii::t('pageTitles', '_website_name_'));
        
    }

    /**
     * check if language content is not available
     * 
     */
    protected function checkContentLang(){
        $languagesInUse = AmcWm::app()->params['languagesInUse'];
        $currentLang = AmcWm::app()->getLanguage();
        if($this->contentNotAvailable === null && is_array($languagesInUse) && !in_array($currentLang, $languagesInUse)){
            self::setCurrentLanguage(AmcWm::app()->sourceLanguage);
            $this->contentNotAvailable = $currentLang;                    
        }
    }
    
    /**
     * Generate all postitions for the given category
     * @access public
     * @return string
     */
    public function generatePositions($category = "sideColumn") {
        $output = null;
        if (isset($this->positions[$category])) {
            $positions = $this->positions[$category];
            foreach ($positions as $rowPosition) {
                if (isset($rowPosition['data'])) {
                    $class = "side_position";
                    if (isset($rowPosition['options']['class'])) {
                        $class = "side_position {$rowPosition['options']['class']}";
                    }
                    $output .= '<div class="' . $class . '">' . $rowPosition['data'] . '</div>';
                }
            }
        }
        return $output;
    }

    /**
     * This method is invoked at the beginning of {@link render()}.
     * You may override this method to do some preprocessing when rendering a view.
     * @param string $view the view to be rendered
     * @return boolean whether the view should be rendered.
     * @since 1.1.5
     */
    protected function beforeRender($view) {
        if ($this->usePostitions) {
            $positionDataFile = Yii::getPathOfAlias("layouts.postitionsData") . ".php";
            if (is_file($positionDataFile)) {
                include_once $positionDataFile;
            }
        }
        return true;
    }

    /**
     * Declares class-based actions.
     * @access public
     * @return array 
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
//            'captcha' => array(
//                'class' => 'CCaptchaAction',
//                'backColor' => 0xFFFFFF,
//            ),
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
     * Genatate LoginForm model to used in all controllers actions 
     * @access public     
     * @return LoginForm
     */
    public function &loginModel() {
        if ($this->loginModel == NULL) {
            $this->loginModel = new LoginForm();
        }
        return $this->loginModel;
    }

    /**
     * Run the current task
     * @param  array $extraParams
     * @access protected
     * @return boolean
     */
    protected function runTask($extraParams = array()) {
        $taskObj = new ControllerTaskManager(array_merge(array($this->getRoute()), $this->getActionParams()), Yii::app()->request->getParam("menu"), $extraParams);
        $ok = false;
        if ($taskObj->isSuccess()) {
//            $ok = true;
            $ok = $taskObj->run(1);
        }
        return $ok;
    }

    /**
     * Swap position
     * @param integer $from
     * @param integer $to
     * @param string $category
     * @access protected
     * @return void
     */
    protected function swapPosition($from, $to, $category = "sideColumn") {
        $positionFromKey = $this->findPositionSortNumber($from, $category);
        $positionToKey = $this->findPositionSortNumber($to, $category);
        if ($positionFromKey !== null && $positionToKey !== null) {
            $positionFrom = $this->positions[$category][$positionFromKey];
            $this->positions[$category][$positionFromKey] = $this->positions[$category][$positionToKey];
            $this->positions[$category][$positionToKey] = $positionFrom;
        }
    }

    /**
     * set position data
     * @param integer $positionId
     * @param string $data
     * @param string $category
     * @access protected
     * @return void
     */
    protected function setPositionData($positionId, $data, $category = "sideColumn") {
        $positionKey = $this->findPositionSortNumber($positionId, $category);
        if ($positionKey !== null) {
            $this->positions[$category][$positionKey]['data'] = $data;
        }
    }

    /**
     * Check id the given $positionId has data inside it or not
     * @param integer $positionId
     * @param string $category
     * @access protected
     * @return boolean
     */
    protected function positionHasData($positionId, $category = "sideColumn") {
        $positionKey = $this->findPositionSortNumber($positionId, $category);
        $hasData = false;
        if ($positionKey !== null) {
            if (isset($this->positions[$category][$positionKey]['data'])) {
                $hasData = true;
            }
        }
        return $hasData;
    }

    /**
     * Find the position sort number for the given $positionId
     * @param integer $positionId
     * @param string $category
     * @access protected
     * @return integer
     */
    protected function findPositionSortNumber($positionId, $category = "sideColumn") {
        $myPositionKey = null;
        if (isset($this->positions[$category])) {
            foreach ($this->positions[$category] as $positionKey => $rowPosition) {
                if (isset($rowPosition['id']) && $rowPosition['id'] == $positionId) {
                    $myPositionKey = $positionKey;
                }
            }
        }
        return $myPositionKey;
    }

    /**
     * Set sister postition data for the given $positionId and $category
     * @param integer $positionId
     * @param type $sisterOptions
     * @param string $category
     * @access protected
     * @return void
     */
    protected function setSisterPositionData($positionId, $sisterOptions, $category = "sideColumn") {
        $sister = new SistersRelatedManager($sisterOptions['type'], $sisterOptions['id']);
        if ($sister->hasItems()) {
            $this->setPositionData($positionId, $this->widget('amcwm.widgets.ItemsSideList', array(
                        'id' => "sister_widget_{$sisterOptions['type']}_{$sisterOptions['id']}",
                        'items' => $sister->getItems(),
                        'params' => $sisterOptions,
                        'title' => $sister->getParentTitle()), true), $category
            );
        }
    }

    /**
     * Unset the given $positionId from the given $category
     * @param integer $positionId
     * @param string $category
     * @access protected
     * @return void
     */
    protected function unsetPosition($positionId, $category = "sideColumn") {
        $positionKey = $this->findPositionSortNumber($positionId, $category);
        if ($positionKey !== null) {
            unset($this->positions[$category][$positionKey]);
        }
    }

    /**
     * get position data  for the giiven $positionId and $category
     * @param integer $positionId
     * @param string $category
     * @access protected
     * @return void
     */
    protected function getPositionData($positionId, $category = "sideColumn") {
        $positionKey = $this->findPositionSortNumber($positionId, $category);
        $data = null;
        if ($positionKey !== null) {
            $data = $this->positions[$category][$positionKey]['data'];
        }
        return $data;
    }

    /**
     * allow the given $positions ids and unset the other positions
     * @param array $positions
     * @access protected
     * @return void
     */
    protected function allowedPositions($positions = array(), $category = "sideColumn") {
        if (is_array($positions)) {
            $allowed = array();
            foreach ($positions as $positionId) {
                $positionKey = $this->findPositionSortNumber($positionId, $category);
                if ($positionKey !== null) {
                    $allowed[$positionKey] = $this->positions[$category][$positionKey];
                }
            }
            $this->positions[$category] = $allowed;
        }
    }

    /**
     * Redirect the page to the given lang
     * @param string $lang
     * @param boolean $redirect
     */
    protected function redirect2Lang($lang, $redirect = true) {
        if (Yii::app()->getLanguage() != $lang && $redirect) {
            $url[0] = $this->getRoute();
            $url = array_merge(array("/" . $this->getRoute()), $this->getActionParams());
            $url['lang'] = $lang;
            $this->redirect($url);
        }
    }

}
