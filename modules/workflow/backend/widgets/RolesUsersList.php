<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * RolesUsersList extension draw a list of roles with its users
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RolesUsersList extends Widget {

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
     * @var array of data to display it
     */
    public $items = array();

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        $this->_dataTree = array(array('text' => '', 'children' => array()));
        $this->_setTree($this->items, $this->_dataTree);
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function run() {
        $js = "
            function check_subs(o, ids) {
                var ar = ids.split(',');
                var id;
                var status = ($(o).attr('checked') == 'checked')?true:false;
                for(var i=0; i < ar.length; i++) {
                    id = ar[i];
                    $('#users_'+id).attr('checked', status);
                }
            }
        ";
        Yii::app()->clientScript->registerScript('jsCodes', $js, CClientScript::POS_HEAD);


        $this->widget('amcwm.core.widgets.treeview.TreeView', array(
            'data' => $this->_dataTree,
            'dir' => Yii::app()->getLocale()->getOrientation(),
            'animated' => 'fast', //quick animation
            'collapsed' => false,
        ));
    }

    /**
     * sets the data tree
     * @param array $items
     * @param array $dataTree
     */
    private function _setTree($items, &$dataTree) {
        $i = 0;
        foreach ($items as $item) {
            $usersIds = array();
            if (count($item['usersList'])) {
                foreach ($item['usersList'] as $user) {
                    $permissionField = "Users[{$item['id']}][]";
                    $checked = $user['selected'];
                    $htmlOptions = array();
                    $userChkId = 'users_' . $user['user_id'];
                    $usersIds[] = $user['user_id'];
                    $htmlOptions['value'] = $user['user_id'];
                    $htmlOptions['id'] = $userChkId;

                    $childrenText = Chtml::checkBox($permissionField, $checked, $htmlOptions);
                    $childrenText .= Chtml::label($user['name'], $userChkId, array("class" => 'normal_label'));

                    $dataTree[$i]['children'][] = array('text' => $childrenText);
                }
            }

            $htmlOptions = array();
            $htmlOptions['value'] = $item['id'];
            $htmlOptions['id'] = 'roles_' . $item['id'];
            $htmlOptions['onchange'] = "check_subs(this,'" . implode(',', $usersIds) . "')";

            $roleText = Chtml::checkBox("Roles[]", $item['selected'], $htmlOptions);
            $roleText .= Chtml::label('<span class="header">' . $item['title'] . '</span>', $htmlOptions['id'], array("class" => 'normal_label'));
            $dataTree[$i]['text'] = $roleText;

            $i++;
        }
    }

}

?>
