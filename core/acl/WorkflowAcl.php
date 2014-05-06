<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Acl class.
 * @package Acl
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WorkflowAcl extends Acl {

    /**
     * get current user access for the given $userId;
     * @param integer $userId
     * @access private
     * @return array
     */
    public function getUserAccees($userId) {
        $userAccessControllers = array();
        $roleStatement = sprintf(
                "select c.controller_id, c.controller, ua.role_id, ua.access, c.hidden
                from controllers c 
                inner join users_access_rights ua on c.controller_id = ua.controller_id 
                where ua.user_id = %d", $userId
        );
        $accessControllersDataset = Yii::app($roleStatement)->db->createCommand($roleStatement)->queryAll();
        $query = sprintf("select wa.action_id, a.controller_id, a.permissions from workflow_actions wa 
                    inner join workflow_users wu on wa.step_id = wu.step_id
                    inner join actions a on a.action_id = wa.action_id                    
                    where wu.user_id = %d", $userId
        );
        $workflowActions = AmcWm::app()->db->createCommand($query)->queryAll();
        foreach ($workflowActions as $workflowAction) {
            $userAccessControllers[$workflowAction['controller_id']]['access'] = 0;
            $userAccessControllers[$workflowAction['controller_id']]['role_id'] = 3;
            $userAccessControllers[$workflowAction['controller_id']]['sumAccess'][$workflowAction['permissions']] = $workflowAction['permissions'];
        }
        foreach ($userAccessControllers as &$userAccessController) {
            $userAccessController['access'] = array_sum($userAccessController['sumAccess']);
            unset($userAccessController['sumAccess']);
        }
        foreach ($accessControllersDataset as $accessController) {
            if (!isset($userAccessControllers[$accessController['controller_id']])) {
                $userAccessControllers[$accessController['controller_id']] = array('access' => $accessController['access'], 'role_id' => $accessController['role_id'], 'visible' => !$accessController['hidden']);
            }
        }
        return $userAccessControllers;
    }  

    /**
     * Set role acccess data
     */
    protected function setRolesAccess() {
        $this->setRoles();
        foreach ($this->roles as $role) {
            $rolesStatements = array();
            foreach ($role['tree'] as $priority => $treeId) {
                $rolesStatements[] = sprintf(
                        "select '{$priority}' priority, c.controller_id, ar.role_id,  ar.access, c.hidden
                from controllers c 
                inner join access_rights ar on c.controller_id = ar.controller_id                
                where ar.role_id = %d", $treeId
                );
            }
            $query = sprintf("select wa.action_id, a.controller_id, a.permissions from workflow_actions wa 
                    inner join workflow_roles wr on wa.step_id = wr.step_id
                    inner join actions a on a.action_id = wa.action_id
                    where role_id = %d", $role['id']);
            $workflowActions = AmcWm::app()->db->createCommand($query)->queryAll();
//            echo $query. "\n";
            foreach ($workflowActions as $workflowAction) {
                $this->accessControllers[$role['id']][$workflowAction['controller_id']]['access'] = 0;
                $this->accessControllers[$role['id']][$workflowAction['controller_id']]['role_id'] = $role['id'];
                $this->accessControllers[$role['id']][$workflowAction['controller_id']]['sumAccess'][$workflowAction['permissions']] = $workflowAction['permissions'];
            }
            $accessControllersDataset = Yii::app()->db->createCommand(implode("\n union ", $rolesStatements) . " order by priority")->queryAll();
            foreach ($accessControllersDataset as $accessController) {
                if (isset($this->accessControllers[$role['id']][$accessController['controller_id']]['sumAccess'])) {
                    $this->accessControllers[$role['id']][$accessController['controller_id']]['access'] = array_sum($this->accessControllers[$role['id']][$accessController['controller_id']]['sumAccess']);
                    $this->accessControllers[$role['id']][$accessController['controller_id']]['visible'] = !$accessController['hidden'];
                    unset($this->accessControllers[$role['id']][$accessController['controller_id']]['sumAccess']);
                } else {
                    $this->accessControllers[$role['id']][$accessController['controller_id']] = array('access' => $accessController['access'], 'role_id' => $accessController['role_id'], 'visible' => !$accessController['hidden']);
                }
            }
        }
    }
}
