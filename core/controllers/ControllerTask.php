<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ControllerTask class, run controller task
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ControllerTask {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     */
    protected $settings = null;

    /**
     *
     * @var boolean 
     */
    protected $displayResult = true;

    /**
     * current route
     * @var string 
     */
    protected $route;

    /**
     * dataset used in the task
     * @var Dataset 
     */
    protected $dataset;

    /**
     * current menu item view param
     * @var array 
     */
    protected $viewType = null;

    /**
     * Options default values read it from item code params, not include view and task 
     * @var array 
     */
    protected $options = array();
    
    /**
     * current action params
     * @var array 
     */
    protected $params = array();

    /**
     * Extra params needed for the task
     * @var array 
     */
    protected $extraParams = array();

    /**
     * Counstructor     
     * @param array $route Route to generate data from it
     * @access public
     */
    public function __construct($route, $viewType = "default", $options = array(), $extraParams = array()) {       
        $this->language = Controller::getCurrentLanguage();
        $this->route = $route[0];
        unset($route[0]);
        $this->params = $route;
        $this->viewType = $viewType;
        $this->extraParams = $extraParams;
        $this->options = $options;
        $this->init();
    }

    /**
     * Initializes the ControllerTask.
     * @param array $options options appended to options attribute
     * You may override this method to perform the needed initialization for the ControllerTask.
     * @access public
     * @return void
     */
    protected function init($options = array()) {
        $this->appendOptions($options);
    }
    
    
    /**
     * append options to options attribute
     * @access public
     * @return void
     */
    protected function appendOptions($options) {
        foreach($options as $option=>$value){
            if(!array_key_exists($option, $this->options)){
                $this->options[$option] = $value;
            }
        }
        
    }
    
    /**
     * Gets actios params sent to the task
     * @access public
     * @return array
     */
    public function getActionParams() {
        return $this->params;
    }
    /**
     * Gets application module setting instance
     * @access public
     * @return Settings
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * get site mapdata used in this task
     * @access public
     * @return array();
     */
    public function getSiteMapData() {
        return array();
    }

    /**
     * Run this task
     * @param boolean $displayResult
     * @access public
     * @return boolean return true if the task render the result
     */
    abstract public function run($displayResult = true);

    /**
     * Renders a view with a layout.
     * @param string $view name of the view to be rendered
     * @param array $data data to be extracted into PHP variables and made available to the view script
     * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
     * @return string the rendering result. Null if the rendering result is not required.
     */
    abstract public function render($view, $data = array(), $return = false);
}

