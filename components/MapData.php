<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MapData class, gets articles based on the given country 
 * @toda need to change the query to match the translation child concept
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MapData {

    /**
     * Articles array to be returned
     * @var array 
     */
    private $_items = array();
    /**
     * Route for viewing article item
     * @var string 
     */
    private $_route = "/articles/default/view";
    /**
     * Numbers of articles 
     * @var integer 
     */
    private $_maxArticles = 5;
    /**
     * Sections to get data from, if equal null then get data from all sections
     * @var array 
     */
    private $_sections = null;
    /**
     * Country code, get news from articles based on this country code
     * @var string 
     */
    private $_country = null;
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
     * Maximum charachters for article details
     * @var integer 
     */
    private $_infoChars = 100;

    /**
     * Counstructor
     * @param string $country 
     * @param integer $maxArticles
     * @param integer $days
     * @access public
     */
    public function __construct($country, $maxArticles = 5, $days = 1) {
        $this->_country = $country;
        $this->_maxArticles = $maxArticles;
        $this->_days = $days;
    }

    /**
     * Add section to MapData._sections array
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
     * MapData._days setter method
     * @param integer $days 
     * @access public
     * @return void
     */
    public function setDays($days) {
        $this->_days = $days;
    }

    /**
     * MapData._infoChars setter method
     * @param integer $chars 
     * @access public
     * @return void
     */
    public function setInfoChars($chars) {
        $this->_infoChars = $chars;
    }

    /**
     * MapData._days setter method
     * @param boolean $isBreaking 
     * @access public
     * @return void
     */
    public function setBreaking($isBreaking) {
        $this->_isBreaking = $isBreaking;
    }

    /**
     * MapData._maxArticles setter method
     * @param integer $maxArticles 
     * @access public
     * @return void
     */
    public function setMaxArticles($maxArticles) {
        $this->_maxArticles = $maxArticles;
    }

    /**
     * MapData._route setter method
     * @param string $route 
     * @access public
     * @return void
     */
    public function setRoute($route) {
        $this->_route = $route;
    }

    /**
     * MapData._items setter method, add articles to MapData._items array
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
        $articlesQuery = sprintf("select 
            a.article_id, article_header, article_detail, thumb , publish_date
            from articles a                            
            inner join news n on n.article_id = a.article_id 
            where a.published = %d
            and a.publish_date <= '$currentDate'
            and a.country_code = %s
            $sectionsWheres
            and a.create_date >= '{$createDate}'
            and (a.content_lang = %s or a.content_lang is null or a.content_lang ='')
            and (a.archive = 0 or a.archive is null)
            and (a.expire_date >='$currentDate' or a.expire_date is null)
            $whereArticleType
            order by a.create_date desc limit 0 , %d", 
                    ActiveRecord::PUBLISHED, 
                    Yii::app()->db->quoteValue($this->_country), 
                    Yii::app()->db->quoteValue($siteLanguage), 
                    $this->_maxArticles
        );
        $dataSet = Yii::app()->db->createCommand($articlesQuery)->queryAll();
        foreach ($dataSet as $rowIndex => $row) {
            $this->_items[$rowIndex]['title'] = $row['article_header'];
            $this->_items[$rowIndex]['link'] = Html::createUrl($this->_route, array('id' => $row['article_id']));
            if (is_file($imagesPath . $row["article_id"] . "." . $row["thumb"])) {
                $this->_items[$rowIndex]['image'] = $images . $row["article_id"] . "." . $row["thumb"];
            } else {

                $this->_items[$rowIndex]['image'] = Yii::app()->baseUrl . "/images/front/{$siteLanguage}/no_image.jpg";
            }

            $this->_items[$rowIndex]['info'] = Html::utfSubstring($row['article_detail'], 0, $this->_infoChars);
            $this->_items[$rowIndex]['publish_date'] = $row['publish_date'];
        }
    }

    /**
     * MapData._items getter method
     * @access public
     * @return array 
     */
    public function getItems() {
        if (!count($this->_items)) {
            $this->setItems();
        }
        return $this->_items;
    }

}