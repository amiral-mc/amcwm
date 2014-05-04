<?php
$sheetModel = $sheetContentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
    AmcWm::t("msgsbase.core", "Videos") => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id),
    $contentModel->video_header => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id, 'id' => $contentModel->video_id),
);

$this->sectionName = AmcWm::t("msgsbase.core", "Dope Sheet");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_video', 'image_id' => 'save'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id), 'id' => 'videos_list', 'image_id' => 'back'),
    ),
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

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => '<span class="required">*</span>')) ?>.</p>    
    <?php echo $form->errorSummary($sheetTranslatedModel); ?>
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
            <div class="row">
                <?php
                $actionParams = $this->getActionParams();
                if (array_key_exists('tlang', $actionParams)) {
                    unset($actionParams['tlang']);
                }
                $translateRoute = Html::createUrl($this->getRoute());
                ?> 
                <?php echo CHtml::label(AmcWm::t("amcTools", "Translate To"), "tlang") ?>
                <?php echo CHtml::dropDownList("tlang", $sheetTranslatedModel->content_lang, $this->getTranslationLanguages(), array("onchange" => "FormActions.translationChange('$translateRoute', " . CJSON::encode($actionParams) . ");")); ?>
            </div>
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Published'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($sheetModel->published) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>            
        </fieldset>
    </div>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Event Options"); ?>:</legend>            
            <div class="row">
                <?php echo $form->labelEx($sheetTranslatedModel, 'reporter'); ?>
                <?php echo $form->textField($sheetTranslatedModel, 'reporter', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetTranslatedModel, 'reporter'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetTranslatedModel, 'source'); ?>
                <?php echo $form->textField($sheetTranslatedModel, 'source', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetTranslatedModel, 'source'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetTranslatedModel, 'location'); ?>
                <?php echo $form->textField($sheetTranslatedModel, 'location', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetTranslatedModel, 'location'); ?>
            </div>
             <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Event Date'); ?></span>:
                <span class="translated_org_item"><?php echo Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $sheetModel->event_date); ?>
                </span>
            </div>
            <div class="row">
                <?php echo $form->labelEx($sheetTranslatedModel, 'sound'); ?>
                <?php echo $form->textField($sheetTranslatedModel, 'sound', array('size' => 60, 'maxlength' => 150)); ?>
                <?php echo $form->error($sheetTranslatedModel, 'sound'); ?>
            </div>
                        <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Time length hh:mm:ss'); ?></span>:
                <span class="translated_org_item"><?php echo $sheetModel->timeLength; ?>
                </span>
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
                        <?php
                        echo count($sheetModel->shots);
                        
                        ?>
                            <tr id="shotRow<?php echo $i ?>">
                                <td><?php echo $form->hiddenField($shot, "[$shot->shot_id]shot_id"); ?></td>
                                <td valign="top">
                                    <?php echo $shot->type->type; ?>
                                </td>
                                <td valign="top">
                                    <?php echo $form->textField($shot->getTranslated($sheetTranslatedModel->content_lang), "[$shot->shot_id]description", array('size' => 20, 'maxlength' => 45, 'style' => 'width:220px;')) ?>
                                    <?php echo $form->error($shot->getTranslated($sheetTranslatedModel->content_lang), "[$shot->shot_id]description"); ?>
                                </td>
                                <td valign="top">
                                    <?php echo $form->textField($shot->getTranslated($sheetTranslatedModel->content_lang), "[$shot->shot_id]sound", array('size' => 10, 'maxlength' => 45, 'style' => 'width:120px;')) ?>
                                    <?php echo $form->error($shot->getTranslated($sheetTranslatedModel->content_lang), "[$shot->shot_id]sound"); ?>
                                </td>
                                <td valign="top">
                                    <?php echo $shot->length_minutes ?> :
                                    <?php echo $shot->length_seconds ?>
                                </td>
                            </tr>                        
                        <?php endforeach; ?>
                    </table>
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
                    'model' => $sheetTranslatedModel,
                    'attribute' => 'story',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:600px;'
                    ),
                        )
                );
                ?>
                <?php echo $form->error($sheetTranslatedModel, 'story'); ?>
            </div>
        </fieldset>
    </div>    
    <?php $this->endWidget(); ?>

</div><!-- form -->