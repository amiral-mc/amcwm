<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectoryCountriesData class, gets glossary as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirectoryCountriesData extends Dataset {   

    /**
     * Country flag size
     * @var string
     */
    protected $flagSize;
    
    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param string $flagSize
     * @param integer $limit, The numbers of items to fetch from table     
     * @access public
     */
    public function __construct($flagSize = 24, $limit = 0) {
        $this->limit = (int) $limit;
        $this->flagSize = $flagSize;
        $this->route = "/directory/default/countryList";
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
            $this->addOrder("tc.country ASC");
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
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $limit = null;
        if($this->limit){
            $limit = "LIMIT {$this->fromRecord} , {$this->limit}";
        }
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            distinct code, country             
            $cols
            FROM dir_companies t
            INNER JOIN countries_translation tc ON tc.code = t.nationality
            {$this->joins}
            WHERE t.published = 1 and t.accepted = 1 and tc.content_lang = %s
            $wheres
            $orders
            $limit
            ", Yii::app()->db->quoteValue($siteLanguage));
        $dataset = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($dataset);
    }

    /**
     *
     * Sets the the items array      
     * @param array $articles 
     * @access protected     
     * @return void
     */
    protected function setDataset($dataset) {
        $publishFolder = AmcWm::app()->getAssetManager()->publish(Yii::getPathOfAlias("icons.flags.{$this->flagSize}"), true);
        $index = -1;
        foreach ($dataset As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['code'];
            } else {
                $index++;
            }
            $item["code"] = strtolower($item["code"]);
            $this->items[$index]['code'] = $item["code"];
            $this->items[$index]['country'] = $item["country"];
            $this->items[$index]['image'] = "{$publishFolder}/{$item["code"]}.png";
            $this->items[$index]['link'] = array($this->route, 'code' => $item['code'], 'title' => $item['country']);            
            if ($this->checkIsActive && $this->route) {
                $this->items[$index]['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("code" => $item['code']), 'code');
            }
            
            if (count($this->cols)) {
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $item[$colIndex];
                }
            }
        }
        $this->count = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
    }   
}