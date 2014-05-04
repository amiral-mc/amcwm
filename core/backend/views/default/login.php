<?php if($hasError){
    $msg = AmcWm::t("amcBack", "incorrect_username_password", array("{access_denied}" => "<b>" . AmcWm::t("amcFront", "access_denied") . "</b>")); //"<b></b>, Incorrect username or password.";    
    //$msg = AmcWm::t("amcFront", "fill_out_the_your_login_credentials");
?>
<div class="top_error">
    <div class="error_inner">
        <span><?php echo $msg ?></span>
    </div>
</div>
<?php }?>
<?php
$this->pageTitle = $this->pageTitle . ' - ' . Yii::t('pageTitles', 'login');
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                )
);
?>
<fieldset>
    <h1 id="logo"><a href="#">AMC Web Manager</a></h1>
    <div class="form">
        <div class="form_inner">				
            <div class="row">
                <?php echo $form->labelEx($model, 'username') ?>
                <br />
                <?php echo  $form->textField($model, 'username', array("class" => "textbox"));?>
                <?php $form->error($model, 'username');?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model, 'password') ?>
                <br />
                <?php echo  $form->passwordField($model, 'password', array("class" => "textbox"));?>
                <?php $form->error($model, 'password');?>
            </div>
            <div class="row">                        
                
            </div>
            <div class="row buttons">                        
                <?php echo $form->checkBox($model, 'rememberMe') . $form->labelEx($model, 'rememberMe');?>
                <?php $form->error($model, 'rememberMe');?>
                |
                <?php echo CHtml::link(AmcWm::t("amcFront","Forget my password?"));?>
                <?php echo CHtml::submitButton(AmcWm::t("amcFront", 'login_button'), array("style" => "width:60px;"));?>
                
                
            </div>         				
        </div>
    </div>
</fieldset>
<?php $this->endWidget(); ?>