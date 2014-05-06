<?php
AmcWm::import("amcwm.core.widgets.ManagePermissions");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ManageRolePermissions extension class, manage role permissions
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ManageRolePermissions extends ManagePermissions {    
    
    /**
     * Set custom access controllers list
     */
    protected function setCustomAccessControllers() {
        $this->customAccessControllers = amcwm::app()->acl->getRoleAccess($this->model->role_id);
    }
}