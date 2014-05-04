<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ApiCommponent manage vendors api components
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VendorApiManager extends CComponent {
    

    /**
     * execute api
     * @static
     * @param $string $id api id
     * @param string $methodName method to run after initializes
     * @param array $properties list of initial property values for the widget (Property Name => Property Value)
     * @param array $proxy 
     * @return VendorApiCommponent
     * @access public     
     */
    static public function &getApi($id, $methodName = null, $properties=array(), $proxy=array()) {
        $class = ucfirst($id) . "Api";
        $class = AmcWm::import("amcwm.apis.$id.$class");
        $instance = new $class($id, $proxy);
        foreach ($properties as $name => $value){
            $instance->$name = $value;
        }
        $instance->init();        
        if($methodName && method_exists($instance, $methodName)){
            $instance->$methodName();
        }
        return $instance;
    }


}