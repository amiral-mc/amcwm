<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ListData abstartc class,  gets records as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class Dataset {

    /**
     * Gets the module name that handle the output 
     * @var string 
     */
    protected $moduleName;

    /**
     * if set to true then use record id as array index key in each record
     * @var boolean
     */
    protected $recordIdAsKey = true;

    /**
     * Records list array,
     * @var array 
     */
    protected $items = array();

    /**
     * Hold orders syntax , to sort records according to values stored in this array
     * @var string 
     */
    protected $orders = array();

    /**
     * Add extra columns to query results
     * @var string 
     */
    protected $cols = array();

    /**
     * Hold wheres syntax , to find records according to values stored in this array
     * @var array 
     */
    protected $wheres = array();

    /**
     * Hold join syntax , to join tables with parent records table
     * @var string 
     */
    protected $joins;

    /**
     * Records count , if you want count all records matchs the carteria in sql result append sql_calc_found_rows in mysql query
     * @var int 
     */
    protected $count = 0;

    /**
     * The numbers of items to fetch from table 
     * @var int 
     */
    protected $limit;

    /**
     * The record number to start getting records from it
     * @var int 
     */
    protected $fromRecord = 0;

    /**
     * Router for viewing content details
     * @var string 
     */
    protected $route = null;

    /**
     * query to get data from
     * @var string 
     */
    protected $query = null;

    /**
     * The parameters array to append to route
     * @var int 
     */
    protected $params = array();

    
    /**
     * if is check is true append isActive to the results items
     * @var boolean 
     */
    protected $checkIsActive = false;
    
    /**
     * Gets records list array
     * @access public
     * @return array
     */
    public function getItems() {
        return $this->items;
    }
    
     /**
     * Gets record
     * @access public
     * @return array
     */
    public function getData() {
        return $this->items;
    }

    /**
     * If the given $ok equal true the append isAcvtive to the result items
     * @access public
     * @return void
     */
    public function checkActive($ok = true) {
        $this->checkIsActive = $ok;
    }
    
    /**
     * Gets query
     * @access public
     * @return array
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Generate columns from Dataset.cols array
     * @access public
     * @return string
     */
    public function generateColumns() {
        $generated = null;
        if (count($this->cols)) {
            foreach ($this->cols as $colIndex => $col) {
                $addedCol = ($col == $colIndex) ? "$col" : "$col $colIndex";
                $generated.=", $addedCol ";
            }
            //$generated = ", " . implode(" , ", $this->cols);
        }
        return $generated;
    }

    /**
     * Generate where from Dataset.wheres array
     * @access public
     * @param string $prefix
     * @return string
     */
    public function generateWheres($prefix = " and ") {
        $generated = null;
        if (count($this->wheres)) {
            $generated = " {$prefix} " . implode(" and ", $this->wheres);
        }
        return $generated;
    }

    /**
     * Generate order from Dataset.orders array
     * @param string $prefix
     * @access public
     * @return string
     */
    public function generateOrders($prefix = "order by") {
        $generated = null;
        if (count($this->orders)) {
            $generated = " {$prefix} " . implode(" , ", $this->orders);
        }
        return $generated;
    }

    /**
     * add order syntax to order array
     * @param string $order 
     * @access public 
     * @return void
     */
    public function addOrder($order) {
        if ($order) {
            $order = trim($order);
            $this->orders[md5($order)] = $order;
        }
    }

    /**
     * add extra parameters to params attribute
     * @param string $index 
     * @param string $value 
     * @access public 
     * @return void
     */
    public function addParam($index, $value) {
        $index = trim($index);
        $this->params[$index] = $value;
    }

    /**
     * add column to cols attribute array      
     * @param string $col 
     * @param string $index 
     * @access public 
     * @return void
     */
    public function addColumn($col, $index = null) {
        $col = trim($col);
        if ($index) {
            $this->cols[$index] = $col;
        } else {
            $this->cols[$col] = $col;
        }
    }

    /**
     * add $where to wheres attribute array      
     * @param string $where 
     * @access public 
     * @return void
     */
    public function addWhere($where) {
        $where = trim($where);
        $this->wheres[md5($where)] = $where;
    }

    /**
     * set the record number to start getting records from it
     * @param integer $fromRecord 
     * @access public 
     * @return void
     */
    public function setFromRecord($fromRecord) {
        $this->fromRecord = (int) $fromRecord;
    }

    /**
     * set the numbers of items to fetch from table 
     * @param integer $limit 
     * @access public 
     * @return void
     */
    public function setLimit($limit) {
        $this->limit = (int) $limit;
    }

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

    abstract protected function setItems();

    /**
     * Add mysql join syntax
     * @param string $join 
     * @access public
     * @return void
     */
    public function addJoin($join) {
        $this->joins .= " " . $join;
    }

    /**
     * Get route router for viewing content details
     * @access public 
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * Set route router for viewing content details
     * @param string $route
     * @access public 
     * @return  void
     */
    public function setRoute($route) {
        $this->route = $route;
    }

    /**
     * Get Records count , if you want count all records matchs the carteria in sql result append sql_calc_found_rows in mysql query
     * @access public 
     * @return integer
     * 
     */
    public function getCount() {
        return $this->count;
    }

    /**
     * if the given $recordIdAsKey equal true then use record id as array index key in each record
     * @access public 
     * @return void
     */
    public function useRecordIdAsKey($recordIdAsKey) {
        $this->recordIdAsKey = $recordIdAsKey;
    }

    /**
     * Gets the module name that handle the output
     * @access public
     * @return string 
     */
    public function getModuleName() {
        return $this->moduleName;
    }

    /**
     * Sets the module name that handle the output
     * @param string $module
     * @access public
     * @return void
     */
    public function setModuleName($module) {
        $this->moduleName = $module;
    }

}