<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AttachmentWidget extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttachmentWidget extends Widget {

    /**
     * @var ActiveRecords the data model associated with this widget.
     */
    public $model = null;

    /**
     *
     * @var array extra attach options  
     */
    protected $attachOptions = array();

    /**
     * @var array attachment container html attributes
     */
    public $htmlOptions = array();

    /**
     * @var string attribute name hold the current attachment list in the module
     */
    public $attribute;

    /**
     *
     * @var base url for the widget
     */
    protected $baseScriptUrl = null;

    /**
     * Initializes the menu widget.
     * This method mainly normalizes the {@link items} property.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = "attach_area";
        }
        if (!isset($this->attachOptions['translateOnly'])) {
            $this->attachOptions['translateOnly'] = false;
        }
        parent::init();
    }

    /**
     * set attach options
     * @param array $attachOptions
     */
    public function setAttachOptions($attachOptions) {
        foreach ($attachOptions as $key => $value) {
            $this->attachOptions[$key] = $value;
        }
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish($this->basePath . DIRECTORY_SEPARATOR . 'assets');
        $output = CHtml::openTag('div', $this->htmlOptions);
        if (!$this->attachOptions['translateOnly']) {
            $cs = Yii::app()->getClientScript();
            $cs->registerScriptFile($this->baseScriptUrl . '/attachmentManager.js');
            $output .= '<div class="attach_button">';
            $output .= CHtml::button(AmcWm::t("{$this->basePath}.core", "_open_file_manager_"), array("onclick" => '$("#' . $this->htmlOptions['id'] . '_dialog").dialog("open"); return false;'));
            $output .= '</div>';
        }
        $output .= '<div class="attach_list" id="' . $this->htmlOptions['id'] . '_list">';
        $output .= $this->renderList();
        $output .= '</div>';
        $output .=CHtml::closeTag("div");
        if (!$this->attachOptions['translateOnly']) {
            $this->renderDialog();
        }
        echo $output;
    }

    protected function renderDialog() {
        $url = Html::createUrl("/backend/uploads/default/index", array("op" => "attach", "dialog" => "{$this->htmlOptions['id']}_dialog"));
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => "{$this->htmlOptions['id']}_dialog",
            // additional javascript options for the dialog plugin
            'options' => array(
                'title' => AmcWm::t("{$this->basePath}.core", "_file_manager_"),
                'width' => 640,
                'height' => 450,
                'resizable' => false,
                'autoResize' => false,
                'autoOpen' => false,
                'iframe' => true,
                'modal' => true,
            ),
            'htmlOptions' => array("class" => "filemanager-wdg"),
        ));
        echo '<iframe class="filemanager-iframe" id="' . $this->htmlOptions['id'] . '_dialog_iframe" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" title="" src="' . $url . '"></iframe>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');
    }

    /**
     * @todo display attachemnt data grid and buttons to update the content of the attachemnt grid using ajax
     * Renders the attachmen list
     */
    protected function renderList() {
        $attribute = $this->attribute;
        $output = '<table border="0" cellpadding="2" cellspacing ="0">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>' . AmcWm::t("{$this->basePath}.core", "_title_") . '</th>';
        $output .= '<th>' . AmcWm::t("{$this->basePath}.core", "_description_") . '</th>';
        $output .= '<th>' . AmcWm::t("{$this->basePath}.core", "_link_") . '</th>';
        $output .= '<th>&nbsp;</th>';
        $output .= '</tr>';
        $output .= '<thead>';
        $items = array();
        $i = 0;
        foreach ($this->model->$attribute as $model) {
            $items[$i] = '<td valign="top" style="cursor: move;">';
            $items[$i] .= CHtml::activeTextField($model, "[{$i}]title", array("id" => "AttachmentTranslation_title_{$i}", "maxlength" => 100, "style" => "width:300px;"));
            $items[$i] .= CHtml::error($model, "[{$i}]title");
            $items[$i] .= '<div dir="ltr" style="overflow: hidden;height:30px;width:300px;">';
            $items[$i] .= $model->attach_url;
            $items[$i] .= '</div>';
            $items[$i] .= '</td>';
            $items[$i] .= '<td valign="top">';
            $items[$i] .= CHtml::activeTextArea($model, "[{$i}]description", array("id" => "AttachmentTranslation_description_{$i}", "style" => "width:200px;height:50px;"));
            $items[$i] .= CHtml::error($model, "[{$i}]description");
            $items[$i] .= '</td>';
            $items[$i] .= '<td valign="top">';
            $items[$i] .= CHtml::link('<img src="' . $this->baseScriptUrl . "/icons/link.png" . '" border="" />', $model->attach_url, array("target" => "_blank", "title" => $model->attach_url));
            $items[$i] .= '</td>';
            $items[$i] .= '<td valign="top">';
            if (!$this->attachOptions['translateOnly']) {
                $items[$i] .= Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "delete-attact-icon"));
            }
            $items[$i] .= CHtml::activeHiddenField($model, "[{$i}]attach_id", array("id" => "AttachmentTranslation_attach_id_{$i}"));
            $items[$i] .= CHtml::activeHiddenField($model, "[{$i}]attach_url", array("id" => "AttachmentTranslation_attach_url_{$i}"));
            $items[$i] .= CHtml::activeHiddenField($model, "[{$i}]content_type", array("id" => "AttachmentTranslation_content_type_{$i}"));
            $items[$i] .= '</td>';
            $i++;
        }
        $output.= $this->widget('zii.widgets.jui.CJuiSortable', array(
            'id' => $this->htmlOptions['id'] . '_dialog_attach_list',
            'tagName' => "tbody",
            'itemTemplate' => '<tr>{content}</tr>',
            'items' => $items,
            // additional javascript options for the JUI Sortable plugin
            'htmlOptions' => array(
                "cellpadding" => 2,
                "border" => "0",
                "cellspacing" => "0",
            ),
            'options' => array(
                'delay' => '300',
            ),
                ), true);
        $output .= '</table>';
        if (!$this->attachOptions['translateOnly']) {
            $options['baseUrl'] = $this->baseScriptUrl;
            $options['itemsCount'] = $i;
            $jsCode = "AttachmentManager.options = " . CJavaScript::encode($options) . ";";
            $jsCode .= "$('body').on('click', '#{$this->htmlOptions['id']}_dialog_attach_list .delete-attact-icon', function() { $(this).parent().parent().remove();});";
            $cs = Yii::app()->getClientScript();
            $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        }
        return $output;
    }

}