<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * GlossaryListData class,  gets glossary as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GlossaryListData extends Dataset {

    protected $categoryId = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $limit, The numbers of items to fetch from table     
     * @param integer $categoryId, The category id to get contents from, if equal null then we gets contents from all categories
     * @access public
     */
    public function __construct($limit = 10) {
//        $this->route = "/site/glossary";
        $this->limit = (int) $limit;
    }

    /**
     *
     * Generate glossary lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
            $this->addOrder("t.expression ASC");
        }
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the glossary array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if ($this->categoryId) {
            $this->addWhere("t.category_id = {$this->categoryId}");
        }
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            t.expression_id, t.expression, t.category_id, tt.meaning, tt.description
            $cols
            FROM  `glossary` t
            INNER JOIN glossary_translation tt ON t.expression_id = tt.expression_id    
            {$this->joins}
            WHERE tt.content_lang = %s
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ", Yii::app()->db->quoteValue($siteLanguage));
        $glossary = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($glossary);
    }

    /**
     *
     * Sets the the items array      
     * @param array $articles 
     * @access protected     
     * @return void
     */
    protected function setDataset($glossary) {
        $index = -1;
        foreach ($glossary As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['expression_id'];
            } else {
                $index++;
            }
            $this->items[$index]['id'] = $item["expression_id"];
            $this->items[$index]['expression'] = $item["expression"];
            $this->items[$index]['title'] = $item["meaning"];
            $this->items[$index]['description'] = $item["description"];

            if (count($this->cols)) {
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $item[$colIndex];
                }
            }
        }
        $this->count = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
    }

    public function setCategory($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function getCategory() {
        return $this->categoryId;
    }

}