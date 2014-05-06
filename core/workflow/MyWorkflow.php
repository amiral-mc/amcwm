<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ManageWorkflow  manage workflow
 * @package amcwm.core.workflow
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MyWorkflow extends CApplicationComponent {

    /**
     *
     * @var enabled modules array
     */
    private $_modules = array();

    /**
     * add module to modules list
     * @param string $moduleId
     */
    public function setModule($moduleId = null) {
        
    }

    /**
     * get module from modules list
     * @param string $moduleId
     * @return WorkflowAppModule
     */
    protected function getModule($moduleId = null) {
        if ($moduleId == null) {
            $moduleId = AmcWm::app()->getController()->getModule()->getId();
        }
        $moduleRoot = AmcWm::app()->getModuleRootName($moduleId);
        $isBackend = false;
        if ($moduleRoot == AmcWm::app()->backendName) {
            $isBackend = true;
            $backend = AmcWm::app()->acl->getModule(AmcWm::app()->backendName);
            $module = $backend['modules'][AmcWm::app()->getModuleName($moduleId)];
            $moduleId = $module['name'];
        } else {
            $module = AmcWm::app()->acl->getModule($moduleId);
        }
        if (isset($this->_modules[$moduleId])) {
            return $this->_modules[$moduleId];
        } else {            
            $this->_modules[$moduleId] = new WorkflowAppModule($module, $isBackend);
            return $this->_modules[$moduleId];
        }
    }

    /**
     * Get Current workflow modules
     * @return array
     */
    public function getModules() {
        return $this->_modules;
    }

}