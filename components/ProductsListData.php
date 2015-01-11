<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ProductsListData class, gets products as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ProductsListData extends SiteData {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Product language
     * @var integer 
     */
    protected $language = null;

    /**
     * Auto generate data set
     * @var boolean 
     */
    protected $generateDataset = true;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @todo fix bug if $limit = 0
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param integer $period, Period time in seconds. 
     * @param integer $limit, The numbers of items to fetch from table     
     * @param integer $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct($tables = array(), $period = 0, $limit = 10, $sectionId = null) {
        if (!$this->language) {
            $this->language = Yii::app()->getLanguage();
        }
        $this->route = "/products/default/view";
        $this->period = $period;
        if ($limit !== NULL) {
            $this->limit = (int) $limit;
        } else {
            $this->limit = null;
        }
        $this->tables = $tables;
        $this->sectionId = $sectionId;
        $wheresForTables = array();
        $tablesCount = count($this->tables);
        if ($tablesCount == 1) {
            $table = current($this->tables);
            if ($table != "products") {
                $this->joins .= " JOIN {$table} ON t.product_id = {$table}.product_id ";
            }
        } else if ($tablesCount > 1) {
            foreach ($this->tables as $table) {
                if ($table != "products") {
                    $this->joins .= " LEFT JOIN {$table} ON t.product_id = {$table}.product_id ";
                    $wheresForTables[] = " {$table}.product_id is not null ";
                }
            }
            $this->addWhere("(" . implode(" or ", $wheresForTables) . ")");
        }
    }

    /**
     * Get products setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("products", false);
        }
        return self::$_settings;
    }

    /**
     * Auto generate dataset
     * @param boolean $ok
     */
    public function setAutoGenerate($ok) {
        $this->generateDataset = $ok;
    }

    /**
     *
     * Generate products lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if ($this->period) {
            $this->toDate = date('Y-m-d 23:59:59');
            $this->fromDate = date('Y-m-d 00:00:01', time() - $this->period);
        }
        if ($this->fromDate) {
            $this->addWhere("t.{$this->dateCompareField} >= '{$this->fromDate}'");
        }
        if ($this->toDate) {
            $this->addWhere("t.{$this->dateCompareField} <='{$this->toDate}'");
        }
        if (!count($this->orders)) {
            $sorting = self::getSettings()->getTablesSoringOrders();
            if (isset($sorting)) {
                $this->addOrder("{$sorting['sortField']} {$sorting['order']}");
            } else {
                $this->addOrder("hits desc");
            }
        }
        $this->setItems();
    }

    /**
     * set product $language
     * @access public
     * @return void
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * @todo explain the query
     * Set the products array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $currentDate = date("Y-m-d H:i:s");
        $sectionsList = array();
        if ($this->sectionId) {
            if ($this->useSubSections) {
                if (is_array($this->sectionId)) {
                    $sections = $this->sectionId;
                    foreach ($sections as $section) {
                        $sectionList = Data::getInstance()->getSectionSubIds($section);
                        $sectionList[] = (int) $section;
                        if (is_array($sectionList) && $sectionList) {
                            $sectionsList = array_merge($sectionsList, $sectionList);
                        }
                    }
                } else {
                    $sectionsList = Data::getInstance()->getSectionSubIds($this->sectionId);
                    $sectionsList[] = (int) $this->sectionId;
                }
                $this->addWhere("(t.section_id IN (" . implode(',', $sectionsList) . "))");
            } else {
                $this->addWhere("t.section_id = {$this->sectionId}");
            }
        }
        $orders = $this->generateOrders(NULL);
        $cols = $this->generateColumns();
        $wheres = sprintf("tt.content_lang = %s
         AND t.publish_date <= '{$currentDate}'
         AND (t.expire_date  >= '{$currentDate}' OR t.expire_date IS null)
         AND t.published = %d", Yii::app()->db->quoteValue($this->language), ActiveRecord::PUBLISHED);
        $wheres .= $this->generateWheres();
        $command = AmcWm::app()->db->createCommand();
        $command->from("products t force index (products_create_date_idx)");
        $command->join = 'JOIN products_translation tt on t.product_id = tt.product_id';
        $command->join .= $this->joins;
        $command->select("t.product_id, t.hits, t.thumb, tt.product_brief, tt.product_description, tt.product_specifications $cols");
        $command->where($wheres);
        $command->order = $orders;
        $this->count = Yii::app()->db->createCommand("SELECT COUNT(*) FROM products t {$command->join} WHERE {$command->where}")->queryScalar();
        if ($this->limit !== null) {
            $command->limit($this->limit, $this->fromRecord);
        }
        if ($this->generateDataset) {
            $products = $command->queryAll();
            $this->setDataset($products);
        }
        $this->query = $command;
    }

    /**
     *
     * Sets the the ProductsListData.items array      
     * @param array $products 
     * @access protected     
     * @return void
     */
    protected function setDataset($products) {
        $index = -1;
        $options = self::getSettings()->options;
        foreach ($products as $product) {
            if ($this->recordIdAsKey) {
                $index = $product['product_id'];
            } else {
                $index++;
            }
            if ($this->titleLength) {
                $this->items[$index]['title'] = Html::utfSubstring($product["product_name"], 0, $this->titleLength);
            } else {
                $this->items[$index]['title'] = $product["product_name"];
            }
            $this->items[$index]['id'] = $product["product_id"];
            $urlParams = array('id' => $product['product_id'], 'title' => $product["product_name"]);
            foreach ($this->params as $paramIndex => $paramValue) {
                $urlParams[$paramIndex] = $paramValue;
            }
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), $urlParams);
            if ($product["thumb"]) {
                $this->items[$index]['imageExt'] = $product["thumb"];
                $this->items[$index]['image'] = $this->mediaPath . $product["product_id"] . "." . $product["thumb"];
            } else {
                $this->items[$index]['imageExt'] = null;
                $this->items[$index]['image'] = null;
            }
            $this->items[$index]['type'] = $this->type;

            if ($this->checkIsActive) {
                $this->items[$index]['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("id" => $product['product_id']));
            }
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $product[$colIndex];
            }
        }
    }

}
