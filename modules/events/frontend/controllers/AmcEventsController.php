<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class AmcEventsController extends FrontendController {

    const PAST_LIMIT = 4;

    public function agendaList($id = 0, $date = null) {
        if ($date == null) {
            $date = date("Y-m-d");
        }
        $currentDateTime = time();
        $dateTime = strtotime($date);
        $agendaList = new AgendaListData(0, 10, $id);

        $agendaPastList = new AgendaListData(0, self::PAST_LIMIT, $id);
        $agendaPastList->addWhere(sprintf("date(t.event_date) < %s", Yii::app()->db->quoteValue($date)));

        $viewNext = ($dateTime - $currentDateTime >= 60 * 60 * 24 * 2);
        $agendaList->addColumn("event_detail");
        $past = Yii::app()->request->getParam('past');
        $allEvents = Yii::app()->request->getParam('all', false);
        if ($allEvents) {
            if ($past) {
                $agendaList->addWhere(sprintf("date(t.event_date) < %s", Yii::app()->db->quoteValue($date)));
            } else {
                if (!$viewNext) {
                    $agendaList->addWhere(sprintf("date(t.event_date) = %s", Yii::app()->db->quoteValue($date)));
                } else {
                    $agendaList->addWhere(sprintf("date(t.event_date) >= %s", Yii::app()->db->quoteValue($date)));
                }
            }
        }
        else{
            $agendaList->addWhere(sprintf("date(t.event_date) = %s", Yii::app()->db->quoteValue($date)));
        }        
        $eventData = new PagingDataset($agendaList, 5, Yii::app()->request->getParam("page"));
        $section = null;
        if ($id) {
            $siteLanguage = Yii::app()->user->getCurrentLanguage();
            $query = sprintf(
                    "select t.section_id , t.image_ext , tt.section_name, tt.description from sections t
                 inner join sections_translation tt on t.section_id = tt.section_id
            where t.published=1            
            and tt.content_lang = %s
            and t.section_id = %d
            "
                    , Yii::app()->db->quoteValue($siteLanguage)
                    , $id
            );
            $section = Yii::app()->db->createCommand($query)->queryRow();
            if ($section) {
                if ($section["image_ext"]) {
                    $section['sectionImage'] = Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['topContent']['path'] . "/" . $section["section_id"] . '.' . $section["image_ext"];
                } else {
                    $section['sectionImage'] = null;
                }
            }
        }
        $agendaPastList->generate();
        $this->render('index', array(
            'eventData' => $eventData->getData(),
            'date' => $date,
            'section' => $section,
            'past' => $past,
            'viewNext' => $viewNext,
            'pastData' => $agendaPastList->getItems(),
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionIndex($date = null) {
        $this->agendaList(Yii::app()->request->getParam('id'), $date);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $agenda = new AgendaData($id);
        $event = $agenda->getItems();
        if (!is_array($event)) {
            throw new CHttpException(404, AmcWm::t("msgsbase.core", 'The requested report does not exist'));
        }
        $agendaList = new AgendaListData(0, self::PAST_LIMIT);
        $date = date("Y-m-d", strtotime($event['event_date']));
        $agendaList->addWhere("date(t.event_date) < '" . $date . "'");
        $agendaList->generate();

        $attach = new AttachmentList("events", "events", $id);
        $attach->generate();
        $attachment = $attach->getItems();
        
        $this->render('view', array(
            'date' => $date,
            'event' => $event,
            'attachment' => $attachment,
            'pastData' => $agendaList->getItems(),
        ));

        //$pastData = $agenda->getPastList($date, self::PAST_LIMIT);
        //$this->drawEventDetails($id, $date, $event, $pastData);
    }

    /**
     * 
     * run ajax
     * @param string $do
     * @access public
     * @return void
     */
    public function ajaxCalender() {
        header('Content-type: text/json');
        $agenda = new AgendaListData(0, 10);
        $month = date('m');
        $year = date('Y');
        if (isset($_GET['month']) && intval($_GET['month'])) {
            $month = $_GET['month'] + 1;
        }

        if (isset($_GET['year']) && intval($_GET['year'])) {
            $year = $_GET['year'];
        }
        $agenda->addWhere("MONTH(t.event_date) = '" . $month . "'");
        $agenda->addWhere("YEAR(t.event_date) = '" . $year . "'");
        $agenda->generate();
        $agendaEvents = $agenda->getItems();

        $monthEvents = array();
        foreach ($agendaEvents as $event) {
            $eventsData = array();
            $eventsData["date"] = "" . (strtotime($event['event_date']) * 1000) . ""; // javascript microtime conversion
            $eventsData["type"] = "meeting";
            $eventsData["title"] = $event['title'];
            $eventsData["description"] = $event['location'];
            $eventsData["url"] = $event['link'];
            $monthEvents[] = $eventsData;
        }
        echo json_encode($monthEvents);
        Yii::app()->end();
    }

}
