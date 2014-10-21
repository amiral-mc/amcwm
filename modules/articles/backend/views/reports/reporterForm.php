<div class="report-form">
    <?php
    $alertLabel = AmcWm::t('amcBack', 'From date & Name fields cannot be empty');
    Yii::app()->clientScript->registerScript('reportsValidation', '
        $(".report-form").submit(function(event) {
            if (($("#datepicker-from").val().trim() <= 0) || ($("#user_id").val().trim()) <= 0) {
                alert("' . $alertLabel . '");
                event.preventDefault();
            }
        });
            ', CClientScript::POS_READY);
    if ($module == 'news') {
        $listUrl = Html::createUrl('/backend/articles/default/ajax', array('do' => 'findEditors'));
        $label = 'Editor';
    } elseif ($module == 'essays') {
        $listUrl = Html::createUrl('/backend/articles/default/ajax', array('do' => 'findWriters'));
        $label = 'Reporter';
    }
    $form = CHtml::beginForm(Yii::app()->controller->createUrl('reports', array('result' => 1, 'rep' => 'reporter', 'module' => AmcWm::app()->request->getParam('module'))), "GET", array('id' => 'articles-reports-form', "target" => 'reports_dialog_iframe'));
    $form .= "<div class='row'>";
    $form .= CHtml::label(AmcWm::t('amcBack', $label), 'user_id', array('class' => 'user-label'));
    $form .= $this->widget('amcwm.core.widgets.select2.ESelect2', array(
        'name' => "user_id",
        'options' => array(
            "dropdownCssClass" => "bigdrop",
            'placeholder' => AmcWm::t('amcBack', $label),
            'ajax' => array(
                'dataType' => "json",
                "quietMillis" => 100,
                'url' => $listUrl,
                'data' => 'js:function (term, page) { // page is the one-based page number tracked by Select2
                        return {
                               q: term, //search term
                               page: page, // page number                     
                           };
                       }',
                'results' => 'js:function (data, page) {
                            var more = (page * ' . Writers::REF_PAGE_SIZE . ') < data.total; // whether or not there are more results available 
                            // notice we return the value of more so Select2 knows if more results can be loaded
                            return {results: data.records, more: more};
                          }',
            ),
        ),
        'htmlOptions' => array(
            'style' => 'min-width:378px;',
        ),
            ), true);
    $form .= '</div>';
    $form .= '<div class="row">';
    $form .= CHtml::label(AmcWm::t("amcBack", "From"), 'datepicker-from');
    $form .= $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'datepicker-from',
//        'value' => date('d-m-Y'),
        'options' => array(
            'showButtonPanel' => true,
            'dateFormat' => 'yy-mm-dd',
        ),
            ), true);
    $form .= CHtml::label(AmcWm::t("amcBack", "To"), 'datepicker-to');
    $form .= $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'datepicker-to',
//        'value' => date('d-m-Y'),
        'options' => array(
            'showButtonPanel' => true,
            'dateFormat' => 'yy-mm-dd',
        ),
            ), true);
    $form .= CHtml::submitButton(AmcWm::t('amcBack', "Search"), array('id' => 'reports-link'));
    $form .= '</div>';
    $form .= CHtml::endForm();
    echo $form;
    ?>
</div>