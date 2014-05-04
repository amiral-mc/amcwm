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
class DirectoryListData extends Dataset {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Company category id
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
            self::$_settings = new Settings("directory", false);
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
            if (isset($sorting['dir_companies'])) {
                $this->addOrder("{$sorting['dir_companies']['sortField']} {$sorting['dir_companies']['order']}");
            } else {
                $this->addOrder("tt.company_name ASC");
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
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $limit = null;
        if ($this->limit) {
            $limit = "LIMIT {$this->fromRecord} , {$this->limit}";
        }
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            t.*, tt.*, c.settings
            $cols
            FROM  `dir_companies` t
            INNER JOIN dir_companies_translation tt ON t.company_id = tt.company_id    
            left JOIN dir_categories c ON t.category_id = c.category_id 
            {$this->joins}
            WHERE tt.content_lang = %s
            and t.accepted = 1
            $wheres
            $orders
           $limit
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
        $mediaSettings = self::getSettings()->mediaSettings;
        $options = self::getSettings()->options['default'];
        $index = -1;
        foreach ($glossary As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['company_id'];
            } else {
                $index++;
            }
            $this->setInternalDataset($item, $index, $mediaSettings, $options);
            $this->afterFillRow($item, $index, $mediaSettings, $options);
        }
        $this->count = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
    }

    protected function afterFillRow($item, $index, $mediaSettings, $options) {
        
    }
    /**
     * 
     * @param type $item
     * @param type $index
     * @param type $mediaSettings
     * @param type $options
     */
    protected function setInternalDataset($item, $index, $mediaSettings, $options) {
        $this->items[$index]['id'] = $item["company_id"];
        $this->items[$index]['company_name'] = $item["company_name"];
        $this->items[$index]['company_address'] = $item["company_address"];
        $this->items[$index]['city'] = $item["city"];
        $this->items[$index]['nationality'] = $item["nationality"];
        $this->items[$index]['email'] = $item["email"];
        $this->items[$index]['phone'] = $item["phone"];
        $this->items[$index]['mobile'] = $item["mobile"];
        $this->items[$index]['fax'] = $item["fax"];
        $this->items[$index]['description'] = Html::utfSubstring($item["description"], 0, 300);
        $this->items[$index]['image'] = null;
        if ($this->route) {
            $this->items[$index]['link'] = array($this->route, 'id' => $item['company_id'], 'title' => $item['company_name']);
        }
        $this->items[$index]['attach'] = null;
        if ($item["image_ext"]) {
            $this->items[$index]['image'] = Yii::app()->baseUrl . "/{$mediaSettings['paths']['images']['path']}/{$item["company_id"]}.{$item["image_ext"]}";
        }
        if ($item["file_ext"]) {
            $this->items[$index]['attach'] = "{$mediaSettings['paths']['attach']['path']}/{$item["company_id"]}.{$item['file_ext']}";
        }
        $this->items[$index]['settings'] = CJSON::decode($item['settings']);
        if (!$this->items[$index]['settings']) {
            $this->items[$index]['settings'] = $options;
        }

        if ($this->checkIsActive && $this->route) {
            $this->items[$index]['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("id" => $item['company_id']));
        }

        if (count($this->cols)) {
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $item[$colIndex];
            }
        }
    }

    public function setCategory($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function getCategory() {
        return $this->categoryId;
    }

}