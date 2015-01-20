<?php $this->beginClip('form'); ?>
<div class="form page-border">
<?php
$query = sprintf('select article_detail from articles_translation where article_id=%d and content_lang= %s', AmcWm::app()->params['reservedContent']['contactUs'], Yii::app()->db->quoteValue(Yii::app()->getLanguage()));
echo Yii::app()->db->createCommand($query)->queryScalar();
?>

<p>
    <?php echo Yii::t('contact', 'Please feel free to contact us if you have any questions, comments.Use the following form emailer to contact us and we will reply you soon.'); ?>
</p>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'contact-form',
        'type' => 'horizontal',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary($model); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()) ?>
    <?php echo $form->dropDownListRow($model, 'contact', $model->contactsList, array('prompt' => AmcWm::t('zii', 'Not set'))); ?>
    <?php echo $form->textFieldRow($model, 'name'); ?>
    <?php echo $form->textFieldRow($model, 'email'); ?>
    <?php echo $form->textFieldRow($model, 'subject'); ?>
    <?php echo $form->textAreaRow($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
    <?php if (CCaptcha::checkRequirements()): ?>
        <div>            
            <?php echo $form->labelEx($model, 'verifyCode'); ?>
            <div>
                <?php $this->widget('CCaptcha', array('imageOptions' => array('height' => '45', 'border' => '0'), 'buttonType' => 'link')); ?>
                <?php echo $form->textField($model, 'verifyCode'); ?>
            </div>
            <div class="hint"><?php echo AmcWm::t('amcFront', 'Please enter the letters as they are shown in the image above.') ?></div>
            <?php echo $form->error($model, 'verifyCode'); ?>
        </div>
    <?php endif; ?>        
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => AmcWm::t("contact", 'contact_button'))); ?>                        
    </div>
    
    <?php $this->endWidget(); ?>

</div><!-- form -->
</div>
<?php $this->endClip('form'); ?>
<?php
$pageContent = $this->clips['form'];
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/site/contact'));
if (!$breadcrumbs) {
    $breadcrumbs[AmcWm::t("app", 'Contact Us')] = array('/site/contact');
}
$widgetImage = Data::getInstance()->getPageImage(null, Yii::app()->request->baseUrl . '/images/front/contact.jpg', null, null, '/site/contact');

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $pageContent,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("app", 'Contact Us'),
));
?>