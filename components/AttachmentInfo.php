<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Generate attachment info
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttachmentInfo extends CComponent {

    /**
     *
     * @var attachment info 
     */
    private $_info;

    /**
     * The AttachmentInfo instance.
     * @var AttachmentInfo
     * @static
     * @access private
     */
    private static $_instance = null;

    /**
     * constructor 
     * You should not call the constructor directly, but instead call the static factory method AttachmentInfo.getInstance().<br />
     * @access private
     */
    private function __construct($isBackend = false) {
        $modules = AmcWm::app()->getApplicationModules();
        $this->_info = array('data' => array(), 'list' => array());
        $rootAction = "/";
        $allowType = 2;
        if($isBackend){
            $rootAction = "/backend/";
            $allowType = 1;
        }
        foreach ($modules as $moduleId => $module) {
            $settings = new Settings($moduleId, false);
            $options = $settings->getOptions();            
            if (isset($options['attachment']['integer'])) {
                $module = amcwm::app()->acl->getModule($moduleId);
                if (isset($module['label'][Controller::getContentLanguage()])) {
                    $label = $module['label'][Controller::getContentLanguage()];                                        
                } 
                else{
                    $label = AmcWm::t("amcwm.modules.{$moduleId}.backend.messages.core", "_attach_{$moduleId}_name_");
                }
//                $this->_info['list'][$label] = array();
                foreach ($options['attachment']['integer'] as $attach => $allow) {
                    if ($allowType <= $allow) {
                        $this->_info['list'][$label][$moduleId . ucfirst($attach)] = AmcWm::t("amcwm.modules.{$moduleId}.backend.messages.core", "_attach_{$attach}_name_");
                        $this->_info['data'][$moduleId][$attach] = $settings->mediaPaths[$attach];                        
                        $this->_info['actions'][$moduleId . ucfirst($attach)] = Html::createUrl("{$rootAction}{$moduleId}/default/ajax", array('do'=>"attachment"));
                    }
                }
            }
        }
    }

    /**
     * Factory AttachmentInfo method.
     * @static
     * @access public
     * @return AttachmentInfo the Singleton instance of the Acl
     */
    public static function &getInstance($isBackend) {
        if (self::$_instance == NULL) {
            self::$_instance = new self($isBackend);
        }
        return self::$_instance;
    }

    /**
     * get attachment info
     * @access public
     * @return array
     */
    public function getInfo() {
        return $this->_info;
    }
    
    /**
     * get attachment list
     * @access public
     * @return array
     */
    public function getList() {
        return $this->_info['list'];
    }

}