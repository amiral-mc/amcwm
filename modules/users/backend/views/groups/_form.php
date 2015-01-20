<div class="form">
    <?php
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model)); ?>

    <fieldset>    
        <legend><?php echo AmcWm::t("msgsbase.roles", "Role data"); ?>:</legend>
        <div class="row">
            <?php echo $form->labelEx($model, 'role'); ?>
            <?php echo $form->textField($model, 'role', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'role'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'parent_role_id'); ?>
            <?php echo $form->dropDownList($model, 'parent_role_id', Users::getUsersRoles($model->role_id), array('prompt' => AmcWm::t("msgsbase.roles", "Select parent role"), 'onchange'=>'loadRolePermissions(this)')); ?>
            <?php echo $form->error($model, 'parent_role_id'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'role_desc'); ?>
            <?php echo $form->textArea($model, 'role_desc'); ?>
            <?php echo $form->error($model, 'role_desc'); ?>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.roles", "Role permissions"); ?>:</legend>
        <div id="users_permissions">            
            <?php
            $this->widget('amcwm.core.widgets.ManageRolePermissions', array(
                'id' => 'manage-permissions-container',
                'model' => $model,
                'modules' => amcwm::app()->acl->getModules(),
            ));
            ?>    
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript">
function loadRolePermissions(slct){
    var selectedRole = slct.value;
    $('#users_permissions').html('<img src="<?php echo $baseScript?>/images/loader.gif" />');
    jQuery.ajax({
        'url':'<?php echo Html::createUrl('/backend/users/groups/ajax', array('do' => 'getRolePermissions')); ?>',
        'cache':false,
        'data':{
            'roleId': selectedRole
        },
        'success':function(html){
            jQuery('#users_permissions').html(html);
        }
    });
}
</script>