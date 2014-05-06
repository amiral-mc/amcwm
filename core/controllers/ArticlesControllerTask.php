<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesControllerTask class, any articles tasks must extend this class
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ArticlesControllerTask extends ControllerTask {

    /**
     * Module manage the content, articles or news etc..
     * @var string 
     */
    protected $module;

    /**
     * table to get articles from
     * @var string 
     */
    protected $table;

    /** Initializes the ControllerTask.
     * @param array $options options appended to options attribute
     * You may override this method to perform the needed initialization for the ControllerTask.
     * @access public
     * @return void
     */
    protected function init($options=array()) {
        $this->settings = new Settings("articles", false);
        $this->module = $this->settings->currentVirtual;
        $this->table = $this->settings->table;
        if(isset($this->settings->settings['options'][$this->module]['default'])){
            $options = $this->settings->settings['options'][$this->module]['default'];            
        }
        parent::init($options);        
    }

}

