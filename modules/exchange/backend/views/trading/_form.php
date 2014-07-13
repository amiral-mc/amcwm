<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $model)); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>
        <div class="row">
            <?php echo $form->labelEx($model, 'exchange_id'); ?>
            <?php echo $form->dropDownList($model, 'exchange_id', CHtml::listData(Exchange::model()->findAll(array('order' => 'exchange_name DESC')), 'exchange_id', 'exchange_name')); ?>
            <?php echo $form->error($model, 'exchange_id'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'exchange_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'exchange_date',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'hh:mm',
                    'changeMonth' => true,
                    'changeYear' => false,
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'style' => 'direction:ltr',
                    'readonly' => 'readonly',
                    //'value' => ($model->publish_date) ? date("Y-m-d H:i", strtotime($model->publish_date)) : date("Y-m-d 00:01", strtotime("+1 day")),
                    'value' => ($model->exchange_date) ? date("Y-m-d H:i", strtotime($model->exchange_date)) : date("Y-m-d H:i"),
                )
            ));
            ?>
            <?php echo $form->error($model, 'exchange_date'); ?>
        </div>
        <div class="row">                       
            <?php echo $form->labelEx($model, 'trading_value'); ?>
            <?php echo $form->textField($model, 'trading_value'); ?>
            <?php echo $form->error($model, 'trading_value'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'shares_of_stock'); ?>
            <?php echo $form->textField($model, 'shares_of_stock'); ?>
            <?php echo $form->error($model, 'shares_of_stock'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'closing_value'); ?>
            <?php echo $form->textField($model, 'closing_value'); ?>
            <?php echo $form->error($model, 'closing_value'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'difference_value'); ?>
            <?php echo $form->textField($model, 'difference_value'); ?>
            <?php echo $form->error($model, 'difference_value'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'difference_percentage'); ?>
            <?php echo $form->textField($model, 'difference_percentage'); ?>
            <?php echo $form->error($model, 'difference_percentage'); ?>
        </div>
    </fieldset>


    <?php $this->endWidget(); ?>

</div><!-- form -->