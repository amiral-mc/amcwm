<?php
$baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Workflow Steps") => array('/backend/workflow/steps/index', 'mid' => $this->module->module_id),
    AmcWm::t("msgsbase.core", "Steps Assign"),
);

$this->sectionName = AmcWm::t("msgsbase.core", "Assign Workflow Step");

$tools = array();
$tools[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId, 'params' => array('mid' => $this->module->module_id)), 'id' => 'add_flow', 'image_id' => 'save');
$tools[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/workflow/steps/index', 'mid' => $this->module->module_id), 'id' => 'polls_list', 'image_id' => 'back');

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $tools,
    'htmlOptions' => array('style' => 'padding:5px;')
));

$this->widget('amcwm.widgets.jsHash.JsHash');
?>

<style type="text/css">
    .bw_wrapper{
        height:200px; 
        position: relative; 
        overflow-y: auto; 
        overflow-x: hidden;
        border: 1px solid #BBDBE8;
        background: #FFF url("<?php echo $baseScript; ?>/images/txtbox_bg.jpg") repeat-x;
        text-align: right;
    }

    .bt_selector{
        width: 100%;
        height: 18px;
        border-bottom: 1px solid #BBDBE8;
        padding: 2px;
        font-size: 13px;
    }

    .bt_selector:hover{
        background: #BBDBE8;
        cursor: pointer;
    }

    .lbl_counter{
        float: left;
        /*width: 10px;*/
        height: 12px;
        text-align: center;
        margin:1px 5px;
        font-size: 10px;
        background-color: #D8DFEA;
        border-radius: 2px 2px 2px 2px;
        color: #3B5998;
        font-weight: bold;
        padding: 2px 4px;
        position: relative;
    }

    .lbl_slctd{
        float: left;
    }

    .lbl_users{
        width: 100%;
        height: 18px;
        border-bottom: 1px solid #BBDBE8;
        padding: 2px;
        font-size: 13px;
    }

    #stepsSelector{
        /*width: 100%;*/
    }
    /*    #stepsSelector li{
            border: 1px solid #0088cc;
            width: 195px;
            height: 15px;
            padding: 5px;
            margin: 1px;
        }*/
    #Roles{
        width: 100%;
    }
    #UsersList, #Users_selected{
        width: 100%;
        height: 100px;
    }
    .scroll_checkboxes {
        height: 100px;
        padding: 5px;
        overflow: auto;
        border: 1px solid #ccc
    }
    .moverLinks{
        text-decoration: none;
        color: #000;
        font-size: 1.1em;
    }
    #tabs {width: 95%; padding: 15px; margin-top: 20px;}
    .ui-tabs-vertical { width: 55em;}
    .ui-tabs-vertical .ui-tabs-nav { padding: .2em .2em .2em .1em; float: right }
    .ui-tabs-vertical .ui-tabs-nav li { clear: right; width: 100%; border-bottom-width: 1px !important; border-left-width: 0 !important; margin: 0 0 .2em -1px; direction: rtl; }
    .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
    .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-left: .1em; border-right-width: 1px; border-right-width: 1px; }
    .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: left; width: 100%;}
</style>
<script type="text/javascript">
    var items = [];
    
    function searchMethod() {
        var searchTerm = document.getElementById("searchQry");
        var searchBounds = document.getElementById("UsersList");
        for(var i = 0; i < searchBounds.length; i++){
            var searchedText = searchBounds[i].text;
            var IsMatch = searchedText.toLowerCase().search(searchTerm.value.toLowerCase());
            if(IsMatch != -1 && searchTerm.value !=''){
                searchBounds[i].selected = true;
            }else{
                searchBounds[i].selected = false;
            }
        }
    }

    function moveToStep(stepId){
        $('#nextStep').val(stepId);
        $('#moveTo').val('next');
        $('#<?php echo $formId; ?>').submit();
    }
    
    function showRoleUsers(stepId, roleId){
        var myval = stepId +''+ roleId;
        if(typeof items[myval] === 'undefined'){
            items[myval] = new Hash();
        }
        
        $('.users_list'+ stepId).hide();
        $('.lbl_slctd'+ stepId).hide();
        $('#users_list_' + stepId + '_' + roleId).show();
        $('#roles_slctd_' + stepId + '_' + roleId).show();
        
        $('.ckdUsrs_' + stepId + '_' + roleId).each(function(){
            if(this.checked)
                items[myval].setItem(this.value, this.value);
        });
    }
    
    function selectUser(stepId, roleId, userId, ckbx){
        var myval = stepId +''+ roleId;
        if(ckbx.checked){
            items[myval].setItem(userId, userId);
        }else{
            items[myval].removeItem(userId);
        }
        $('#roles_counter_' + stepId + '_' + roleId).html(items[myval].length);
    }
    
    $(function() {
        $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
        $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
//        $( "#tabs li.tbs").click(function(){
//            if(!confirm('Are you sure you want to move to the next step?')){
//                return false;
//            }
//        });
    });
