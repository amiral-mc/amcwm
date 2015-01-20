<?php $this->beginClip('profileForm'); ?>
<div class="form" style="padding: 3px 10px 10px 10px">
    <?php
    $orintation = strtolower(Yii::app()->getLocale()->getOrientation());
    if ($orintation == 'rtl') {
        $align = "right";
    } else {
        $align = "left";
    }
    $mediaPaths = Persons::getSettings()->mediaPaths;
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'profile_form',
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
    ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $contentModel, $model->users)); ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Personal data"); ?></legend>
        <?php echo $form->textFieldRow($contentModel, 'name', array('size' => 35, 'maxlength' => 100)); ?>
        <?php
        echo $form->radioButtonListRow($model, 'sex', array(
            'm' => AmcWm::t("msgsbase.core", 'Male'),
            'f' => AmcWm::t("msgsbase.core", 'Female'),
        ));
        ?>       
        <?php
        $years = $months = $days = array();
        for ($i = 1930; $i <= 1995; $i++) {
            $years[$i] = $i;
        }
        for ($i = 1; $i <= 12; $i++) {
            $v = ($i < 10) ? "0{$i}" : $i;
            $months[$v] = $v;
        }
        for ($i = 1; $i <= 31; $i++) {
            $v = ($i < 10) ? "0{$i}" : $i;
            $days[$v] = $v;
        }
        $model->setAttributeLabel('dobYear', '');
        $model->setAttributeLabel('dobMonth', '');
        $model->setAttributeLabel('dobDay', '');
        ?>
        <div class="control-group " style="width: 400px;">
            <?php echo $form->labelEx($model, 'date_of_birth', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'dobYear', $years, array('prompt' => AmcWm::t('msgsbase.core', 'Year'), 'style' => 'width:80px')); ?>
                <?php echo $form->dropDownList($model, 'dobMonth', $months, array('prompt' => AmcWm::t('msgsbase.core', 'Month'), 'style' => 'width:70px')); ?>
                <?php echo $form->dropDownList($model, 'dobDay', $days, array('prompt' => AmcWm::t('msgsbase.core', 'Day'), 'style' => 'width:62px')); ?>
            </div>
        </div>
        <?php echo $form->textFieldRow($model, 'email', array('size' => 35, 'maxlength' => 65)); ?>
        <?php echo $form->textFieldRow($model, 'phone', array('size' => 35, 'maxlength' => 45)); ?>
        <?php echo $form->textFieldRow($model, 'mobile', array('size' => 35, 'maxlength' => 45)); ?>
        <?php echo $form->textFieldRow($model, 'fax', array('size' => 35, 'maxlength' => 45)); ?>

        <?php echo $form->dropDownListRow($model, 'country_code', $this->getCountries(), array('prompt' => AmcWm::t("msgsbase.core", 'Choose Country'))); ?>
    </fieldset>
    <fieldset style="margin-top:10px;">
        <?php
        $drawImage = NULL;
        if ($model->person_id && $model->thumb) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb))) {
                $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb . "?" . time(), "", array("class" => "image", "width" => "60")) . '</div>';
            }
        }
        ?>
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?></legend>
        <?php echo $form->fileFieldRow($model, 'personImage'); ?>                
        <?php if ($drawImage): ?>
            <div id="mainImg" style="padding-<?php echo $align ?>: 120px;">
                <?php echo $drawImage ?>
            </div>
            <div class="control-group">
                <div class="controls">                    
                    <input type="checkbox" name="deleteImageFile" id="deleteImageFile" onclick="deleteMainImage(this);" style="display: none;"/>
                    <label for="deleteImageFile" class="control-label" style="width:200px;">                    
                        <span>
                            <img id="imageActionSrc" src="<?php echo Yii::app()->baseUrl; ?>/images/remove.png">
                        </span>
                        <span id="imgLabel"><?php echo AmcWm::t("amcFront", 'Delete Image'); ?></span>
                    </label>                
                </div>
            </div>
            <?php
            Yii::app()->clientScript->registerScript('displayDeleteMainImage', "
                deleteMainImage = function(chk){
                    if(chk.checked){
                        if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                            jQuery('#imageActionSrc').attr('src', '" . Yii::app()->baseUrl . "/images/undo.png');
                            jQuery('#imgLabel').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                            jQuery('#mainImg').slideUp();                            
                        }else{
                            chk.checked = false;
                        }
                    }else{
                        jQuery('#imgLabel').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                        jQuery('#imageActionSrc').attr('src', '" . Yii::app()->baseUrl . "/images/remove.png');
                        jQuery('#mainImg').slideDown();                        
                        
                    }
                }    
            ", CClientScript::POS_HEAD);
        endif;
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Profile"); ?></legend>
        <div class="control-group">                        
            <div class="controls">
                <?php
                $passwordError = count($model->users->getErrors('passwd'));
                if ($passwordError) {
                    $model->users->setAttribute('passwd', NULL);
                    echo $form->labelEx($model->users, 'passwd');
                    echo $form->passwordField($model->users, 'passwd', array('class' => 'control-label', 'size' => 30, 'maxlength' => 30));
                } else {
                    echo $form->labelEx($model->users, 'passwd', array('class' => 'control-label', 'style' => 'display:none;', 'id' => 'passwd_label'));
                    $model->users->setAttribute('passwd', NULL);
                    echo $form->passwordField($model->users, 'passwd', array('disabled' => 'disabled', 'size' => 30, 'maxlength' => 30, 'style' => 'display:none;'));
                    echo Chtml::button(AmcWm::t("msgsbase.core", 'Change Password'), array('onclick' => '$("#Users_passwd").removeAttr("disabled");$("#Users_passwd").show();$("#change_passwd").hide();$("#passwd_label").show();', 'id' => 'change_passwd'));
                }
                ?>
            </div>
        </div>  
        <?php if ($enableSubscribe): ?>
            <?php echo $form->checkBoxRow($model, 'toMailList'); ?>
        <?php endif; ?>
    </fieldset>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => AmcWm::t("msgsbase.core", 'Save'))); ?>                        
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
<?php $this->endClip('profileForm') ?>
<?php
$breadcrumbs[AmcWm::t("msgsbase.core", "Member Area")] = array('/users/default/index');
$breadcrumbs[] = AmcWm::t("msgsbase.core", "User Profile");
$this->widget('PageContentWidget', array(
    'id' => 'profile',
    'contentData' => $this->clips['profileForm'],
    'title' => AmcWm::t("msgsbase.core", 'Profile'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>
