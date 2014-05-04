<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * AgendaListData class, gets aganda array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AgendaListData extends SiteData {

      /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;
    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @todo fix bug if $limit = 0
     * @param integer $period, Period time in seconds. 
     * @param integer $limit, The numbers of items to fetch from table     
     * @param integer $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct($period = 0, $limit = 10, $sectionId = null) {
        $this->dateCompareField = "event_date";
        $this->route = "/events/default/view";
        $this->period = $period;
        $this->limit = (int) $limit;
        $this->sectionId = (int) $sectionId;
    }
    
     /**
     * Get module setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("events", false);
        }
        return self::$_settings;
    }

    /**
     *
     * Generate agenda lists
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
            $this->addOrder("event_date desc");
        }
        
        $this->addColumn("country");
        //$this->addColumn("t.section_id", 'sectionId');
        $this->addColumn("section_name");
        //$this->addColumn("image_ext");
        
        $this->addJoin("left join sections s on s.section_id=t.section_id");
        $this->addJoin("left join sections_translation st on st.section_id=s.section_id and tt.content_lang = st.content_lang");
        $this->addJoin("left join countries_translation c on c.code=t.country_code and tt.content_lang = c.content_lang");
        $this->setItems();
    }
    
    /**
     * Set the events array list    
     * @todo explain the query
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if ($this->sectionId) {
            if ($this->useSubSections) {
                $sections = Data::getInstance()->getSectionSubIds($this->sectionId);
                $sections[] = $this->sectionId;
                $this->addWhere("(t.section_id in (" . implode(',', $sections) . "))");
            } else {
                $this->addWhere("t.section_id = {$this->sectionId}");
            }
        }
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT sql_calc_found_rows            
            t.event_id, tt.event_header, event_date, location $cols
            FROM  events t
            inner join events_translation tt on t.event_id = tt.event_id
            {$this->joins}
            where tt.content_lang = %s 
            and t.published = %d
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ", Yii::app()->db->quoteValue($siteLanguage), ActiveRecord::PUBLISHED);
        $events = Yii::app()->db->createCommand($this->query)->queryAll();
        $index = -1;
        foreach ($events As $event) {
            if ($this->recordIdAsKey) {
                $index = $event['event_id'];
            } else {
                $index++;
            }
            if ($this->titleLength) {
                $this->items[$index]['title'] = Html::utfSubstring($event["event_header"], 0, $this->titleLength);
            } else {
                $this->items[$index]['title'] = $event["event_header"];
            }
            $this->items[$index]['id'] = $event["event_id"];
            $this->items[$index]['section_name'] = $event["section_name"];
            $this->items[$index]['event_date'] = $event["event_date"];
            $this->items[$index]['location'] = $event["location"];
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), array('id' => $event['event_id'], 'title' => $event["event_header"]));
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $event[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }
}