<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Any dabase log class must extend this class
 * @subpackage AmcWm.data.log
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class DbLogData {

    /**
     *
     * @var MainActiveRecord $model main active record model 
     */
    private $_model;

    /**
     *
     * @var array $settings current log settings
     */
    private $_settings;

    /**
     *
     * @var integer $action current uesr action 
     */
    private $_userAction;

    /**
     *
     * @var DbLogManager current manager
     */
    private $_manager;

    /**
     * Constructor, this DbLogData
     * @param DbLogManager $manager current manager
     * @param array $logSettings current module settings array
     * @param MainActiveRecord $model main active record model
     * @param integer $action current uesr action
     */
    public function __construct(DbLogManager $manager, $logSettings, $model, $action) {
        $this->_model = $model;
        $this->_settings = $logSettings;
        $this->_userAction = $action;
        $this->_manager = $manager;
    }

    /**
     * Get main active record model that fire this log
     * @return MainActiveRecord
     */
    protected function getModel() {
        return $this->_model;
    }

    /**
     * Get current log settings
     * @return array
     */
    protected function getSettings() {
        return $this->_settings;
    }

    /**
     * 
     * Get DbLogManager current manager
     * @return DbLogManager
     */
    protected function getManager() {
        return $this->_manager;
    }

    /**
     * Get current uesr action 
     * @return integer
     */
    protected function getUserAction() {
        return $this->_userAction;
    }

    /**
     * Get table info for the given $tableName
     * @param $tableName current table name
     * @return array
     */
    protected function getTableInfo($tableName) {
        $tableInfo = array();
        foreach ($this->_settings['tables'] as $table) {
            if ($table['name'] == $tableName) {
                $tableInfo = $table;
                break;
            }
        }
        return $tableInfo;
    }

    /**
     * log data
     * Make sure you call the parent implementation so the log run properly.
     * @access public
     */
    public function log() {
        if ($this->_manager->isNew() && $this->_manager->getLogid()) {
            $primaryKey = $this->_model->primaryKey;
            $module = $this->_settings['module'];
            $queries = array();

            foreach ($this->_settings['tables'] as $tableInfo) {
                $logNameKey = isset($tableInfo['logNameKey']) ? $tableInfo['logNameKey'] : $tableInfo['name'];
                $queries[$logNameKey]['method'] = 'queryRow';
                $queries[$logNameKey]['translation'] = array();

                if (isset($tableInfo['hasMany'])) {
                    $queries[$logNameKey]['method'] = 'queryAll';
                }
                if (isset($tableInfo['key'])) {
                    if (isset($tableInfo['onRelations'])) {
                        $tableKey = isset($tableInfo['onRelationsWhereKey']) ? $tableInfo['onRelationsWhereKey'] : $tableInfo['key'];
                        $select = isset($tableInfo['logSelect']) ? $tableInfo['logSelect'] : "{$tableInfo['name']}.*";
                        $queries[$logNameKey]['query'] = sprintf("select $select from {$tableInfo['name']} {$tableInfo['onRelations']} where {$tableKey} = %s", AmcWm::app()->db->quoteValue($primaryKey));
                    } else {
                        $queries[$logNameKey]['query'] = sprintf("select * from {$tableInfo['name']} where {$tableInfo['key']} = %s", AmcWm::app()->db->quoteValue($primaryKey));
                    }
                }

                if (isset($tableInfo['translation'])) {
                    $queries[$logNameKey]['translation']['tableName'] = $tableInfo['translation']['name'];
                    if (isset($tableInfo['onRelations'])) {
                        $relationTrans = isset($tableInfo['translation']['onRelations']) ? $tableInfo['translation']['onRelations'] : null;
                        $tableKey = isset($tableInfo['onRelationsWhereKey']) ? $tableInfo['onRelationsWhereKey'] : $tableInfo['translation']['key'];
                        $queries[$logNameKey]['translation']['query'] = sprintf("select {$tableInfo['translation']['name']}.* from {$tableInfo['translation']['name']} {$relationTrans} {$tableInfo['onRelations']} where {$tableKey} = %s", AmcWm::app()->db->quoteValue($primaryKey));
                    } else {
                        $queries[$logNameKey]['translation']['query'] = sprintf("select {$tableInfo['translation']['name']}.* from {$tableInfo['translation']['name']} where {$tableInfo['translation']['key']} = %s", AmcWm::app()->db->quoteValue($primaryKey));
                    }
                }
            }
            $db = $this->_model->dbConnection;
            $data = array(
                'data' => array(),
                'options' => array(
                    'template' => $this->_settings['log']['template'],
                )
            );
            foreach ($queries as $tableName => $querySettings) {
                $method = $querySettings['method'];
                $master = $db->createCommand($querySettings['query'])->$method();
                if (($querySettings['method'] == "queryRow" && $master) || $querySettings['method'] == "queryAll") {
                    $data['data'][$tableName]['db']['master'] = $master;
                    $data['data'][$tableName]['files'] = $this->getFileSystem($tableName);
                }
                if ($querySettings['translation']) {
                    $translation = $db->createCommand($querySettings['translation']['query'])->queryAll();
                    foreach ($translation as $translationRow) {
                        $data['data'][$tableName]['db']['translation']['db'][$translationRow['content_lang']] = $translationRow;
                        $data['data'][$tableName]['db']['translation']['contentLang'] = Controller::getContentLanguage();
                    }
                }
            }
            eval("\$title = (isset({$this->_settings['log']['title']})) ? {$this->_settings['log']['title']} : '';");
            $sql = "INSERT INTO log_data (log_id, title, data) VALUES (:logId, :logTitle , :logData)";
            $command = $db->createCommand($sql);
            $command->bindValue(":logId", $this->_manager->getLogid(), PDO::PARAM_INT);
            $command->bindValue(":logTitle", $title, PDO::PARAM_STR);
            $command->bindValue(":logData", gzdeflate(serialize($data)), PDO::PARAM_STR);
            $command->execute();
        }
    }

    public function getLogData() {
        
    }

    /**
     * get file system for the given $tableName
     * @access public
     * @return void     
     */
    abstract public function getFileSystem($tableName);
}
