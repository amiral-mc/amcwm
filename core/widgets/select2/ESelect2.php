<?php

/**
 * Wrapper for ivaynberg jQuery select2 (https://github.com/ivaynberg/select2)
 * 
 * @author Anggiajuang Patria <anggiaj@gmail.com>
 * @link http://git.io/Mg_a-w
 * @license http://www.opensource.org/licenses/apache2.0.php
 */
class ESelect2 extends CInputWidget {

    /**
     * @var array select2 options
     */
    public $options = array();

    /**
     * Init list selection
     * @var array 
     */
    public $initSelection;

    /**
     * @var array CHtml::dropDownList $data param
     */
    public $data = array();

    /**
     * @var string html element selector
     */
    public $selector;

    /**
     * @var use select , if equal false then we will use input tag
     */
    public $useSelect = false;

    /**
     *
     * @var boolean if equal true then add the search keywards to the dropdown 
     */
    public $addingNoMatch = false;

    /**
     * @var array javascript event handlers
     */
    public $events = array();
    protected $defaultOptions = array();

    public function init() {
        //print_r($this->data);
        //die();
        $this->defaultOptions = array(
            'formatNoMatches' => 'js:function(){return "' . Yii::t('ESelect2.select2', 'No matches found') . '";}',
            'formatInputTooShort' => 'js:function(input,min){return "' . Yii::t('ESelect2.select2', 'Please enter {chars} more characters', array('{chars}' => '"+(min-input.length)+"')) . '";}',
            'formatInputTooLong' => 'js:function(input,max){return "' . Yii::t('ESelect2.select2', 'Please enter {chars} less characters', array('{chars}' => '"+(input.length-max)+"')) . '";}',
            'formatSelectionTooBig' => 'js:function(limit){return "' . Yii::t('ESelect2.select2', 'You can only select {count} items', array('{count}' => '"+limit+"')) . '";}',
            'formatLoadMore' => 'js:function(pageNumber){return "' . Yii::t('ESelect2.select2', 'Loading more results...') . '";}',
            'formatSearching' => 'js:function(){return "' . Yii::t('ESelect2.select2', 'Searching...') . '";}',
        );
        if ($this->addingNoMatch) {
            $this->defaultOptions['createSearchChoice'] = 'js:function(term, data) { if ($(data).filter(function() { return this.text.localeCompare(term)===0; }).length===0) {return {id:term, text:term};} }';
            $this->useSelect = false;
        }
        if (isset($this->options['ajax'])) {
            $this->useSelect = false;
            $this->data = array();
        }
    }

    public function run() {
        if ($this->selector == null) {
            list($this->name, $this->id) = $this->resolveNameId();
            $this->selector = '#' . $this->id;
            if (isset($this->htmlOptions['placeholder']))
                $this->options['placeholder'] = $this->htmlOptions['placeholder'];

            if (!isset($this->htmlOptions['multiple']) && $this->useSelect && isset($this->options['placeholder'])) {
                $this->htmlOptions['prompt'] = '';
            }

            if ($this->hasModel()) {
                $this->value = CHtml::resolveValue($this->model, $this->attribute);
                if ($this->useSelect) {
                    echo CHtml::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
                } else {
                    echo CHtml::activeHiddenField($this->model, $this->attribute, $this->htmlOptions);
                }
            } else {
                $this->htmlOptions['id'] = $this->id;
                if ($this->useSelect) {
                    echo CHtml::dropDownList($this->name, $this->value, $this->data, $this->htmlOptions);
                } else {
                    echo CHtml::hiddenField($this->name, $this->value, $this->htmlOptions);
                }
            }
            if (isset($this->options['multiple']) && $this->options['multiple']) {
                $this->options['data'] = array();
                if (!$this->useSelect) {
                    $data = CJSON::encode($this->initSelection);
//                    die($data);
                    $this->defaultOptions["initSelection"] = "js:function (element, callback) {
                        var data = {$data};
                        callback(data);
                    }";
                }
            } else {
                if (!isset($this->initSelection['id'])) {
                    $this->initSelection['id'] = $this->value;
                }
                if (!isset($this->initSelection['text'])) {
                    $this->initSelection['text'] = $this->value;
                }
                if (!$this->useSelect) {
                    if($this->initSelection['id'] === null){
                        $this->initSelection['id'] = '';
                    }
                    if($this->initSelection['text'] === null){
                        $this->initSelection['text'] = '';
                    }
                    $data = CJSON::encode($this->initSelection);
                    $this->defaultOptions["initSelection"] = "js:function (element, callback) {
                        var data = {$data};
                        callback(data);
                    }";
                }
            }
        }

        $bu = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets/');
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($bu . '/select2.css');

        if (YII_DEBUG)
            $cs->registerScriptFile($bu . '/select2.js');
        else
            $cs->registerScriptFile($bu . '/select2.min.js');
        $cs->registerScriptFile($bu . '/select2.js');
        if (!$this->useSelect) {
            $this->options['data'] = $this->data;
        }
        $options = CMap::mergeArray($this->defaultOptions, $this->options);
        if (isset($this->options['ajax'])) {
            unset($options['data']);
        }

        ob_start();
        echo "jQuery('{$this->selector}').select2(" . CJavaScript::encode($options) . ");";
        foreach ($this->events as $event => $handler)
            echo ".on('{$event}', " . CJavaScript::encode($handler) . ")";

        $cs->registerScript(__CLASS__ . '#' . $this->id, ob_get_clean() . ';');
    }

}
