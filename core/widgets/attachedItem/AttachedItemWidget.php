<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AttachedItemWidget extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttachedItemWidget extends Widget {

    /**
     * @var ActiveRecords the data model associated with this widget.
     */
    public $model = null;

    /**
     * default value to initialize the boxes
     * @var array
     */
    public $defaultValueData = array('id' => null, 'text' => null);

    /**
     * hidden element name 
     * @var string
     */
    public $elementName = null;

    /**
     *
     * @var array extra attached options  
     */
    protected $attachedOptions = array();

    /**
     * param
     * @var string
     */
    public $param = null;

    /**
     * @var array attachedment container html attributes
     */
    public $htmlOptions = array();

    /**
     * @var string attribute name hold the current attachedment list in the module
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

        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish($this->basePath . DIRECTORY_SEPARATOR . 'assets', true, -1, $this->forcePublish);

        $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = "attached_area";
        }
        if (!isset($this->attachedOptions['translateOnly'])) {
            $this->attachedOptions['translateOnly'] = false;
        }

        parent::init();
    }

    /**
     * set attached options
     * @param array $attachedOptions
     */
    public function setAttachOptions($attachedOptions) {
        foreach ($attachedOptions as $key => $value) {
            $this->attachedOptions[$key] = $value;
        }
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $output = "";
        $myParamId = 'MenuItemsParams_' . $this->param['component_id'] . '_' . $this->param['param_id'];
        $paramName = "MenuItemsParams[{$this->param['component_id']}][{$this->param['param_id']}]";

        $output .= Chtml::radioButton("MenuItemsChk", (isset($this->defaultValueData['id'])?true:false), array('id' => "rd_" . $myParamId, "onclick" => '$("#' . $this->htmlOptions['id'] . '_dialog").dialog("open"); return this.checked;'));

        $label = $this->param['label'];
        $defTxt = (isset($this->defaultValueData['text']) ? " ( <b>{$this->defaultValueData['text']}</b> - " . CHtml::image($this->baseScriptUrl . '/icons/edit.png', '', array('align' => 'absmiddle', 'style' => 'height:16px;')) . " ) " : '');
        $label .= "&nbsp;<span id='my_{$myParamId}' class='popUpPreview'>" . $defTxt . "</span>";

        $output .= Chtml::label($label, "rd_" . $myParamId, array("class" => 'normal_label popUpSelector'));
//        $output .= "&nbsp;&nbsp;" . CHtml::button(AmcWm::t("{$this->basePath}.core", "_open_attached_list_"), array("class" => "popUpSelector", "onclick" => '$("#' . $this->htmlOptions['id'] . '_dialog").dialog("open"); return false;'));
        $output .= CHtml::hiddenField($paramName, (isset($this->defaultValueData['id']) ? $this->defaultValueData['id'] : null), array("class" => "popUpHidden"));

        $this->renderDialog();

        echo $output;
    }

    protected function renderDialog() {
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => "{$this->htmlOptions['id']}_dialog",
            'options' => array(
                'title' => AmcWm::t("{$this->basePath}.core", "_open_attached_list_"),
                'width' => 640,
                'height' => 500,
                'resizable' => false,
                'autoResize' => false,
                'autoOpen' => false,
                'iframe' => true,
                'modal' => true,
            ),
            'htmlOptions' => array("class" => "filemanager-wdg"),
        ));
        $url = Html::createUrl('/backend/menus/default/ajax', array('do' => 'componentParam', 'pid' => $this->param['param_id'], 'cid' => $this->param['component_id'], "dialog" => "{$this->htmlOptions['id']}_dialog", 'slctd' => (isset($this->defaultValueData['id']) ? $this->defaultValueData['id'] : '')));
        echo '<iframe class="filemanager-iframe" id="' . $this->htmlOptions['id'] . '_dialog_iframe" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" title="" src="' . $url . '"></iframe>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');
    }

    /**
     * @todo display attachedemnt data grid and buttons to update the content of the attachedemnt grid using ajax
     * Renders the attachedmen list
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
            $items[$i] .= $model->attached_url;
            $items[$i] .= '</div>';
            $items[$i] .= '</td>';
            $items[$i] .= '<td valign="top">';
            $items[$i] .= CHtml::activeTextArea($model, "[{$i}]description", array("id" => "AttachmentTranslation_description_{$i}", "style" => "width:200px;height:50px;"));
            $items[$i] .= CHtml::error($model, "[{$i}]description");
            $items[$i] .= '</td>';
            $items[$i] .= '<td valign="top">';
            $items[$i] .= CHtml::link('<img src="' . $this->baseScriptUrl . "/icons/link.png" . '" border="" />', $model->attached_url, array("target" => "_blank", "title" => $model->attached_url));
            $items[$i] .= '</td>';
            $items[$i] .= '<td valign="top">';
            if (!$this->attachedOptions['translateOnly']) {
                $items[$i] .= Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "delete-attact-icon"));
            }
            $items[$i] .= CHtml::activeHiddenField($model, "[{$i}]attached_id", array("id" => "AttachmentTranslation_attached_id_{$i}"));
            $items[$i] .= CHtml::activeHiddenField($model, "[{$i}]attached_url", array("id" => "AttachmentTranslation_attached_url_{$i}"));
            $items[$i] .= CHtml::activeHiddenField($model, "[{$i}]content_type", array("id" => "AttachmentTranslation_content_type_{$i}"));
            $items[$i] .= '</td>';
            $i++;
        }
        $output.= $this->widget('zii.widgets.jui.CJuiSortable', array(
            'id' => $this->htmlOptions['id'] . '_dialog_attached_list',
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
        if (!$this->attachedOptions['translateOnly']) {
            $options['baseUrl'] = $this->baseScriptUrl;
            $options['itemsCount'] = $i;
            $jsCode = "AttachmentManager.options = " . CJavaScript::encode($options) . ";";
            $jsCode .= "$('body').on('click', '#{$this->htmlOptions['id']}_dialog_attached_list .delete-attact-icon', function() { $(this).parent().parent().remove();});";
            $cs = Yii::app()->getClientScript();
            $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        }
        return $output;
    }

}