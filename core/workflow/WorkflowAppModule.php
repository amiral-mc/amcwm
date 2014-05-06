<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * WorkflowAppModule get work flow for a module
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WorkflowAppModule {

    /**
     * module of the current work follow
     * @var array
     */
    private $_module = array();

    /**
     *
     * @var Settings module settings
     */
    private $_settings = null;

    /**
     * Constructor
     * @param string $module
     * @param boolean $isBackend
     */
    public function __construct($module, $isBackend = true) {
        $query = "select ws.step_title, w.flow_id, m.module, ws.step_id, a.action_id, a.action, c.controller, wr.role_id, wu.user_id from workflow w 
                inner join modules m on m.module_id = w.module_id  
                inner join workflow_steps ws on w.flow_id = ws.flow_id
                inner join workflow_actions wa on ws.step_id = wa.step_id
                inner join actions a on wa.action_id = a.action_id
                inner join controllers c on a.controller_id = c.controller_id
                left join workflow_roles wr on wr.step_id = ws.step_id
                left join workflow_users wu on wr.step_id = ws.step_id
                where w.enabled = 1 and is_major = 1 and m.enabled = 1 
                and m.module_id=" . (int) $module['id'] .
                //" and (wr.role_id=" . (int) AmcWm::app()->user->getRole() . " or wu.user_id=" . (int) AmcWm::app()->user->id . ")" .
                " order by w.flow_id, step_sort";
        $workflows = AmcWm::app()->db->createCommand($query)->queryAll();
        if ($workflows) {
            foreach ($workflows as $workflow) {
                $route = ($isBackend) ? AmcWm::app()->backendName . "/{$module['name']}/{$workflow['controller']}/{$workflow['action']}" : "{$module['name']}/{$workflow['controller']}/{$workflow['action']}";
                $route = trim($route, "/");
                $routeIndex = strtolower($route);
                if ($workflow['role_id'] == AmcWm::app()->user->getRole() || $workflow['user_id'] == AmcWm::app()->user->id) {
                    $this->_module['user'][AmcWm::app()->user->id]['steps'][$workflow['step_id']] = $workflow['step_id'];
                }
                $stepTitle = trim(preg_replace('/\s+/', '', $workflow['step_title']));
                $this->_module['routes'][$routeIndex]['flowId'] = $workflow['flow_id'];
                $this->_module['routes'][$routeIndex]['stepId'] = $workflow['step_id'];
                $this->_module['routes'][$routeIndex]['step_title'] = $stepTitle;
                $this->_module['routes'][$routeIndex]['route'] = $route;
                
                $this->_module['data'][$workflow['flow_id']]['id'] = $workflow['flow_id'];
                $this->_module['data'][$workflow['flow_id']]['steps'][$workflow['step_id']]['step_title'] = $stepTitle;
                $this->_module['data'][$workflow['flow_id']]['steps'][$workflow['step_id']]['stepId'] = $workflow['step_id'];
                $this->_module['data'][$workflow['flow_id']]['steps'][$workflow['step_id']]['routes'][$workflow['action_id']] = $route;
            }
        }
//        print_r($this->_module);
//        die();
        $this->_settings = new Settings($module['name'], $isBackend);
    }

    /**
     * generate task join
     * @return array
     */
    public function generateTaskJoin($col) {
        $join = null;
        if ($this->hasUserSteps()) {
            $join = " left join workflow_tasks on {$col} = workflow_tasks.item_id";
            $join .= " left join workflow_steps on workflow_tasks.step_id = workflow_steps.step_id";
        }
        return $join;
    }

    /**
     * check if the given $itemId in the user steps
     * @return boolean
     */
    public function checkTaskItem($itemId) {
        $query = "select step_id from workflow_tasks where item_id = " . (int) $itemId;
        $taskstepId = Yii::app()->db->createCommand($query)->queryScalar();
        $ok = ((isset($this->_module['user'][AmcWm::app()->user->id]['steps'][$taskstepId]) || !$taskstepId)) ? true : false;
        return $ok;
    }
    
    
    /**
     * get flow from the given $itemId
     * @return array()
     */
    public function getFlowFromTaskItem($itemId) {
        $query = "select step_id from workflow_tasks where item_id = " . (int) $itemId;
        $taskstepId = Yii::app()->db->createCommand($query)->queryScalar();
        $flow = array();
        foreach($this->_module['routes'] as $step){
            if($step['stepId'] == $taskstepId){
                $flow = $step;
            }
        }
        return $flow;
    }
    

    /**
     * generate task condition
     * @return array
     */
    public function generateTaskCondition() {
        if ($this->hasUserSteps()) {
            return "workflow_tasks.step_id in(" . implode(",", $this->getUserStepsIds()) . ")";
        } else {
            return "workflow_tasks.step_id = -1";
        }
    }

    /**
     * save current task  $taskId 
     * @param integer $taskId
     * @param string $route
     */
    public function saveTaskStep($taskId, $route = null) {
        $taskId = (int) $taskId;
        if ($route == null) {
            $route = AmcWm::app()->getController()->route;
        }
        $flow = $this->getFlowFromRoute($route);
        if (isset($flow['stepId'])) {
            $query = sprintf("select task_id from workflow_tasks where item_id = %d and step_id = %d", $taskId, $flow['stepId']);
            $task = AmcWm::app()->db->createCommand($query)->queryScalar();
            if (!$task) {
                $query = sprintf("insert into workflow_tasks(step_id, item_id) values(%d, %d)", $flow['stepId'], $taskId);
                AmcWm::app()->db->createCommand($query)->execute();
            }
        }
    }
    
    /**
     * save current task  $taskId 
     * @param integer $taskId
     * @param string $route
     */
    public function deleteTaskStep($taskId, $route = null) {
        $taskId = (int) $taskId;
        if ($route == null) {
            $route = AmcWm::app()->getController()->route;
        }
        $flow = $this->getFlowFromRoute($route);
        if (isset($flow['stepId'])) {
            $query = sprintf("select task_id from workflow_tasks where item_id = %d and step_id = %d", $taskId, $flow['stepId']);
            $task = AmcWm::app()->db->createCommand($query)->queryScalar();
            if ($task) {
                $query = sprintf("delete from workflow_tasks where item_id = %d and step_id = %d", $taskId, $flow['stepId']);            
                AmcWm::app()->db->createCommand($query)->execute();
            }
        }        
    }

    /**
     * Move the given $taskId to next step
     * @param integer $taskId
     * @param boolean $deleteFirst delete task steps first
     * @param boolean $previous delete task steps first
     * @param string $route
     */
    public function moveTaskToNextStep($taskId, $deleteFirst = false, $previous = false, $route = null) {
        $taskId = (int) $taskId;
        if ($route == null) {
            $route = AmcWm::app()->getController()->route;
        }
        $flow = $this->getFlowFromRoute($route);
        if($previous){
            $step = $this->getPreviousStep($flow);
        }
        else{
            $step = $this->getNextStep($flow);
        }
        if ($step !== null) {
            if (isset($step['stepId'])) {
                if ($deleteFirst) {
                    $query = "delete from workflow_tasks where " . $this->generateTaskCondition();
                    $task = false;
                    AmcWm::app()->db->createCommand($query)->execute();
                } else {
                    $query = sprintf("select task_id from workflow_tasks where item_id = %d and step_id = %d", $taskId, $flow['stepId']);
                    $task = AmcWm::app()->db->createCommand($query)->queryScalar();
                }
                if ($task) {
                    $query = sprintf("update workflow_tasks set step_id = %d where item_id = %d and step_id = %d", $step['stepId'], $taskId, $flow['stepId']);
                } else {
                    $query = sprintf("insert into workflow_tasks(step_id, item_id) values(%d, %d)", $step['stepId'], $taskId);
                }
                AmcWm::app()->db->createCommand($query)->execute();
            }
            else{
                $query = sprintf("delete from workflow_tasks where item_id = %d and step_id = %d", $taskId, $flow['stepId']);
                AmcWm::app()->db->createCommand($query)->execute();
            }
        }
        else{
            $query = sprintf("delete from workflow_tasks where item_id = %d and step_id = %d", $taskId, $flow['stepId']);            
            AmcWm::app()->db->createCommand($query)->execute();
        }
    }
            

    /**
     * Get next step for the given $flow
     * @param array $flow
     */
    public function getNextStep($flow) {
        $step = null;
        if (isset($flow['flowId']) && isset($flow['stepId'])) {
            $steps = $this->getFlowStepsIds($flow['flowId']);
            while (list($stepId, $step) = each($steps)) {
                if ($step['stepId'] == $flow['stepId']) {
                    break;
                }
            }
            $step = current($steps);
            if (!$step) {
                $step = array();
            }
        }
        return $step;
    }
    
      /**
     * Get next step for the given $flow
     * @param array $flow
     */
    public function getPreviousStep($flow) {
        $step = null;
        if (isset($flow['flowId']) && isset($flow['stepId'])) {
            $steps = array_reverse($this->getFlowStepsIds($flow['flowId']));
            while (list($stepId, $step) = each($steps)) {
                if ($step['stepId'] == $flow['stepId']) {
                    break;
                }
            }
            $step = current($steps);
            if (!$step) {
                $step = array();
            }
        }
        return $step;
    }

    /**
     * Check if the current user has steps or not
     * @return boolean
     */
    public function hasUserSteps() {
        return isset($this->_module['user'][AmcWm::app()->user->id]['steps']) && count($this->_module['user'][AmcWm::app()->user->id]['steps']);
    }

    /**
     * Get steps ids for the current user
     * @param integer $flowId
     * @return array
     */
    public function getFlowStepsIds($flowId) {
        $steps = array();
        if (isset($this->_module['data'][$flowId]['steps'])) {
            $steps = $this->_module['data'][$flowId]['steps'];
        }
        return $steps;
    }

    /**
     * Get steps ids for the current user
     * @return array
     */
    public function getUserStepsIds() {
        $steps = array();
        if ($this->hasUserSteps()) {
            $steps = $this->_module['user'][AmcWm::app()->user->id]['steps'];
        }
        return $steps;
    }

    /**
     * Get flow data from giving $route     
     * @param string $route
     * @param boolean $forCurrentUser
     * @return array
     */
    public function getFlowFromRoute($route, $forCurrentUser = true) {
        $route = trim(strtolower($route), "/");
        $flow = array();        
        if($forCurrentUser){
            $userSteps = $this->getUserStepsIds();
            $allow = isset($this->_module['routes'][$route]) && isset($userSteps[$this->_module['routes'][$route]['stepId']]);
        }
        else{
            $allow = isset($this->_module['routes'][$route]);
        }
        if ($allow) {
            $flow = $this->_module['routes'][$route];
        }
        return $flow;
    }

    /**
     * get settings
     * @return setiings
     */
    public function getSettings() {
        return $this->_settings;
    }

}