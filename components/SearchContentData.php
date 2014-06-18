<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * SearchContentData class.  
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class SearchContentData {

    /**
     * Content results assoicated array that contain's content results:
     * @var array 
     */
    protected $results = array();

    /**
     * Content type multimedia  or text
     * @var string
     */
    protected $contentType;

    /**
     * The numbers of items to fetch from content
     * @var int 
     */
    protected $limit;

    /**
     * Tables contain's information to get data from, its array contain's tables list ,  that contain's following items:
     * <ul>
     * <li>text:array articles array list
     * <li>multimedia:array multimedia array list
     * <ul>
     * @var array 
     */
    protected $tables = array("news" => array('news' => 'news'), "articles" => array('articles' => 'articles'), "essays" => array('essays' => 'essays'), "videos" => array('videos' => "videos"), "images" => array('images' => "images"));

    /**
     * Advanced parameters
     * @var array
     */
    protected $advancedParams = array('archive' => 1, 'contentType' => array('news' => 1, 'essays'=>1 ,'articles' => 1, 'videos' => 1, 'images' => 1), 'section' => null, 'date' => array());

    /**
     * Counstructor, the content type
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param string $contentType, Content type multimedia  or text   
     * @param integer $limit, The numbers of items to fetch from articles or videos 
     * @access public
     * 
     */
    public function __construct($contentType = "news", $limit = 15) {
        $this->limit = $limit;
        switch ($contentType) {
            case 'news':
                if (!$this->advancedParams['contentType']['news']) {
                    $contentType = 'multimedia';
                }
                break;
            case 'articles':
                if (!$this->advancedParams['contentType']['articles']) {
                    $contentType = 'articles';
                }
                break;
            case 'essays':
                if (!$this->advancedParams['contentType']['essays']) {
                    $contentType = 'essays';
                }
                break;
            case 'videos':
                if (!$this->advancedParams['contentType']['videos']) {
                    $contentType = 'videos';
                }
                break;
            case 'images':
                if (!$this->advancedParams['contentType']['images']) {
                    $contentType = 'images';
                }
                break;     
        }
        $this->contentType = $contentType;
    }

    /**
     * Sets advanced prameter inside SearchData.advancedParams
     * @access public
     * @param string $key
     * @param string $value 
     * @return void
     */
    public function setAdvancedParam($key, $value) {
        $this->advancedParams[$key] = $value;
    }

    /**
     * Sets table to got content from
     * @access public
     * @param string $contentType , Table content type , text or multimedia
     * @param string $table table name to fot content from
     * @return void
     */
    public function setTable($contentType, $table) {
        if (isset($this->tables[$contentType])) {
            $this->tables[$contentType][$table] = $table;
        }
    }

    /**
     * Get advanced paramers array
     * @access public
     * @return array 
     */
    public function getAdvancedParam() {
        return $this->advancedParams;
    }

    /**
     *
     * Generate content articles results
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $this->set();
    }

    /**
     * Set the results assoicated array that contain's following items:
     * <ul>
     * <li>text:array News dataset
     * <li>videos:array Vidoes daatset
     * <ul>
     * @access protected
     * @return void
     */
    abstract protected function set();

    /**
     * get Content type 
     * @access public
     * @return string     
     */
    public function getContentType() {
        return $this->contentType;
    }

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