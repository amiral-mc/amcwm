<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of RelatedWebsites
 * @author Amiral Management Corporation amc.amiral.com
 */
class RelatedWebsitesData extends Dataset{
    
    public function __construct($limit = 10) {
        $this->limit = (int) $limit;
    }
    
     public function generate() {
        if (!count($this->orders)) {
            $this->addOrder("tt.name ASC");
        }
        $this->setItems();
    }
    
    public function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            t.website_id, t.url, t.image_ext, tt.name, tt.description
            $cols
            FROM  `related_websites` t
            INNER JOIN  related_websites_translation tt ON t.website_id = tt.website_id    
            {$this->joins}
            WHERE tt.content_lang = %s and t.published = " . (int)ActiveRecord::PUBLISHED . "
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ", Yii::app()->db->quoteValue($siteLanguage));
        $glossary = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($glossary);
    }
    
    protected function setDataset($glossary) {
        $index = -1;
        foreach ($glossary As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['website_id'];
            } else {
                $index++;
            }
            $this->items[$index]['id'] = $item["website_id"];
            $this->items[$index]['name'] = $item["name"];
            $this->items[$index]['description'] = $item["description"];
            $this->items[$index]['url'] = $item["url"];
            $this->items[$index]['image'] = $item["website_id"] . "." .$item["image_ext"];

            if (count($this->cols)) {
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $item[$colIndex];
                }
            }
        }
        $this->count = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
    }
}

?>
