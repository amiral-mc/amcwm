<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ListManager class,  base class for any list manager
 * @package amcwm.list
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ListManager extends CComponent {

    /**
     * List data class name
     * @var string
     */
    private $_listClassName = "ListData";

    /**
     * array of list data instances
     * @var ListData[]
     */
    private $_list = array();

    /**
     * alias of directory that contain the list data classes
     * @var string
     */
    private $_listDataDir = null;

    /**
     * The page size , number of records displayed in each page
     * @var integer
     */
    private $_pageSize = 10;

    /**
     * Constructor,
     * @param integer $pageSize, The numbers of record to fetch
     * @param string $listClassName List data class name
     * @param string $listDataDir directory that contain the report class
     */
    public function __construct($pageSize = 10, $listClassName = "ListData", $listDataDir = "amcwm.components") {
        $this->_listClassName = ucfirst($listClassName);
        $this->_listDataDir = $listDataDir;
        $this->_pageSize = $pageSize;
    }

    /**
     * @param string $listDataDir directory that contain the report class
     */
    public function setListDataDir($listDataDir){
        $this->_listDataDir = $listDataDir;
    }
    /**
     * get the numbers of items to be fetch
     * @access public 
     * @return integer
     */
    public function getPageSize() {
        return $this->_pageSize;
    }

    /**
     * Returns the manager ID.
     * @return string the list ID.
     * @access public
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Getter magic method.
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        $attribute = null;
        if (!$this->hasList($name)) {
            $className = ucfirst($name) . $this->_listClassName;
            if (file_exists(AmcWm::getPathOfAlias("{$this->_listDataDir}.{$className}") . ".php")) {
                AmcWm::import("{$this->_listDataDir}.{$className}");
                $attribute = new $className($name, $this, $this->_pageSize);
                if(!$attribute->getIsInitialized()){
                    $attribute->init();    
                }
                $this->_list[$name] = $attribute;
                
                
            } else {
                $attribute = parent::__get($name);
            }
        } else {
            $attribute = $this->_list[$name];
        }
        return $attribute;

        //    return $this->getList($name);
        //else
        //    return parent::__get($name);
    }

    /**
     * Calls the named method which is not a class method.
     * Do not call this method. This is a PHP magic method that we override
     * to implement the list sub classes call feature.
     * @param string $name the method name
     * @param array $parameters method parameters
     * @return mixed the method return value
     */
    public function __call($name, $parameters) {
        parent::__call($name, $parameters);
//        if (!$this->hasList($name)) {
//            $className = ucfirst($name) . $this->_listClassName;
//            if (file_exists(AmcWm::getPathOfAlias("{$this->_listDataDir}.{$className}") . ".php")) {
//                print_r($parameters);
//                AmcWm::import("{$this->_listDataDir}.{$className}");                
//                if(isset($parameters[0])){
//                    $limit = $parameters[0];    
//                    unset($parameters[0]);
//                }                
//                $list = new $className($name, $limit);
//                $list->init($parameters);
//                
//            }
//        }
        die($className);
    }

    /**
     * Checks whether the list named exists.
     * @param string $listName list name
     * @access public
     * @return boolean whether the report named exists
     */
    public function hasList($listName) {
        return isset($this->_list[$listName]);
    }

}