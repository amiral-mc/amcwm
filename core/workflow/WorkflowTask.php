<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Workflow, generate common data and put it in the cache
 * @package AmcWebManager
 * @subpackage Data
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WorkflowTask {

    /**
     *
     * @var workflow tasks information
     */
    private $_taskInfo = array();
    
    /**
     *
     * @var integer workflow item id
     */
    private $_itemId = null;

    /**
     *
     * @var workflow tasks comments
     */
    private $_taskComments = array();

    /**
     * Constructor
     */
    public function __construct($itemId) {
        $this->_itemId = $itemId;
    }

    /**
     * 
     * Get task information
     * @return array();
     */
    public function getTaskinfo() {
        return $this->_taskInfo;
    }
    
    /**
     * redirect task
     * @return boolean
     */
    public function redirect(){
        
    }
    
    /**
     * 
     * Get workflow item id
     * @return array();
     */
    public function getItemId() {
        return $this->_itemId;
    }

    /**
     * 
     * Get task comments
     * @return array();
     */
    public function getTaskComments() {
        return $this->_taskComments;
    }

    /**
     * create task
     */
    public function create() {
        
    }

    /**
     * get previous task
     * @return WorkflowTask
     */
    public function previousTask() {
        
    }

    /**
     * get next task
     * @return WorkflowTask
     */
    public function nextTask() {
        
    }

    /**
     *  reject the previous task
     * @return boolean
     */
    public function rejectPrevious() {
        
    }

    /**
     * complete the this task and go the next task
     */
    public function complete() {
        
    }

}