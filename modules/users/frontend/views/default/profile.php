<?php
$mediaPaths = Persons::getSettings()->mediaPaths;

$this->beginClip('profileForm');

$form = $this->beginWidget('Form', array(
    'id' => 'profile_form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array('enctype' => 'multipart/form-data')
        ));
?>
<div class="form" style="padding: 3px 10px 10px 10px">
    <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $contentModel, $model->users)); ?>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Personal data"); ?></legend>

        <div class="row">            
            <?php echo $form->labelEx($contentModel, 'name'); ?>
            <?php echo $form->textField($contentModel, 'name', array('size' => 35, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'name'); ?>
        </div>

        <div class="row">
            <div>
                <?php $sexLabels = AmcWm::t("msgsbase.core", 'sexLabels'); ?>
                <?php echo $form->labelEx($model, 'sex'); ?>  
                <?php echo $form->radioButton($model, 'sex', array("uncheckValue" => null, 'value' => 'm')); ?>            
                <span class="op_item"><?php echo $sexLabels['m']; ?></span>
                <?php echo $form->radioButton($model, 'sex', array("uncheckValue" => null, 'value' => 'f')); ?>
                <span class="op_item"><?php echo $sexLabels['f']; ?></span>
                <?php echo $form->error($model, 'sex'); ?>        
            </div>
        </div>

        <div class="row">
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
            ?>
            <?php echo $form->labelEx($model, 'date_of_birth'); ?>
            <?php echo $form->dropDownList($model, 'dobYear', $years, array('prompt' => AmcWm::t('msgsbase.core', 'Year'))); ?>
            <?php echo $form->dropDownList($model, 'dobMonth', $months, array('prompt' => AmcWm::t('msgsbase.core', 'Month'))); ?>
            <?php echo $form->dropDownList($model, 'dobDay', $days, array('prompt' => AmcWm::t('msgsbase.core', 'Day'))); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 35, 'maxlength' => 65)); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'phone'); ?>
            <?php echo $form->textField($model, 'phone', array('size' => 35, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'phone'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'mobile'); ?>
            <?php echo $form->textField($model, 'mobile', array('size' => 35, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'mobile'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'fax'); ?>
            <?php echo $form->textField($model, 'fax', array('size' => 35, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'fax'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'country_code'); ?>
            <?php echo $form->dropDownList($model, 'country_code', $this->getCountries(), array('prompt' => AmcWm::t("msgsbase.core", 'Choose Country'))); ?>
            <?php echo $form->error($model, 'country_code'); ?>
        </div>

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
        <div class="row">
            <?php // echo $form->labelEx($model, 'personImage'); ?>
            <?php echo $form->fileField($model, 'personImage'); ?>
            <?php echo $form->error($model, 'personImage'); ?>
        </div>

        <div id="mainImg">
            <?php echo $drawImage ?>
        </div>
        <?php if ($drawImage): ?>
            <div class="row">
                <input type="checkbox" name="deleteImageFile" id="deleteImageFile" style="float: right" onclick="deleteMainImage(this);" />
                <label for="deleteImageFile" id="lbldltimg_1" title=""><span><?php echo AmcWm::t("amcFront", 'Delete Image'); ?></span></label>
                <label for="deleteImageFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklbl_1'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
            </div>
            <?php
            Yii::app()->clientScript->registerScript('displayDeleteMainImage', "
                deleteMainImage = function(chk){
                    if(chk.checked){
                        if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                            jQuery('#chklbl_1').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                            jQuery('#mainImg').slideUp();
                            jQuery('#lbldltimg_1').toggleClass('isChecked');
                        }else{
                            chk.checked = false;
                        }
                    }else{
                        jQuery('#chklbl_1').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                        jQuery('#mainImg').slideDown();
                        jQuery('#lbldltimg_1').toggleClass('isChecked');
                    }
                }    
            ", CClientScript::POS_HEAD);

            Yii::app()->clientScript->registerCss('displayMainImageCss', "
                label#lbldltimg_1 span {
                    display: none;
                }
                #deleteImageFile{
                    display: none;
                }
                label#lbldltimg_1 {
                    background:  url(" . Yii::app()->baseUrl . "/images/remove.png) no-repeat;
                    width: 18px;
                    height: 18px;
                    display: block;
                    cursor: pointer;
                    float:right;
                    margin: 3px;
                }
                label#lbldltimg_1.isChecked {
                    background:  url(" . Yii::app()->baseUrl . "/images/undo.png) no-repeat;
                }
            ");

        endif;
        ?>

    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Profile"); ?></legend>
        <div class="row">
            <?php echo AmcWm::t("msgsbase.core", "Username") . " " . $model->users->username; ?>
        </div>

        <div class="row">
            <?php //echo $form->labelEx($model->users, 'passwd'); ?> 
            <?php
            $passwordError = count($model->users->getErrors('passwd'));
            if ($passwordError) {
                $model->users->setAttribute('passwd', NULL);
                echo $form->labelEx($model->users, 'passwd');
                echo $form->passwordField($model->users, 'passwd', array('size' => 30, 'maxlength' => 30));
            } else {
                echo $form->labelEx($model->users, 'passwd', array('style' => 'display:none;', 'id' => 'passwd_label'));
                $model->users->setAttribute('passwd', NULL);
                echo $form->passwordField($model->users, 'passwd', array('disabled' => 'disabled', 'size' => 30, 'maxlength' => 30, 'style' => 'display:none;'));
                echo Chtml::button(AmcWm::t("msgsbase.core", 'Change Password'), array('onclick' => '$("#Users_passwd").removeAttr("disabled");$("#Users_passwd").show();$("#change_passwd").hide();$("#passwd_label").show();', 'id' => 'change_passwd'));
            }
            ?>
        </div>
        <?php if ($enableSubscribe): ?>
            <div class="row" style="padding-top: 10px;">
                <?php
                echo $form->checkBox($model, 'toMailList');
                echo $form->labelEx($model, 'toMailList', array("style" => 'display:inline;'));
                ?>
            </div>
        <?php endif; ?>
    </fieldset>

    <?php echo CHtml::submitButton(AmcWm::t("msgsbase.core", 'Save')); ?>

    <?php $this->endWidget(); ?>
</div><!-- form -->

<?php
$this->endClip('profileForm');
$breadcrumbs[AmcWm::t("msgsbase.core", "Member Area")] = array('/users/default/index');
$breadcrumbs[] = AmcWm::t("msgsbase.core", "User Profile");
$this->widget('PageContentWidget', array(
    'id' => 'siteMap',
    'contentData' => $this->clips['profileForm'],
    'title' => AmcWm::t("msgsbase.core", 'Profile'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>
