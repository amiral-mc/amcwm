<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * EventsList, draw events list
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class EventsTopList extends ExecuteWidget {

    /**
     * Section id to get contents from it, null to get from all sections
     * @var integer 
     */
    public $sectionId;
    /**
     * prepare widget properties
     */
    protected function prepareProperties() {
        $settings = AgendaListData::getSettings()->options['default'];
        $list = new AgendaListData(0, $settings['integer']['topList'], $this->sectionId);
        $list->addColumn("event_detail", "detail");
        $list->useRecordIdAsKey(false);
        $list->addOrder("event_date desc");
        $list->generate();        
        $items = $list->getItems();        
        if ($items) {
            $firstItem = $items[0];
            $attach = new AttachmentList("events", "events", $firstItem['id']);
            $attach->generate();
            $firstItem['attachment'] = $attach->getItems();
//            $firstItem['mediaImage'] = "/aspf/images/front/video.jpg";
//            $firstItem['mediaLink'] = "http://localhost";
//            $firstItem['attachLink'] = "http://localhost";
//            $firstItem['attach'] = "جدول أعمال المؤتمر الـ 44";
            
            unset($items[0]);
            $this->setProperty('items', $items);
            $this->setProperty('firstItem', $firstItem);
        }
    }

}

