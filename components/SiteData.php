<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SiteData class, Gets the contents "articles / videos / images" to displayed inside some widgets like news ticker area.
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class SiteData extends Dataset {
    /**
     * Content text type
     */
    const TEXT_TYPE = 1;
    /**
     * Content image type
     * @todo rename the mame
     */
    const IAMGE_TYPE = 2;
    /**
     * Content video type
     */
    const VIDEO_TYPE = 3;

    /**
     * Gets the module name that handle the output 
     * @var string 
     */
    protected $moduleName;
    /**
     * Title length , if greater than 0 then we get the first titleLength characters from content tite
     * @var integer 
     */
    protected $titleLength = 0;
    /**
     * Type of content item line, this could be one of the following, text, image or video
     * @var integer 
     */
    protected $type = 1;
    /**
     * Period time in seconds, 
     * If atrribute value is greater than 0 then articles generated from this class must be between "current date" and "current date" subtracted from the value of this attribute 
     * @var int 
     */
    protected $period = 0;
    /**
     * The section id to get contents from, if equal null then we gets contents from all sections
     * @var int 
     */
    protected $sectionId;
    /**
     * If equal true and SiteData.sectionId attribute is not equal 0 then we gets contents belong tho the section and sub sections
     * @var boolean
     */
    protected $useSubSections = true;
    /**
     * The numbers of items to fetch from table 
     * @var int 
     */
    protected $limit = 5;
    /**
     * Array contain's tables names to get data from, 
     * @var array 
     */
    protected $tables;
    /**
     * get archived articles or not, if equal 1 then get none-archived articles ,  2 get archived articles 0 get both.
     * @var integer
     */
    protected $archive = 1;
    /**
     *
     * path to content images
     * @var string
     */
    protected $mediaPath;
    /**
     * If not equal null then articles generated from this class must be greater than or equal the value of this attribue
     * if period atrribute value is greater than 0 then 
     * the value of this atrribute is calculated based on SiteData.period and current date
     * @var string
     */
    protected $fromDate = NULL;
    /**
     * If not equal null then articles generated from this class must be less than or equal the value of this attribue
     * if period atrribute value is greater than 0 then
     * the value of this atrribute is calculated based on SiteData.period and current date
     * @var string
     */
    protected $toDate = NULL;
    /**
     * date field to compare  SiteData.toDate or SiteData.fromDate with
     * @var string 
     */
    protected $dateCompareField = "create_date";

    /**
     * Set the SiteData.useSubSections flag.
     * If the given $useSubSections equal true and SiteData.sectionId attribute is not equal 0 then we gets contents belong the section and sub sections
     * @param boolean $useSubSections
     * @access public 
     * @return void
     */
    public function subSectionsInUse($useSubSections) {
        $this->useSubSections = $useSubSections;
    }

     /**
     * set media path      
     * @param string $path 
     * @access public 
     * @return void
     */
    public function setMediaPath($path) {
        $this->mediaPath = $path;
    }
    /**
     * set the section id to get data from
     * @param integer sectionId 
     * @access public 
     * @return void
     */
    public function setSectionId($sectionId) {
        if(!is_array($sectionId)){
            $sectionId = (int) $sectionId;
        }
        $this->sectionId = $sectionId;
    }

    /**
     * set fromDate
     * If the $date value is not equal null then then articles generated from this class must be greater than or equal the value of this attribue
     * @param string $order 
     * @access public 
     * @return void
     */
    public function setFromDate($date) {
        $this->fromDate = $date;
    }

    /**
     * set toDate
     * If the $date value is not equal null then then articles generated from this class must be greater than or equal the value of this attribue
     * @param string $order 
     * @access public 
     * @return void
     */
    public function setToDate($date) {
        $this->toDate = $date;
    }

    /**
     * Set the archive flag,
     * if equal 1 then get none-archived articles ,  2 get archived articles 0 get both.
     * @param integer $archive 
     * @access public 
     * @return void
     */
    public function setArchive($archive) {
        $this->archive = (int) $archive;
    }

    /**
     * Set Content title length
     * @param integer $length 
     * @access public 
     * @return void
     */
    public function setTitleLength($length) {
        $this->titleLength = $length;
    }   

}