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
class Acl extends CApplicationComponent {

    /**
     *
     * @var boolean Checks if this application component bas been initialize 
     */
    protected $initialized = false;

    /**
     * guest role name
     */

    const GUEST_ROLE = "guest";

    /**
     * registered role name
     */
    const REGISTERED_ROLE = "registered";

    /**
     * editor role name
     */
    const EDITOR_ROLE = "editor";

    /**
     * client role name
     */
    const CLIENT_ROLE = "client";

    /**
     * routers used in the application
     * @var array
     */
    protected $routers = array();

    /**
     * User modules array
     * @var array 
     */
    protected $modules = array();

    /**
     * Access Controllers array
     * @var array 
     */
    protected $accessControllers = array();

    /**
     * Current user access Controllers array
     * @var array 
     */
    protected $userAccessControllers = array();

    /**
     * Forward actions array list
     * @var array 
     */
    protected $forwardActions = array();

    /**
     * Forward modules array list
     * @var array 
     */
    protected $forwardModules = array();

    /**
     * Users roles array
     * @var array 
     */
    protected $roles = array();

    /**
     * init access
     * @access private
     * @static
     * @return void     
     */
    public function init() {
        if (!$this->initialized) {
            $this->setRolesAccess();
            $this->setForwardActions();
            $this->setForwardModules();
            $this->setModuleAccessRules($this->modules);
            $this->setUserAccees();
        }
        parent::init();
        $this->initialized = $this->getIsInitialized();
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

            $accessControllersDataset = Yii::app()->db->createCommand(implode("\n union ", $rolesStatements) . " order by priority")->queryAll();
            foreach ($accessControllersDataset as $accessController) {
                $this->accessControllers[$role['id']][$accessController['controller_id']] = array('access' => $accessController['access'], 'role_id' => $accessController['role_id'], 'visible' => !$accessController['hidden']);
            }
        }
    }

    /**
     * set module access for the childs modules of the given $moduleParent if $moduleParent is null then we set access for the top levels
     * @param array $modules modules dataset to append child data set to it
     * @param array $module module child
     * @access private     
     * return void
     */
    protected function setModuleAccessRules(&$modules, $moduleParent = null) {
        if ($moduleParent) {
            $where = "m.parent_module  = " . (int) $moduleParent['id'];
        } else {
            $where = "m.parent_module  is null ";
        }
        $query = "select 
                 m.parent_module,
                 m.module_id, 
                 m.module,    
                 m.system,
                 m.enabled ,
                 m.workflow_enabled,
                 m.virtual, 
                 c.hidden,  
                 c.controller, 
                 c.controller_id,                 
                 a.action, 
                 a.permissions, 
                 a.action_id,
                 a.is_system is_system_action
                from modules m 
                inner join controllers c on m.module_id = c.module_id
                inner join actions a on c.controller_id = a.controller_id
                
                where m.enabled = 1 and $where
                order by m.module_id, c.controller_id, a.action_id
         ";
        $modulesDataset = Yii::app()->db->createCommand($query)->queryAll();
        //echo "$query<hr />";
        foreach ($modulesDataset as $module) {
            if ($module['virtual']) {
                if (isset($moduleParent['name'])) {
                    $moduleLink = "/{$moduleParent['name']}/{$module['controller']}/index";
                    $route = "{$moduleParent['name']}/{$module['controller']}/{$module['action']}";
                } else {
                    $moduleLink = "/{$module['controller']}/index";
                    $route = "{$module['controller']}/{$module['action']}";
                }
                $moduleName = $module['controller'];
            } else {
                if (isset($moduleParent['name'])) {
                    $moduleLink = "/{$moduleParent['name']}/{$module['module']}/default/index";
                    $route = "{$moduleParent['name']}/{$module['module']}/{$module['controller']}/{$module['action']}";
                } else {
                    $route = "{$module['module']}/{$module['controller']}/{$module['action']}";
                    $moduleLink = "/{$module['module']}/default/index";
                }
                $moduleName = $module['module'];
            }

            $routeIndex = strtolower($route);
            $this->routers[$routeIndex] = array(
                'actionId' => $module['action_id'],
                'permissions' => $module['permissions'],
                'controllerId' => $module['controller_id'],
                'moduleId' => $module['module_id'],
                'forwardTo' => array(),
            );
            if (isset($this->forwardActions[$module['action_id']])) {
                $this->routers[$routeIndex]['forwardTo'] = $this->forwardActions[$module['action_id']];
            }
            $modules[$module['module']]['id'] = $module['module_id'];
            $modules[$module['module']]['workflowEnabled'] = $module['workflow_enabled'];
            $modules[$module['module']]['messageSystem'] = "amcwm.system.messages.system";
            if (is_dir(AmcWm::getPathOfAlias("application.system.messages.{$module['module']}"))) {
                $modules[$module['module']]['messageSystem'] = "application.system.messages.{$module['module']}.system";
            } else if (is_dir(AmcWm::getPathOfAlias("amcwm.system.messages.{$module['module']}"))) {
                $modules[$module['module']]['messageSystem'] = "amcwm.system.messages.{$module['module']}.system";
            }
            $modules[$module['module']]['name'] = $module['module'];
            $modules[$module['module']]['virtual'] = $module['virtual'];
            $modules[$module['module']]['system'] = $module['system'];
            $labelPrefix = "";
            if ($module['parent_module'] != 1 && $module['module_id'] != 1) {
                $labelPrefix = "_front";
            }
            $modules[$module['module']]['label'] = strtoupper("_{$module['module']}_module_");
            $modules[$module['module']]['url'] = array($moduleLink);
            $modules[$module['module']]['image_id'] = $moduleName;
            if (array_key_exists('visible', $modules[$module['module']])) {
                $modules[$module['module']]['visible'] = ($modules[$module['module']]['visible'] || !$module['hidden']);
            } else {
                $modules[$module['module']]['visible'] = !$module['hidden'];
            }

            $modules[$module['module']]['controlles'][$module['controller_id']]['id'] = $module['controller_id'];
            $modules[$module['module']]['controlles'][$module['controller_id']]['label'] = ($module['controller'] == "default") ? strtoupper("{$labelPrefix}_{$module['module']}_default_controller_") : strtoupper("{$labelPrefix}_{$module['controller']}_controller_");
            $modules[$module['module']]['controlles'][$module['controller_id']]['name'] = $module['controller'];
            $modules[$module['module']]['controlles'][$module['controller_id']]['visible'] = !$module['hidden'];
            $modules[$module['module']]['controlles'][$module['controller_id']]['actions'][$module['action_id']]['id'] = $module['action_id'];
            $modules[$module['module']]['controlles'][$module['controller_id']]['actions'][$module['action_id']]['name'] = $module['action'];
            $modules[$module['module']]['controlles'][$module['controller_id']]['actions'][$module['action_id']]['permissions'] = $module['permissions'];
            $modules[$module['module']]['controlles'][$module['controller_id']]['actions'][$module['action_id']]['is_system'] = $module['is_system_action'];
            $modules[$module['module']]['controlles'][$module['controller_id']]['actions'][$module['action_id']]['label'] = strtoupper("_{$module['action']}_action_");
            if (!isset($modules[$module['module']]['modules'])) {
                $modules[$module['module']]['modules'] = array();
                $this->setModuleAccessRules($modules[$module['module']]['modules'], $modules[$module['module']]);
            }
        }
    }

    /**
     * Get parent route path for the given $moduleId
     * @param integer $moduleId
     * @access private
     * @return string
     */
    private function _getParentRoutePath($moduleId) {
        $parentPath = null;
        $query = sprintf(
                "select 
                 m.parent_module,
                 m.module_id, 
                 m.module
                from  modules m
                where m.module_id = %d
                ", $moduleId);
        $parent = Yii::app()->db->createCommand($query)->queryRow();
        if ($parent) {
            $parentPath .= $parent['module'] . "/";
            if ($parent['parent_module']) {
                $parentPath .= $this->_getParentRoutePath($parent['parent_module']);
            }
        }
        return $parentPath;
    }

    /**
     * @todo add virtual module to forward list
     * Sets the forward Modules array
     * @access private
     * @return void
     */
    protected function setForwardModules() {
        //(select controller from controllers c where cf.module_id = mf.module_id and mf.virtual =1 and cf.controller = 'index') controller_from, 
        //(select controller from controllers c where c.module_id = m.module_id and m.virtual =1 and c.controller = 'index') controller_to 

        $query = "select 
                 f.forward_from,
                 f.forward_to,                 
                 m.parent_module to_parent_module,
                 m.module_id,
                 m.module module_to,
                 mf.parent_module from_parent_module,
                 mf.module module_from      
                from forward_modules f                 
                inner join modules m on f.forward_to = m.module_id
                inner join modules mf on f.forward_from = mf.module_id               
                ";

        $forwardsTo = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($forwardsTo as $forwardTo) {
            if ($forwardTo) {
                $parentPath = $this->_getParentRoutePath($forwardTo['from_parent_module']);
                $moduleFrom = $parentPath . $forwardTo['module_from'];
                $parentPath = $this->_getParentRoutePath($forwardTo['to_parent_module']);
                $moduleTo = $parentPath . $forwardTo['module_to'];
                $this->forwardModules[$forwardTo['forward_from']] = array(
                    $moduleFrom => $moduleTo,
                );
            }
        }
    }

    /**
     * Sets the forward actions array
     * @access private
     * @return void
     */
    protected function setForwardActions() {
        $query = "select 
                 f.forward_from,
                 a.action, 
                 a.permissions, 
                 a.action_id,
                 a.action,
                 c.hidden,  
                 c.controller, 
                 c.controller_id,                 
                 m.parent_module,
                 m.module_id, 
                 m.module,               
                 m.enabled ,
                 m.virtual                 
                from forward_actions f 
                inner join actions a on f.forward_to = a.action_id
                inner join controllers c on a.controller_id = c.controller_id                
                inner join modules m on c.module_id = m.module_id
                where m.enabled = 1
                ";
        $forwardsTo = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($forwardsTo as $forwardTo) {
            if ($forwardTo) {
                if ($forwardTo['virtual']) {
                    $this->forwardActions[$forwardTo['forward_from']] = array(
                        'route' => $this->_getParentRoutePath($forwardTo['parent_module']) . "{$forwardTo['controller']}/{$forwardTo['action']}",
                        'permissions' => $forwardTo['permissions'],
                        'controllerId' => $forwardTo['controller_id'],
                        'actionId' => $forwardTo['action_id'],
                    );
                } else {
                    $this->forwardActions[$forwardTo['forward_from']] = array(
                        'route' => $this->_getParentRoutePath($forwardTo['parent_module']) . "{$forwardTo['module']}/{$forwardTo['controller']}/{$forwardTo['action']}",
                        'permissions' => $forwardTo['permissions'],
                        'controllerId' => $forwardTo['controller_id'],
                        'actionId' => $forwardTo['action_id'],
                    );
                }
            }
        }
    }

    /**
     * Return an array with the names of all variables of that acl instance that should be serialized
     * @return array
     * @access public
     */
    public function __sleep() {
        return array("initialized", "accessControllers", "forwardActions", "routers", "modules", "roles", "forwardModules",);
    }

    /**
     * check route access against role id
     * @param integer $roleId role id to check route against
     * @param string $route route to check     
     * @return boolean
     * @access public
     */
    public function checkRouteAccess($roleId, $route) {
        $route = trim($route, "/");
        if (!$roleId) {
            $roleId = $this->getRoleId();
        }
        $parentModule = AmcWm::app()->getModuleRootName($route);
        $routeIndex = strtolower($route);
        $checkAccess = false;
        if (array_key_exists($routeIndex, $this->routers)) {
            $checkAccess = true;
        } else if ($parentModule == AmcWm::app()->backendName) {
            $checkAccess = true;
            $routeIndex = strtolower(AmcWm::app()->backendName . "/default/index");
        }
        $access = true;
        if ($checkAccess) {
            $access = $this->routeAccess($roleId, $routeIndex);
        }
        return $access;
    }

    /**
     * Get route access
     * @param integer $roleId role id to check route against
     * @param string $route route to check     
     * @return boolean
     */
    protected function routeAccess($roleId, $route) {
        $access = false;
        $routeAccess = $this->routers[$route];
        if (isset($this->accessControllers[$roleId]) && isset($this->accessControllers[$roleId][$routeAccess['controllerId']])) {
            $perm = new Permissions($this->accessControllers[$roleId][$routeAccess['controllerId']]['access']);
            $access = $perm->checkPermission($routeAccess['permissions']);
            if (isset($routeAccess["forwardTo"]['controllerId']['permissions']) && isset($this->accessControllers[$roleId][$routeAccess["forwardTo"]['controllerId']])) {
                $perm = new Permissions($this->accessControllers[$roleId][$routeAccess["forwardTo"]['controllerId']]['access']);                
                $access = $perm->checkPermission($routeAccess["forwardTo"]['controllerId']['permissions']);
            }
        }
        if (isset($this->userAccessControllers[$routeAccess['controllerId']])) {
            $perm = new Permissions($this->userAccessControllers[$routeAccess['controllerId']]['access']);
            $access = $perm->checkPermission($routeAccess['permissions']);
            if (isset($routeAccess["forwardTo"]['controllerId']['permissions']) && isset($this->userAccessControllers[$routeAccess["forwardTo"]['controllerId']])) {
                $perm = new Permissions($this->userAccessControllers[$routeAccess["forwardTo"]['controllerId']]['access']);
                $access = $perm->checkPermission($routeAccess["forwardTo"]['controllerId']['permissions']);
            }
        }
        return $access;
    }

    /**
     * Sets current user access list
     * @access private
     * @return void
     */
    protected function setUserAccees() {
        $userId = Yii::app()->user->getId();
        if (!count($this->userAccessControllers) && !Yii::app()->user->isGuest && $userId) {
            $this->userAccessControllers = $this->getUserAccees($userId);
        }
    }

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
        foreach ($accessControllersDataset as $accessController) {
            $userAccessControllers[$accessController['controller_id']] = array('access' => $accessController['access'], 'role_id' => $accessController['role_id'], 'visible' => !$accessController['hidden']);
        }
        return $userAccessControllers;
    }
    
    /**
     * get role access for the given $roleId;
     * @param integer $roleId
     * @access private
     * @return array
     */
    public function getRoleAccess($roleId) {
        $roleAccessControllers = array();
        $roleStatement = sprintf(
                "select c.controller_id, c.controller, a.role_id, a.access, c.hidden
                from controllers c 
                inner join access_rights a on c.controller_id = a.controller_id 
                where a.role_id = %d", $roleId
        );
        $accessControllersDataset = Yii::app($roleStatement)->db->createCommand($roleStatement)->queryAll();
        foreach ($accessControllersDataset as $accessController) {
            $roleAccessControllers[$accessController['controller_id']] = array('access' => $accessController['access'], 'role_id' => $accessController['role_id'], 'visible' => !$accessController['hidden']);
        }
        return $roleAccessControllers;
    }

    /**
     * get route action,
     * @param string $route route to get its action
     * @static
     * @return string
     * @access public
     */
    static public function getRouteAction($route) {
        $routeParts = explode("/", $route);
        $currentAction = $routeParts[count($routeParts) - 1];
        return $currentAction;
    }

    /**
     * Reconstruct any resources that the acl instance may have after unserialize it.
     * @access public
     * @return void
     */
    public function __wakeup() {
        $this->setUserAccees();
    }

    /**
     * Get modules used in the system
     * @access public
     * @return array
     */
    public function getModules() {
        return $this->modules;
    }

    /**
     * Get module structure for the given $moduleName
     * @param string $moduleName
     * @access public
     * @return array
     */
    public function getModule($moduleName) {
        $module = array();
        if (isset($this->modules[$moduleName])) {
            $module = $this->modules[$moduleName];
        }
        return $module;
    }

    /**
     * Get forwards modules used in the system
     * @access public
     * @return array
     */
    public function getForwardModules() {
        return $this->forwardModules;
    }

    /**
     * Get routes used in the system
     * @access public
     * @return array
     */
    public function getRoutes() {
        return $this->routers;
    }

    /**
     * Get routes info for the given $route
     * @param string $rout
     * @access public
     * @return array
     */
    public function getRouteInfo($route) {
        $info = array();
        $route = strtolower($route);
        if (isset($this->routers[$route])) {
            $info = $this->routers[$route];
        }
        return $info;
    }

    /**
     * set users roles tree
     * @access private     
     * return void
     */
    protected function setRoles() {
        $query = "select role_id, parent_role_id,role from roles";
        $rolesData = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($rolesData as $role) {
            if ($role['parent_role_id']) {
                $tree = array($role['role_id'], (int) $role['parent_role_id']);
            } else {
                $tree = array($role['role_id']);
            }
            $this->roles[$role['role']] = array("id" => $role['role_id'], "role" => $role["role"], 'tree' => $tree);
            $this->_setRolesTree($this->roles[$role['role']], $role['parent_role_id']);
            $this->roles[$role['role']]['tree'] = array_reverse($this->roles[$role['role']]['tree']);
        }
    }

    /**
     * set roles tree for the given $role and $roleId
     * @access private     
     * return void
     */
    private function _setRolesTree(&$role, $roleId) {
        $query = "select role_id, parent_role_id,role from roles where role_id = " . (int) $roleId;
        $childsData = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($childsData as $child) {
            if ($child["parent_role_id"]) {
                $role['tree'][] = $child["parent_role_id"];
                $this->_setRolesTree($role, $child["parent_role_id"]);
            }
        }
    }

    /**
     * Get roles array
     * @return array 
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Get roles list names
     * @return array 
     */
    public function getRolesList() {
        return array_keys($this->roles);
    }

    /**
     * get role id for the given $roleName
     * Default returned id is guest role id
     * @return int
     * @access public 
     */
    public function getRoleId($roleName = self::GUEST_ROLE) {
        $roleId = 0;
        if (isset($this->roles[$roleName])) {
            $roleId = $this->roles[$roleName]['id'];
        }
        return $roleId;
    }

    /**
     * Acl._accessControllers getter method
     * @param integer $roleId, if not equal 0 then return all access controllers from the acl otherwise return the access controllers of the given $roleId only
     * @access public
     * @return array      
     */
    public function getAccessControllers($roleId = 0) {
        $accessControllers = array();
        if ($roleId) {
            if (isset($this->accessControllers[$roleId])) {
                $accessControllers = $this->accessControllers[$roleId];
            }
        } else {
            $accessControllers = $this->accessControllers;
        }
        return $accessControllers;
    }

    /**
     * Acl._userAccessControllers getter method
     * @access public
     * @return array      
     */
    public function getUserAccessControllers() {
        return $this->userAccessControllers;
    }

    /**
     * Acl.forwardActions getter method
     * @access public
     * @return array      
     */
    public function getForwardActions() {
        return $this->forwardActions;
    }

}
