<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ListData, any list data must extend this class
 * @package amcwmb.data 
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class SqlProviderListData extends CComponent {

    /**
     * List parameter array
     * @var array 
     */
    private $_parameters = array();

    /**
     * List name
     * @var string
     */
    private $_id = null;
    
    /**
     * Current pageNumber
     * @var integer
     */
    protected $pageNumber = 1;

    /**
     * The list manager owner
     * @var ListManager
     */
    private $_manager = null;

    /**
     * Checks if this list bas been initialized.
     * @var boolean
     */
    private $_initialized = false;

    /**
     * Data provider
     * @var CSqlDataProvider
     */
    protected $dataset;

    /**
     * The page size , number of records displayed in each page
     * @var integer
     */
    protected $pageSize = 10;

    /**
     * Counstructor     
     * @param string $id list name
     * @param integer $pageSize, The numbers of record to fetch
     * @access public
     */
    public function __construct($id, $manager, $pageSize = 10) {
        $this->_id = $id;
        $this->_manager = $manager;
        $this->pageSize = $pageSize;        
    }

    /**
     * Initializes the list
     * This method is required by {@link ListManager} and is invoked by any ListManager.
     * If you override this method, make sure to call the parent implementation
     * so that the list can be marked as initialized.
     * @access public
     * @return void
     */
    public function init() {
        $this->_initialized = true;
    }

    /**
     * Returns the list ID.
     * @return string the list ID.
     * @access public
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Checks if this list bas been initialized.
     * @return boolean whether this list has been initialized.
     */
    public function getIsInitialized() {
        return $this->_initialized;
    }

    /**
     * set the numbers of dataset to be fetch
     * @param integer $pageSize
     * @access public 
     * @return void
     */
    public function setPageSize($pageSize) {
        $this->pageSize = $pageSize;        
    }
    
     /**
     * set current page number
     * @param integer $page
     * @access public 
     * @return void
     */
    public function setPageNumber($page) {
        $this->pageNumber = $page;        
    }

    /**
     * Generate the array the contain dataset 
     * @param string $action the generate method, default is generateDefault
     * @param boolean $return if equal true then generate and return the dataset
     * @access public
     * @return array|void     
     */
    public function generate($action = "default", $return = false) {
        $method = "generate{$action}";
        $dataset = $this->$method($return);
        return $dataset;
    }
    
    /**
     * Generate the array the contain dataset  
     * @param boolean $return if equal true then generate and return the dataset
     * @access public
     * @return array|void     
     */
    protected function generateDefault($return = false) {
        $dataset = $this->setDataset();
        if (!$return) {
            $dataset = null;
        }
        return $dataset;
    }

    /**
     * set the dataset array
     * @access public
     * @return void     
     */
    abstract protected function setDataset();

    /**
     * Gets List data as associated array
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
    public function getDataset() {
        return $this->dataset;
    }  

    /**
     * Return true if dataset contain records or not
     * @access public
     * @return boolean
     */
    public function hasRecords() {
        if($this->dataset !== null){
            return $this->dataset->getTotalItemCount();
        }
        else{
            return false;
        }
    }

    /**
     * Return List params array
     * @access public
     * @return boolean
     */
    public function getParams() {
        return $this->_parameters;
    }

    /**
     * Return manager owner instance
     * @access public
     * @return ListManager
     */
    public function getManager() {
        return $this->_manager;
    }

    /**
     * add param to parameters aray
     * @param mixed $param
     * @param mixed $value
     * @access public
     * @return void
     */
    public function setParam($param, $value) {
        $this->_parameters[$param] = $value;
    }

}

