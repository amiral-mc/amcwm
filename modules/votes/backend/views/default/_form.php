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

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>    
    <?php
    echo $form->errorSummary(array_merge(array($model, $contentModel), $contentModel->votesOptions));
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
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>                    
            <?php echo $form->checkBox($model, 'suspend'); ?>
            <?php echo $form->labelEx($model, 'suspend', array("style" => 'display:inline;')); ?>                    
        </div>

    </fieldset>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Question Data"); ?>:</legend>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'ques'); ?>
            <?php echo $form->textField($contentModel, 'ques', array('size' => 30, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'ques'); ?>
        </div>        

        <div class="row">
            <?php echo $form->labelEx($model, 'publish_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'publish_date',
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'readonly' => 'readonly',
                    'value' => ($model->publish_date) ? Yii::app()->dateFormatter->format("y-MM-dd", $model->publish_date) : date("Y-m-d"),
                )
            ));
            ?>
            <?php echo $form->error($model, 'publish_date'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'expire_date'); ?>                        
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'expire_date',
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'readonly' => 'readonly',
                    'value' => ($model->expire_date) ? Yii::app()->dateFormatter->format("y-MM-dd", $model->expire_date) : "",
                )
            ));
            ?>            
            <?php echo Chtml::checkBox('o', ($model->expire_date) ? 0 : 1, array('onclick' => '$("#VotesQuestions_expire_date").val("")')) ?>
            <?php echo Chtml::label(AmcWm::t("msgsbase.core", "No expiry date"), "remove_expiry", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
            <?php echo $form->error($model, 'expire_date'); ?>

        </div>      
    </fieldset>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Options"); ?>:</legend>
        <div id="optionContainer">            
            <?php foreach ($contentModel->votesOptions as $i => $option): ?>
                <div class="row" id="optionOf_row<?php echo $i ?>">
                    <?php echo $form->hiddenField($option, "[$i]option_id"); ?>
                    <?php
                    $optionItem = $form->textField($option, "[$i]value", array('size' => 30, 'maxlength' => 100));
                    $optionItem .= '&nbsp;' . Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("id" => "row{$i}", "onclick" => "voteAction.removeoption(this.id)", "class" => "btn_label"));
                    echo $optionItem;
                    ?>
                    <?php echo $form->error($option, "value"); ?>
                </div>         
            <?php endforeach; ?>
        </div>
        <?php
        $optionNumber = count($contentModel->votesOptions);
        echo Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add.png", "", array("border" => 0, "align" => 'absmiddle')) . "&nbsp;" . AmcWm::t("msgsbase.core", "Add new option"), "javascript:void(0);", array("id" => "addOption", "class" => "btn_label"));
        ?>
    </fieldset>
    <?php
        $removedOptions = Yii::app()->request->getParam("VotesOptionsRemoved", array());
        if(count($removedOptions)){
            foreach ($removedOptions as $removedOption){
                echo CHtml::hiddenField('VotesOptionsRemoved[]' , $removedOption, array('id'=>"VotesOptionsRemoved_{$removedOption}"));      
            }
        }
    ?>
    <?php $this->endWidget(); ?>
    <?php
    $count = count($contentModel->votesOptions);
    Yii::app()->clientScript->registerScript('addOption', "
$('#addOption').click(function(){            
    optionItems = $('input:text[name^=\"VotesOptions\"]');    
    optionNumber = optionItems.length;       
    voteAction.addoption(optionNumber);
    return false;
});
voteAction = {};
voteAction.addoption = function(optionNumber){
    var optionRow = '<div class=\"row\" id=\"optionOf_row'+optionNumber+'\">';
    optionRow += '<input name=\"VotesOptions['+optionNumber+'][option_id]\" id=\"VotesOptions_'+optionNumber+'_option_id\" type=\"hidden\" />';    
    optionRow += '<input size=\"30\" maxlength=\"100\" name=\"VotesOptions['+optionNumber+'][value]\" id=\"VotesOptions_'+optionNumber+'_value\" type=\"text\" />';
    optionRow +='&nbsp;<a id=\"row'+optionNumber+'\" onclick=\"voteAction.removeoption(this.id)\" class=\"btn_label\" href=\"javascript:void(0);\"><img border=\"0\" align=\"absmiddle\" src=\"" . Yii::app()->baseUrl . "/images/remove.png\" alt=\"\" />';
    optionRow += '</div>';
    $('#optionContainer').append(optionRow);
}
voteAction.removeoption = function(removeoptionRow){
removeoptionNumber = removeoptionRow.substring(3);
optionId = parseInt($('#VotesOptions_'+removeoptionNumber+'_option_id').val());
if(!isNaN(optionId) && optionId){
    $('#" . Yii::app()->params["adminForm"] . "').append('<input type=\"hidden\" name=\"VotesOptionsRemoved[]\" value=\"'+optionId+'\" />');
}
$('#VotesOptions_'+removeoptionNumber+'_option_id').remove();
$('#VotesOptions_'+removeoptionNumber+'_value').attr('disabled', 'disabled');
$('#optionOf_'+removeoptionRow).hide();
}
");
    ?>
</div><!-- form -->