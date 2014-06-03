<div class="form">
    <?php
    $maillistSettings = new Settings('maillist', 0);
    $maillistOptions = $maillistSettings->getOptions();
    $enableSubscribe = $maillistOptions['default']['check']['enableSubscribe'];
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $model = $contentModel->getParentContent();
    $user = $model->users;
    $allowChangeUserName = true;
    if (!$user->isNewRecord) {
        $currentUser = $user->findByPk($user->user_id);
        $userInfo = AmcWm::app()->user->getInfo();
//        $allowChangeUserName = !($userInfo['username'] == $currentUser->username && $currentUser->username != $user->username) ;
    }
    $mediaPaths = Persons::getSettings()->mediaPaths;
    /**
     * @todo fix the client side validation , if model in edit mode then check password only when user click on change passsword button
     */
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
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($user, $model, $contentModel)); ?>
    <fieldset>    
        <legend><?php echo AmcWm::t("msgsbase.core", "Personal data"); ?>:</legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'name'); ?>
            <?php echo $form->textField($contentModel, 'name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'sex'); ?>                        
            <?php echo $form->radioButtonList($model, 'sex', AmcWm::t("msgsbase.core", 'sexLabels'), array('separator' => ' ', 'labelOptions' => array('class' => 'checkbox_label'))); ?>            
            <?php echo $form->error($model, 'sex'); ?>
        </div>        

        <div class="row">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>

        <div class="row">
            <?php
            if ($model->isNewRecord) {
                $model->country_code = 'EG';
            }
            ?>
            <?php echo $form->labelEx($model, 'country_code'); ?>
            <?php echo $form->dropDownList($model, 'country_code', $this->getCountries()); ?>
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
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?>:</legend>
        <div class="row">
            <?php echo $form->labelEx($model, 'personImage'); ?>
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
                    background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                    width: 18px;
                    height: 18px;
                    display: block;
                    cursor: pointer;
                    float:right;
                    margin: 3px;
                }
                label#lbldltimg_1.isChecked {
                    background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                }
            ");

        endif;
        ?>

    </fieldset>
    <fieldset style="margin-top:10px;">
        <legend><?php echo AmcWm::t("msgsbase.core", "User data"); ?>:</legend>        
        <?php if (!$user->is_system): ?>
            <div class="row" >
                <?php echo $form->checkBox($user, 'published'); ?>
                <?php echo $form->labelEx($user, 'published', array("style" => 'display:inline;')); ?>            
            </div>
        <?php endif; ?>
        <?php if ($allowChangeUserName): ?>
            <div class="row">
                <?php echo $form->labelEx($user, 'username'); ?>
                <?php echo $form->textField($user, 'username', array('size' => 30, 'maxlength' => 65)); ?>
                <?php echo $form->error($user, 'username'); ?>
            </div>
        <?php endif; ?>
        <div class="row">            
            <?php
            $passwordError = count($user->getErrors('passwd'));
            if ($passwordError) {
                $user->setAttribute('passwd', NULL);
                echo $form->labelEx($user, 'passwd');
                echo $form->passwordField($user, 'passwd', array('size' => 30, 'maxlength' => 30));
            } else if ($user->isNewRecord) {
                echo $form->labelEx($user, 'passwd');
                echo $form->passwordField($user, 'passwd', array('size' => 30, 'maxlength' => 30));
            } else {
                echo $form->labelEx($user, 'passwd', array('style' => 'display:none;', 'id' => 'passwd_label'));
                $user->setAttribute('passwd', NULL);
                echo $form->passwordField($user, 'passwd', array('disabled' => 'disabled', 'size' => 30, 'maxlength' => 30, 'style' => 'display:none;'));
                echo Chtml::button(AmcWm::t("msgsbase.core", 'Change Password'), array('onclick' => '$("#Users_passwd").removeAttr("disabled");$("#Users_passwd").show();$("#change_passwd").hide();$("#passwd_label").show();', 'id' => 'change_passwd'));
            }
            ?>                        
            <?php echo $form->error($user, 'passwd'); ?>
        </div>
        <?php if (!$user->is_system): ?>
            <div class="row">
                <?php echo $form->labelEx($user, 'role_id'); ?>        
                <?php echo $form->dropDownList($user, 'role_id', Users::getUsersRoles(), array('prompt' => AmcWm::t("msgsbase.core", 'Select User Role'))); ?>
                <?php if (!$model->isNewRecord) echo AmcWm::t("msgsbase.core", 'Change user role may lead to lose his / her special permissions'); ?>
                <?php echo $form->error($user, 'role_id'); ?>

            </div>
        <?php else: ?>
            <?php echo $form->label($user, 'role_id', array("style" => "display:inline;")); ?>:
            <?php echo $user->role->role; ?>
        <?php endif; ?>
        <?php if ($enableSubscribe): ?>
            <div class="row" style="padding-top: 10px;">
                <?php
                echo $form->checkBox($model, 'toMailList');
                echo $form->labelEx($model, 'toMailList', array("style" => 'display:inline;'));
                ?>
            </div>
        <?php endif; ?>
    </fieldset>        

    <?php $this->endWidget(); ?>

</div><!-- form -->