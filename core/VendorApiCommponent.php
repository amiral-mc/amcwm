<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ApiCommponent any vendors api component must extend this class
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class VendorApiCommponent extends CComponent {

    /**
     * Current api id
     * @var string
     */
    private $_id;
    
    /**
     * Application Proxy 
     * @var string
     */
    protected $proxy = array();

     /**
     * Setting instance
     * @var Settings
     * @var array
     */
    protected $settings = null;
    
     /**
     * Constructor
     * @param string $id the ID of this module
     * @param array $proxy proxy to be send to api
     * @access public
     */
    public function __construct($id, $proxy) {
        $this->_id = $id;
        $this->proxy = $proxy;
    }
    
      /**
     * Get the api id 
     * @access public
     * @return string the directory that contains the application module.
     */
    public function getId() {
        return $this->_id;
    }
    
    /**
     * Initializes the api.
     * This method is called by the manager before the api starts to execute.
     * You may override this method to perform the needed initialization for the api.
     * @access public
     */
    public function init() {
    }
}