<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ControllerTaskManager class, run controller task 
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ControllerTaskManager {

    /**
     *  Equal true if the sister class generate sisters data 
     * @var mixed     
     */
    private $_success = false;

    /**
     * Class used for controlling the task
     * @var ControllerTask
     */
    private $_class = null;

    /**
     * Constructor,
     * @access public
     * @throws Error Error if you call the constructor directly
     */
    public function __construct($routeUrl, $menuId = null, $extraParams = array()) {
        $codeParams = Menus::getMenuCodeParams($menuId);
        $task = "default";
        $viewType = "default";
        if (isset($extraParams['defaultView'])) {
            $viewType = $extraParams['defaultView'];
        }
        $options = array();
        foreach ($codeParams as $codeParam) {
            switch ($codeParam['param']) {
                case 'view':
                    $viewType = $codeParam['value'];
                    break;
                case 'task':
                    $task = $codeParam['value'];
                    break;
                default:
                    $options[$codeParam['param']] = $codeParam['value'];
            }
        }
        if (isset($extraParams['className'])) {
            $className = $extraParams['className'];
        } else {
            $className = Data::createClassFromRoute($routeUrl, ucfirst("{$task}Task"), "amcwm.components.task");
        }

        if ($className) {
            $this->_class = new $className($routeUrl, $viewType, $options, $extraParams);
            $this->_success = true;
        }
    }

    /**
     * Run this task
     * @param boolean $displayResult
     * @access public
     * @return boolean return true if the task render the result
     */
    public function run($displayResult = true) {
        return $this->_class->run($displayResult);
    }

    /**
     * get site mapdata used in this task
     * @access public
     * @return array();
     */
    public function getSiteMapData() {
        return $this->_class->getSiteMapData();
    }

    /**
     * Return true if the sister class generate sisters data 
     * @access public
     * @return boolean
     */
    public function isSuccess() {
        return $this->_success;
    }

}