<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AgendaData class, gets agenda record based on a gvin id
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AgendaData extends Dataset {

    /**
     * agenda id to get data based on it
     * @var integer 
     */
    private $_id;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $id, agenda id to get data based on it
     * @param boolean $autoGenerate if true then call the generate method from counstructor     
     * @access public
     */
    public function __construct($id, $autoGenerate = true) {
        $this->_id = $id;
        if ($autoGenerate) {
            $this->generate();
        }
    }

    /**
     *
     * Generate agenda lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $this->addColumn("section_name");
        $this->addColumn("event_date");
        $this->addColumn("event_detail");
        $this->addColumn("location");
        $this->addColumn("country");
        $this->addColumn("t.section_id", 'section_id');
        $this->addColumn("section_name");
        $this->addColumn("image_ext");
        $this->addJoin("left join sections s on s.section_id=t.section_id");
        $this->addJoin("left join sections_translation st on st.section_id=s.section_id and tt.content_lang = st.content_lang");
        $this->addJoin("left join countries_translation c on c.code=t.country_code and tt.content_lang = c.content_lang");
        $this->setItems();
    }

    /**
     * Set the articles array list    
     * @access protected
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $items = array();
        $this->query = sprintf("SELECT            
            t.event_id, tt.event_header $cols
            FROM  events t                        
            inner join events_translation tt on t.event_id = tt.event_id
            {$this->joins}
            where tt.content_lang = %s
            and t.published = %d 
            and t.event_id = %d
            $wheres            
            ", 
                Yii::app()->db->quoteValue($siteLanguage), 
                ActiveRecord::PUBLISHED, 
                $this->_id);
        $event = Yii::app()->db->createCommand($this->query)->queryRow();
        $this->items['event_header'] = $event["event_header"];
        $this->items['event_id'] = $event["event_id"];
        if ($event["image_ext"]) {
            $this->items['sectionImage'] = Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['topContent']['path'] . "/" . $event["section_id"] . '.' . $event["image_ext"];
        } else {
            $settings = AgendaListData::getSettings()->getOptions();
            if (isset($settings['default']['text']['sectionImage'])) {
                $this->items['sectionImage'] = Yii::app()->baseUrl . "/" . $settings['default']['text']['sectionImage'];
            } else {
                $this->items['sectionImage'] = null;
            }
        }
        foreach ($this->cols as $colIndex => $col) {
            $this->items[$colIndex] = $event[$colIndex];
        }
    }

}