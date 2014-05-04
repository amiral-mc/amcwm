<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * BreadcrumbsData class,  generate breadcrumbs data and append it to menu breadcrumbs
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class BreadcrumbsData {

    /**
     * The primary key id to get tree according to it
     * @var integer
     */
    protected $id = null;
    /**
     * Route to generate data from it
     * @var array 
     */
    protected $route;

    /**
     * Current language
     * @var string 
     */
    protected $language;
    
    /**
     * Final Breadcrumbs Path
     * @var array 
     */
    protected $path = array();

    /**
     * Counstructor     
     * @param array $route Route to generate data from it
     * @access public
     */
    public function __construct($route) {
        $this->path = array();
        if(isset($route['id']))
            $this->id = $route['id'];
        
        $this->language = Controller::getCurrentLanguage();
        $this->route = $route;
        $this->setPath($this->id);
    }

    /**
     * Append to menu breadcruumbs 
     * @param array $route
     * @access protected
     * @return boolean if the menu appended to the path
     */
    protected function appendToMenuBreadcrumbs($route){
        $parentPath = Menus::getMenuItemPath(0, $route);   
        $appended = false;
        if (count($parentPath)) {
            $appended = true;
            $this->path = array_merge($parentPath, $this->path);
        }
        return $appended;
     
    }
    
   /**
     * get section breadcrumbs for the given section $id 
     * @param string $route 
     * @param int $id
     */
    public function getPath() {        
        return $this->path;
    }

    /**
     * set Breadcrumbs path for the given $id 
     * @param int $id
     * @access protected
     * @return array     
     */
    abstract protected function setPath($id) ;        

}

