<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ParamsTask class, run controller task 
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ParamsTask {

    /**
     * @var PagingDatasetProvider
     */
    protected $dataProvider;

    /**
     *  Equal true if the sister class generate sisters data 
     * @var mixed     
     */
    protected $success = false;

    /**
     * The page size , number of records displayed in each page
     * @var integer
     */
    protected $pageSize = 10;

    /**
     * current menu component params
     * @var array 
     */
    protected $params = array();

    /**
     * the selected row Id to order the grid with it.
     * @var int
     */
    protected $selectedRow = null;

    /**
     * @var array $gridCols the generated grid columns
     */
    protected $gridCols = array();

    /**
     * Counstructor     
     * @param mixed $route the primary key used to get the related data from
     * @param integer $pageSize, The numbers of record to fetch
     * @param array $extraParams Extra params needed for this class instance 
     * @access public
     */
    public function __construct($searchFor, $params = array(), $pageSize = 10) {
        $this->params = $params;
        $this->searchFor = $searchFor;
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
     * Get the item title
     * @access public
     * @return string
     */
    abstract public function getTitle();

    /**
     * Validate to validate each parameter in the menu item againest the posted value.
     * @param Array $paramData the posted params data to validate it.
     * @access public
     * @return boolean
     */
    abstract public function validate($paramData);

    /**
     * Generate the array the contain the sisters data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    abstract public function generate($page = 1);

    /**
     * Return true if the sister class generate sisters data 
     * @access public
     * @return boolean
     */
    public function hasItems() {
        return $this->success;
    }

    /**
     * set PagingDatasetProvider based on PagingDataset
     * @param PagingDataset $pagingDataset
     * @param array $config
     */
    public function setDataProvider($pagingDataset, $config = array()) {
        $this->dataProvider = new PagingDatasetProvider($pagingDataset, $config);
    }

    /**
     * @return PagingDatasetProvider
     */
    public function getDataProvider() {
        return $this->dataProvider;
    }

    public function setSelectedRow($row) {
        $this->selectedRow = $row;
    }

    public function setGridColumns($cols) {
        $this->gridCols = $cols;
    }

    public function getGridColumns() {
        return $this->gridCols;
    }

}

