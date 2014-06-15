<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * NewsSectionChilds class, generate generate news section childs and append it to the menu
 * Any menu module class must extend this class
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionSectionsListChilds extends MenuModuleChilds {

    /**
     * @var string module name
     */
    protected $moduleName;
    /**
     * Initilize the class data
     * @access protected
     * @return void
     */
    protected function init() {
        $this->setRoute("articles/default/sections");
        if ($this->id) {
            $this->addWhere("t.parent_section = " . (int) $this->id);
        } else {
            $this->addWhere("t.parent_section is null");
        }        
        if (!count($this->orders)) {
            $this->addOrder(SectionsData::getDefaultSortOrder());
        }
    }   

    /**
     *
     * Generate child list
     * @access public
     * @return void
     */
    public function generate() {
        
        $this->addJoin("inner join articles a on a.section_id = t.section_id");
        $articlesTables = ArticlesListData::getArticlesTables();
        if ($this->moduleName) {            
            switch ($this->moduleName) {

                case "news":
                    $this->addJoin("inner join news n on n.article_id = a.article_id");
                    break;
                case "essays":
                    $this->addJoin("inner join essays on essays.article_id = a.article_id");
                    break;
            }
        } else {
            foreach ($articlesTables as $articleTable) {
                $this->addJoin("left join {$articleTable} on a.article_id = {$articleTable}.article_id");
                $this->addWhere("{$articleTable}.article_id is null");
            }
        }
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the sections menu array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf(
                "select distinct t.section_id , tt.section_name {$cols} from sections t
                inner join sections_translation tt on t.section_id = tt.section_id
            {$this->joins}
            where           
            t.published=1            
            and tt.content_lang = %s
            $wheres            
            {$orders} limit %d "
                , Yii::app()->db->quoteValue($siteLanguage)
                , $this->limit
        );
        $menuItems = Yii::app()->db->createCommand($this->query)->queryAll();
        $index = 0;
        foreach ($menuItems as $menuItem) {
            $this->items[$index]['label'] = $menuItem['section_name'];
            $this->items[$index]['url'] = $this->generateUrl(array("id" => $menuItem['section_id'], "title" => $menuItem['section_name'],));
            //$this->items[$index]['active'] = $menuItem['link'];
            $index++;
        }
    }

}
