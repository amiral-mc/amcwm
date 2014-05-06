<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Log articles
 * @subpackage AmcWm.data.log
 * @author Amiral Management Corporation
 * @version 1.0
 */
class LogArticles extends DbLogData{
    
    /**
     * set file system for the given $tableName
     * @access public
     * @return array     
     */
    public function getFileSystem($tableName){
        return array();
    }
}
