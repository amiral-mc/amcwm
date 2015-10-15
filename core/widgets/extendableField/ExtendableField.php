<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ExtendableField extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ExtendableField extends Widget {

    /**
     * @var ActiveRecords the data model associated with this widget.
     */
    public $model = null;

    /**
     *
     * @var array extend attribute
     */
    public $extendAttribute = array();

    /**
     *
     * @var array field options array
     */
    public $fieldOptions = array();

    /**
     *
     * @var string field type defaut is textField
     */
    public $fieldType = 'textField';

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
        if (!isset($this->fieldOptions['translateOnly'])) {
            $this->fieldOptions['translateOnly'] = false;
        }
        parent::init();
    }

    /**
     * @todo add textArea dropdownlist .. etc to switch case 
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish($this->basePath . DIRECTORY_SEPARATOR . 'assets');
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.extendableField.js');
        $output = '<div id="' . $this->getId() . "_extendable_area" . '">';
        switch ($this->fieldType) {
            case 'textField':
                if ($this->model->hasAttribute($this->attribute)) {
                    $output .= CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
                }
                break;
        }
        if ($this->extendAttribute['isExtendable'] && isset($this->extendAttribute['listData']) && !$this->fieldOptions['translateOnly']) {
            $output .= CHtml::dropDownList("select_attribute_{$this->attribute}", "", $this->extendAttribute['listData'], array("class"=>"extra_selector"));
            $output .= '&nbsp;' . Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/add.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "add-extendable-icon"));
        }
        if (isset($this->extendAttribute['isExtendable']) && $this->extendAttribute['isExtendable']) {
            $output .= $this->renderList();
        } else {
            
        }
        $output .= "</div>";
        echo $output;
    }

    /**
     * Renders the attachmen list
     */
    protected function renderList() {
        $output = '<div id="' . $this->getId() . "_extendable_list" . '">';
        $items = array();
        $f = 0;
        $htmlOptions = $this->htmlOptions;
        if (isset($htmlOptions['id'])) {
            unset($htmlOptions['id']);
        }
        $options['itemsCount'] = array();
        $options['showSort'] = false;
        if (count($this->extendAttribute['data'])) {
            foreach ($this->extendAttribute['data'] as $attributeData) {
                if (!isset($items[$attributeData->name])) {
                    $items[$attributeData->name] = "";
                    $options['itemsCount'][$attributeData->name] = 0;
                }
                $items[$attributeData->name] .= '<div class="extra_row">';
                if ($this->fieldOptions['translateOnly']) {
                    $items[$attributeData->name] .= $attributeData->label;
                } else {
                    $items[$attributeData->name] .= CHtml::activeDropDownList($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]systemAttrbuiteId", $this->extendAttribute['listData'], array("class"=>"extra_selector"));
                }
                switch ($this->fieldType) {
                    case 'textField':
                        $htmlOptions['maxlength'] = $this->extendAttribute['struct']['length'];
                        $items[$attributeData->name] .= CHtml::activeTextField($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]value", $htmlOptions);
                        break;
                }
                if (!$this->fieldOptions['translateOnly']) {
                    if ($options['showSort']) {
                        $items[$attributeData->name] .= '&nbsp;' . CHtml::activeTextField($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]sort", array('maxlength' => 10, 'style' => 'width:30px'));
                        $items[$attributeData->name] .= '&nbsp;' . Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/up.gif", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "sort-extendable-icon", 'data-sort' => 'up'));
                        $items[$attributeData->name] .= '&nbsp;' . Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/down.gif", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "sort-extendable-icon", 'data-sort' => 'down'));
                    }
                    $items[$attributeData->name] .= '&nbsp;' . Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "delete-extendable-icon"));
                }
                $items[$attributeData->name] .= "</div>";
                $options['itemsCount'][$attributeData->name]++;
                $items[$attributeData->name] .= CHtml::activeHiddenField($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]id");
                $f++;
            }
        }
        if (isset($this->extendAttribute['inheritedAttributes'])) {
            foreach ($this->extendAttribute['inheritedAttributes'] as $inheritedAttribute) {
                $f = 0;
                foreach ($inheritedAttribute['data'] as $attributeData) {
                    if (!isset($items[$attributeData->name])) {
                        $items[$attributeData->name] = "";
                        $options['itemsCount'][$attributeData->name] = 0;
                    }
                    $items[$attributeData->name] .= "<div>";
                    if ($this->fieldOptions['translateOnly']) {
                        $items[$attributeData->name] .= $attributeData->label;
                    } else {
                        $items[$attributeData->name] .= CHtml::activeDropDownList($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]systemAttrbuiteId", $this->extendAttribute['listData'], array("class"=>"extra_selector"));
                    }
                    switch ($this->fieldType) {
                        case 'textField':
                            $items[$attributeData->name] .= CHtml::activeTextField($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]value", $htmlOptions);
                            break;
                    }
                    if (!$this->fieldOptions['translateOnly']) {
                        if ($options['showSort']) {
                            $items[$attributeData->name] .= '&nbsp;' . CHtml::activeTextField($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]sort", array('maxlength' => 10, 'style' => 'width:30px'));
                            $items[$attributeData->name] .= '&nbsp;' . Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/up.gif", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "sort-extendable-icon", 'data-sort' => 'up'));
                            $items[$attributeData->name] .= '&nbsp;' . Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/down.gif", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "sort-extendable-icon", 'data-sort' => 'down'));
                        }
                        $items[$attributeData->name] .= '&nbsp;' . Chtml::link(CHtml::image($this->baseScriptUrl . "/icons/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("class" => "delete-extendable-icon"));
                    }
                    $items[$attributeData->name] .= "</div>";

                    $options['itemsCount'][$attributeData->name]++;
                    $items[$attributeData->name] .= CHtml::activeHiddenField($attributeData, "[{$this->model->getClassName()}][{$attributeData->name}][{$f}]id");
                    $f++;
                }
            }
        }
        foreach ($this->extendAttribute['listNames'] as $attributeName) {
            $output .= '<div id="' . $this->getId() . "_extendable_area_{$attributeName}" . '">';
            if (isset($items[$attributeName])) {
                $output .= $items[$attributeName];
            }
            $output .= "</div>";
        }
        $options['baseUrl'] = $this->baseScriptUrl;
        $options['attribute'] = $this->attribute;
        $options['fieldType'] = $this->fieldType;
        $options['defaultGroup'] = $this->getId() . "_extendable_area_{$this->attribute}";
        $options['className'] = $this->model->getClassName();
        $options['className'] = $this->model->getClassName();
        $options['htmlOptions'] = $this->htmlOptions;
        $options['fieldOptions'] = $this->fieldOptions;
        $options['listNames'] = $this->extendAttribute['listNames'];
        $options['listData'] = $this->extendAttribute['listData'];
        if (!$this->fieldOptions['translateOnly']) {
            $jsCode = "$('#{$this->getId()}_extendable_area').extendableField(" . CJavaScript::encode($options) . ");";
            $cs = Yii::app()->getClientScript();
            $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        }
        $output .= "</div>";
        return $output;
    }

}