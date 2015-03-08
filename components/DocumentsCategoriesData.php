<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectoryCategoriesData class,  gets Directory Categories  as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DocumentsCategoriesData extends Dataset {
    
     /**
     * Content language
     * @var integer 
     */
    protected $language = null;
    /**
     * The title index in array list
     * @var string
     */
    protected $titleIndex = "title";
    
    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $limit, The numbers of items to fetch from table     
     * @param integer $categoryId, The category id to get contents from, if equal null then we gets contents from all categories
     * @access public
     */
    public function __construct($limit = 10) {
        $this->limit = (int) $limit;        
        if(!$this->language){
            $this->language = Yii::app()->getLanguage();
        }
    }

    /**
     *
     * Generate glossary lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $this->addWhere('t.published = ' . ActiveRecord::PUBLISHED);
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the glossary array list    
     * @access private
     * @return void
     */
    protected function setItems() {        
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $limit = null;
        if($this->limit){
            $limit = "LIMIT {$this->fromRecord} , {$this->limit}";
        }
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            t.*, tt.*
            $cols
            FROM  `docs_categories` t
            INNER JOIN docs_categories_translation tt ON t.category_id = tt.category_id    
            {$this->joins}
            WHERE tt.content_lang = %s
            $wheres
            $orders
           $limit
            ", Yii::app()->db->quoteValue($this->language));
        $dir = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($dir);
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
     * Set the title index in array list
     * @param string $index
     * @access public
     * @return void
     */
    public function setTitleIndex($index) {
        $this->titleIndex = $index;
    }

    /**
     *
     * Sets the the items array      
     * @param array $articles 
     * @access protected     
     * @return void
     */
    protected function setDataset($dir) {
        $index = -1;
        foreach ($dir As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['category_id'];
            } else {
                $index++;
            }
            $this->items[$index]['id'] = $item["category_id"];
            $this->items[$index][$this->titleIndex] = $item["category_name"];
            
            if (count($this->cols)) {
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $item[$colIndex];
                }
            }
        }
        $this->count = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
    }
    
    
    public static function getTitle($catId){        
        $query = sprintf("select category_name from docs_categories_translation 
                 where category_id = %d and content_lang = %s
                ", $catId, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        return Yii::app()->db->createCommand($query)->queryScalar();
    }
}