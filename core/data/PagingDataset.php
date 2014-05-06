<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * PagingDataset class, create dataset array for paging
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class PagingDataset {

    /**
     * The page size , number of records displayed in each page
     * @var integer
     */
    protected $pageSize = 10;

    /**
     * Current page number
     * @var int 
     */
    protected $page = 1;

    /**
     * The dataset object to get data from
     * @var Dataset
     */
    protected $dataset = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * If the count of dataset is equal 0 then call the generate method from $dataset instance
     * @param Dataset $dataset, The dataset object to get data from
     * @param integer $pageSize, The page size , number of records displayed in each page
     * @param integer $page, Current page number     
     * @access public
     */
    public function __construct(Dataset $dataset, $pageSize = 10, $page = 1) {
        $page = (int) $page;
        $pageSize = (int) $pageSize;
        if (!$page) {
            $this->page = 1;
        } else {
            $this->page = abs($page);
        }
        $this->pageSize = $pageSize;
        $this->dataset = $dataset;
        if (!$this->dataset->getCount()) {
            $this->dataset->setLimit($this->pageSize);
            $this->dataset->setFromRecord(($this->page - 1 ) * $this->pageSize);
            $this->dataset->generate();
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
    public function getData() {
        $data = array(
            'records' => array(),
            'pager' => array(
                'count' => 0,
                'pageSize' => $this->pageSize,
            )
        );
        if ($this->dataset instanceof Dataset) {
            $data['records'] = $this->dataset->getItems();
            $data['pager']['count'] = $this->dataset->getCount();
        }
        return $data;
    }

    /**
     * Get route router for viewing content details from PagingDataset.dataset
     * @access public 
     * @return string
     */
    public function getRoute() {
        return $this->dataset->getRoute();
    }
    
     /**
     * Get the pagging dataset
     * @access public 
     * @return Dataset
     */
    public function getDataset() {
        return $this->dataset;
    }

}