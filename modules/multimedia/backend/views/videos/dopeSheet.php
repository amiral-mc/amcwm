<?php
$sheetModel = $sheetContentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
    AmcWm::t("msgsbase.core", "Videos") => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id),
    $contentModel->video_header => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id, 'id' => $contentModel->video_id),
);
$items = array();
$items[] = array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_video', 'image_id' => 'save');
if(!$sheetModel->isNewRecord){
    $items[] = array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/multimedia/videos/dopeSheetTranslate', 'gid' => $this->gallery->gallery_id, 'mmId' => $contentModel->video_id), 'id' => 'translate_dopsheet', 'image_id' => 'translate');
}
$items[] = array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id), 'id' => 'videos_list', 'image_id' => 'back');
$this->sectionName = AmcWm::t("msgsbase.core", "Dope Sheet");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $items,
));
?>
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

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => '<span class="required">*</span>')) ?>.</p>
    <?php
    $errors['length_hours'] = $sheetModel->getErrors('length_hours');
    if (!$errors['length_hours']) {
        unset($errors['length_hours']);
    }
    $errors['length_minutes'] = $sheetModel->getErrors('length_minutes');
    if (!$errors['length_minutes']) {
        unset($errors['length_minutes']);
    }
    $errors['length_seconds'] = $sheetModel->getErrors('length_seconds');
    if (!$errors['length_seconds']) {
        unset($errors['length_seconds']);
    }
    $sheetModel->clearErrors('length_hours');
    $sheetModel->clearErrors('length_minutes');
    $sheetModel->clearErrors('length_seconds');
    ?>
    <?php
    echo $form->errorSummary($sheetModel);
    $sheetModel->addErrors($errors);
    ?>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "General Options"); ?>:</legend>
            <div class="row">                       
                <span class="translated_label">
                    <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
                </span>
                :
                <span class="translated_org_item">
                    <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
                </span>
            </div>              
            <?php echo $form->checkBox($sheetModel, 'published'); ?>
            <?php echo $form->labelEx($sheetModel, 'published', array("style" => 'display:inline;')); ?>
        </fieldset>
    </div>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Event Options"); ?>:</legend>
            <div class="row">
                <?php echo $form->labelEx($sheetContentModel, 'reporter'); ?>
                <?php echo $form->textField($sheetContentModel, 'reporter', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetContentModel, 'reporter'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetContentModel, 'source'); ?>
                <?php echo $form->textField($sheetContentModel, 'source', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetContentModel, 'source'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetContentModel, 'location'); ?>
                <?php echo $form->textField($sheetContentModel, 'location', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetContentModel, 'location'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetModel, 'event_date'); ?>
                <?php
                $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                    'model' => $sheetModel,
                    'attribute' => 'event_date',
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
                        'value' => ($sheetModel->event_date) ? date("Y-m-d H:i", strtotime($sheetModel->event_date)) : NULL,
                    )
                ));
                ?>
                <?php echo $form->error($sheetModel, 'event_date'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetContentModel, 'sound'); ?>
                <?php echo $form->textField($sheetContentModel, 'sound', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetContentModel, 'sound'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetModel, 'timeLength'); ?>
                <?php echo $form->textField($sheetModel, 'length_hours', array('size' => 3, 'maxlength' => 2, 'style' => 'width:25px;')); ?>
                :
                <?php echo $form->textField($sheetModel, 'length_minutes', array('size' => 3, 'maxlength' => 2, 'style' => 'width:25px;')); ?>
                :
                <?php echo $form->textField($sheetModel, 'length_seconds', array('size' => 3, 'maxlength' => 2, 'style' => 'width:25px;')); ?>
                <?php echo $form->error($sheetModel, 'timeLength'); ?>
            </div>
        </fieldset>
    </div>
    <div>
        <div class="row">
            <fieldset>
                <legend><?php echo AmcWm::t("msgsbase.core", "Shots"); ?>:</legend>
                <div>
                    <table border="0" cellpadding="2" cellspasing ="0" id="shootsGrid">
                        <tr>
                            <th>&nbsp;</th>
                            <th><?php echo AmcWm::t("msgsbase.core", "Shot Type"); ?></th>
                            <th><?php echo AmcWm::t("msgsbase.core", "Shot Description"); ?></th>
                            <th><?php echo AmcWm::t("msgsbase.core", "Sound"); ?></th>
                            <th><?php echo AmcWm::t("msgsbase.core", 'Sound length mm:ss'); ?></th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php foreach ($sheetModel->shots as $i => $shot): ?>
                            <?php if ($shot->getCurrent()->content_lang == $sheetContentModel->content_lang): ?>
                                <tr id="shotRow<?php echo $i ?>">
                                    <td><?php echo $form->hiddenField($shot, "[$i]shot_id"); ?></td>
                                    <td valign="top">
                                        <?php echo $form->dropDownList($shot, "[$i]type_id", DopeSheet::getShotsTypes(), array('prompt' => '-')); ?>
                                        <?php echo $form->error($shot, "[$i]type_id"); ?>
                                    </td>
                                    <td valign="top">
                                        <?php echo $form->textField($shot->getCurrent(), "[$i]description", array('size' => 20, 'maxlength' => 100, 'style' => 'width:220px;')) ?>
                                        <?php echo $form->error($shot->getCurrent(), "[$i]description"); ?>
                                    </td>
                                    <td valign="top">
                                        <?php echo $form->textField($shot->getCurrent(), "[$i]sound", array('size' => 10, 'maxlength' => 45, 'style' => 'width:120px;')) ?>
                                        <?php echo $form->error($shot->getCurrent(), "[$i]sound"); ?>
                                    </td>
                                    <td valign="top">
                                        <?php echo $form->textField($shot, "[$i]length_minutes", array('size' => 3, 'maxlength' => 2, 'style' => 'width:25px;')) ?> :
                                        <?php echo $form->textField($shot, "[$i]length_seconds", array('size' => 3, 'maxlength' => 2, 'style' => 'width:25px;')) ?>
                                        <?php echo $form->error($shot, "[$i]length_minutes"); ?>
                                        <?php echo $form->error($shot, "[$i]length_seconds"); ?>
                                    </td>
                                    <td valign="top">
                                        <?php echo Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("id" => "shotRowLink{$i}", "onclick" => "shotAction.removeShot(this.id)", "class" => "btn_label")) ?>
                                    </td>
                                </tr>                        
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                    <div style="text-align: right;">
                        <?php
                        echo Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add.png", "", array("border" => 0, "align" => 'absmiddle')) . "&nbsp;" . AmcWm::t("msgsbase.core", "Add new shot"), "javascript:void(0);", array("id" => "addNewShot", "class" => "btn_label"));
                        ?>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div>
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Story"); ?>:</legend>
            <div class="row">
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $sheetContentModel,
                    'attribute' => 'story',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:600px;'
                    ),
                        )
                );
                ?>
                <?php echo $form->error($sheetContentModel, 'story'); ?>
            </div>
        </fieldset>
    </div>
    <?php
    $removedShots = Yii::app()->request->getParam("DopeSheetShotsRemoved", array());
    if (count($removedShots)) {
        foreach ($removedShots as $removedShot) {
            echo CHtml::hiddenField('DopeSheetShotsRemoved[]', $removedShot, array('id' => "DopeSheetShotsRemoved_{$removedShot}"));
        }
    }
    ?>
    <?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$count = count($sheetModel->shots);
