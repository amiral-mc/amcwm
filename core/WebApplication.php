<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * WebApplication extends CWebApplication by providing AamcWm functionalities
 * 
 * @package AmcWm.web
 * @property WebUser $user The user session information.
 * @property Acl $acl The user access control list information.
 * @property Workflow $workflow The user access control list information.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WebApplication extends CWebApplication {

    /**
     *
     * @var string default backend language; 
     */
    public $backendLang = 'ar';

    /**
     *
     * @var string default content language; 
     */
    public $contentLang = null;

    /**
     *
     * @var boolean using issue or not 
     */
    public $useIssue = false;

    /**
     *
     * @var boolean set to true to use workflow 
     */
    public $useWorkflow = false;

    /**
     * Backend module name
     * @var string 
     */
    public $backendName = "backend";

    /**
     * 
     * Current Running Application Module
     * @var ApplicationModule
     */
    private $_applicationModule = null;

    /**
     *
     * @var array frontend configuration array 
     */
    public $frontend = array();

    /**
     *
     * @var array backend configuration array 
     */
    public $backend = array();

    /**
     * Backend or frontend Application
     * @var boolean 
     */
    protected $isBackend = false;

    /**
     * Possible list names include the following:
     * <ul>
     * <li>class : string, specifies module class name</li>         
     * <li>attributes: array list of name-value pairs of attributes used in this module.
     * <li>instance: ApplicationModule instance equal null if instance has not been created yet
     * </ul>    
     * @var array ApplicationModules list
     */
    private $_applicationModules = array();

    /**
     * Constructor.
     * @param mixed $config application configuration.
     * If a string, it is treated as the path of the file that contains the configuration;
     * If an array, it is the actual configuration information.
     * Please make sure you specify the {@link getBasePath basePath} property in the configuration,
     * which should point to the directory containing all application logic, template and data.
     * If not, the directory will be defaulted to 'protected'.
     * @access public
     */
    public function __construct($config = null) {
        $applicationModules = include_once AMC_PATH . DIRECTORY_SEPARATOR . "params" . DIRECTORY_SEPARATOR . "applicationModules.php";
        $this->_applicationModules = $applicationModules;
        $this->setApplicationModules($applicationModules, false);
        $aclClass = "Acl";
        AmcWm::setPathOfAlias("bootstrap", AmcWm::getPathOfAlias("amcwm.vendors.bootstrap"));
        if (!isset($config['components']['assetManager']['class'])) {
            $config['components']['assetManager']['class'] = "AssetManager";
        }
        if (!isset($config['components']['session']['class'])) {
            $config['components']['session']['class'] = "HttpSession";
        }

        if (!isset($config['components']['session']['cookieParams']['httponly'])) {
            $config['components']['session']['cookieParams']['httponly'] = true;
        }
        
         if (!isset($config['components']['request']['enableCookieValidation'])) {
            $config['components']['request']['enableCookieValidation'] = true;
        }

        if (!isset($config['components']['imageworkshop']['class'])) {
            $config['components']['imageworkshop']['class'] = "amcwm.vendors.PHPImageWorkshop.YiiImageWorkshop";
        }

        if (!isset($config['backend']['bootstrap']['use'])) {
            $config['backend']['bootstrap']['use'] = false;
        }
        if (!isset($config['backend']['bootstrap']['useResponsive'])) {
            $config['backend']['bootstrap']['useResponsive'] = false;
        }
        if (!isset($config['frontend']['bootstrap']['use'])) {
            $config['frontend']['bootstrap']['use'] = false;
        }
        if (!isset($config['frontend']['bootstrap']['useResponsive'])) {
            $config['frontend']['bootstrap']['useResponsive'] = false;
        }
        $config['components']['bootstrap']['class'] = "bootstrap.components.MyBootstrap";
        if (isset($config['useWorkflow']) && $config['useWorkflow']) {
            $aclClass = "WorkflowAcl";
            if (!isset($config['components']['workflow'])) {
                $config['components']['workflow']['class'] = "MyWorkflow";
            }
        }
        if (isset($config['components']['acl']['class'])) {
            $aclClass = $config['components']['acl']['class'];
        }
        parent::__construct($config);
        $acl = unserialize($this->getGlobalState('acl'));
//        $acl = null;
        if ($acl == null) {
            $acl = new $aclClass();
            $acl->init();
            $this->setGlobalState('acl', serialize($acl));
        }
        $this->setComponent("acl", $acl);
    }

    /**
     * Puts a application module under the management
     * @param string $id module ID
     * @param array $module application module configruations
     * @param boolean $merge whether to merge the new configuration
     * with the existing one. Defaults to true, meaning the previously registered
     * configuration with the same ID will be merged with the new configuration.
     * If set to false, the existing configuration will be replaced completely.
     * This parameter is available since 1.1.13.
     * @access public
     * @return void 
     */
    public function setApplicationModule($id, $module, $merge = true) {
        if (!isset($module['class'])) {
            $class = ucfirst($id) . "ApplicationModule";
        } else {
            $class = $module['class'];
            unset($module['class']);
        }
        $inCore = false;
        if (isset($module['inCore'])) {
            $inCore = $module['inCore'];
            unset($module['inCore']);
        }

        $moduleConfig = array('inCore' => $inCore, 'class' => $class, 'attributes' => $module, 'instance' => null,);
        $moduleConfig['attributes']['custom'] = false;
        if (!isset($this->_applicationModules[$id])) {
            $moduleConfig['attributes']['custom'] = true;
            $this->_applicationModules[$id] = $moduleConfig;
        } else if ($merge) {
            $this->_applicationModules[$id] = CMap::mergeArray($this->_applicationModules[$id], $moduleConfig);
        } else {
            $this->_applicationModules[$id] = $moduleConfig;
        }
    }

    /**
     * Sets the application modules.   
     * @param array $modules application $modules(id=>module configuration or instances)
     * @param boolean $merge whether to merge the new configuration
     * with the existing one. Defaults to true, meaning the previously registered
     * configuration with the same ID will be merged with the new configuration.
     * If set to false, the existing configuration will be replaced completely.
     * This parameter is available since 1.1.13.
     * @access public
     * @return void
     */
    public function setApplicationModules($modules, $merge = true) {
        foreach ($modules as $id => $module)
            $this->setApplicationModule($id, $module, $merge);
    }

    /**
     * @todo add event to auto set the backend attribute then remove this method
     * @param boolean $ok
     * @access public
     * @return void
     */
    public function setIsBackend($ok) {
        $this->isBackend = $ok;
    }

    /**
     * Check if this application is Backend or frontend Application
     * @access public
     * @return void
     */
    public function getIsBackend() {
        return $this->isBackend;
    }

    /**
     * Get current running application module
     * @access public
     * @return ApplicationModule 
     */
    public function getAppModule() {
        return $this->_applicationModule;
    }

    /**
     * Get a applications modules used in the system
     * @access public
     * @return array
     */
    public function getApplicationModules() {
        return $this->_applicationModules;
    }

    /**
     * Get a applications module
     * @todo add event to get the current module and then remove $parentModule 
     * @param string $id component ID
     * @param CModule $parentModule the parent module to attach with it , attatch the prent module in initialization only
     * @access public
     * @return ApplicationModule 
     */
    public function getApplicationModule($id, $parentModule = null) {
        $module = null;
        if (isset($this->_applicationModules[$id])) {
            if ($this->_applicationModules[$id]['instance'] === null) {
                $class = $this->_applicationModules[$id]['class'];
                $isCustom = $this->_applicationModules[$id]['attributes']['custom'];
                unset($this->_applicationModules[$id]['attributes']['custom']);
                if (!$isCustom) {
                    Yii::import("amcwm.modules.{$id}.{$class}");
                } else {
                    Yii::import("application.modules.application.{$id}.{$class}");
                }
                if ($parentModule === null) {
                    $parentModule = AmcWm::app();
                }
                if ($this->isBackend) {
                    $viewsInProject = (isset($this->backend['viewsInProject']) && $this->backend['viewsInProject']);
                    $viewsInProjectIndex = "backend";
                } else {
                    $viewsInProject = (isset($this->frontend['viewsInProject']) && $this->frontend['viewsInProject']);
                    $viewsInProjectIndex = "frontend";
                }
                if (isset($this->_applicationModules[$id]['attributes']['viewsInProject'][$viewsInProjectIndex])) {
                    $viewsInProject = $this->_applicationModules[$id]['attributes']['viewsInProject'][$viewsInProjectIndex];
                }
                unset($this->_applicationModules[$id]['attributes']['viewsInProject']);
                $module = new $class($id, $parentModule, $viewsInProject, $isCustom);
                foreach ($this->_applicationModules[$id]['attributes'] as $attribute => $value) {
                    $module->setConfigAttribute($attribute, $value);
                }
                if (!$module->getIsInitialized()) {
                    $module->init();
                }
                $this->_applicationModules[$id]['instance'] = $module;
                $this->_applicationModule = $module;
                //die($this->_applicationModule->getMessageBase());
                AmcWm::setPathOfAlias('msgsbase', AmcWm::getPathOfAlias($this->_applicationModule->getMessageBase()));
            } else {
                $module = $this->_applicationModules[$id]['instance'];
            }
        }
        return $module;
    }

    /**
     * Initializes the application.
     * This method overrides the parent implementation by preloading the 'request' component.
     * @access protected
     * @return void
     */
    protected function init() {
        $modules = $this->modules;
        $newConfig = array(
            'modules' => array(
                'backend' => array(),
        ));
        if (!isset($modules['backend'])) {
            //$newConfig['modules']['backend'] = array('modules'=>array());
        }
        foreach ($this->_applicationModules as $id => $appModule) {
            $manageTarget = ApplicationModule::BOTH_TARGET;
            if (isset($appModule['attributes']['moduleManageTarget'])) {
                $manageTarget = $appModule['attributes']['moduleManageTarget'];
            }
            $moduleProp = array();
            switch ($manageTarget) {
                case ApplicationModule::BOTH_TARGET:
                    if ($appModule['inCore']) {
                        $moduleProp = array("class" => "amcwm.modules.{$id}.backend." . ucfirst($id) . "Module");
                        $newConfig['modules']['backend']['modules'][$id] = $moduleProp;
                        $moduleProp = array("class" => "amcwm.modules.{$id}.frontend." . ucfirst($id) . "Module");
                        $newConfig['modules'][$id] = $moduleProp;
                    } else {
                        if (!isset($modules['backend']['modules'][$id])) {
                            $newConfig['modules']['backend']['modules'][$id] = array();
                            $newConfig['modules'][$id] = array();
                        }
                    }
                    break;
                case ApplicationModule::BACKEND_TARGET:
                    if ($appModule['inCore']) {
                        $moduleProp = array("class" => "amcwm.modules.{$id}.backend." . ucfirst($id) . "Module");
                        $newConfig['modules']['backend']['modules'][$id] = $moduleProp;
                    } else {
                        if (!isset($modules['backend']['modules'][$id])) {
                            $newConfig['modules']['backend']['modules'][$id] = array();
                        }
                    }
                    break;
                case ApplicationModule::FRONTEND_TARGET:
                    if ($appModule['inCore']) {
                        $moduleProp = array("class" => "amcwm.modules.{$id}.frontend." . ucfirst($id) . "Module");
                        $newConfig['modules'][$id] = $moduleProp;
                    } else {
                        if (!isset($modules['modules'][$id])) {
                            $newConfig['modules'][$id] = array();
                        }
                    }
                    break;
            }
        }
        $this->configure($newConfig);
        $this->controllerMap = require AmcWm::getAmcWmPath() . DIRECTORY_SEPARATOR . "params" . DIRECTORY_SEPARATOR . "systemControllers.php";
        parent::init();
    }

    /**
     * This method method used create instance of  execute widget and run it
     * @param array $executeAlias name of the execute alias to run
     * @param array $managerProperties list of initial property values for the widget manager (Property Name => Property Value)
     * @param array $properties list of initial property values for the widget (Property Name => Property Value)
     * @param boolean $captureOutput whether to capture the output of the widget. If true, the method will capture
     * @access public
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function executeWidget($executeAlias, $managerProperties = array(), $properties = array(), $captureOutput = false) {
        $className = AmcWm::import($executeAlias);
        if (isset($managerProperties['widget'])) {
            $widgetName = $managerProperties['widget'];
            unset($managerProperties['widget']);
            $widget = new $className($widgetName, $properties, $captureOutput);
            foreach ($managerProperties as $name => $value) {
                $widget->$name = $value;
            }
            return $widget->executeWidget();
        }
    }

    /**
     * Get Module root name
     * @param string $id
     * @access public
     * @return string
     */
    public function getModuleRootName($id) {
        $tree = explode("/", $id);
        return $tree[0];
    }

    /**
     * Get Module root name
     * @param string $id
     * @access public
     * @return string
     */
    public function getModuleName($id) {
        $root = $this->getModuleRootName($id);
        if ($root) {
            return substr($id, strpos($id, "/") + 1);
        } else {
            return $id;
        }
    }

}
