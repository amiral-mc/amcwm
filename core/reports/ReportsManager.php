<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ReportsManager class, base class for any reports manager
 * @package amcwm.reports
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ReportsManager extends ListManager {
  
    /**
     * Constructor,
     * @param integer $pageSize, The numbers of record to fetch
     * @param string $listClassName List data class name
     * @param string $listDataDir directory that contain the report class
     */
     public function __construct($pageSize = 10, $listClassName = "ReportData", $listDataDir = "amcwm.components.reports") {
        parent::__construct($pageSize, $listClassName, $listDataDir);
    }  
}