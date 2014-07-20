<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */

/**
 * AmcEventsService
 * a command class to copy todays events and send them as a bulk to a new article
 * INSERT INTO `services` (`service_id`, `service_name`, `class_name`, `enabled`, `cron_condition`, `cron_time`, `cron_step`) VALUES (NULL, 'events', 'Events', '1', 'min', '0', '1');
 */
class AmcEventsService extends AmcService {

    public function setInformation() {
        $this->information['table'] = 'events';
    }

    public function getData() {
        return array();
    }

    protected function update() {

        $todayDate = date("Y-m-d H:i:s");
//        $todayDate = '2012-11-1';
        
        $sectionId = array(); // misc
        $sectionId['en'] = 23;
        $sectionId['ar'] = 6;
        
        $siteLanguage = array('ar', 'en');
        foreach ($siteLanguage as $lang) {
            Yii::app()->setLanguage($lang);

            $sqlQuery = "SELECT e.*, s.section_name , c.country_{$lang} country from events e
                left join sections s on s.section_id=e.section_id
                left join countries c on c.code=e.country_code";
            $sqlQuery .= " WHERE e.published =1 ";
            $sqlQuery .= " AND DATE(e.event_date) = %s";
            $sqlQuery .= " AND (e.content_lang = %s or e.content_lang is null or e.content_lang ='') ";
            $sqlQuery .= " ORDER BY e.event_date DESC ";
            
            $query = sprintf($sqlQuery, Yii::app()->db->quoteValue($todayDate), Yii::app()->db->quoteValue($lang));
            $eventData = Yii::app()->db->createCommand($query)->queryAll();
            if (count($eventData)) {

                $pageTitle = Yii::t("agenda", 'Top news for {day}', array('{day}' => Yii::app()->dateFormatter->format('EEEE dd-MM-yyyy', $todayDate)));
                $pageDesc = "<b>". Yii::t("agenda", 'Agenda - Arab News Agency Reports for {day}', array('{day}' => Yii::app()->dateFormatter->format('EEEE dd-MM-yyyy', $todayDate))) ."</b>";
                $pageDesc .= "<ul>";
                foreach ($eventData as $event) {
                    $title = "{$event["country"]} - {$event["location"]} : {$event["event_header"]}";
                    $link = "<a href='" . $this->createPostUrl("agenda/default/view", array('date' => $todayDate, 'id' => $event["event_id"], 'lang' => $lang, 'title' => $event["event_header"])) . "' title='" . CHtml::encode($title) . "'>{$title}</a>";
                    $pageDesc .= "<li>
                                <p>" . Yii::app()->dateFormatter->format('EEEE dd MMMM yyyy', $event["event_date"]) . '&nbsp;&nbsp;&nbsp' . Yii::app()->dateFormatter->format('h:m a', $event["event_date"]) . "</p>
                                <p>{$link}</p>
                                <p> - {$event["section_name"]}</p>
                            </li>";
                }

                $pageDesc .= "</ul>";
                $pageDesc .= "<a href='" . $this->createPostUrl("agenda/default/index", array('date' => $todayDate, 'vn' => 0, 'lang' => $lang)) . "' title='" . Yii::t("agenda", "More about Agenda") . "'>" . Yii::t("agenda", "More about Agenda") . "</a>";
                
                // start inserting to the news page...
                $insertArticle = sprintf("insert into articles (
                    update_date, 
                    create_date, 
                    publish_date,
                    article_header, 
                    article_detail, 
                    content_lang, 
                    section_id
                    ) values (%s, %s, %s, %s, %s, %s, %d)"
                        , Yii::app()->db->quoteValue($todayDate)
                        , Yii::app()->db->quoteValue($todayDate)
                        , Yii::app()->db->quoteValue($todayDate)
                        , Yii::app()->db->quoteValue($pageTitle)
                        , Yii::app()->db->quoteValue($pageDesc)
                        , Yii::app()->db->quoteValue($lang)
                        , $sectionId[$lang]
                        );
                Yii::app()->db->createCommand($insertArticle)->execute();
                Yii::app()->db->createCommand(sprintf("insert into news (article_id) values (%d)", Yii::app()->db->getLastInsertID()))->execute();
                
            } // end if has events
        } // end foreach language
    }

// end method

    protected function createPostUrl($route, $params) {
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            $url = Yii::app()->params['siteUrl'];
        } else {
            $url = Yii::app()->params['siteUrl'] . '/index.php';
        }
        return Html::createConsoleUrl($url, $route, $params);
    }

}

?>
