<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Mangae log for database changes
 * @subpackage AmcWm.data.log
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DbLogManager extends LogManager {
    
    /**
     * User actions
     */
    const INSERT = 1;
    const UPDATED = 2;
    const DELETE = 3;

    /**
     * Class used for generating database logs
     * @var DbLogData
     */
    private $_class = null;

    /**
     * set the data setting for the logger
     * @param ActiveRecord $model main active record model
     * @param integer $action current uesr action
     * 
     */
    public function setData($model, $action) {
        $moduleSettings = $model->getModuleSettings();
        $settings['log'] = array();
        if ($moduleSettings->isBackend()) {
            $key = 'backend';
        } else {
            $key = 'frontend';
        }
        $mySettings = $moduleSettings->getSettings($key);
        if (isset($mySettings['log'])) {
            $settings['log'] = $mySettings['log'];            
        }        
        $modelClass = $model->getClassName();
        $className = "Log{$modelClass}";
        $alias = $moduleSettings->getMainAlias() . ".{$key}.components.$className";
        $className = AmcWm::import($alias);
        $settings['module'] = $moduleSettings->getCurrentVirtual();
        $settings['log']['template'] = $moduleSettings->getMainAlias() . ".backend.views.logs.{$settings['module']}";
        if (isset($settings['log']['use']) && $settings['log']['use'] && class_exists($className)) {
            $settings['virtuals'] = $moduleSettings->getVirtuals();
            $settings['module'] = $moduleSettings->getCurrentVirtual();
            $settings['tables'] = $moduleSettings->getTables();
            $settings['media'] = $moduleSettings->getMediaSettings();
            if ($alias) {
                $this->_class = new $className($this, $settings, $model, $action);
            }
        }
    }

    /**
     * log action 
     * @param integer $logId if is not empty then the log instead of create new log will return the the current log id from log table
     * @access public
     * @return array if the given $logId is not empty then return log data for the given $logId
     */
    public function log() {
        if ($this->_class) {
        //if ($this->_class && $this->_class->isChanged()) {
            $log = parent::log();
            $this->_class->log();
            return $log;
        }
    }

    /**
     * Get log data from log table
     * @return array
     */
    public function getLogData() {        
        if ($this->getLogid()) {
            $row = Yii::app()->db->createCommand("
                select * 
                from users_log 
                inner join log_data on users_log.log_id = log_data.log_id 
                where log_data.log_id = {$this->getLogid()}")->queryRow();
            if($row){
                $row['data'] = unserialize(gzinflate($row['data']));
                return $row;
            }
        }
    }

    /**
     * Log database before changed
     * @param ActiveRecord $model     
     * @param integer $action user action  update delete 
     * 
     */
    static public function logAction(ActiveRecord $model, $action) {
        $isSystem = isset($model->is_system) && $model->is_system;
        if ($action == self::DELETE && $isSystem) {
            return;
        }
        $logManager = new DbLogManager();
        $logManager->setData($model, $action);
        $logManager->log();
    }

}
