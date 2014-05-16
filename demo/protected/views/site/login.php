<?php $this->beginClip('loginForm')?>
<!-- START Form -->						
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'inlineErrors' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <?php echo $form->textFieldRow($model, 'username', array('class' => 'span3')); ?>
    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span3')); ?>
    <?php echo $form->checkboxRow($model, 'rememberMe'); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()) ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => Yii::t('app', 'Login'))); ?>

    <?php $this->endWidget(); ?>
</div>
<?php
$this->endClip('loginForm');
$breadcrumbs = array(AmcWm::t("app", 'Login'));
$this->widget('AccountFormWidget', array(
    'id' => 'login_form',
    'formTitle' => AmcWm::t("app", 'Login To Member Area'),
    'contentData' => $this->clips['loginForm'],
//    'pannelInformationMessage' => 'Hello',
    'title' => AmcWm::t("app", 'Login'),
    'preHeader' => AmcWm::t("app", "Account management ..."),
    'informationMessage' => AmcWm::t("amcFront", "fill_out_the_your_login_credentials"),
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("app", 'Login'),
));
?>