</script>

<div id="tabs" class="form">
    <table width="100%">
        <tr>
            <td valign="top" style=" width:200px;">
                <ul id="stepsSelector">
                    <?php
                    foreach ($steps as $step) {
                        echo "<li><a href='#tabs-{$step['step_id']}'>{$step['step_title']}</a></li>";
                    }
                    ?>
                </ul>
            </td>
            <td width="20">
                
            </td>
            <td valign="top">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => $formId,
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                        ));

                foreach ($steps as $step) {
                    $rolesData = $dataset[$step['step_id']]->getItems();
                    ?>
                    <div id="<?php echo "tabs-{$step['step_id']}"; ?>">
                        <table width="100%">
                            <tr>
                                <td colspan="3" align="center">
                                    <b><?php echo AmcWm::t('msgsbase.core', 'Assign users to the current step') . ' <u>(' . $step['step_title'] . ')</u>'; ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%"><?php echo AmcWm::t('msgsbase.core', 'Select Role'); ?></td>
                                <td width="40%"><?php echo AmcWm::t('msgsbase.core', 'Select Users'); ?></td>
                            </tr>
                            <tr>
                                <?php
                                $usersContainer = "";
                                echo '<td valign="top">';
                                echo '  <div class="bw_wrapper">';
                                $first = true;
                                foreach ($rolesData as $role) {
                                    $usersSelected = 0;
                                    $usersContainer .= '<div class="bw_wrapper users_list' . $step['step_id'] .'" id="users_list_' . $step['step_id'] . '_' . $role['id'] . '" style="display:' . ($first ? 'block' : 'none') . '">';
                                    if (count($role['usersList'])) {
                                        foreach ($role['usersList'] as $user) {
                                            $chkd = '';
                                            if ($role['selected']) {
                                                $chkd = 'checked=checked';
                                                $usersSelected++;
                                            }

                                            if ($user['selected']) {
                                                $usersSelected++;
                                                $chkd = 'checked=checked';
                                            }
                                            $usersContainer .= '<label class="lbl_users" for="users_' . $step['step_id'] . '_' . $role['id'] . '_' . $user['user_id'] . '" id="lbl_users_' . $step['step_id'] . '_' . $role['id'] . '_' . $user['user_id'] . '">';
                                            $usersContainer .= '<input type="checkbox" class="ckdUsrs_' . $step['step_id'] . '_' . $role['id'] . '" name="Users[' . $step['step_id'] . '][' . $role['id'] . '][' . $user['user_id'] . ']" ' . $chkd . ' id="users_' . $step['step_id'] . '_' . $role['id'] . '_' . $user['user_id'] . '" value="' . $user['user_id'] . '" onclick="selectUser(' . $step['step_id'] . ', ' . $role['id'] . ', ' . $user['user_id'] . ', this)">';
                                            $usersContainer .= '<span class="lbl_users_title">' . $user['name'] . '</span>';
                                            $usersContainer .= '</label>';
                                        }
                                    }
                                    $usersContainer .= '</div>';

                                    echo "<div id='roles_{$step['step_id']}_{$role['id']}' class='bt_selector' onclick='showRoleUsers({$step['step_id']}, {$role['id']});'>";
                                    echo $role['title'];
                                    echo "<span id='roles_counter_{$step['step_id']}_{$role['id']}' class='lbl_counter'>{$usersSelected}</span>";
                                    echo "<span id='roles_slctd_{$step['step_id']}_{$role['id']}' class='lbl_slctd lbl_slctd{$step['step_id']}' style='display:" . ($first ? 'block' : 'none') . "'> 
                                        <img src='{$baseScript}/images/bullet_green.png' alt='selected'/>
                                    </span>";
                                    echo "</div>";
                                    if ($first) {
                                        $jsCodes = "showRoleUsers({$step['step_id']}, {$role['id']});";
                                        Yii::app()->clientScript->registerScript('jsCodes_' . $step['step_id'], $jsCodes, CClientScript::POS_READY);
                                    }
                                    $first = false;
                                }
                                echo '  </div>';
                                echo '</td>';
                                echo '<td valign="top" align="left">';
                                echo $usersContainer;
                                echo '</td>';
                                ?>
                            </tr>
                        </table>
                    </div>
                    <?php
                } // end steps
                $this->endWidget();
                ?>
            </td>
        </tr>
    </table>
</div>