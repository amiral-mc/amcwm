<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
            ));
    echo CHtml::hiddenField("issue", Issue::getInstance()->getIssueValue());
    ?>

    <div class="row">
<?php echo $form->label($model, 'issue_id'); ?>
<?php echo $form->textField($model, 'issue_id'); ?>
    </div>

    <div class="row">
<?php echo $form->label($model, 'issue_date'); ?>
<?php echo $form->textField($model, 'issue_date'); ?>
    </div>

    <div class="row">
<?php echo $form->label($model, 'published'); ?>
<?php echo $form->textField($model, 'published'); ?>
    </div>

    <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->