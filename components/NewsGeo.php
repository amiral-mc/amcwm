<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * NewsGeo get countries where there is news coming from.
 * @toda need to change the query to match the translation child concept
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class NewsGeo {

    /**
     * countries array to be returned
     * @var array 
     */
    private $_items = array();
    /**
     * Sections to get data from, if equal null then get data from all sections
     * @var array 
     */
    private $_sections = null;
    /**
     * Number of days, get articles with in this days
     * @var string 
     */
    private $_days = 1;
    /**
     * If equal true then get data from breaking news
     * @var boolean 
     */
    private $_isBreaking = false;
    /**
     * The Singleton Data instance.
     * @var NewsGeo
     * @static
     * @access private
     */
    private static $_instance = null;
    /**
     * Numbers of articles 
     * @var integer 
     */
    private $_maxArticles = 5;
    /**
     * Durations images and time
     * @var array 
     */
    private $_durations = array(
        'red' => 1, 'orange' => 12, 'blue' => 13
    );

    /**
     * Counstructor
     * Constructor, this NewsGeo implementation is a Singleton.
     * You should not call the constructor directly, but instead call the static Singleton factory method AnaNewsGeo.getInstance().<br />
     * @param integer $maxArticles
     * @param integer $days
     * @access private
     */
    private function __construct($maxArticles = 5, $days = 1) {
        $this->_maxArticles = $maxArticles;
        $this->_days = $days;
    }

    /**
     * Factory Singleton Data method.
     * @static
     * @param integer $maxArticles
     * @param integer $days
     * @access public
     * @return NewsGeo the Singleton instance of the Config
     */
    public static function &getInstance($maxArticles = 5, $days = 1) {
        if (self::$_instance == null) {
            self::$_instance = new self($maxArticles, $days);
        }
        return self::$_instance;
    }

    /**
     * Add section to NewsGeo._sections array
     * @param integer $sectionId 
     * @access public
     * @return void
     */
    public function addSection($sectionId) {
        if (!isset($this->_sections[$sectionId])) {
            $this->_sections[$sectionId] = $sectionId;
        }
    }

    /**
     * AnaNewsGeo._days setter method
     * @param integer $days 
     * @access public
     * @return void
     */
    public function setDays($days) {
        $this->_days = $days;
    }

    /**
     * NewsGeo._infoChars setter method
     * @param integer $chars 
     * @access public
     * @return void
     */
    public function setInfoChars($chars) {
        $this->_infoChars = $chars;
    }

    /**
     * NewsGeo._days setter method
     * @param boolean $isBreaking 
     * @access public
     * @return void
     */
    public function setBreaking($isBreaking) {
        $this->_isBreaking = $isBreaking;
    }

    /**
     * NewsGeo._maxArticles setter method
     * @param integer $maxArticles 
     * @access public
     * @return void
     */
    public function setMaxArticles($maxArticles) {
        $this->_maxArticles = $maxArticles;
    }

    /**
     * NewsGeo._route setter method
     * @param string $route 
     * @access public
     * @return void
     */
    public function setRoute($route) {
        $this->_route = $route;
    }

    /**
     * NewsGeo._items setter method, add articles to NewsGeo._items array
     * @access public
     * @return void
     */
    function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $images = Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths['list']['path'] . "/";
        $imagesPath = Yii::app()->basePath . "/../" . ArticlesListData::getSettings()->mediaPaths['list']['path'] . "/";
        $createDate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")) - $this->_days * 24 * 60 * 60);
        $sectionsTree = array();
        $sectionsWheres = null;
        if ($this->_sections !== null && is_array($this->_sections)) {
            foreach ($this->_sections as $sectionId) {
                $sectionsTree[$sectionId] = $sectionId;
                $sectionsTree = array_merge(Data::getInstance()->getSectionSubIds($sectionId), $sectionsTree);
            }
            $sectionsWheres = " and (a.section_id in (" . implode(',', $sectionsTree) . ")) ";
        }
        $currentDate = date("Y-m-d H:i:s");
        $whereArticleType = " and n.is_breaking <> 1 ";
        if ($this->_isBreaking) {
            $whereArticleType = " and n.is_breaking = 1 ";
        }
        $query = sprintf("select 
            max(a.create_date) max_create , c.code country, c.longitude, c.latitude , c.country_{$siteLanguage} title 
            from articles a                            
            inner join countries c on c.code = a.country_code
            inner join news n on n.article_id = a.article_id 
            where a.published = %d
            and a.publish_date <= '$currentDate'
            $sectionsWheres
            and a.create_date >= '{$createDate}'
            and (a.content_lang = %s or a.content_lang is null or a.content_lang ='')
            and (a.archive = 0 or a.archive is null)
            and (a.expire_date >='$currentDate' or a.expire_date is null)
            $whereArticleType
            group by c.code
            ", ActiveRecord::PUBLISHED, 
                    Yii::app()->db->quoteValue($siteLanguage)
        );
        $items = Yii::app()->db->createCommand($query)->queryAll();
        $this->_items['count'] = 0;
        $this->_items['countries'] = array();
        foreach ($items as $itemIndex => $item) {
            
            $this->_items['countries'][$itemIndex]['country'] = $item['country'];
            $this->_items['countries'][$itemIndex]['longitude'] = $item['longitude'];
            $this->_items['countries'][$itemIndex]['latitude'] = $item['latitude'];
            $this->_items['countries'][$itemIndex]['title'] = $item['title'];
            $maxTime = (time() - strtotime($item['max_create'])) / (60 * 60 );
            if ($maxTime <= $this->_durations['red']) {
                $this->_items['countries'][$itemIndex]['duration'] = 'red';
                $this->_items['countries'][$itemIndex]['img'] = 'red';
                
            } else if ($maxTime <= $this->_durations['orange']) {
                $this->_items['countries'][$itemIndex]['duration'] = 'orange';
                $this->_items['countries'][$itemIndex]['img'] = 'orange';
            } else {
                $this->_items['countries'][$itemIndex]['duration'] = 'blue';
                $this->_items['countries'][$itemIndex]['img'] = 'blue';
            }
        }
        $this->_items['count'] = count($this->_items['countries']);
    }

    /**
     * NewsGeo._items getter method
     * @access public
     * @return array 
     */
    public function getItems() {
        if (!count($this->_items)) {
            $this->setItems();
        }
        return $this->_items;
    }

    /**
     * NewsGeo._sections getter method
     * @access public
     * @return array 
     */
    public function getSections() {
        return $this->_sections;
    }

    /**
     * NewsGeo._maxArticles getter method
     * @access public
     * @return integer 
     */
    public function getMaxArticles() {
        return $this->_maxArticles;
    }

    /**
     * NewsGeo._days getter method
     * @access public
     * @return integer 
     */
    public function getDays() {
        return $this->_days;
    }

    /**
     * NewsGeo._isBreaking getter method
     * @access public
     * @return boolean 
     */
    public function isBreaking() {
        return $this->_isBreaking;
    }
}