<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SistersRelatedManager class, Generate the sisters dataset array
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SistersRelatedManager {   

    /**
     * The page size , number of records displayed in each page
     * @var integer
     */
    private $_pageSize = 10;

    /**
     *  Equal true if the sister class generate sisters data 
     * @var mixed     
     */
    private $_success = false;

    /**
     * Class used for generating systers data list
     * @var SistersRelatedData
     */
    private $_class = null;

    /**
     * Constructor, this SistersRelatedManager
     * @param string $type
     * @param mixed $id the primary key used to get the related data from
     * @param integer $limit, The numbers of record to fetch
     * @access private
     * @throws Error Error if you call the constructor directly
     */
    public function __construct($type, $id, $limit = 10) {
        $className = ucfirst($type) . "SistersRelatedData";
        $this->_pageSize = $limit;
        if (file_exists(Yii::getPathOfAlias("amcwm.components.sisterRelated.{$className}") . ".php")) {
            $this->_class = new $className($id, $limit);
            $this->_class->generate(Yii::app()->request->getParam("page", 1));
            $this->_success = $this->_class->hasItems();
        }
    }    

    /**
     * Make sure you call the parent method so that the method is raised properly.
     * Gets PagingDataset data as associated array
     * Possible list names include the following:
     * <ul>
     * <li>records: array, specifies the records of the current page</li>    
     * <li>pager: array list of name-value pairs dataset.
     * Possible list names include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * </ul> 
     * @access public
     * @return array
     */
    public function getItems() {
        return $this->_class->getItems();
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
     * Convert items to json
     * @access public
     * @return string
     */
    public function itemsToJson() {
        return CJSON::encode($this->getItems());
    }

    /**
     * Get the parent sisters item title
     * @access public
     * @return string
     */
    public function getParentTitle() {
        return $this->_class->getParentTitle();
    }

    /**
     * Return true if the sister class generate sisters data 
     * @access public
     * @return boolean
     */
    public function hasItems() {
        return $this->_success;
    }

}