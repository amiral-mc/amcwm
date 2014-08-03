<?php

//use PHPImageWorkshop\ImageWorkshop as ImageWorkshop;
/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * YiiImageWorkshop class.
 * @package Images
 * @author Amiral Management Corporation
 * @version 1.0
 */
class YiiImageWorkshop extends CApplicationComponent {

    /**
     *
     * @var array static methods used in  PHPImageWorkshop\ImageWorkshop
     */
    protected $methods;

    /**
     * init access
     * @access private
     * @static
     * @return void     
     */
    public function init() {
        AmcWm::setPathOfAlias('PHPImageWorkshop', AmcWm::getPathOfAlias('amcwm.vendors') . DIRECTORY_SEPARATOR . "PHPImageWorkshop");
        Amcwm::import("PHPImageWorkshop.Exception.*");
        Amcwm::import("PHPImageWorkshop.Core.Exception.*");
        Amcwm::import("PHPImageWorkshop.Core.*");
        Amcwm::import("PHPImageWorkshop.ImageWorkshop");
        $class = new ReflectionClass("PHPImageWorkshop\ImageWorkshop");
        $methods = $class->getMethods(ReflectionMethod::IS_STATIC);
        foreach ($methods as $method) {
            $this->methods[$method->name] = "{$method->class}::{$method->name}";
        }
        unset($methods);
        unset($class);
        parent::init();
    }

    /**
     * Calls the named method which is not a class method.
     * Do not call this method. This is a PHP magic method that we override
     * to implement the behavior feature.
     * @param string $name the method name
     * @param array $parameters method parameters
     * @throws CException if current class and its behaviors do not have a method or closure with the given name
     * @return mixed the method return value
     */
    public function __call($name, $parameters) {
        if (isset($this->methods[$name])) {
            return call_user_func_array($this->methods[$name],$parameters);
        } else {
            parent::__call($name, $parameters);
        }
    }

    public function getShop() {
        return "PHPImageWorkshop\ImageWorkshop";
    }

}
