<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SectionArticlesData class,  gets articles as array list for a given sectionId
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionArticlesData extends Dataset {

    /**
     *
     * path to content images
     * @var string
     */
    protected $articleMediaPath;

    /**
     *
     * path to section images
     * @var string
     */
    protected $mediaPath;

    /**
     * If equal true and ArticlesListData.sectionId attribute is not equal 0 then we gets contents belong tho the section and sub sections
     * @var boolean
     */
    protected $useSubSections = true;

    /**
     * Article ListData instance
     * @var ArticlesListData
     */
    protected $articles;

    /**
     * The record number to start getting articles from it
     * @var int
     */
    protected $articlesFromRecord = 0;

    /**
     * Table name to get articles from,
     * @var string
     */
    private $_table;

    /**
     * Add extra columns to articles results
     * @var array
     */
    protected $articlesCols = array();

    /**
     * articles extra wheres syntax , to find articles according to values stored in this array
     * @var array
     */
    protected $articlesWheres = array();

    /**
     * articles extra join syntax , to join tables with parent records table
     * @var array
     */
    protected $articlesJoin = null;

    /**
     * Title length , if greater than 0 then we get the first SectionsArticlesData._titleLength characters from article tite
     * @var integer
     */
    protected $_titleLength = 0;

    /**
     * Section id to get content belong to it
     * @var integer
     */
    protected $sectionId = null;

    /**
     * Period time in seconds,
     * if atrribute value is greater than 0 then articles generated from this class must be between "current date" and "current date" subtracted from the value of this attribute
     * @var int
     */
    protected $period = 0;

    /**
     * If not equal null then articles generated from this class must be greater than or equal the value of this attribue
     * if period atrribute value is greater than 0 then
     * The value of this atrribute is calculated based on SectionsArticlesData.period and current date
     * @var string
     */
    protected $fromDate = NULL;

    /**
     * If not equal null then articles generated from this class must be less than or equal the value of this attribue
     * if period atrribute value is greater than 0 then
     * The value of this atrribute is calculated based on SectionsArticlesData.period and current date
     * @var string
     */
    protected $toDate = NULL;

    /**
     * Counstructor
     * @todo fix bug if $articlesLimit = 0
     * @param string $table, Table name to get articlies from
     * @param integer sectionId, Parent section id to get sub sections belong to it, equal null to get top parent sections
     * @param integer $limit, The numbers of articles to fetch from each section
     * @access public
     */
    public function __construct($table, $sectionId, $limit = 4) {
        $this->route = "/articles/default/sections";
        $this->_table = $table;
        $this->sectionId = (int) $sectionId;
        if($limit !== NULL){
            $this->limit = (int) $limit;
        }
        $this->mediaPath = Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['images']['path'] . "/";
    }

    /**
     * set media path
     * @param string $path
     * @access public
     * @return void
     */
    public function setArticleMediaPath($path) {
        $this->articleMediaPath = $path;
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
     * set period time in seconds,
     * Articles generated from this class must be between "current date" and "current date" subtracted from the value of this attribute
     * @param string $order
     * @access public
     * @return void
     */
    public function setPeriod($period) {
        $this->period = (int) $period;
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
     * Set the ArticlesListData.useSubSections flag.
     * If the given $useSubSections equal true and ArticlesListData.sectionId attribute is not equal 0 then we gets contents belong the section and sub sections
     * @param boolean $useSubSections
     * @access public
     * @return void
     */
    public function subSectionsInUsed($useSubSections) {
        $this->useSubSections = $useSubSections;
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
     * add $where to SectionArticlesData.articlesWheres array
     * @param string $where
     * @access public
     * @return void
     */
    public function addArticlesWhere($where) {
        $where = trim($where);
        $this->articlesWheres[md5($where)] = $where;
    }

    /**
     * append $join to SectionArticlesData.articlesJoin string
     * @param string $join
     * @access public
     * @return void
     */
    public function addArticlesJoin($join) {
        $this->articlesJoin .= " " . $join;
    }

    /**
     * add column to articlesCols attribute array
     * @param string $col
     * @param string $index
     * @access public
     * @return void
     */
    public function addArticlesColumn($col, $index = null) {
        $col = trim($col);
        if ($index) {
            $this->articlesCols[$index] = $col;
        } else {
            $this->articlesCols[$col] = $col;
        }
    }

    /**
     * Set Article title length
     * @param integer $length
     * @access public
     * @return void
     */
    public function setArticleTitleLength($length) {
        $this->_titleLength = $length;
    }

    /**
     * set the record number to start getting articles from it
     * @param integer $fromRecord
     * @access public
     * @return void
     */
    public function setArticlesFromRecord($fromRecord) {
        $this->articlesFromRecord = (int) $fromRecord;
    }

    /**
     *
     * Generate sections lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
            $this->addOrder(SectionsData::getDefaultSortOrder());
        }
        $this->setItems();
    }

    /**
     * Set the articles array list
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $this->addWhere("t.section_id = {$this->sectionId}");
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf(
                "select t.section_id , tt.section_name, image_ext {$cols} from sections t
                 inner join sections_translation tt on t.section_id = tt.section_id
            {$this->joins}
            where t.published = %d
            and tt.content_lang = %s
            {$wheres}
            {$orders} 
            limit %d, 1"
                , ActiveRecord::PUBLISHED
                , Yii::app()->db->quoteValue($siteLanguage)
                , $this->fromRecord
        );
        $section = Yii::app()->db->createCommand($this->query)->queryRow();
        $items = array();
        if ($section != null) {
            $index = -1;
            $articlesTables = array();
            if ($this->_table) {
                $articlesTables = array($this->_table);
            }
            $articles = new ArticlesListData($articlesTables, $this->period, $this->limit, $section['section_id']);
            if ($this->articleMediaPath) {
                $articles->setMediaPath($this->articleMediaPath);
            }
            $articles->subSectionsInUse($this->useSubSections);
            $articles->useRecordIdAsKey($this->recordIdAsKey);
            $articles->setFromDate($this->fromDate);
            $articles->setToDate($this->toDate);
            $articles->addColumn("create_date");
            $articles->setFromRecord($this->articlesFromRecord);
            $articles->setTitleLength($this->_titleLength);
            $articles->addColumn("publish_date");
            if (count($this->articlesCols)) {
                foreach ($this->articlesCols as $articlesColIndex => $articlesCol) {
                    $articles->addColumn($articlesCol, $articlesColIndex);
                }
            }
            if (count($this->articlesWheres)) {
                foreach ($this->articlesWheres as $articlesWhere) {
                    $articles->addWhere($articlesWhere);
                }
            }
            if ($this->articlesJoin) {
                $articles->addJoin($this->articlesJoin);
            }          
            $urlParams = array('id' => $section['section_id']);
            $articles->generate();
//            echo $articles->getQuery();
            $forwardModules = amcwm::app()->acl->getForwardModules();
            
            //if (count($articles->getItems())) {
            if ($this->recordIdAsKey) {
                $index = $section['section_id'];
            } else {
                $index++;
            }
            $items[$index]['sectionId'] = $section["section_id"];
            $items[$index]['sectionTitle'] = $section["section_name"];            
            foreach ($forwardModules as $moduleId => $forwardModule) {
                if ($this->getModuleName() == key($forwardModule)) {
                    $urlParams['module'] = $moduleId;
                    break;
                }
            }
            $items[$index]['sectionLink'] = Html::createUrl($this->route, $urlParams);

            $items[$index]['articlesCount'] = $articles->getCount();
            if (isset($section["image_ext"])) {
                $items[$index]['sectionImage'] = $this->mediaPath . $section["section_id"] . "." . $section["image_ext"];
            } else {
                $items[$index]['sectionImage'] = null;
            }
            $this->articles = $articles;
            foreach ($this->cols as $colIndex => $col) {
                $items[$index][$colIndex] = $section[$colIndex];
            }
            $this->items = $items[$index];
            //}
        }
    }

    /**
     * Get the articles ListData instance
     * @access public
     * @return ArticlesListData
     */
    public function getArticles() {
        return $this->articles;
    }

    /**
     * Get keywords for the current section
     * @access public
     * @return array
     */
    public function getKeywords() {
        $section = $this->getItems();
        $keywords = array();
        if (isset($section['sectionId'])) {
            $childs = Data::getInstance()->getSubSections($section['sectionId']);
            $keywords[md5(trim($section['sectionTitle']))] = trim($section['sectionTitle']);
            foreach ($childs as $keyword) {
                $keyword['data']['section_name'] = trim($keyword['data']['section_name']);
                $keywords[md5($keyword['data']['section_name'])] = $keyword['data']['section_name'];
            }
            if (isset($section['tags'])) {
                str_replace("\n\r", "\n", $section['tags']);
                $keywordsTags = explode("\n", $section['tags']);
                foreach ($keywordsTags as $keyword) {
                    $keyword = trim($keyword);
                    $keywords[md5($keyword)] = $keyword;
                }
            }
        }
        return $keywords;
    }

}
