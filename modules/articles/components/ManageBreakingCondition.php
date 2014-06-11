<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ManageBreakingCondition class, manage breaking content condtion
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ManageBreakingCondition implements IManageContentCondition {

    /**
     * Get list condtions for members
     */
    public function getListCondition() {
        return "news.is_breaking > 0";
    }    
    
    /**
     * check if member can edit the given $record
     * @param integer $id
     */
    public function canManage(ActiveRecord &$record) {
        return true;
    }

    /**
     * save date to related tables
     * @param ActiveRecord &$record
     */
    public function saveRelated(ActiveRecord &$record) {
    }

}