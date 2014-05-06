<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AgendaTopData class, gets top aganda as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AgendaTopData {

    /** Dates used for generating lists
     * @var array
     */
    protected static $dates = array();
    /**
     * data list array,
     * @var array 
     */
    protected $items = array();

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @todo fix bug if $limit = 0
     * @param integer $limit, The numbers of items to fetch from table     
     * @access public
     */
    public function __construct($limit = 10) {
        self::$dates = array(
            "allmonth" => date("m"),
            "yesterday" => date("Y-m-d", strtotime("-1 day")),
            'today' => date("Y-m-d"),
            'tomorow' => date("Y-m-d", strtotime("1 day")),
            'afterTomorow' => date("Y-m-d", strtotime("2 day"))
        );
        $this->items = array();
        
        $agenda = new AgendaListData(0, $limit);
        $agenda->addWhere("MONTH(t.event_date) = '" . self::$dates['allmonth'] . "'");
        $agenda->generate();
        $this->items['allmonth'] = $agenda->getItems();
        
        $agenda = new AgendaListData(0, $limit);
        $agenda->addWhere("date(t.event_date) = '" . self::$dates['yesterday'] . "'");
        $agenda->generate();
        $this->items['yesterday'] = $agenda->getItems();

        $agenda = new AgendaListData(0, $limit);
        $agenda->addWhere("date(t.event_date) = '" . self::$dates['today'] . "'");
        $agenda->generate();
        $this->items['today'] = $agenda->getItems();

        $agenda = new AgendaListData(0, $limit);
        $agenda->addWhere("date(t.event_date) = '" . self::$dates['tomorow'] . "'");
        $agenda->generate();
        $this->items['tomorow'] = $agenda->getItems();

        $agenda = new AgendaListData(0, $limit);
        $agenda->setFromDate(self::$dates['afterTomorow']);
        $agenda->generate();
        $this->items['afterTomorow'] = $agenda->getItems();        
    }
    
    /**
     * Get dates used for generating lists
     * @static
     * @access public
     * @return array 
     */
    public static function getDates(){
        return self::$dates;
    }

    /**
     * Gets dataset array list
     * @access public
     * @return array
     */
    public function getItems() {
        return $this->items;
    }

}