<div class="report-form">
    <?php
    $alertLabel = AmcWm::t('amcBack', 'From date field cannot be empty');
    Yii::app()->clientScript->registerScript('reports', '
        $(".report-form").submit(function(event) {
            if (($("#datepicker-from").val().trim() <= 0)) {
                alert("' . $alertLabel . '");
                event.preventDefault();
            }
        });
            ', CClientScript::POS_READY);
    $form = CHtml::beginForm(Yii::app()->controller->createUrl('reports', array('result' => 1, 'rep' => 'reporters', 'module' => AmcWm::app()->request->getParam('module'))), "GET", array('id' => 'articles-reports-form', "target" => 'reports_dialog_iframe'));
    $form .= "<div class='row'>";
    $form .= CHtml::label(AmcWm::t("amcBack", "From"), 'datepicker-from');
    $form .= $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'datepicker-from',
        'options' => array(
            'showButtonPanel' => true,
            'dateFormat' => 'yy-mm-dd',
        ),
            ), true);
    $form .= CHtml::label(AmcWm::t("amcBack", "To"), 'datepicker-to');
    $form .= $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'datepicker-to',
        'options' => array(
            'showButtonPanel' => true,
            'dateFormat' => 'yy-mm-dd',
        ),
            ), true);
    $form .= CHtml::submitButton(AmcWm::t('amcBack', "Search"), array('id' => 'reports-link'));
    $form .= "</div>";
    $form .= CHtml::endForm();
    echo $form;
    ?>
</div>