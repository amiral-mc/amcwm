<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * InFocusData class,  gets Infocus data array in home
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InfocusHome extends Dataset {

    /**
     * Counstructor, the content type
     * @param integer $limit = null
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($limit, $autoGenerate = true) {
        $this->route = "/infocus/default/view";
        $this->recordIdAsKey = false;
        $this->limit = $limit;
        if ($autoGenerate) {
            $this->generate();
        }
    }

    /**
     * Generate infocus data list each item in the list is associated array that contain's following items:
     * <ul>
     * <li>id: integer, infocus id</li>
     * <li>header: string, infocus title</li>
     * <li>brief: string, infocus brief</li>
     * <li>thumb: string, infocus image extension</li>
     * </ul>
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
            $this->addOrder("t.create_date desc");
        }
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the infocus data list associated 
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $currentDate = date("Y-m-d H:i:s");
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $order = $this->generateOrders();        
        $this->query = sprintf("select 
            t.thumb imageExt, 
            t.infocus_id,
            tt.header,
            tt.brief
            $cols    
            from infocus t
            inner join infocus_translation tt on t.infocus_id = tt.infocus_id    
            {$this->joins}
            where t.published = %d
            and t.publish_date <= '$currentDate'             
            and (t.expire_date >= '$currentDate' or t.expire_date is null)
            and tt.content_lang = %s
            $wheres    
            $order limit %d", ActiveRecord::PUBLISHED, Yii::app()->db->quoteValue($siteLanguage), $this->limit);
        $this->items = Yii::app()->db->createCommand($this->query)->queryAll();
    }
}