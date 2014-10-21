<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Generate social info
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SocialInfo extends CComponent {

    /**
     *
     * @var integer module id  
     */
    private $_moduleId = 0;

    /**
     *
     * @var integer referer id  
     */
    private $_refId = 0;

    /**
     *
     * @var integer table id  
     */
    private $_tableId = 0;

    /**
     * constructor 
     */
    public function __construct($module, $tableId, $refId) {
        $moduleData = amcwm::app()->acl->getModule($module, true);
        if ($moduleData) {
            $this->_moduleId = (int) $moduleData['id'];
            $this->_refId = (int) $refId;
            $this->_tableId = (int) $tableId;
        }
    }

    /**
     * get social info
     * @access public
     * @return array
     */
    public function getSocialIds() {
        $query = sprintf("select social_id, ref_id, table_id from module_social_config "
                . "where module_id=%d and table_id=%d and ref_id=%d ", $this->_moduleId, $this->_tableId, $this->_refId);
        $socials = AmcWm::app()->db->createCommand($query)->queryAll();
        $socialsIds = array();
        foreach ($socials as $social) {
            $socialsIds[] = $social['social_id'];
        }
        return $socialsIds;
    }

    /**
     * Save social
     * @param array $socialIds
     * @return bool
     */
    public function saveSocial($socialIds) {
        $query = sprintf("select ref_id from module_social_config "
                . "where module_id=%d and table_id=%d and ref_id=%d and post_date is not null", $this->_moduleId, $this->_tableId, $this->_refId);
        $posted = AmcWm::app()->db->createCommand($query)->queryAll();
        $ok = false;
        if (!$posted) {
            $query = sprintf("delete from module_social_config "
                    . "where module_id=%d and table_id=%d and ref_id=%d", $this->_moduleId, $this->_tableId, $this->_refId);
            AmcWm::app()->db->createCommand($query)->execute();
            if (is_array($socialIds)) {
                foreach ($socialIds as $socialId) {
                    $query = sprintf("insert into module_social_config(module_id, social_id, ref_id, table_id) "
                            . "values(%d, %d , %d, %d)", $this->_moduleId, $socialId, $this->_refId, $this->_tableId);
                    AmcWm::app()->db->createCommand($query)->execute();
                }
            }
        }
        return $ok;
    }

}
