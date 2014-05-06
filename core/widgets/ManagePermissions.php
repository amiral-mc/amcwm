<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ManagePermissions extension class, manage users permissions
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ManagePermissions extends CWidget {

    /**
     * @var array the data that can be used to generate the tree view content.
     * Each array element corresponds to a tree view node with the following structure:
     * <ul>
     * <li>text: string, required, the HTML text associated with this node.</li>
     * <li>expanded: boolean, optional, whether the tree view node is expanded.</li>
     * <li>id: string, optional, the ID identifying the node. This is used
     *   in dynamic loading of tree view (see {@link url}).</li>
     * <li>hasChildren: boolean, optional, defaults to false, whether clicking on this
     *   node should trigger dynamic loading of more tree view nodes from server.
     *   The {@link url} property must be set in order to make this effective.</li>
     * <li>children: array, optional, child nodes of this node.</li>
     * <li>htmlOptions: array, additional HTML attributes (see {@link CHtml::tag}).
     *   This option has been available since version 1.1.7.</li>
     * </ul>
     */
    private $_dataTree = array();

    /**
     * Access Controllers array
     * @var array 
     */
    private $_accessControllers = array();

    /**
     * controllers actions list
     * @var array 
     */
    private $_countrollersActions = array();

    /**
     * Forward to controllers list
     * @var array 
     */
    private $_forwardToControllers = array();

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * Current user active record 
     * @var Users
     */
    public $model = array();

    /**
     * Current user access Controllers array
     * @var array 
     */
    protected $customAccessControllers = array();

    /**
     * User modules array
     * @var array 
     */
    public $modules = array();

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        $this->_forwardToControllers = amcwm::app()->acl->getForwardActions();
        $this->_dataTree = array(array('text' => '', 'children' => array()));
        $this->htmlOptions['id'] = $this->getId();
        $this->_accessControllers = amcwm::app()->acl->getAccessControllers($this->model->role_id);
        $this->setCustomAccessControllers();
    }

    /**
     * Set custom access controllers list
     */
    protected function setCustomAccessControllers() {
        $this->customAccessControllers = amcwm::app()->acl->getUserAccees($this->model->user_id);
    }

    /**
     * Render the widget and display the result
     * Calls {@link runItem} to render each article row.
     * @access public
     * @return void
     */
    public function run() {
        $this->_setPermissions($this->modules, $this->_dataTree);
        $jsCode = "
            ManagePermissions = {};
                ManagePermissions.uncheckForwardActions = function(controllerId, checked){
                    $(\"input[name='permissions[\"+controllerId+\"][]']\").each(function (){
                        this.checked = checked;
                    });
                }
                ManagePermissions.checkForwardFrom = function (fromActionId,  toController,   checked){
                    if(!checked){
                        $(\"input[name='permissions[\"+toController+\"][]']\").each(function (){
                            checked = checked || this.checked;
                        });                        
                    }
                    $('#permissions_'+fromActionId).attr('checked',checked);
                }

        ";
        foreach ($this->_countrollersActions as $controller) {
            if (isset($controller['fromAction'])) {
                $jsCode .="
                    $(\"#permissions_{$controller['fromAction']}\").click(function() {
                        ManagePermissions.uncheckForwardActions({$controller['id']}, this.checked);
                    });
                    ";
                foreach ($controller['actions'] as $action) {
                    $permissionActionId = 'permissions_' . $action['id'];
                    $jsCode .="
                    $(\"#{$permissionActionId}\").click(function() {
                        ManagePermissions.checkForwardFrom({$controller['fromAction']}, {$controller['id']}, this.checked);
                    });
                    ";
                }
            }
        }
        Yii::app()->clientScript->registerScript('userPermissions', $jsCode);
        $this->widget('amcwm.core.widgets.treeview.TreeView', array(
            'data' => $this->_dataTree,
            'dir' => Yii::app()->getLocale()->getOrientation(),
            'animated' => 'fast', //quick animation
            'collapsed' => false,
        ));
    }

    /**
     * sets the permission tree
     * @staticvar array $countrollersActions
     * @staticvar array $forwardCountrollers
     * @param array $modules
     * @param array $dataTree
     */
    private function _setPermissions($modules, &$dataTree) {
        $currentLang = Yii::app()->getLanguage();
        $i = 0;
        $messageSystem = "amcwm.system.messages.system";
        foreach ($modules as $module) {
            if (!$module['system'] && ($module['visible'] || count($module['modules']))) {
                if (isset($module['messageSystem'])) {
                    $messageSystem = $module['messageSystem'];
                }
                $dataTree[$i]['text'] = '<span class="header">' . AmcWm::t($messageSystem, $module['label']) . ': <br /></span>';
                foreach ($module['controlles'] as $controller) {
                    $this->_countrollersActions[$controller['id']]['id'] = $controller['id'];
                    $this->_countrollersActions[$controller['id']]['actions'] = $controller['actions'];
                    if ($controller['visible']) {
                        $controllerPerm = null;
                        if (isset($this->_accessControllers[$controller['id']])) {
                            $access = isset($this->_accessControllers[$controller['id']]['access']) ? $this->_accessControllers[$controller['id']]['access'] : 0;
                            $controllerPerm = new Permissions($access);
                        }
                        if (isset($this->customAccessControllers[$controller['id']])) {
                            $access = isset($this->customAccessControllers[$controller['id']]['access']) ? $this->customAccessControllers[$controller['id']]['access'] : 0;
                            $controllerPerm = new Permissions($access);
                        }

                        $childrenText = AmcWm::t($messageSystem, $controller['label']) . "<br />";
                        $permissionField = "permissions[{$controller['id']}][]";
                        foreach ($controller['actions'] as $action) {
                            if (!$action['is_system']) {
                                $htmlOptions = array();
                                $checked = ($controllerPerm !== null) ? (bool) $controllerPerm->checkPermission($action['permissions']) : false;
                                $permissionActionId = 'permissions_' . $action['id'];
                                if ($action['permissions'] > 1 || $action['name'] == 'index') {
                                    if (isset($this->_forwardToControllers[$action['id']])) {
                                        $this->_countrollersActions[$this->_forwardToControllers[$action['id']]['controllerId']]['fromController'] = $controller['id'];
                                        $this->_countrollersActions[$this->_forwardToControllers[$action['id']]['controllerId']]['fromAction'] = $action['id'];
                                    }
                                    $htmlOptions['value'] = $action['permissions'];
                                    $htmlOptions['id'] = $permissionActionId;
                                    $childrenText .= Chtml::checkBox($permissionField, $checked, $htmlOptions);
                                    $childrenText .= Chtml::label(AmcWm::t($messageSystem, $action['label']), $permissionActionId, array("class" => 'normal_label'));
                                }
                            }
                        }

                        $dataTree[$i]['children'][] = array('text' => $childrenText);
                    }
                    if (isset($module['modules'])) {
                        $this->_setPermissions($module['modules'], $dataTree[$i]['children']);
                    }
                }
            }
            $i++;
        }
    }

}