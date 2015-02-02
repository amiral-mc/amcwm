<style type="text/css">
    .counterNum{
        float: right;
        width: 20px;
        height: 17px;
        border: 1px solid #BBDBE8;
        background: #fff;
        text-align: center;
        padding-top: 3px;
    }
</style>

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
    <?php
    echo $form->errorSummary(array_merge(array($model), $model->workflowSteps));
    ?>    

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Workflow Details"); ?>:</legend>

        <div class="row">
            <?php echo $form->checkBox($model, 'enabled'); ?>
            <?php echo $form->labelEx($model, 'enabled', array("style" => 'display:inline;')); ?>
            <?php echo $form->checkBox($model, 'system'); ?>
            <?php echo $form->labelEx($model, 'system', array("style" => 'display:inline;')); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'flow_title'); ?>
            <?php echo $form->textField($model, 'flow_title', array('size' => 30, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'flow_title'); ?>
        </div> 

    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Workflow Steps"); ?>:</legend>
        <div id="stepsContainer">            
            <?php
            $counter = 1;
            foreach ($model->workflowSteps as $i => $step):
                ?>
                <div class="row" id="optionOf_row<?php echo $i ?>">
                    <?php echo $form->hiddenField($step, "[$i]step_id"); ?>
                    <?php
                    $stepItem = "<div class='counterNum'>" . $counter . '</div> &nbsp;';
                    $stepItem .= $form->textField($step, "[$i]step_title", array('size' => 30, 'maxlength' => 100));
                    $stepItem .= '&nbsp;' . Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("id" => "row{$i}", "onclick" => "stepsAction.removeStep(this.id)", "class" => "btn_label"));
                    $stepItem .= "<fieldset>";
                    $stepItem .= "<legend></legend>";
                    
                    $controllers = $model->getControllers($step->step_id);
                    foreach ($controllers as $item) {
                        $actions = $item['actions'];
                        $stepItem .= "<br /><b>" . $item['controller_name'] . "</b><br />";
                        foreach ($actions as $action) {
                            $htmlOptions = array();
                            $htmlOptions['value'] = $action['action_id'];
                            $htmlOptions['id'] = 'action_' . $i . '_' . $action['action_id'];
                            
                            $stepItem .= Chtml::checkBox("WorkflowActions[$i][]", $action['selected'], $htmlOptions);
                            $stepItem .= Chtml::label($action['action_name'], $htmlOptions['id'], array("class" => 'normal_label'));
                        }
                    }
                    $stepItem .= '</fieldset>';
                    echo $stepItem;
                    ?>
                    <?php echo $form->error($step, "step_title"); ?>
                </div>         
                <?php
                $counter++;
            endforeach;
            ?>
        </div>
        <?php
        $stepsCount = count($model->workflowSteps);
        echo Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add.png", "", array("border" => 0, "align" => 'absmiddle')) . "&nbsp;" . AmcWm::t("msgsbase.core", "Add new step"), "javascript:void(0);", array("id" => "add_step", "class" => "btn_label"));
        ?>
    </fieldset>

    <?php
    $removedSteps = Yii::app()->request->getParam("WorkflowStepsRemoved", array());
    if (count($removedSteps)) {
        foreach ($removedSteps as $removedStep) {
            echo CHtml::hiddenField('WorkflowStepsRemoved[]', $removedStep, array('id' => "WorkflowStepsRemoved_{$removedStep}"));
        }
    }
    ?>

    <?php $this->endWidget(); ?>
    <?php
    $count = count($model->workflowSteps);
    Yii::app()->clientScript->registerScript('add_Step', "
var counter = ({$counter} -1);

$('#add_step').click(function(){
    counter++;
    flowSteps = $('input:text[name^=\"WorkflowSteps\"]');    
    stepNumber = flowSteps.length;       
    stepsAction.addStep(stepNumber);
    return false;
});


stepsAction = {};
stepsAction.addStep = function(stepNumber){
    var stepRow = '<div class=\"row\" id=\"optionOf_row'+stepNumber+'\">';
    stepRow += '<div class=\"counterNum\">'+ counter + '</div> &nbsp; ';
    stepRow += '<input name=\"WorkflowSteps['+stepNumber+'][step_id]\" id=\"WorkflowSteps_'+stepNumber+'_step_id\" type=\"hidden\" />';
    stepRow += '<input size=\"30\" maxlength=\"100\" name=\"WorkflowSteps['+stepNumber+'][step_title]\" id=\"WorkflowSteps_'+stepNumber+'_step_title\" type=\"text\" />';
    stepRow +='&nbsp;<a id=\"row'+stepNumber+'\" onclick=\"stepsAction.removeStep(this.id)\" class=\"btn_label\" href=\"javascript:void(0);\"><img border=\"0\" align=\"absmiddle\" src=\"" . Yii::app()->baseUrl . "/images/remove.png\" alt=\"\" />';
    stepRow += '</div>';
    $('#stepsContainer').append(stepRow);
}

stepsAction.removeStep = function(removeStepRow){
    counter--;
    if(counter <2){
        alert('" . Yii::t('msgsbase.core', 'You must have at least two steps') . "');
    }else{
        removeStepNumber = removeStepRow.substring(3);
        stepId = parseInt($('#WorkflowSteps_'+removeStepNumber+'_step_id').val());
        if(!isNaN(stepId) && stepId){
            $('#" . Yii::app()->params["adminForm"] . "').append('<input type=\"hidden\" name=\"WorkflowStepsRemoved[]\" value=\"'+stepId+'\" />');
        }

        $('#WorkflowSteps_'+removeStepNumber+'_step_id').remove();
        $('#WorkflowSteps_'+removeStepNumber+'_step_title').attr('disabled', 'disabled');
        $('#optionOf_'+removeStepRow).remove();
    }
    
    var newCounter= 1;
    $('.counterNum').each(function(){
        $(this).html(newCounter);
        newCounter++;
    });
    counter = (newCounter-1);
    
}

");
    ?>
</div><!-- form -->