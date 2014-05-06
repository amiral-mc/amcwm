<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * DocumentsListData class,  gets documents as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DocumentsListData extends Dataset {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Document category id
     * @var integer 
     */
    protected $categoryId = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $limit, The numbers of items to fetch from table     
     * @param integer $categoryId, The category id to get contents from, if equal null then we gets contents from all categories
     * @access public
     */
    public function __construct($limit = 10) {
        $this->limit = (int) $limit;
    }

    /**
     * Get module setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("documents", false);
        }
        return self::$_settings;
    }

    /**
     *
     * Generate glossary lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $sorting = self::getSettings()->getTablesSoringOrders();        
        $this->addWhere('t.published = ' . ActiveRecord::PUBLISHED);
        if (!count($this->orders)) {
            if(isset($sorting['docs'])){
                $this->addOrder("{$sorting['docs']['sortField']} {$sorting['docs']['order']}");
            }
            else{
                $this->addOrder("tt.title ASC");
            }            
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
        else{
            $this->addWhere("t.category_id is not null");
        }
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            t.*, tt.*
            $cols
            FROM  `docs` t
            INNER JOIN docs_translation tt ON t.doc_id = tt.doc_id    
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
                $index = $item['doc_id'];
            } else {
                $index++;
            }
            $this->items[$index]['id'] = $item["doc_id"];
            $this->items[$index]['title'] = $item["title"];
            $this->items[$index]['description'] = Html::utfSubstring($item["description"], 0, 100);
            $this->items[$index]['publish_date'] = $item["start_date"];
            $this->items[$index]['file_lang'] = $item["file_lang"];
            

            $this->items[$index]['file_ext'] = $item["file_ext"];

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