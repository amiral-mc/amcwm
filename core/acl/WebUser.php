<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * User class.
 * @package Acl
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WebUser extends CWebUser {

    // default return URL property
    public $defaultReturnUrl;
    // default login after 
    public $defaultLoggedRoute = "/site/index";

    /**
     * $allowLoginView weather to allow the website to view the login as a page or as a widget
     * true to be viewed as a page, false to be viewed as a widget
     * @var boolean 
     */
    public $allowLoginView = true;

    /**
     * constructor
     * @access public
     */
    public function __construct() {
        $this->loginUrl = array("/" . AmcWm::app()->defaultController . '/login', 'lang' => Controller::getCurrentLanguage());
    }

    /**
     * Returns the URL that the user should be redirected to after successful login.
     * This property is usually used by the login action. If the login is successful,
     * the action should read this property and use it to redirect the user browser.
     * @param string $defaultUrl the default return URL in case it was not set previously. If this is null,
     * the application entry URL will be considered as the default return URL.
     * @return string the URL that the user should be redirected to after login.
     * @see loginRequired
     */
    public function getReturnUrl($defaultUrl = null) {
        if ($defaultUrl === null) {
            if (AmcWm::app()->getIsBackend()) {
                $defaultUrl = array("/" . AmcWm::app()->backendName . "/default/index");
            } else {
                if ($this->isGuest) {
                    $defaultUrl = array("/" . AmcWm::app()->defaultController . "/index");
                } else {
                    $defaultUrl = array($this->defaultLoggedRoute);
                }
            }
        }
        return parent::getReturnUrl($defaultUrl);
    }

    /**
     * login to system using the given $username and $password
     * @param string $username
     * @param string $password
     * @return int
     */
    public function authenticate($username, $password) {
        if ($username && $password) {
            $statement = sprintf("
                    select u.published, u.role_id, u.user_id user_id, u.username, u.passwd, p.email
                    from users u
                    inner join persons p on u.user_id = p.person_id
                    where u.username =%s
                ", Yii::app()->db->quoteValue($username));            
            $record = Yii::app()->db->createCommand($statement)->queryRow();
            $errorCode = UserIdentity::ERROR_USERNAME_INVALID;
            if (is_array($record) && Data::getInstance()->verifyPassword($password, $record['passwd'], $username)) {
                 if ($record['published']) {
                    $this->setId($record['user_id']);
                    $log = new LogManager();
                    $lastLogIp = $log->getLastIP();
                    if (!$lastLogIp) {
                        $lastLogIp = $log->getUserIP();
                    }
                    $this->setState('lastLogIp', $lastLogIp);
                    $log->log();

                    $errorCode = UserIdentity::ERROR_NONE;
                } else {
                    $errorCode = UserIdentity::ERROR_ACCOUNT_IS_INACTIVE;
                }                             
            }
        }
        return $errorCode;
    }

    /**
     * set user access rules,
     * @return void
     * @access public
     */
    public function setAccessRules() {
        $userId = ($this->isGuest) ? null : $this->getId();
        $this->user->setAccessRules($userId);
    }

    /**
     * Get last log IP
     * @access public
     * @return string
     */
    public function getLastLogIp() {
        return $this->getState("lastLogIp");
    }

    /**
     * check route access,
     * @param string $route route to check
     * @return boolean
     * @access public
     */
    public function checkRouteAccess($route) {
        $access = amcwm::app()->acl->checkRouteAccess($this->getRole(), $route);
        return $access;
    }

    /**
     * Get sub modules list for the givn $moduleName
     * @access public
     * @return array
     */
    public function getSubModulesList($moduleName) {
        $modules = amcwm::app()->acl->getModules();
        $forwardModules = amcwm::app()->acl->getForwardModules();
        static $subModulesList = array();
        static $lang = null;
        if (!count($subModulesList) && $lang != Controller::getCurrentLanguage()) {
            $lang = Controller::getCurrentLanguage();
            if (isset($modules[$moduleName]['modules'])) {
                $subModules = $modules[$moduleName]['modules'];
                foreach ($subModules as $key => $module) {
                    if (amcwm::app()->acl->checkRouteAccess($this->getRole(), $module['url'][0])) {
                        $subModulesList[$key]['id'] = $module['id'];
                        $subModulesList[$key]['name'] = $module['id'];
                        $subModulesList[$key]['virtual'] = $module['virtual'];
                        $subModulesList[$key]['label'] = AmcWm::t("{$module['messageSystem']}", $module['label']);
                        //$subModulesList[$key]['label'] = $module['messageSystem']; //AmcWm::t("{$module['messageSystem']}", $module['label']);
                        $subModulesList[$key]['label'] = AmcWm::t("{$module['messageSystem']}", $module['label']);
                        if (isset($forwardModules[$module['id']])) {
                            $forwardFrom = key($forwardModules[$module['id']]);
                            $forwardTo = $forwardModules[$module['id']][$forwardFrom];
                            $module['url'][0] = str_replace($forwardFrom, $forwardTo, $module['url'][0]);
                            $module['url']['module'] = $module['id'];
                        }
                        $subModulesList[$key]['url'] = $module['url'];
                        $subModulesList[$key]['image_id'] = $module['image_id'];
                        $subModulesList[$key]['visible'] = $module['visible'];
                    }
                }
            }
        }
        return $subModulesList;
    }

    /**
     * check route access,
     * @param string $route route to get its rules
     * @return array
     * @access public
     */
    public function getRouteRules($route) {
        $currentAction = Acl::getRouteAction($route);
        $access = $this->checkRouteAccess($route);
        $rules = array();
        if ($access) {
            $allow[0] = 'allow';
            $allow['actions'][0] = $currentAction;
            $allow['users'] = array('*'); //($this->isGuest) ? array('*') : array('@');
            array_push($rules, $allow);
        } else {
            array_push($rules, array('deny', 'users' => array('*')));
        }
        return $rules;
    }

    /**
     * Get current user role
     * @access public
     * @return int
     */
    public function getRole() {
        $role = null;
        $user = $this->getInfo();
        if (is_array($user) && isset($user['role_id'])) {
            $role = $user['role_id'];
        }
        return $role;
    }

    /**
     * get user information
     * @return array
     * @access public
     */
    public function getInfo() {
        $info = array();
        if ($this->getId()) {
            $statement = sprintf("
               select u.published, u.role_id, u.user_id user_id, u.username, p.email
                from users u
                inner join persons p on u.user_id = p.person_id
                where u.user_id = %d and published = %d
            ", $this->getId(), ActiveRecord::PUBLISHED);
            $info = Yii::app()->db->createCommand($statement)->queryRow();
            if (!$info) {
                $this->logout();
            }
        }
        return $info;
    }

    /**
     * get new messages count for the current user
     * @todo need to implement messages
     * @return int
     * @access public
     */
    public function getMessagesCount() {
        return 0;
    }

    /**
     * Get denied users
     * @access public
     * @return array
     */
    public function getDeniedUsers() {
        return array("root", "manager", "admin", "administrator", "admins", "info", "contact", "sales");
    }

    /**
     * Get User Language
     * @access public
     * @return array
     */
    public function getCurrentLanguage() {
        return Controller::getCurrentLanguage();
        //return Yii::app()->getLanguage();
    }

    /**
     * This method is invoked right after a user is logged out.
     * You may override this method to do some extra cleanup work for the user.
     * @since 1.1.3
     */
    protected function afterLogout() {
        AmcWm::app()->session->regenerateID(true);
    }

    /**
     * This method is called after the user is successfully logged in.
     * You may override this method to do some post-processing (e.g. log the user
     * login IP and time; load the user permission information).
     * @param boolean $fromCookie whether the login is based on cookie.
     * @since 1.1.3
     */
    protected function afterLogin($fromCookie) {
        AmcWm::app()->session->regenerateID();
    }

}
