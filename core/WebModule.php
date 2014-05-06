<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * WebModule represents an application module.
 * 
 * An application module may be considered as a self-contained sub-application
 * that has its own controllers, models and views and can be reused in a different
 * project as a whole. Controllers inside a module must be accessed with routes
 * that are prefixed with the module ID.
 * 
 * @package  AmcWm.core
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WebModule extends CWebModule {

    /**
     * Application Module Class
     * @var ApplicationModule
     */
    protected $applicationModule = null;

    /**
     * Initializes the WebModule.
     * This method is called by the application before the WebModule starts to execute.
     * You may override this method to perform the needed initialization for the WebModule.
     * @access public
     * @return void
     */
    public function init() {
        if ($this->applicationModule === null) {
            $moduleName = $this->getName();
            $this->applicationModule = AmcWm::app()->getApplicationModule($moduleName, $this);
        }
        parent::init();
    }

    /**
     * Application Module Class
     * @return ApplicationModule
     */
    public function getAppModule() {
        return $this->applicationModule;
    }

//    public function beforeControllerAction($controller, $action) {
//        if (parent::beforeControllerAction($controller, $action)) {
//            // this method is called before any module controller action is performed
//            // you may place customized code here
//            return true;
//        }
//        else
//            return false;
//    }
}
