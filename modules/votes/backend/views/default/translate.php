<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Votes Questions") => array('/backend/votes/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->ques;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_poll', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/votes/default/index'), 'id' => 'polls_list', 'image_id' => 'back'),
    ),
));
?>

<div class="form">
    <?php
    $model = $contentModel->getParentContent();
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
    <?php
    echo $form->errorSummary(array_merge(array($model, $translatedModel), $translatedModel->votesOptions));
    ?>    

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Vote Options"); ?>:</legend>
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
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Published'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->published) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Suspend'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->suspend) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>
        </div>
    </fieldset>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Question Data"); ?>:</legend>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'ques'); ?>
            <?php echo $form->textField($translatedModel, 'ques', array('size' => 30, 'maxlength' => 100)); ?>
            <?php echo $form->error($translatedModel, 'ques'); ?>
        </div>        
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Publish Date'); ?></span>:
            <span class="translated_org_item"><?php echo Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->publish_date); ?>
            </span>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Expire Date'); ?></span>:
            <span class="translated_org_item">
                <?php
                $expireDate = ($model->expire_date) ? Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->expire_date) : AmcWm::t("msgsbase.core", "No expiry date");
                echo $expireDate;
                ?>
            </span>
        </div>

    </fieldset>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Options"); ?>:</legend>
        <div id="optionContainer">            
            <?php foreach ($translatedModel->votesOptions as $i => $option): ?>
                <div class="row" id="optionOf_row<?php echo $i ?>">
                    <?php echo $form->hiddenField($option, "[$i]option_id"); ?>
                    <?php
                    echo $form->textField($option, "[$i]value", array('size' => 30, 'maxlength' => 100));
                    ?>
                    <?php echo $form->error($option, "value"); ?>
                </div>         
            <?php endforeach; ?>
        </div>      
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->