<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * InFocusListData class,  gets Infocus as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InFocusListData extends SiteData {

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
        $this->route = "/infocus/default/view";
        $this->setModuleName("infocus");
        $this->period = $period;
        $this->limit = (int) $limit;
        $this->sectionId = (int) $sectionId;
        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths['list']['path'] . "/";        
    }

    /**
     * Get articles setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("infocus", false);
        }
        return self::$_settings;
    }
    
    /**
     *
     * Generate infocus list
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
            $this->addOrder("create_date desc");
        }
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        switch ($this->archive) {
            case 1:
                $this->addWhere('(t.archive = 0 or t.archive is null)');
                break;
            case 2:
                $this->addWhere('t.archive = 1');
                break;
        }
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the infocus array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $currentDate = date("Y-m-d H:i:s");
        if ($this->sectionId) {
            if ($this->useSubSections) {
                $sections = Data::getInstance()->getSectionSubIds($this->sectionId);
                $sections[$this->sectionId] = $this->sectionId;
                $this->addWhere("(section_id in (" . implode(',', $sections) . "))");
            } else {
                $this->addWhere("t.section_id = {$this->sectionId}");
            }
        }
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();

        $this->query = sprintf("SELECT sql_calc_found_rows            
            t.infocus_id, tt.header, t.thumb $cols
            FROM  infocus t
            inner join infocus_translation tt on t.infocus_id = tt.infocus_id    
            {$this->joins}
            where tt.content_lang = %s
            and t.publish_date <= %s            
            and (t.expire_date  >= %s or t.expire_date is null)  
            and t.published = %d
            and (t.thumb is not null or t.thumb <> 0)
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ",  Yii::app()->db->quoteValue($siteLanguage), 
                Yii::app()->db->quoteValue($currentDate),
                Yii::app()->db->quoteValue($currentDate),
                ActiveRecord::PUBLISHED);
        $items = Yii::app()->db->createCommand($this->query)->queryAll();
        $index = -1;
        foreach ($items As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['infocus_id'];
            } else {
                $index++;
            }
            $this->items[$index]['id'] = $item["infocus_id"];
            $this->items[$index]['title'] = $item["header"];
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), array('id' => $item['infocus_id'], 'title' => $item["header"]));
            $this->items[$index]['image'] = $this->mediaPath . $item["infocus_id"] . "." . $item["thumb"];
            
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $item[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}
