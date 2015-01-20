<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Users") => array('/backend/users/groups/index'),
    AmcWm::t("msgsbase.core", "Permissions"),
);
$this->sectionName = $model->username;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'user_permissions', 'image_id' => 'save'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/users/groups/index'), 'id' => 'users_list', 'image_id' => 'back'),
    ),
));
?>
<div class="form">   
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary(array($model->person, $model)); ?>       
    <div>            
        <?php
        $this->widget('amcwm.core.widgets.ManagePermissions', array(
            'id' => 'manage-permissions-container',
            'model' => $model,
            'modules' => amcwm::app()->acl->getModules(),
        ));
        ?>    
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->