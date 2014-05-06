<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Settings class get setting for any application module
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Settings extends CComponent {

    /**
     * Backend or frontend settings
     * @var boolean 
     */
    private $_isBackend = false;

    /**
     * Custom application module not implement in AmcWm core application module
     * @var boolean 
     */
    private $_custom = false;

    /**
     * setting generated from settings.php inside application module folder
     * @var array
     */
    private $_settings = array();

    /**
     * module id;
     * @var string 
     */
    private $_id;

    /**
     * @var string Current alias for application module
     */
    private $_mainAlias;

    /**
     * Constructor
     * @param string $id the ID of this module
     * @param boolean $isBackend Backend or frontend settings
     * @param boolean $isCustom Custom application module not implement in AmcWm core application module
     * @access public
     */
    public function __construct($id, $isBackend, $isCustom = false) {
        $this->_id = $id;
        $this->_custom = $isCustom;
        if ($this->_custom) {
            $this->_mainAlias = "application.modules.application.{$id}";
        } else {
            $this->_mainAlias = "amcwm.modules.{$id}";
        }
        $this->_isBackend = $isBackend;
        $settingsFile = AmcWm::getPathOfAlias("application.config") . DIRECTORY_SEPARATOR . "{$id}.php";
        $orgSettingsFile = AmcWm::getPathOfAlias($this->_mainAlias) . DIRECTORY_SEPARATOR . "settings.php";
        if (file_exists($orgSettingsFile)) {
            $this->_settings = require $orgSettingsFile;
            if (file_exists($settingsFile)) {
                $this->_settings = CMap::mergeArray($this->_settings, require $settingsFile);
            }
        }
    }

    /**
     * Get frontend module structure
     * @access public
     * @return array
     */
    public function getModule() {
        return amcwm::app()->acl->getModule($this->_id);
    }

    /**
     * Custom application module not implement in AmcWm core application module
     * @access public
     * @return bolean
     */
    public function isCustom() {
        return $this->_custom;
    }

    /**
     * Get media settings array
     * @return array
     * @access public 
     */
    public function getMediaSettings() {
        return (isset($this->_settings['media'])) ? $this->_settings['media'] : array();
    }

    /**
     * Get media settings paths array
     * @return array
     * @access public 
     */
    public function getMediaPaths() {
        $paths = array();
        $current = $this->getCurrentVirtual();
        if (isset($this->_settings['media']['paths'])) {
            $paths = $this->_settings['media']['paths'];
        }
        if (isset($this->_settings['media']['info']['excludeMedia'][$current])) {
            foreach ($this->_settings['media']['info']['excludeMedia'][$current] as $info) {
                if (isset($paths[$info])) {
                    unset($paths[$info]);
                }
            }
        }
        return $paths;
    }

    /**
     * Get information setting
     * @param string $key if not equal null then we return the setting of this key only
     * @return array
     * @access public
     */
    public function getSettings($key = null) {
        $settings = null;
        if ($key) {
            if (isset($this->_settings[$key])) {
                $settings = $this->_settings[$key];
            }
        } else {
            $settings = $this->_settings;
        }
        return $settings;
    }

    /**
     * Get backend or frontend structure 
     * @return array
     * @access public
     */
    public function getStructure() {
        $structure = array();
        $index = ($this->_isBackend) ? "backend" : "frontend";
        if (isset($this->_settings[$index]['structure'])) {
            $structure = $this->_settings[$index]['structure'];
        }
        return $structure;
    }

    /**
     * Get settings options 
     * @return array
     * @access public
     */
    public function getOptions() {
        $options = array();
        if (isset($this->_settings['options'])) {
            $options = $this->_settings['options'];
        }
        return $options;
    }

    /**
     * Get Virtual modules
     * @return array
     * @access public
     */
    public function getVirtuals() {
        $virtual = array();
        $index = ($this->_isBackend) ? "backend" : "frontend";
        if (isset($this->_settings[$index]['virtual']))
            $virtual = $this->_settings[$index]['virtual'];
        return $virtual;
    }

    /**
     * Get Virtual modules
     * @return array
     * @access public
     */
    public function getVirtual($virtualName) {
        $virtuals = $this->getVirtuals();
        $virtual = array();
        if (isset($virtuals[$virtualName])) {
            $virtual = $virtuals[$virtualName];
        }
        return $virtual;
    }

    /**
     * Get Virtual modules
     * @return array
     * @access public
     */
    public function getVirtualId($virtualName) {
        $virtual = $this->getVirtual($virtualName);
        $currentId = 0;
        if (isset($virtual['route'])) {
            $route = $virtual['route'];
            $forwards = amcwm::app()->acl->getForwardModules();
            foreach ($forwards as $id => $row) {
                if (isset($row[$route])) {
                    $currentId = $id;
                    break;
                }
            }
        }
        return $currentId;
    }

    /**
     * Get tables soring orders
     * @return array
     * @access public    
     */
    public function getTablesWheres() {
        $wheres = array();
        if (isset($this->_settings['tables'])) {
            foreach ($this->_settings['tables'] as $table) {
                if (isset($table['wheres'])) {
                    $wheres[$table['name']] = $table['wheres'];
                }
            }
        }
        return ($wheres);
    }

    /**
     * Get tables soring orders
     * @return array
     * @access public    
     */
    public function getTablesSoringOrders() {
        $sorting = array();
        if (isset($this->_settings['tables'])) {
            foreach ($this->_settings['tables'] as $table) {
                if (isset($table['sorting'])) {
                    $sorting[$table['name']] = $table['sorting'];
                }
            }
        }
        return ($sorting);
    }

    /**
     * Get Tables used in virtual modules
     * @return array
     * @access public    
     */
    public function getExtendsTables() {
        return (isset($this->_settings['tables'][0]['extendsTables'])) ? $this->_settings['tables'][0]['extendsTables'] : array();
    }

    /**
     * Get Tables used in the module
     * @return array
     * @access public    
     */
    public function getTables() {
        $tables = array();
        if (isset($this->_settings['tables'])) {
            $tables = $this->_settings['tables'];
        }
        return $tables;
    }

    /**
     * Get Table structure for the given $tableName
     * @param string $tableName
     * @return array
     * @access public    
     */
    public function getTableStruct($tableName) {
        $tables = $this->getTables();
        $tableStruct = array();
        foreach ($tables as $table) {
            if ($table['name'] == $tableName) {
                $tableStruct = $table;
                break;
            }
        }
        return $tableStruct;
    }

    /**
     * Get Main Table used in virtual module
     * @return string
     * @access public    
     */
    public function getTable() {
        $forward = AmcWm::app()->getController()->getForwardModule();
        $virtuals = $this->getVirtuals();
        $table = null;
        if (!$table && count($virtuals)) {
            if (isset($forward[0])) {
                foreach ($virtuals as $virtual) {
                    $route = $virtual['route'];
                    if ($route == $forward[0]) {
                        $table = $virtual['table'];
                    }
                }
            }
            if (!$table && isset($this->_settings['tables'][0]['name'])) {
                $table = $this->_settings['tables'][0]['name'];
            }
        }
        return $table;
    }

    /**
     * Get Tables used in virtual modules
     * @param string $default return the default if cannot found the table in settings
     * @return string
     * @access public    
     */
    public function getCurrentVirtual() {
        $isWeb = AmcWm::app() instanceof CWebApplication;
        if ($isWeb) {
            $forward = AmcWm::app()->getController()->getForwardModule();
        }
        $virtuals = $this->getVirtuals();
        $virtual = null;
        if (isset($forward[0])) {
            foreach ($virtuals as $virtualValue) {
                $route = $virtualValue['route'];
                if ($route == $forward[0]) {
                    $virtual = $virtualValue['module'];
                }
            }
        }
        if (!$virtual) {
            $virtual = $this->_id;
        }
        return $virtual;
    }

    /**
     * Get information setting for the given $moduleId
     * @param string $moduleId
     * @param string $key if not equal null then we return the setting of this key only
     * @param boolean $custom if true then get the innformmation from custom mosules inside the project
     * @static
     * @return array
     * @access public
     */
    static public function getModuleSettings($moduleId, $key = null, $custom = false) {
        static $settings = array();
        if (!isset($settings[$moduleId])) {
            if ($custom) {
                $mainAlias = "application.modules.application.{$moduleId}";
            } else {
                $mainAlias = "amcwm.modules.{$moduleId}";
            }
            $settingsFile = AmcWm::getPathOfAlias("application.config") . DIRECTORY_SEPARATOR . "{$moduleId}.php";
            if (!file_exists($settingsFile)) {
                $settingsFile = AmcWm::getPathOfAlias($mainAlias) . DIRECTORY_SEPARATOR . "settings.php";
            }
            if (file_exists($settingsFile)) {
                $settings = include $settingsFile;
            }
        }
        $return = $settings;
        if ($key && isset($settings[$key])) {
            $return = $settings[$key];
        }
        return $return;
    }

    /**
     * Get current alias for application module
     * @return  string 
     */
    public function getMainAlias() {
        return $this->_mainAlias;
    }

    /**
     * Gets setting status Backend or frontend settings
     * @return boolean 
     */
    public function isBackend() {
        return $this->_isBackend;
    }

}
