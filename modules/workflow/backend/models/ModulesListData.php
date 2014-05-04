<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ModulesListData class, gets applications modules as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ModulesListData extends Dataset {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @todo fix bug if $limit = 0
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param integer $period, Period time in seconds. 
     * @param integer $limit, The numbers of items to fetch from table     
     * @param integer $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct() {
        
    }

    /**
     * Get logs setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("workflow", false);
        }
        return self::$_settings;
    }

    /**
     * Enable module to be workflow enabled.
     * @param integer $moduleId
     * @param boolean $published
     */
    public function enable2Workflow($moduleId, $published) {
        $ok = false;
        if ($moduleId) {
            $query = sprintf('update modules set workflow_enabled = %d where module_id = %d', $published, $moduleId);
            Yii::app()->db->createCommand($query)->execute();
            $ok = true;
        }
        return $ok;
    }
    
    /**
     * get the selected module details
     * @param integer $moduleId
     */
    public function getModuleDetails($moduleId){
        $backendModule = amcwm::app()->acl->getModule(AmcWm::app()->backendName);
        $query = sprintf("SELECT m.*
            from modules m
            where m.module_id = %d
            ",  $moduleId);        
        $row = Yii::app()->db->createCommand($query)->queryRow();
        if($row){
            $row['title'] = AmcWm::t($backendModule['modules'][$row["module"]]['messageSystem'], $backendModule['modules'][$row["module"]]['label']);
        }
        return $row;
    }

    /**
     *
     * Generate logs lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
            $this->addOrder("m.workflow_enabled desc");
        }

        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the articles array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        
        $this->query = sprintf("SELECT sql_calc_found_rows    
            m.*
            $cols
            from modules m            
            {$this->joins}
            where m.parent_module = 1
            and m.enabled = 1
            and m.system <> 1
            and m.virtual = 0
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ");
        $data = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($data);
    }

    /**     
     * Sets the the ArticlesListData.items array      
     * @param array $data 
     * @access protected     
     * @return void
     */
    protected function setDataset($data) {
        $index = -1;
        $backendModule = amcwm::app()->acl->getModule(AmcWm::app()->backendName);
        foreach ($data As $row) {
            if ($this->recordIdAsKey) {
                $index = $row['module_id'];
            } else {
                $index++;
            }
            $this->items[$index]['id'] = $row["module_id"];
            $this->items[$index]['title'] = AmcWm::t($backendModule['modules'][$row["module"]]['messageSystem'], $backendModule['modules'][$row["module"]]['label']);
            $this->items[$index]['module'] = $row['module'];
            $this->items[$index]['workflow_enabled'] = $row['workflow_enabled'];
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $row[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}