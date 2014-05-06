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
class SectionArticlesListChilds extends MenuModuleChilds {

    /**
     * Initilize the class data
     * @access protected
     * @return void
     */
    protected function init() {
        $this->setRoute("articles/default/view");
        if ($this->id) {
            $this->addWhere("t.section_id = " . (int) $this->id);
        }        
    }

    /**
     *
     * Generate child list
     * @access public
     * @return void
     */
    public function generate() {
        if ($this->id) {            
            $forwardModules = amcwm::app()->acl->getForwardModules();
            $articlesTables = ArticlesListData::getArticlesTables();
            if (isset($forwardModules[$this->moduleId])) {
                $module = key($forwardModules[$this->moduleId]);
                switch ($module) {
                    case "news":
                        $this->addJoin("inner join news n on n.article_id = t.article_id");
                        break;
                }
            } else {
                foreach ($articlesTables as $articleTable) {
                    $this->addJoin("left join {$articleTable} on t.article_id = {$articleTable}.article_id");
                    $this->addWhere("{$articleTable}.article_id is null");
                }
            }            
            $this->init();
            $this->setItems();
        }
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
                "SELECT 
            t.article_id, tt.article_header $cols
            FROM  `articles` t                    
            inner join articles_translation tt on t.article_id = tt.article_id    
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
            $this->items[$index]['label'] = $menuItem['article_header'];
            $this->items[$index]['url'] = $this->generateUrl(array("id" => $menuItem['article_id'], "title" => $menuItem['article_header'],));
            //$this->items[$index]['active'] = $menuItem['link'];
            $index++;
        }
    }

}