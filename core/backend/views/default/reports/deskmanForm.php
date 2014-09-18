<div class="report-form">
    <?php
    
    $usersListUrl = Html::createUrl('/backend/users/default/ajax', array('do' => 'usersList',));
    $form = CHtml::beginForm(Yii::app()->controller->createUrl('reports', array('result' => 1, 'rep' => 'deskman', 'module' => AmcWm::app()->request->getParam('module'))), "GET", array('id' => 'articles-reports-form', "target" => 'reports_dialog_iframe'));
    $form .= $this->widget('amcwm.core.widgets.select2.ESelect2', array(
        'name' => "user_id",
        'addingNoMatch' => false,
        'options' => array(
            "dropdownCssClass" => "bigdrop",
            'ajax' => array(
                'dataType' => "json",
                "quietMillis" => 100,
                'url' => $usersListUrl,
                'data' => 'js:function (term, page, writer) { // page is the one-based page number tracked by Select2
                                                    return {
                                                           q: term, //search term
                                                           page: page, // page number
                                                       };
                                                   }',
                'results' => 'js:function (data, page, writer) {
                                                        var more = (page * ' . Users::REF_PAGE_SIZE . ') < data.total; // whether or not there are more results available 
                                                        // notice we return the value of more so Select2 knows if more results can be loaded
                                                        return {results: data.records, more: more};
                                                    }',
            ),
        ),
        'htmlOptions' => array(
            'style' => 'width:200px;',
        ),
            ), true);
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
    $form .= CHtml::endForm();
    echo $form;
    ?>
</div>