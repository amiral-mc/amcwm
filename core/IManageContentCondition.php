<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * IManageContentCondition, any Manage Content Condition class must implement this interface
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
interface IManageContentCondition {

    /**
     * Change extend and add interface contains the above methods
     * @return string
     */
    public function getListCondition();   

    /**
     * check if member can save the given $record
     * @param integer $id
     */
    public function canManage(ActiveRecord &$record);

    /**
     * save date to related tables
     * @param ActiveRecord &$record
     */
    public function saveRelated(ActiveRecord &$record);
}
