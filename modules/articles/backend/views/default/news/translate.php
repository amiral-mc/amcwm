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
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
$editors = null;
if (count($model->news->editors)) {
    foreach ($model->news->editors as $editor) {
        $editors .= "<br />{$editor->editor->person->getCurrent()->name}";
    }
}
?>

<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    /**
     * @todo fix the client side validation in the tinyMCE editor.
     */
    $form = $this->beginWidget('Form', array(
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
    <?php
    $models[] = $translatedModel;
    foreach ($translatedModel->attachment as $attachmentModel) {
        $models[] = $attachmentModel;
    }
    foreach ($translatedModel->titles as $title) {
        $models[] = $title;
    }
    echo $form->errorSummary($models);
    ?>
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
            -
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Archive'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->archive) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>
            -
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'In Ticker'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->in_ticker) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>
            <!--            --->
            <span class="translated_label"><?php //echo AmcWm::t("msgsbase.core", 'In Spot');              ?></span>
            <span class="translated_org_item">
                <?php
                if ($model->in_spot) {
                    //echo AmcWm::t("amcFront", "Yes");
                } else {
                    //echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>
            <?php if ($options[$module]['default']['check']['addToBreaking']): ?>
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.news", 'Breaking News'); ?></span>
                <span class="translated_org_item">
                    <?php
                    if ($model->news->is_breaking) {
                        echo AmcWm::t("amcFront", "Yes");
                    } else {
                        echo AmcWm::t("amcFront", "No");
                    }
                    ?>
                </span>
            <?php endif; ?>
        </div>
        <?php if ($options['default']['check']['addToSlider']): ?>
            <div class="row" style="padding-top:5px;padding-bottom: 5px;">            
                <?php
                $drawSliderImage = NULL;
                if ($model->article_id && $model->in_slider) {
                    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['slider']['path'] . "/" . $model->article_id . "." . $model->in_slider))) {
                        $drawSliderImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['slider']['path'] . "/" . $model->article_id . "." . $model->in_slider . "?" . time(), "", array("class" => "image", "width" => "200")) . '</div>';
                    }
                }
                ?>
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'In Slider'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    if ($model->in_slider) {
                        echo AmcWm::t("amcFront", "Yes");
                        echo $drawSliderImage;
                    } else {
                        echo AmcWm::t("amcFront", "No");
                    }
                    ?>
                </span>                     
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <?php
        $drawImage = NULL;
        if ($model->article_id && $model->thumb) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['list']['path'] . "/" . $model->article_id . "." . $model->thumb))) {
                $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['list']['path'] . "/" . $model->article_id . "." . $model->thumb . "?" . time(), "", array("class" => "image",)) . '</div>';
            }
        }
        ?>
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?>:</legend>       
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Image File'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($drawImage) {
                    echo AmcWm::t("amcFront", "Yes");
                    echo $drawImage;
                } else {
                    echo Yii::t('zii', 'Not set');
                }
                ?>
            </span>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Details"); ?>:</legend>             
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'article_pri_header'); ?>
            <?php echo $form->textField($translatedModel, 'article_pri_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($translatedModel, 'article_pri_header'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'article_header'); ?>
            <?php echo $form->textField($translatedModel, 'article_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($translatedModel, 'article_header'); ?>
        </div>
        <div class="row">
            <?php
            $this->widget('EditMulti', array(
                'id' => 'ArticlesTitles',
                'modelName' => 'ArticlesTitles',
                'data' => $translatedModel->titles,
                'title' => AmcWm::t("msgsbase.core", "Add new title"),
                'elements' => array(
                    'title' => array(
                        'type' => 'text',
                        'maxlength' => 500,
                        'size' => 30,
                    ),
                ),
                'form' => $form,
                    )
            );
            ?>
        </div>
        <span class="translated_label"><?php echo AmcWm::t("msgsbase.news", 'Source'); ?></span>
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
            <?php echo $form->labelEx($translatedModel, 'article_detail'); ?>
            <?php echo $form->error($translatedModel, 'article_detail'); ?>
            <?php echo $form->richTextField($translatedModel, 'article_detail', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>           
        </div>       
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
                <?php
                if ($model->country_code) {
                    echo $model->countryCode->getCountryName();
                } else {
                    echo Yii::t('zii', 'Not set');
                }
                ?>
            </span>
        </div>     

        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.news", 'Editors'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($editors) {
                    echo $editors;
                } else {
                    echo Yii::t('zii', 'Not set');
                }
                ?>
            </span>
        </div>
        <?php if ($options['default']['check']['addToInfocus']): ?>
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'In Focus File'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    $infocusName = $this->getInfocucName($model->infocusId);
                    if ($infocusName) {
                        echo $infocusName;
                    } else {
                        echo Yii::t('zii', 'Not set');
                    }
                    ?>
                </span>
            </div>   
        <?php endif ?>
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

    <fieldset>
        <legend><?php echo AmcWm::t("amcBack", "Tags"); ?>:</legend>
        <div class="row">
            <?php
            $this->widget('Keywards', array(
                'model' => $translatedModel,
                'attribute' => "tags[]",
//                    'name' => "tags",
                'values' => $translatedModel->tags,
                'formId' => $formId,
                'container' => "keywordItems",
                'delimiter' => Yii::app()->params["limits"]["delimiter"],
                'elements' => Yii::app()->params["limits"]["elements"], // keyword boxs count
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], // words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Files"); ?>:</legend>
        <div class="row">
            <?php echo $form->attachmentField($translatedModel, 'attachment', array("id" => "attachment_area", 'attachOptions' => array('translateOnly' => true))); ?>
        </div>     
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->    