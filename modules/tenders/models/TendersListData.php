<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * TendersListData class, gets tenders as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersListData extends Dataset {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;
    public $departmentId;

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
    public function __construct() {
        $this->route = self::getSettings()->settings['options']['default']['text']['homeRoute'];
        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths['files']['path'] . "/";
    }

    /**
     * Get articles setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("tenders", false);
        }
        return self::$_settings;
    }

    /**
     *
     * Generate articles lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
//        if (!count($this->orders)) {
////            $sorting = self::getSettings()->getTablesSoringOrders();
//            $this->addOrder("hits desc");
//        }
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the articles array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $currentDate = date("Y-m-d H:i:s");
//        if ($this->sectionId) {
//            if ($this->useSubSections) {
//                $sections = Data::getInstance()->getSectionSubIds($this->sectionId);
//                $sections[] = $this->sectionId;
//                $this->addWhere("(t.section_id in (" . implode(',', $sections) . "))");
//            } else {
//                $this->addWhere("t.section_id = {$this->sectionId}");
//            }
//        }
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT sql_calc_found_rows            
            t.*, tt.* $cols
            FROM  `tenders` t
            inner join tenders_translation tt on t.tender_id = tt.tender_id    
            {$this->joins}
            where tt.content_lang = %s
            and t.published = %d           
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}", 
                Yii::app()->db->quoteValue($siteLanguage),
                ActiveRecord::PUBLISHED
            );
        $tenders = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($tenders);
    }

    /**
     *
     * Sets the the ArticlesListData.items array      
     * @param array $tenders 
     * @access protected     
     * @return void
     */
    protected function setDataset($tenders) {
        $index = -1;
        foreach ($tenders As $tender) {
            if ($this->recordIdAsKey) {
                $index = $tender['tender_id'];
            } else {
                $index++;
            }
//            if ($this->titleLength) {
//                $this->items[$index]['title'] = Html::utfSubstring($tender["title"], 0, $this->titleLength);
//            } else {
            $this->items[$index]['title'] = $tender["title"];
//            }

            $this->items[$index]['id'] = $tender["tender_id"];
            $urlParams = array('id' => $tender['tender_id'], 'title' => $tender["title"]);
            foreach ($this->params as $paramIndex => $paramValue) {
                $urlParams[$paramIndex] = $paramValue;
            }
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), $urlParams);

            if ($tender["file_ext"]) {
                $this->items[$index]['imageExt'] = $tender["file_ext"];
                $this->items[$index]['image'] = $this->mediaPath . $tender["tender_id"] . "." . $tender["file_ext"];
            } else {
                $this->items[$index]['imageExt'] = null;
                $this->items[$index]['image'] = null;
            }

            $this->items[$index]['tender_type'] = $tender['tender_type'];
            $this->items[$index]['tender_status'] = $tender['tender_status'];
            
            
                    
            $this->items[$index]['rfp_start_date'] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm)", $tender['rfp_start_date']);
            $this->items[$index]['submission_start_date'] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm)", $tender['submission_start_date']);
            $this->items[$index]['rfp_price1'] = $tender['rfp_price1'];
            $this->items[$index]['rfp_price2'] = $tender['rfp_price2'];
            $this->items[$index]['primary_insurance'] = $tender['primary_insurance'];

            if ($this->checkIsActive) {
                $this->items[$index]['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("id" => $tender['tender_id']));
            }

            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $tender[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}