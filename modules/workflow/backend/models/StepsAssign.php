<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * StepsAssign class, gets applications roles and users as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class StepsAssign extends Dataset {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;
    private $_stepId = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $limit, The numbers of items to fetch from table
     * @access public
     */
    public function __construct($stepId, $limit = 100) {
        $this->limit = $limit;
        $this->setStepId($stepId);
        $this->generate();
    }

    public function setStepId($id) {
        $this->_stepId = $id;
    }

    public function getStepId() {
        return $this->_stepId;
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
     *
     * Generate logs lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
//            $this->addOrder("m.workflow_enabled desc");
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
        $data = $this->_getItems();
        $this->setDataset($data);
    }

    private function _getItems($parentId = null, &$dataItems = array()) {
        $baseRoleId = amcwm::app()->acl->getRoleId(Acl::EDITOR_ROLE);
        if ($parentId) {
            $wheres = "parent_role_id = $parentId";
        }else{
            $wheres = "role_id = $baseRoleId";
        }
        
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres .= $this->generateWheres();
               
        $q = sprintf("SELECT r.*
            $cols
            from roles r            
            {$this->joins}
            where  $wheres
            $orders
            ");
        $data = Yii::app()->db->createCommand($q)->queryAll();
        if (count($data)) {
            foreach ($data as $role){
                $dataItems[] = $role;
                $this->_getItems($role['role_id'], $dataItems);
            }
        }
        return $dataItems;
    }

    /**
     * Sets the the ArticlesListData.items array      
     * @param array $data 
     * @access protected     
     * @return void
     */
    protected function setDataset($data) {
        $index = -1;
        foreach ($data As $row) {
            if ($this->recordIdAsKey) {
                $index = $row['role_id'];
            } else {
                $index++;
            }
            $this->items[$index]['id'] = $row["role_id"];
            $this->items[$index]['title'] = $row["role"];
            $this->items[$index]['desc'] = $row['role_desc'];
            if ($this->_stepId) {
                $this->items[$index]['selected'] = Yii::app()->db->createCommand(sprintf('select count(*) from workflow_roles where role_id= %d and step_id=%d', $row['role_id'], $this->_stepId))->queryScalar();
            } else {
                $this->items[$index]['selected'] = 0;
            }
            $this->items[$index]['usersList'] = $this->_getUsers($row["role_id"]);
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $row[$colIndex];
            }
        }
        $this->count = count($data);
    }

    private function _getUsers($roleId) {
        $currentLang = Controller::getCurrentLanguage();
        $query = sprintf("select u.*, pt.name 
            from users u 
            inner join persons p on p.person_id = u.user_id
            inner join persons_translation pt on pt.person_id = p.person_id
            where pt.content_lang = %s
            and u.role_id = %d
            limit 100", Yii::app()->db->quoteValue($currentLang), $roleId);
        $users = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($users as $k => $user) {
            if ($this->_stepId) {
                $users[$k]['selected'] = Yii::app()->db->createCommand(sprintf('select count(*) from workflow_users where user_id= %d and step_id=%d', $user['user_id'], $this->_stepId))->queryScalar();
            } else {
                $users[$k]['selected'] = 0;
            }
        }
        return $users;
    }

}