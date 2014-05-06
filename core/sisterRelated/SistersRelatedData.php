<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * SistersRelatedData, get sisters data related to the given item
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class SistersRelatedData {

    /**
     * Sister items array list
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
     * @var array 
     */
    protected $items;

    /**
     * The parent item title
     * @var  string 
     */
    protected $parentTitle = "";

    /**
     * the primary key used to get the related data from
     * @var mixed
     */
    protected $id = null;

    /**
     *  Equal true if the sister class generate sisters data 
     * @var mixed     
     */
    protected $success = false;

    /**
     * Extra params needed for this class instance 
     * @var array 
     */
    protected $extraParams = array();

    /**
     * The page size , number of records displayed in each page
     * @var integer
     */
    protected $pageSize = 10;

    /**
     * Counstructor     
     * @param mixed $route the primary key used to get the related data from
     * @param integer $pageSize, The numbers of record to fetch
     * @param array $extraParams Extra params needed for this class instance 
     * @access public
     */
    public function __construct($id, $pageSize = 10, $extraParams = array()) {
        $this->id = $id;
        $this->extraParams = $extraParams;
        $this->pageSize = $pageSize;
        $this->items = array(
            'records' => array(),
            'pager' => array(
                'count' => 0,
                'pageSize' => $pageSize,
            )
        );
    }
    
    /**
     * Generate the array the contain the sisters data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    abstract public function generate($page = 1);

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
        return $this->items;
    }

    /**
     * Get the parent sisters item title
     * @access public
     * @return string
     */
    public function getParentTitle() {
        return $this->parentTitle;
    }

    /**
     * Return true if the sister class generate sisters data 
     * @access public
     * @return boolean
     */
    public function hasItems() {
        return $this->success;
    }

}

