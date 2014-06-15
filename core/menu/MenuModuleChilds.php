<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MenuModuleChilds class, generate generate module childs and append it to the menu
 * Any menu module class must extend this class
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class MenuModuleChilds extends Dataset {

    /**
     * The primary key to get sub childs related to it , if null we get records from parent tabel
     * @var int 
     */
    protected $id = 0;

    /**
     * The parameters array to be used in this class
     * @var int 
     */
    protected $params = array();

    /**
     * The current module_id
     * @var int 
     */
    protected $moduleId = 0;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $moduleId, the current module id
     * @param array $params, The parameters array to be used in this class
     * @access public
     */
    public function __construct($moduleId, $params = array()) {
        $this->moduleId = $moduleId;
        if (isset($params['count'])) {
            $this->limit = (int) $params['count'];
            unset($params['count']);
        } else {
            $this->limit = 25;
        }
        if (isset($params['id'])) {
            $this->id = (int) $params['id'];
            unset($params['id']);
        }
        $this->params = $params;
        $forwardModules = amcwm::app()->acl->getForwardModules();
        if (isset($forwardModules[$this->moduleId])) {            
            $this->moduleName = key($forwardModules[$this->moduleId]);
            if ($this->moduleName != "articles") {
                $this->params["module"] = $this->moduleId;
            }
        }        
        $this->init();
        $this->generate();
    }

    /**
     * append parameters to url   
     * @return array
     */
    public function appendParamsToParent() {
        $settings = new Settings('articles', false);
        if (isset($settings->options[$this->moduleName]['default']['menu']['section']['linkOnTop']) && $settings->options[$this->moduleName]['default']['menu']['section']['linkOnTop']) {
            return array('id' => $this->id);
        }
    }

    /**
     * Initilize the class data
     * @access protected
     * @return void
     */
    abstract protected function init();

    /**
     *
     * Generate dataset
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $this->setItems();
    }

    /**
     * set menu item url
     * @param array $urlParams parameter to append to url
     * @access private
     * return string|array
     */
    protected function generateUrl($urlParams = array()) {
        $url = array();
        if ($this->route) {
            $url[0] = "/" . $this->route;
            $urlParams = array_merge($this->params, $urlParams);
            $url = array_merge($url, $urlParams);
        }
        return $url;
    }

}
