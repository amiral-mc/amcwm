<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SectionsData class, gets sections data list for a given section id 
 * If the sectionId attribute is equal null then we get top parent sections
 * used to get related articles and most reads or comments 
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionsData extends Dataset {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * The section id to get contents from, if equal null then we gets contents from all sections
     * @var int 
     */
    protected $sectionId;

    /**
     * The title index in array list
     * @var string
     */
    protected $titleIndex = "title";

    /**
     * If true get the top parent sections
     * @var boolean
     */
    protected $topParentOnly = true;

    /**
     *
     * path to section images
     * @var string
     */
    protected $mediaPath;

    /**
     * If equal true and SiteData.sectionId attribute is not equal 0 then we gets contents belong tho the section and sub sections
     * @var boolean
     */
    protected $useSubSections = true;

    /**
     * Content language
     * @var integer 
     */
    protected $language = null;

    /**
     * Counstructor     
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param integer sectionId, Parent section id to get sub sections belong to it, equal null to get top parent sections
     * @param integer $limit, The numbers of sections to fetch from sections table 
     * @access public
     */
    public function __construct($table = null, $sectionId = null, $limit = 10) {
        $this->route = "/articles/default/sections";
        $this->sectionId = (int) $sectionId;
        if (!$this->language) {
            $this->language = Yii::app()->getLanguage();
        }
        if (is_string($table)) {
            switch ($table) {
                case 'events':
                    $this->joins .= " inner JOIN {$table} ON t.section_id = {$table}.section_id ";
                    break;
                case 'articles':
                    $this->addJoin("left join articles a on t.section_id = a.section_id");
                    if ($table != "articles")
                        $this->joins .= " inner JOIN {$table} ON t.section_id = {$table}.section_id ";

                    $articlesTables = ArticlesListData::getArticlesTables();
                    foreach ($articlesTables as $articleTable) {
                        $this->addJoin("left join {$articleTable} on a.article_id = {$articleTable}.article_id");
                        $this->addWhere("{$articleTable}.article_id is null");
                    }
                    break;
                default :
                    $this->addJoin("left join articles a on t.section_id = a.section_id");
                    if ($table != "articles") {
                        $this->joins .= " inner JOIN {$table} ON a.article_id = {$table}.article_id ";
                    }
            }
        } else {
            $articlesTables = ArticlesListData::getArticlesTables();
            foreach ($articlesTables as $articleTable) {
                $this->addJoin("left join {$articleTable} on a.article_id = {$articleTable}.article_id");
                $this->addWhere("{$articleTable}.article_id is null");
            }
        }


        $this->limit = (int) $limit;
        if ($this->limit == 0) {
            $this->limit = 10;
        }
        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths['images']['path'] . "/";
    }

    /**
     * Get sections setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("sections", false);
        }
        return self::$_settings;
    }

    /**
     * Get sections default sort order
     * @return string
     * @access public 
     */
    static public function getDefaultSortOrder() {
        $sorting = self::getSettings()->getTablesSoringOrders();
        $orderBy = null;
        if (isset($sorting['sections'])) {
            $orderBy = "{$sorting['sections']['sortField']} {$sorting['sections']['order']}";
        }
        return $orderBy;
    }

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
     * set content $language
     * @access public
     * @return void
     */
    public function setLanguage($language) {
        $this->language = $language;
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
     *
     * Generate sections lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
            $this->addOrder(self::getDefaultSortOrder());
        }
        if ($this->sectionId) {
            $this->addWhere("t.parent_section = {$this->sectionId}");
        } else if ($this->topParentOnly) {
            $this->addWhere("t.parent_section is null");
        }
        $this->setItems();
    }

    /**
     * If the given $ok equal true get the top parent sections
     * @param boolean $ok
     * @access public
     * @return void
     */
    public function getTopParentOnly($ok) {
        $this->topParentOnly = $ok;
    }

    /**
     * Set the title index in array list
     * @param string $index
     * @access public
     * @return void
     */
    public function setTitleIndex($index) {
        $this->titleIndex = $index;
    }

    /**
     * Set the sections array list    
     * @todo explain the query
     * @access protected
     * @return void
     */
    protected function setItems() {
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf(
                "select distinct sql_calc_found_rows t.section_id , t.image_ext , tt.section_name, tt.description {$cols} from sections t force index(idx_section_sort)
                 inner join sections_translation tt on t.section_id = tt.section_id
            {$this->joins}
            where t.published = %d
            and tt.content_lang = %s
            $wheres            
            {$orders} limit %d, %d "
                , ActiveRecord::PUBLISHED
                , Yii::app()->db->quoteValue($this->language)
                , $this->fromRecord
                , $this->limit
        );
        $sections = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->items = array();
        $forwardModules = amcwm::app()->acl->getForwardModules();
        if (count($sections)) {
            $index = -1;
            foreach ($sections as $section) {
                if ($this->recordIdAsKey) {
                    $index = $section['section_id'];
                } else {
                    $index++;
                }
                $urlParams = array('id' => $section['section_id']);
                foreach ($forwardModules as $moduleId => $forwardModule) {
                    if ($this->getModuleName() == key($forwardModule)) {
                        $urlParams['module'] = $moduleId;
                        break;
                    }
                }
                $this->items[$index]['id'] = $section["section_id"];
                $this->items[$index][$this->titleIndex] = $section["section_name"];
                $this->items[$index]['description'] = $section["description"];
                if (isset($section["image_ext"])) {
                    $this->items[$index]['imageExt'] = $section["image_ext"];
                    $this->items[$index]['image'] = $this->mediaPath . $section["section_id"] . "." . $section["image_ext"];
                } else {
                    $this->items[$index]['imageExt'] = null;
                    $this->items[$index]['image'] = null;
                }
                $this->items[$index]['link'] = Html::createUrl($this->route, $urlParams);
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $section[$colIndex];
                }
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

    /**
     * Get keywords for the current section
     * @todo need to implement
     * @access public
     * @return array 
     */
    public function getKeywords() {
        $keywords = array();
        return $keywords;
    }

    /**
     * get section settings for the given section id
     * @param int $sectionId
     * @return array
     */
    public static function getSectionSettings($sectionId) {
        $sectionSettings = Yii::app()->db->createCommand(
                        sprintf('select settings from sections where section_id = %d', $sectionId)
                )->queryScalar();

        return CJSON::decode($sectionSettings);
    }

}
