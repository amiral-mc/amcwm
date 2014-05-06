<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * SectionSectionsData class,  gets sections as array list for a given sectionId
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionSectionsData extends Dataset {

    /**
     *
     * path to content images
     * @var string
     */
    protected $sectionMediaPath;

    /**
     *
     * path to section images
     * @var string
     */
    protected $mediaPath;

    /**
     * If equal true and SectionSectionsData.sectionId attribute is not equal 0 then we gets contents belong tho the section and sub sections
     * @var boolean
     */
    protected $useSubSections = true;

    /**
     * SectionsData instance
     * @var SectionsData
     */
    protected $sections;   

    /**
     * Table name to get sections with kind of articles from,
     * @var string
     */
    private $_table;    
      
    /**
     * Section id to get content belong to it
     * @var integer
     */
    protected $sectionId = null;        

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
        $this->limit = (int) $limit;        
        $this->mediaPath = Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['images']['path'] . "/";
    }
   
    /**
     * set media path
     * @param string $path
     * @access public
     * @return void
     */
    public function setSectionMediaPath($path) {
        $this->sectionMediaPath = $path;
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
                "select t.section_id , tt.section_name, tt.description, image_ext {$cols} from sections t
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
            $sections = new SectionsData($this->_table, $section['section_id'], $this->limit);
            if ($this->sectionMediaPath) {
                $sections->setMediaPath($this->sectionMediaPath);
            }
            $sections->subSectionsInUse($this->useSubSections);
            $sections->useRecordIdAsKey($this->recordIdAsKey);      
            $sections->generate();
            $forwardModules = amcwm::app()->acl->getForwardModules();
            if (count($sections->getItems())) {
                if ($this->recordIdAsKey) {
                    $index = $section['section_id'];
                } else {
                    $index++;
                }
                $items[$index]['sectionId'] = $section["section_id"];
                $items[$index]['sectionTitle'] = $section["section_name"];      
                $items[$index]['sectionDescription'] = $section["description"];                      
                $urlParams = array('id' => $section['section_id']);
                foreach ($forwardModules as $moduleId => $forwardModule) {
                    if ($this->getModuleName() == key($forwardModule)) {
                        $urlParams['module'] = $moduleId;
                        break;
                    }
                }       
                $items[$index]['sectionLink'] = Html::createUrl($this->route, $urlParams);
                $items[$index]['articlesCount'] = $sections->getCount();                
                if (isset($section["image_ext"])) {
                    $items[$index]['sectionImage'] = $this->mediaPath . $section["section_id"] . "." . $section["image_ext"];
                } else {
                    $items[$index]['sectionImage'] = null;
                }                
                $this->sections = $sections;
                foreach ($this->cols as $colIndex => $col) {
                    $items[$index][$colIndex] = $section[$colIndex];
                }
                $this->items = $items[$index];
            }
        }
    }

    /**
     * Get the articles ListData instance
     * @access public
     * @return SectionsData
     */
    public function getSections() {
        return $this->sections;
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
