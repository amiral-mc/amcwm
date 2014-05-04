<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Mangae log for users actions
 * @subpackage AmcWm.data.log
 * @author Amiral Management Corporation
 * @version 1.0
 */
class LogManager {

    /**
     *  Equal true if the log class generate logs 
     * @var mixed     
     */
    private $_success = false;

    /**
     *  route action id number
     * @var integer     
     */
    private $_routeActionId = false;

    /**
     * Current user ip
     * @var string 
     */
    private $_userIP;

    /**
     * Current log id
     * @var integer 
     */
    private $_logId;

    /**
     *
     * @var boolean Whether the log is new and should be inserted into log table
     */
    private $_isNew;

    /**
     * Constructor, this DbLogManager     
     * @param integer $logId if is not empty then the log instead of create new log will return the the current log id from log table
     * 
     */
    public function __construct($logId = 0) {
        $this->_logId = $logId;
        if (!$logId) {
            $this->_isNew = true;
        }
        $this->init();
    }

    /**
     * Get route action id number
     * @return integer
     */
    public function getRouteAction() {
        return $this->_routeActionId;
    }

    /**
     * Whether the log is new and should be inserted into log table
     * @return boolean
     */
    public function isNew() {
        return $this->_isNew;
    }

    /**
     * Get log id
     * @return integer
     */
    public function getLogid() {
        return $this->_logId;
    }

    /**
     * Get log data from log table
     * @return array
     */
    public function getLogData() {
        if ($this->_logId) {
            return Yii::app()->db->createCommand("select * from users_log where log_id = {$this->_logId}")->queryRow();
        }
    }

    /**
     * Get log data from log table
     * @return array
     */
    public function getLog() {
        if ($this->_logId) {
            $query = sprintf("SELECT   
            u.username, ul.ip, 
            ul.log_id, ul.action_date,
            a.action, c.controller, m.module, m.parent_module
            from users_log ul 
            inner join actions a on a.action_id = ul.action_id
            inner join controllers c on c.controller_id = a.controller_id
            inner join modules m on m.module_id = c.module_id            
            inner join users u on u.user_id = ul.user_id
            where log_id = %d
            ", $this->_logId);
            $modules = amcwm::app()->acl->getModules();
            $log = Yii::app()->db->createCommand($query)->queryRow();
            if ($log) {
                $messageSystem = "amcwm.system.messages.system";
                if ($log['parent_module'] == 1) {
                    if (isset($modules[AmcWm::app()->backendName]['modules'][$log["module"]]['messageSystem'])) {
                        $messageSystem = $modules[AmcWm::app()->backendName]['modules'][$log["module"]]['messageSystem'];
                    }
                } else if (isset($modules[$log["module"]]['messageSystem'])) {
                    $messageSystem = $modules[$log["module"]]['messageSystem'];
                }
                $log['action_name'] = AmcWm::t($messageSystem, strtoupper("_{$log['action']}_action_"));
            }
            return $log;
        }
    }

    /**
     * Get Current user ip
     * @return string
     */
    public function getUserIP() {
        return $this->_userIP;
    }

    /**
     * Log data to master log table "users_log"
     * @return integer last inserted id
     */
    protected function logToMaster() {
        if (!AmcWm::app()->user->isGuest && $this->_routeActionId && $this->_isNew) {
            $query = sprintf(
                    "insert into users_log(ip, action_id, user_id, action_date)
                            values(%s, %d, %d, '%s')
                        ", Yii::app()->db->quoteValue($this->_userIP)
                    , $this->_routeActionId
                    , AmcWm::app()->user->id
                    , date("Y-m-d H:i:s")
            );
            $this->_success = Yii::app()->db->createCommand($query)->execute();
            $this->_logId = AmcWm::app()->db->getLastInsertID();
            return $this->_logId;
        }
    }

    /**
     * Get last ip inserted for the current acction
     * @return string
     */
    public function getLastIP() {
        if ($this->_routeActionId) {
            return Yii::app()->db->createCommand("select ip from users_log where action_id= {$this->_routeActionId} order by action_date desc  limit 0,1 ")->queryScalar();
        }
    }

    /**
     *  get current route
     * @todo add translate to action tables after correcting the transation concept for any acl tables
     *  @return string
     */
    protected function getRoute() {
        $controller = AmcWm::app()->getController();
        $forward = $controller->getForwardModule();
        $route = $controller->getRoute();
        if ($controller->getAction()->getId() == "translate") {
            $langsCount = count(AmcWm::app()->params->languages);
            if ($langsCount > 1) {
                $route = str_replace("translate", "update", $route);
            }
        }
        if ($forward) {
            $route = str_replace($forward[1], $forward[0], $route);
        }
        return $route;
    }

    /**
     * Initializes the log.
     * If you override this method, make sure to call the parent implementation.
     */
    protected function init() {
        $routeInfo = amcwm::app()->acl->getRouteInfo($this->getRoute());
        if (isset($routeInfo['actionId'])) {
            $this->_routeActionId = (int) $routeInfo['actionId'];
        }
        $this->_userIP = Yii::app()->request->getUserHostAddress();
    }

    /**
     * log action      
     * @access public
     * @return array if the given $logId is not empty then return log data for the given $logId
     */
    public function log() {
        if ($this->_isNew) {
            $this->logToMaster();
        } else {
            return $this->getLogData();
        }
    }

    /**
     * Return true if the log class generate logs 
     * @access public
     * @return boolean
     */
    public function isSucceed() {
        return $this->_success;
    }

}
