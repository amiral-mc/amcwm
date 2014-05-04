<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SectionsArticlesData class, gets articles as array list
 * used to get related articles and most reads or comments 
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionsArticlesData extends SectionArticlesData {

    /**
     * articles limit for each section
     * @var integer 
     */
    protected $articlesLimit;

    /**
     * Counstructor     
     * @todo fix bug if $articlesLimit = 0
     * @todo fix bug if $sectionsLimit = 0
     * @param string $table, Table name to get articlies from
     * @param integer sectionId, Parent section id to get sub sections belong to it, equal null to get top parent sections
     * @param integer $articlesLimit, The numbers of articles to fetch from each section
     * @param integer $sectionsLimit, The numbers of sections to fetch from sections table 
     * @access public
     */
    public function __construct($table = null, $sectionId = null, $articlesLimit = 4, $sectionsLimit = 4) {
        $this->route = "/articles/default/sections";
        $this->table = $table;
        $this->sectionId = (int) $sectionId;
        $this->articlesLimit = (int) $articlesLimit;
        $this->limit = (int) $sectionsLimit;
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
        $this->addWhere("t.parent_section = {$this->sectionId}");
        $this->setItems();
    }

    /**
     * Set the sections array list    
     * @access protected
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();

        $this->query = sprintf(
                "select t.section_id , tt.section_name {$cols} from sections t force index(idx_section_sort)
                 inner join sections_translation tt on t.section_id = tt.section_id
            {$this->joins}
            where t.published = %d
            and tt.content_lang = %s
            {$wheres}
            {$orders}
            limit %d, %d "
                , ActiveRecord::PUBLISHED
                , Yii::app()->db->quoteValue($siteLanguage)
                , $this->fromRecord
                , $this->limit
        );
        $sections = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->items = array();
        if (count($sections)) {
            $index = -1;
            $articlesTables = array();
            if ($this->table) {
                $articlesTables = array($this->table);
            }
            $forwardModules = amcwm::app()->acl->getForwardModules();
            foreach ($sections as $section) {
                $articles = new ArticlesListData($articlesTables, $this->period, $this->articlesLimit, $section['section_id']);
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
                $articles->generate();
                if (count($articles->getItems())) {
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
                    $this->items[$index]['sectionId'] = $section["section_id"];
                    $this->items[$index]['sectionTitle'] = $section["section_name"];
                    $this->items[$index]['sectionLink'] = Html::createUrl($this->route, $urlParams);
                    $this->items[$index]['articles'] = $articles->getItems();
                    $this->items[$index]['articlesCount'] = $articles->getCount();
                }
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $section[$colIndex];
                }
            }
        }
    }

}
