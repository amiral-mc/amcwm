<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Install Manager
 * @package  AmcWm.core.controllers
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DefaultController extends BackendController {

    /**
     * Discover and install the given module $name          
     * @param string $name
     * @param array $config
     * @param boolean $remove
     * @return string
     */
    private function _processInstallModule($name, $config, $remove) {
        if ($remove) {
            $query = sprintf("select count(*) "
                    . "from modules m "
                    . "inner join modules_components c on m.module_id = c.module_id  "
                    . "where module = %s ", AmcWm::app()->db->quoteValue($name));

            $cannotRemove = AmcWm::app()->db->createCommand($query)->queryScalar();
            if($cannotRemove){
                return AmcWm::t("msgsbase.core", 'Cannot remove module "{name}"', array("{name}" => $name));
            }            
            $msg = AmcWm::t("msgsbase.core", 'Module "{name}" has been removed successfully', array("{name}" => $name));
        } else {
            $msg = AmcWm::t("msgsbase.core", 'Module "{name}" has been installed successfully', array("{name}" => $name));
        }


        $transaction = AmcWm::app()->db->beginTransaction();
        try {
            if (isset($config['backend']['install'])) {
                $moduleId = $this->_installModule($name, $config['backend'], true, $remove);
                $this->_processInstallVirtaulModules($moduleId, $config['backend'], true, $remove);
            }
            if (isset($config['frontend']['install'])) {
                $moduleId = $this->_installModule($name, $config['frontend'], false, $remove);
                $this->_processInstallVirtaulModules($moduleId, $config['frontend'], false, $remove);
            }
            AmcWm::app()->clearGlobalState("acl");
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            $msg = AmcWm::t("msgsbase.core", 'Cannot install module "{name}"', array("{name}" => $name));
            $msg .= "<br />" . $e->getMessage();
        }

        return $msg;
    }

    /**
     * Discover and install the given module $name          
     * @param string $name
     * @param array $config
     * @param boolean $isBackend
     * @return string
     */
    private function _processInstallVirtaulModules($forwardTo, $config, $isBackend, $remove) {
        if (isset($config['virtual'])) {
            $query = "delete from forward_modules where forward_to = {$forwardTo}";
            AmcWm::app()->db->createCommand($query)->execute();

            foreach ($config['virtual'] as $virtaul) {

                $forwardFrom = $this->_installModule($virtaul['module'], $config, $isBackend, $remove, true);
//                echo  "{$isBackend}:{$virtaul['module']}<hr>";
                if (!$remove) {
                    $query = "insert into forward_modules(forward_from, forward_to) values ($forwardFrom, $forwardTo);";
//                    echo "{$virtaul['module']}:$query<hr />";                   
                }
                AmcWm::app()->db->createCommand($query)->execute();
            }
        }
    }

    /**
     * Discover and install the given module $name          
     * @param string $name
     * @param array $allConfig
     * @param boolean $isBackend
     * @return integer
     */
    private function _installModule($name, $allConfig, $isBackend, $remove, $virtaul = false) {
        $config = $allConfig['install'];
        if ($isBackend) {
            $query = sprintf(" select module_id from modules where module = %s ", AmcWm::app()->db->quoteValue(AmcWm::app()->backendName));
            $backendId = AmcWm::app()->db->createCommand($query)->queryScalar();
            $where = " and parent_module = {$backendId}";
        } else {
            $where = sprintf(" and parent_module is null ");
            $backendId = 'NULL';
        }
        $query = sprintf("select
            m.module, m.module_id 
            from modules m 
            where m.module = %s {$where} ", AmcWm::app()->db->quoteValue($name));
        $moduleData = AmcWm::app()->db->createCommand($query)->queryRow();
        $moduleId = 0;
        //echo $name . "<hr>";
        if ($moduleData) {
            $moduleId = $moduleData['module_id'];
            $query = "select * from controllers where module_id = " . $moduleId;
            $controllersData = AmcWm::app()->db->createCommand($query)->queryAll();
            $installedControllers = array();
//            foreach ($controllersData as $controller) {
//                $installedControllers[$controller['controller']]['id'] = $controller['controller_id'];
//                $installedControllers[$controller['controller']]['actions'] = array();
//            }
            if (!isset($allConfig['virtual'])) {
                $query = "select forward_from from forward_modules where forward_to = " . $moduleId;
                $forwardModules = AmcWm::app()->db->createCommand($query)->queryAll();
                foreach ($forwardModules as $forwardModule) {
                    $query = "delete from modules where module_id = " . $forwardModule['forward_from'];
                    AmcWm::app()->db->createCommand($query)->execute();
                }
            }
        }
        if ($remove) {
            $installedControllers = array();
            $moduleData = null;
            $moduleId = 0;
            try {
                $query = sprintf("delete from modules where module = %s {$where} ", AmcWm::app()->db->quoteValue($name));
                AmcWm::app()->db->createCommand($query)->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            if (!$moduleId) {
                if ($virtaul) {
                    $enabled = isset($allConfig['virtual'][$name]['enabled']) ? $allConfig['virtual'][$name]['enabled'] : 1;
                    $system = isset($allConfig['virtual'][$name]['system']) ? $allConfig['virtual'][$name]['system'] : 0;
                } else {
                    $enabled = 1;
                    $system = isset($config['options']['system']) ? $config['options']['system'] : 0;
                }
                $query = sprintf(
                        "insert into modules(`parent_module`, `module`, `virtual`, enabled, `system`, workflow_enabled) 
                    values (%s, %s , 0, %d, %d, %d);"
                        , $backendId
                        , AmcWm::app()->db->quoteValue($name)
                        , $enabled
                        , $system
                        , isset($config['options']['workflow']) ? $config['options']['workflow'] : 0
                );
                AmcWm::app()->db->createCommand($query)->execute();
                $moduleId = AmcWm::app()->db->lastInsertID;
            }
            else{
                $ok = AmcWm::app()->db->createCommand("delete from controllers where module_id = $moduleId")->execute();
                //print_r($installedControllers);
                //echo $ok;
                //echo "delete from controllers where module_id = $moduleId\n";
                //die();
//                return ;
            }
            $forwardTo = array();
            foreach ($config['controllers'] as $controllerName => $controller) {
                if (isset($installedControllers[$controllerName]['id'])) {
                    $controllerId = $installedControllers[$controllerName]['id'];
                    $query = "select * from actions where controller_id = " . $controllerId;
                    $actionsData = AmcWm::app()->db->createCommand($query)->queryAll();
                    foreach ($actionsData as $action) {
                        $installedControllers[$controllerName]['actions'][$action['action']]['id'] = $action['action_id'];
                        $installedControllers[$controllerName]['actions'][$action['action']]['permissions'] = $action['permissions'];
                    }
                } else {
//                        action_id, controller_id, action, permissions
                    $query = sprintf(
                            "insert into controllers(module_id, controller, hidden) 
                                values (%d, %s, %d);"
                            , $moduleId
                            , AmcWm::app()->db->quoteValue($controllerName)
                            , isset($controller['options']['hidden']) && $controller['options']['hidden'] ? $controller['options']['hidden'] : 0
                    );
                    AmcWm::app()->db->createCommand($query)->execute();
                    $controllerId = AmcWm::app()->db->lastInsertID;
                    $installedControllers[$controllerName]['id'] = $controllerId;
                    $installedControllers[$controllerName]['actions'] = array();
                }
                foreach ($controller['actions'] as $actionName => $action) {
                    if (isset($installedControllers[$controllerName]['actions'][$actionName]['id'])) {
                        $installedControllers[$controllerName]['actions'][$actionName]['roles'] = $action['roles'];
                        $actionId = $installedControllers[$controllerName]['actions'][$actionName]['id'];
                    } else {
                        $query = sprintf(
                                "insert into actions(controller_id, action, permissions) 
                                values (%d, %s, %d);"
                                , $controllerId
                                , AmcWm::app()->db->quoteValue($actionName)
                                , $action['perm']
                        );
                        AmcWm::app()->db->createCommand($query)->execute();
                        $actionId = AmcWm::app()->db->lastInsertID;
                        $installedControllers[$controllerName]['actions'][$actionName]['id'] = $actionId;
                        $installedControllers[$controllerName]['actions'][$actionName]['permissions'] = $action['perm'];
                        $installedControllers[$controllerName]['actions'][$actionName]['roles'] = $action['roles'];
                    }
                    if (isset($action['roles4Virtual'][$name])) {
                        $action['roles'] = $action['roles4Virtual'][$name];
                    }
                    if (isset($action['forwardTo'])) {
                        $forwardTo[$actionId]['action'] = $actionName;
                        $forwardTo[$actionId]['forwardTo'] = $action['forwardTo'];
                        $forwardTo[$actionId]['forwardTo']['id'] = 0;
                    }
                    foreach ($action['roles'] as $role) {
                        $installedControllers[$controllerName]['permissions'][$role][$action['perm']] = $action['perm'];
                    }
                }
            }
            if (count($forwardTo)) {
                $deletedForwards = array_keys($forwardTo);
                $query = "delete from forward_actions where forward_from in (" . implode(",", $deletedForwards) . ")";
                AmcWm::app()->db->createCommand($query)->execute();
                foreach ($forwardTo as $forwardFrom => $forward) {
                    if (isset($installedControllers[$forward['forwardTo']['controller']]['actions'][$forward['forwardTo']['action']]['id'])) {
                        $forwardTo = $installedControllers[$forward['forwardTo']['controller']]['actions'][$forward['forwardTo']['action']]['id'];
                        $query = "insert into forward_actions(forward_from, forward_to) values ($forwardFrom, $forwardTo);";
                        AmcWm::app()->db->createCommand($query)->execute();
                        //echo $query . "\n";
                    }
                }
            }
            foreach ($installedControllers as $controllerName => $controller) {
                if (isset($controller['permissions'])) {
                    foreach ($controller['permissions'] as $role => $permissions) {
                        $roleId = amcwm::app()->acl->getRoleId($role);
                        $inRoleId = AmcWm::app()->db->createCommand("select role_id from access_rights where controller_id = {$controller['id']} and role_id = {$roleId}")->queryScalar();
                        $access = array_sum($permissions);
                        if ($inRoleId) {
                            $query = "update access_rights set access = {$access} where controller_id = {$controller['id']} and role_id = {$roleId}";
                        } else {
                            $query = "insert into access_rights(controller_id, role_id, access) values ({$controller['id']}, {$roleId}, {$access});";
                        }
                        AmcWm::app()->db->createCommand($query)->execute();
                    }
                }
            }
        }
        return $moduleId;
    }

    /**
     * Default action
     * @param string $name to install
     * @param boolean $remove
     */
    public function actionIndex($name, $remove = false) {
        if (file_exists(AmcWm::getPathOfAlias("application.modules.application.{$name}"))) {
            $isCustom = true;
        } else {
            $isCustom = false;
        }
        $settting = new Settings($name, true, $isCustom);
        $settingsData = $settting->getSettings();
        $msg = AmcWm::t("msgsbase.core", 'Cannot install module "{name}"', array("{name}" => $name));
        if (count($settingsData)) {
            $msg = $this->_processInstallModule($name, $settingsData, $remove);
        }
        $this->render("index", array('msg' => $msg, 'module' => $name));
    }

}
