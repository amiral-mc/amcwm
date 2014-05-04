<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * EventCalendar extension class, displays events in calendar.
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */

class EventCalendar extends SideWidget {

    public $container = "eventCalendar";
    public $eventsLimit = 2;

    public function init() {
        
        $baseUrl = Yii::app()->getAssetManager()->publish($this->basePath . DIRECTORY_SEPARATOR . 'assets');
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseUrl . '/css/eventCalendar.css');
        $cs->registerCssFile($baseUrl . '/css/ec_theme_'.Yii::app()->getLanguage().'.css');
        $cs->registerScriptFile($baseUrl . '/js/jquery.eventCalendar.js', CClientScript::POS_HEAD);
        $this->contentClass .= " noBG";
        parent::init();
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {        
        $this->contentData = "<div id='{$this->container}'></div>";
        $appMonths = Yii::app()->getLocale()->getMonthNames();
        $months = array();
        foreach ($appMonths as $month) {
            $months[] = $month;
        }

        $js_code = "
                $('#{$this->container}').eventCalendar({
                    eventsLimit: {$this->eventsLimit},
                    eventsjson: '" . Html::createUrl('/events/default/ajax' , array('do'=>'calender')) . "',
                    txt_noEvents : '" . AmcWm::t("amcwm.modules.events.frontend.messages.core", 'There are no events in this period') . "',
                    txt_next : '" . AmcWm::t("amcwm.modules.events.frontend.messages.core", 'next') . "',
                    txt_prev : '" . AmcWm::t("amcwm.modules.events.frontend.messages.core", 'prev') . "',
                    txt_SpecificEvents_after : '" . AmcWm::t("amcwm.modules.events.frontend.messages.core", 'events') . "',
                    txt_NextEvents : '" . AmcWm::t("amcwm.modules.events.frontend.messages.core", 'Next events') . "',
                    txt_GoToEventUrl : '" . AmcWm::t("amcwm.modules.events.frontend.messages.core", 'See the event') . "',
                    txt_Loading : '" . AmcWm::t("amcwm.modules.events.frontend.messages.core", 'loading...') . "',
                    dayNamesShort: " . json_encode(Yii::app()->getLocale()->getWeekDayNames('abbreviated')) . ",
                    dayNames: " . json_encode(Yii::app()->getLocale()->getWeekDayNames()) . ",
                    monthNames: " . json_encode($months) . ",
                    cacheJson: false,
                    startWeekOnMonday: false,
                    showDescription: true
                });
            ";
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->container, $js_code, CClientScript::POS_READY);
    }

}

?>
