<?php
$mediaPaths = Persons::getSettings()->mediaPaths;
$model = $contentModel->getParentContent();
$user = $model->users;
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Users") => array('/backend/users/default/index'),
    AmcWm::t("msgsbase.core", "Edit"),
);
$this->sectionName = $user->username;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_user', 'image_id' => 'save'),
        array('label' => AmcWm::t("msgsbase.core", 'Permissions'), 'url' => array('/backend/users/default/permissions', 'id' => $model->person_id), 'id' => 'user_permissions', 'image_id' => 'permissions'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/users/default/index'), 'id' => 'users_list', 'image_id' => 'back'),
    ),
));
?>
<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "User data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
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
            <?php
            $actionParams = $this->getActionParams();
            if (array_key_exists('tlang', $actionParams)) {
                unset($actionParams['tlang']);
            }
            $translateRoute = Html::createUrl($this->getRoute());
            ?> 
            <?php echo CHtml::label(AmcWm::t("amcTools", "Translate To"), "tlang") ?>
            <?php echo CHtml::dropDownList("tlang", $translatedModel->content_lang, $this->getTranslationLanguages(), array("onchange" => "FormActions.translationChange('$translateRoute', " . CJSON::encode($actionParams) . ");")); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'name'); ?>
            <?php echo $form->textField($translatedModel, 'name', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($translatedModel, 'name'); ?>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Sex'); ?></span>:
            <span class="translated_org_item">
                <?php echo $model->getSexLabel() ?>
            </span>
        </div>        
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Email'); ?></span>:
            <span class="translated_org_item">
                <?php echo $model->email ?>
            </span>
        </div>       
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Country'); ?></span>:
            <span class="translated_org_item">
                <?php echo $model->country->getCountryName() ?>
            </span>
        </div>        

    </fieldset>
    <div class="row" >
        <fieldset>
            <?php
            $drawImage = NULL;
            if ($model->person_id && $model->thumb) {
                if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb))) {
                    $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb . "?" . time(), "", array("class" => "image", "width" => "60")) . '</div>';
                }
            }
            ?>
            <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?>:</legend>
            <div id="mainImg">
                <?php echo $drawImage ?>
            </div>    
        </fieldset>
    </div>

    <fieldset style="margin-top:10px;">        
        <legend><?php echo AmcWm::t("msgsbase.core", "User data"); ?>:</legend>        
        <div class="row" >
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Published'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($user->published) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Username'); ?></span>:
            <span class="translated_org_item">
                <?php echo $user->username ?>
            </span>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Role'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($user->role_id) {
                    echo $user->role->role;
                } else {
                    echo Yii::t('zii', 'Not set');
                }
                ?>
            </span>                
        </div>
    </fieldset>        
    <?php $this->endWidget(); ?>

</div><!-- form -->