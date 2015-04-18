<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectoryItemData class, Gets the directory record for a given directory id
 * @package AmcWebManager
 * @subpackage Data
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class JobsList extends Dataset {

    /**
     * Counstructor, the content type
     * @param integer $directoryId 
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($autoGenerate = true) {
        $this->route = '/jobs/default/view';
        if ($autoGenerate) {
            $this->generate();
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
        $this->setItems();
    }

    protected function setItems() {
        $currentDate = date("Y-m-d H:i:s");
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $limit = null;
        if ($this->limit) {
            $limit = "LIMIT {$this->fromRecord} , {$this->limit}";
        }
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            t.job_id, tc.job, tc.job_description,
            t.expire_date, t.publish_date
            $cols
            FROM jobs t
            INNER JOIN jobs_translation tc ON tc.job_id = t.job_id
            {$this->joins}
            WHERE t.published = 1 
            and t.publish_date <= '{$currentDate}'            
            and (t.expire_date  >= '{$currentDate}' or t.expire_date is null) 
            and tc.content_lang = %s
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
        $index = -1;
        foreach ($dataset As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['job_id'];
            } else {
                $index++;
            }
            $this->items[$index]['job_id'] = $item["job_id"];
            $this->items[$index]['name'] = $item["job"];
            $this->items[$index]['description'] = $item["job_description"];
            $this->items[$index]['publish_date'] = AmcWm::app()->dateFormatter->format('dd/MM/y hh:mm a', $item["publish_date"]);
            ;
            $this->items[$index]['expire_date'] = AmcWm::app()->dateFormatter->format('dd/MM/y hh:mm a', $item["expire_date"]);
            ;

            $this->items[$index]['link'] = array($this->route, 'id' => $item['job_id'], 'title' => $item['job']);
            if ($this->checkIsActive && $this->route) {
                $this->items[$index]['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("id" => $item['job_id']), 'id');
            }

            if (count($this->cols)) {
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $item[$colIndex];
                }
            }
        }
        $this->count = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
    }

    /**
     * get the jobs details
     * @param integer $id
     */
    public static function getJob($id) {
        $currentDate = date("Y-m-d H:i:s");
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $query = sprintf("SELECT t.job_id, tc.job as name, tc.job_description as description,
            t.expire_date, t.publish_date, allow_request
            FROM jobs t
            INNER JOIN jobs_translation tc ON tc.job_id = t.job_id
            WHERE t.published = 1 
            and t.job_id = %d
            and t.publish_date <= '{$currentDate}'            
            and (t.expire_date  >= '{$currentDate}' or t.expire_date is null) 
            and tc.content_lang = %s
            ", $id, Yii::app()->db->quoteValue($siteLanguage));
        $dataset = Yii::app()->db->createCommand($query)->queryRow();
        return $dataset;
    }

}

?>
