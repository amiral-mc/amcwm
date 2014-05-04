<?php

$js = "
    $('#addToForm').click(function(){
        var currentId = rowTitle = '';
        $(\"input[name='ids']:checked\").each(function(){
            currentId = $(this).val();
            rowTitle = $('#title_' + currentId).html();
        });
        
        window.parent.setAttr(rowTitle, currentId, '{$myParamId}');
        window.parent.$('#{$dialog}').dialog('close');   

    });
    
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('data-grid', {
            url: $(this).attr('action'),
            data: $(this).serialize()
        });
        return false;
    });
";
Yii::app()->clientScript->registerScript('addBtn', $js, CClientScript::POS_READY);

echo "<div class='search-form'>";
    $this->beginWidget('CActiveForm', array(
        'method' => 'get',
        'htmlOptions' => array('class' => "form_{$this->id}")
    ));
    echo CHtml::label(AmcWm::t("msgsbase.core", 'Search'), $this->id);
    echo CHtml::textField("q", ((Yii::app()->request->getParam('q')) ? Yii::app()->request->getParam('q') : ''));
    echo CHtml::submitButton(AmcWm::t("amcBack", 'Search'));
    $this->endWidget();
echo "</div>";

echo "<div class='form'>";
$this->beginWidget('CActiveForm', array(
    'id' => Yii::app()->params["adminForm"],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'data-grid',
    'dataProvider' => $dataProvider,
    'columns' => $columns
));

$this->endWidget();
echo "</div>";
echo CHtml::Button(AmcWm::t("msgsbase.core", 'Add'), array('id' => 'addToForm'));
?>