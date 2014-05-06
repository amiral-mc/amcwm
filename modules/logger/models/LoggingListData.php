<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * LoggingListData class, gets applications logs as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class LoggingListData extends Dataset {

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
            self::$_settings = new Settings("logger", false);
        }
        return self::$_settings;
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
            $this->addOrder("ul.action_date desc");
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
//        $currentDate = date("Y-m-d H:i:s");
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres('where');
        $this->query = "SELECT sql_calc_found_rows    
            u.username, a.action, ul.ip, 
            ul.log_id, ul.action_date,
            ld.title, c.controller, m.module , m.parent_module
            $cols
            from users_log ul            
            inner join actions a on a.action_id = ul.action_id
            inner join controllers c on c.controller_id = a.controller_id
            inner join modules m on m.module_id = c.module_id                        
            inner join users u on u.user_id = ul.user_id
            left join log_data ld on ld.log_id = ul.log_id
            {$this->joins}
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ";
        $loggers = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($loggers);
    }

    /**
     *
     * Sets the the ArticlesListData.items array      
     * @param array $loggers 
     * @access protected     
     * @return void
     */
    protected function setDataset($loggers) {
        $index = -1;
        $modules = amcwm::app()->acl->getModules();        
        foreach ($loggers As $logger) {
            if ($this->recordIdAsKey) {
                $index = $logger['log_id'];
            } else {
                $index++;
            }

            $this->items[$index]['id'] = $logger["log_id"];
            $this->items[$index]['action'] = 'logger["action_name"]';
            $messageSystem = "amcwm.system.messages.system";
            if($logger['parent_module'] == 1){
                if(isset($modules[AmcWm::app()->backendName]['modules'][$logger["module"]]['messageSystem'])){
                    $messageSystem = $modules[AmcWm::app()->backendName]['modules'][$logger["module"]]['messageSystem'];    
                }
            }
            else if(isset($modules[$logger["module"]]['messageSystem'])){
                $messageSystem = $modules[$logger["module"]]['messageSystem'];
            }
//            $this->items[$index]['action'] = AmcWm::t($backendModule['modules'][$logger["module"]]['messageSystem'], $backendModule['modules'][$logger["module"]]['label']);
            $this->items[$index]['action'] = AmcWm::t($messageSystem, strtoupper("_{$logger['action']}_action_"));
            $this->items[$index]['username'] = $logger["username"];
            $this->items[$index]['fromip'] = $logger["ip"];
            $this->items[$index]['action_date'] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm a)", $logger['action_date']);

            $this->items[$index]['title'] = $logger['title'];
            $this->items[$index]['controller'] = $logger['controller'];
            $this->items[$index]['module'] = $logger['module'];

            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $logger[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}