Yii::app()->clientScript->registerScript('addNewShot', "
    
$('#addNewShot').click(function(){    
    shotAction.addShot();    
    return false;
});

shotAction = {};
shotAction.shotTypes = " . CJSON::encode(DopeSheet::getShotsTypes(false)) . ";
shotAction.addShot = function(){    
    lastRow = ($('#shootsGrid tr').length -1);
    var shotRow = '<tr id=\"shotRow'+lastRow+'\">';
    shotRow += '<td><input name=\"DopeSheetShots['+lastRow+'][shot_id]\" id=\"DopeSheetShots_'+lastRow+'_shot_id\" type=\"hidden\" value=\"\"/></td>';
    shotRow += '<td>';
    shotRow += '<select name=\"DopeSheetShots['+lastRow+'][type_id]\" id=\"DopeSheetShots_'+lastRow+'_type_id\">';
    shotRow += '<option value=\"\">-</option>';
    for(var shotRef =0 ; shotRef < shotAction.shotTypes.length ; shotRef++){
        shotRow += '<option value=\"'+shotAction.shotTypes[shotRef].type_id+'\">'+shotAction.shotTypes[shotRef].type+'</option>';
    }
    shotRow += '</select>';
    shotRow += '</td>';
    shotRow += '<td><input name=\"DopeSheetShotsTranslation['+lastRow+'][description]\" id=\"DopeSheetShotsTranslation_'+lastRow+'_description\" type=\"text\" value=\"\" style=\"width:220px;\" size=\"20\" maxlength=\"100\" /></td>';
    shotRow += '<td><input name=\"DopeSheetShotsTranslation['+lastRow+'][sound]\" id=\"DopeSheetShotsTranslation_'+lastRow+'_sound\" type=\"text\" value=\"\" style=\"width:120px;\" size=\"20\" maxlength=\"45\" /></td>';
    shotRow += '<td><input name=\"DopeSheetShots['+lastRow+'][length_minutes]\" id=\"DopeSheetShots_'+lastRow+'_length_minutes\" type=\"text\" value=\"\" style=\"width:25px;\" size=\"3\" maxlength=\"2\" /> : ';
    shotRow += '<input name=\"DopeSheetShots['+lastRow+'][length_seconds]\" id=\"DopeSheetShots_'+lastRow+'_length_seconds\" type=\"text\" value=\"\" style=\"width:25px;\" size=\"3\" maxlength=\"2\" /></td>';
    shotRow +='<td><a id=\"shotRowLink'+lastRow+'\" onclick=\"shotAction.removeShot(this.id)\" class=\"btn_label\" href=\"javascript:void(0);\"><img border=\"0\" align=\"absmiddle\" src=\"" . Yii::app()->baseUrl . "/images/remove.png\" alt=\"\" /></td>';
    shotRow += '</tr>';
    $('#shootsGrid').append(shotRow);
}
shotAction.removeShot = function(shotRowId){
    removeNumber = shotRowId.substring(11);
    optionId = parseInt($('#DopeSheetShots_'+removeNumber+'_shot_id').val());
    if(!isNaN(optionId) && optionId){
       $('#" . Yii::app()->params["adminForm"] . "').append('<input type=\"hidden\" name=\"DopeSheetShotsRemoved[]\" value=\"'+optionId+'\" />');
    }        
    $('#shotRow'+removeNumber).html('<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>');
    $('#shotRow'+removeNumber).hide();
}
");
?>