<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AgendaWidget extension class, displays agenda items
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AgendaWidget extends SideWidget {

    /**
     * @var array of data to display it
     */
    public $items = array();

    /**
     * Render the widget and display the result
     * Calls {@link runItem} to render each article row.
     * @access public
     * @return void
     */
    public function setContentData() {
        $dates = AgendaTopData::getDates();
        $eventsTabs = array(
            "yesterday" => array('title' => AmcWm::t("amcFront", "Yesterday"), 'content' => ""),
            'today' => array('title' => AmcWm::t("amcFront", "Today"), 'content' => ""),
            'tomorow' => array('title' => AmcWm::t("amcFront", "Tomorrow"), 'content' => ""),
            'afterTomorow' => array('title' => AmcWm::t("amcFront", "Next Events"), 'content' => "")
        );
        foreach ($this->items as $eventIndex => $eventData) {
            if (isset($eventsTabs[$eventIndex])) {
                if (count($eventData)) {
                    $eventsTabs[$eventIndex]['content'] = '<ul class="agenda_list">';
                    foreach ($eventData as $event) {
                        $link = Html::link("{$event["country"]} - {$event["location"]} : {$event["title"]}", $event["link"]);
                        $eventsTabs[$eventIndex]['content'] .= '<li>';
                        $eventsTabs[$eventIndex]['content'] .= '<div>';
                        $eventsTabs[$eventIndex]['content'] .= Yii::app()->dateFormatter->format('EEEE dd MMMM yyyy', $event["event_date"]) . '&nbsp;&nbsp;&nbsp' . Yii::app()->dateFormatter->format('h:m a', $event["event_date"]);
                        $eventsTabs[$eventIndex]['content'] .= '</div>';
                        $eventsTabs[$eventIndex]['content'] .= '<div>';
                        $eventsTabs[$eventIndex]['content'] .= $link;
                        $eventsTabs[$eventIndex]['content'] .= '</div>';
                        $eventsTabs[$eventIndex]['content'] .= '<div class="agenda_section">';
                        $eventsTabs[$eventIndex]['content'] .= $event["section_name"];
                        $eventsTabs[$eventIndex]['content'] .= '</div>';
                        $eventsTabs[$eventIndex]['content'] .= '</li>';
                    }
                    $eventsTabs[$eventIndex]['content'] .= '</ul>';
                    $eventsTabs[$eventIndex]['content'] .= Html::link(AmcWm::t("amcFront", "More"), array("/events/default/index", 'date' => $dates[$eventIndex]), array("class" => "agenda_more"));
                } else {
                    $eventsTabs[$eventIndex]['content'] .= AmcWm::t("amcFront", "No event added yet") . $dates[$eventIndex];
                }
            }
        }
        $this->contentData .= $this->widget('TabView', array('tabs' => $eventsTabs, 'activeTab' => "today"), true);
    }

}
