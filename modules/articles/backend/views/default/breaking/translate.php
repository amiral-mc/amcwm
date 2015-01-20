<?php
$imagesInfo = $this->getModule()->appModule->mediaPaths;
$model = $contentModel->getParentContent();
$sectionTree = Sections::getSectionTree($model->section_id);
$formId = Yii::app()->params["adminForm"];
$module = $this->module->appModule->currentVirtual;
$options = $this->module->appModule->options;
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles") => array('/backend/articles/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
//$this->sectionName = AmcWm::t("msgsbase.core", "Update News");
$this->sectionName = $contentModel->article_header;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_article', 'image_id' => 'save'),
        array('label' => AmcWm::t("msgsbase.breaking", 'Details'), 'url' => array('/backend/articles/default/more', 'id' => $model->article_id, 'action'=>'translate'), 'id' => 'news_comments', 'image_id' => 'articles'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    /**
     * @todo fix the client side validation in the tinyMCE editor.
     */
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
    ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <?php echo $form->errorSummary(array_merge(array($model, $translatedModel), $translatedModel->titles)); ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "General Option"); ?>:</legend>
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
            <?php echo CHtml::dropDownList("tlang", $translatedModel->content_lang, $this->getTranslationLanguages(), array("onchange" => "FormActions.translationChange('$translateRoute', " . CJSON::encode($actionParams) . ");")); ?>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Published'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->published) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>           
        </div>
    </fieldset>    

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Details"); ?>:</legend>             
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'article_header'); ?>
            <?php echo $form->textField($translatedModel, 'article_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($translatedModel, 'article_header'); ?>
        </div>       
        <span class="translated_label"><?php echo AmcWm::t("msgsbase.news", 'Source'); ?></span>:
        <span class="translated_org_item">
            <?php
            if ($model->news->source) {
                echo $model->news->source->getCurrent()->source;
            } else {
                echo Yii::t('zii', 'Not set');
            }
            ?>
        </span>        
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Section'); ?></span>:
            <span class="translated_org_item">
                <?php
                $sectionName = Sections::drawSectionPath($model->section_id);
                if ($sectionName) {
                    echo $sectionName;
                } else {
                    echo Yii::t('zii', 'Not set');
                }
                ?>
            </span>
        </div>            
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Country'); ?></span>:
            <span class="translated_org_item">
                <?php echo $model->countryCode->getCountryName() ?>
            </span>
        </div>     
            
        <div class="row">
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Publish Date'); ?></span>:
                <span class="translated_org_item">
                    <?php echo Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->publish_date); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Expire Date'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    $expireDate = ($model->expire_date) ? Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->expire_date) : AmcWm::t("msgsbase.core", "No expiry date");
                    echo $expireDate;
                    ?>
                </span>
            </div>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->    