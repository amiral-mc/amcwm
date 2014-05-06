<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * GlossaryContentData class.
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ContentData {

    /**
     * Content results assoicated array that contain's content results:
     * @var array 
     */
    protected $results = array();    
    /**
     * The numbers of items to fetch from content
     * @var int 
     */
    protected $limit;
    /**
     * Advanced parameters
     * @var array
     */
    protected $advancedParams = array('category' => null);

    /**
     * Counstructor, the content type
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $limit, The numbers of items to fetch from the glossary
     * @access public
     * 
     */
    public function __construct($limit = 15) {
        $this->limit = $limit;
    }

    /**
     * Sets advanced prameter inside GlossaryData.advancedParams
     * @access public
     * @param string $key
     * @param string $value 
     * @return void
     */
    public function setAdvancedParam($key, $value) {
        $this->advancedParams[$key] = $value;
    }

    /**
     * Get advanced parameters array
     * @access public
     * @return array 
     */
    public function getAdvancedParam() {
        return $this->advancedParams;
    }

    /**
     *
     * Generate content glossary results
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $this->set();
    }

    /**
     * Set the results assoicated array that contain's the glossary dataset
     * @access protected
     * @return void
     */
    abstract protected function set();

    /**
     * Get Content results
     * @access public
     * @return array
     */
    public function getResults() {
        $results = array(
            'records' => array(),
            'pager' => array(
                'count' => 0,
                'pageSize' => $this->limit,
            )
        );
        if (count($this->results)) {
            $results = $this->results;
        }
        return $results;
    }

}