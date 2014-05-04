<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ApplicationModule class application module
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ApplicationModule extends CComponent {
    /**
     * Disable module from frontend and backend
     */

    const DISABLED = 0;
    /**
     * Module manage frontend and backend
     */
    const BOTH_TARGET = 1;

    /**
     * Module manage only the backend
     */
    const BACKEND_TARGET = 2;
    /**
     * Module manage only the frontend
     */
    const FRONTEND_TARGET = 3;

    /**
     * Module manage target, manage frontend or backend or both
     * @var string 
     */
    protected $moduleManageTarget = 1;

    /**
     * Checks if this application module bas been initialized.
     * @var boolean
     */
    private $_initialized = false;

    /**
     * the view path alias of root directory of view files.
     * If $viewsInProject is true then the view path is set to the module's views folder inside the site
     * If $viewsInProject is false then the view path is set in the views folder of amcwm or site
     * according to the value of $mainAlias.
     * Otherwise set the viewpath the the default path generate from Yii
     * @var boolean 
     */
    protected $viewPathAlias = null;

    /**
     * Custom application module not implement in AmcWm core application module
     * @var boolean 
     */
    private $_custom = false;

    /**
     * the alias of messages base path
     * @var boolean 
     */
    protected $messagesBase = null;

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     */
    private $_settings = null;

    /**
     * module id;
     * @var string 
     */
    private $_id;

    /**
     * Current path
     * @var string
     */
    protected $_appModulePath = null;

    /**
     * @var CModule owner/creator of this Application module. It could be either a WebApplication or a CModule.
     */
    private $_owner;

    /**
     * Backend or frontend Application
     * @var boolean 
     */
    private $_isBackend = false;

    /**
     * Constructor
     * @param string $id the ID of this module
     * @param CModule $owner/creator of this Application module. It could be either a WebApplication or a CModule.
     * @param integer $viewsInProject Default view inside amcwm library or current controller views directory in project 
     * @param boolean $isCustom Custom application module not implement in AmcWm core application module
     * @access public
     */
    public function __construct($id, $owner = null, $viewsInProject = null, $isCustom = false) {
        $this->_id = $id;
        $this->_custom = $isCustom;
        if ($owner === null) {
            $owner = AmcWm::app();
        }
        $this->_owner = $owner;
        $this->_isBackend = AmcWm::app()->getIsBackend();
        $folder = ($this->_isBackend) ? "backend" : "frontend";
        if ($this->_custom) {
            $viewsInProject = false;
            $mainAlias = "application.modules.application.{$id}";
        } else {
            $mainAlias = "amcwm.modules.{$id}";
        }
        $this->_appModulePath = "{$mainAlias}.{$folder}";
        Yii::import("{$mainAlias}.models.*");
        Yii::import("{$mainAlias}.components.*");
        $this->_settings = new Settings($this->_id, $this->_isBackend, $this->_custom);
        $structure = $this->_settings->getStructure();
        if (isset($this->_settings->settings['attributes']) && is_array($this->_settings->settings['attributes'])) {
            $attributes = $this->_settings->settings['attributes'];
            foreach ($attributes as $name => $value) {
                $this->setConfigAttribute($name, $value);
            }
        }

        if (isset($structure['controllers'])) {
            foreach ($structure['controllers'] as $controllerName => $controllerAlias) {
                if ($controllerAlias) {
                    $this->_owner->controllerMap[$controllerName] = "{$controllerAlias}";
                }
            }
        }
//        die();
        if ($viewsInProject) {
            $alias = str_replace(DIRECTORY_SEPARATOR, ".", trim(str_replace(AmcWm::app()->basePath, "application", $this->_owner->getViewPath()), DIRECTORY_SEPARATOR));
            AmcWm::setPathOfAlias($alias, $this->_owner->getViewPath());
            $this->viewPathAlias = $alias;
        } else {
            $this->viewPathAlias = "{$this->_appModulePath}.views";
            $this->_owner->setViewPath(AmcWm::getPathOfAlias($this->viewPathAlias));
        }
        $specialSettings = $this->_settings->getSettings($folder);
        $this->messagesBase = (isset($specialSettings["messageBase"])) ? $specialSettings["messageBase"] : "{$this->_appModulePath}.messages";
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
     * Initializes the ApplicationModule.
     * You may override this method to perform the needed initialization for the ApplicationModule.
     * @access public
     * @return void
     */
    public function init() {
        $this->setImport(array(
            "{$this->_appModulePath}.models.*",
            "{$this->_appModulePath}.components.*",
            "{$this->_appModulePath}.controllers.*",
        ));
        $this->_initialized = true;
    }

    /**
     * Checks if this application module bas been initialized.
     * @access public
     * @return boolean whether this application module has been initialized (ie, {@link init()} is invoked).   
     */
    public function getIsInitialized() {
        return $this->_initialized;
    }

    /**
     * Returns the directory that contains the application module.
     * @access public
     * @return string the directory that contains the application module.
     */
    public function getAppModulePath() {
        return AmcWm::getPathOfAlias($this->_appModulePath);
    }

    /**
     * Returns the alias that contains the message base path.
     * @access public
     * @return string the alias that contains the message base path.
     */
    public function getMessageBase() {
        return $this->messagesBase;
    }

    /**
     * Returns the alias of the directory that contains the application module.
     * @access public
     * @return string alias of the directory that contains the application module.
     */
    public function getAppModulePathAlias() {
        return $this->_appModulePath;
    }

    /**
     * Gets the root directory of view files.
     * * @access public
     * @return string the root directory of view files..
     */
    public function getViewPath() {
        return AmcWm::getPathOfAlias($this->viewPathAlias);
    }

    /**
     * Gets the alias of the directory  view files.
     * @access public
     * @return string the alias of view files.
     */
    public function getViewPathAlias() {
        return $this->viewPathAlias;
    }

    /**
     * Set Module manage target, manage frontend or backend or both
     * @access public
     * @return void
     */
    public function setModuleManageTarget($taregt = self::BOTH_TARGET) {
        if ($taregt == self::BACKEND_TARGET || $taregt == self::FRONTEND_TARGET || $taregt == self::BOTH_TARGET || $taregt == self::DISABLED) {
            $this->moduleManageTarget = $taregt;
        } else {
            $this->moduleManageTarget = self::BOTH_TARGET;
        }
    }

    /**
     * Sets an attribute found in application config array
     * @param string $name the attribute to be set
     * @param mixed $value the attribute value
     * @access public
     * @return void
     */
    public function setConfigAttribute($name, $value) {
        if (!$this->getIsInitialized()) {
            $this->$name = $value;
        }
    }

    /**
     * Get media settings array
     * @return array
     * @access public 
     */
    public function getMediaSettings() {
        return $this->_settings->getMediaSettings();
    }

    /**
     * Get media settings paths array
     * @return array
     * @access public 
     */
    public function getMediaPaths() {
        return $this->_settings->getMediaPaths();
    }

    /**
     * Get settings options 
     * @return array
     * @access public
     */
    public function getOptions() {
        return $this->_settings->options;
    }

    /**
     * Sets the aliases that are used in the module.
     * @param array $aliases list of aliases to be imported     
     * @access public    
     * @return void
     */
    public function setImport($aliases) {
        foreach ($aliases as $alias)
            Yii::import($alias);
    }

    /**
     * Get information setting
     * @param string $key if not equal null then we return the setting of this key only
     * @return array
     * @access public
     */
    public function getSettings($key = null) {
        return $this->_settings->getSettings($key);
    }
    
     /**
     * Get settings as object
     * @return Settings
     * @access public
     */
    public function settingsObject() {
        return $this->_settings;
    }

    /**
     * Get backend or frontend structure 
     * @param $isBackend Backend or frontend Application
     * @return array
     * @access public
     */
    public function getStructure() {
        return $this->_settings->getStructure();
    }

    /**
     * Get Virtual modules
     * @return array
     * @access public
     */
    public function getVirtuals() {
        return $this->_settings->getVirtuals();
    }

    /**
     * Get tables soring orders
     * @return array
     * @access public    
     */
    public function getTablesSoringOrders() {
        return $this->_settings->getTablesSoringOrders();
    }
    
    /**
     * Get tables wheres
     * @return array
     * @access public    
     */
    public function getTablesWheres() {
        return $this->_settings->getTablesWheres();
    }

    /**
     * Get Tables used in virtual modules
     * @return array
     * @access public    
     */
    public function getExtendsTables() {
        return $this->_settings->getExtendsTables();
    }

    /**
     * Get Main Table used in virtual module
     * @return string
     * @access public    
     */
    public function getTable() {
        return $this->_settings->getTable();
    }

    /**
     * Get Tables used in virtual modules
     * @param string $default return the default if cannot found the table in settings
     * @return string
     * @access public    
     */
    public function getCurrentVirtual() {
        return $this->_settings->getCurrentVirtual();
    }

    /**
     * Get virtual module view alias 
     * @param string $view
     * @return string
     */
    public function getVirtualView($view) {
        $current = $this->_settings->getCurrentVirtual();
        $virtuals = $this->_settings->getVirtuals();
        $alias = $view;
        if (isset($virtuals[$current]['views'][$view])) {
            $alias = "{$this->viewPathAlias}." . AmcWm::app()->getController()->getId() . ".{$current}.{$view}";
        }
        return $alias;
    }

    /**
     * Get id
     * @return string
     */
    public function getId(){
        return $this->_id;
    }
    
     /**
     * Get Table structure for the given $tableName
     * @param string $tableName
     * @return array
     * @access public    
     */
    public function getTableStruct($tableName) {
        return $this->_settings->getTableStruct($tableName);
    }
}
