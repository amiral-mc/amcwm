<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * DropDownSwitcher extension, used to generate image switcher dropdown example language switcher
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DropDownSwitcher extends Widget {

    /**
     * Items array used to generate dropdown list
     * @var array 
     */
    public $items;

    /**
     * Submit label name
     * @var string  
     */
    public $submitLabel = "Select";

    /**
     *
     * @var string the selected value 
     */
    public $selected;

    /**
     *
     * @var string select dropdown name 
     */
    public $selecteName = "option";

    /**
     * The route used to generate the link for every item in the list
     * @var string|array 
     */
    public $switcherRouteAction = null;
    
    public $ajaxAction = array('targetDiv'=> null);

    /**
     * @var array HTML attributes for switcher container tag
     */
    public $htmlOptions = array();

    /**
     * @var array HTML attributes for the select container tag
     */
    public $selectHtmlOptions = array();

    /**
     * @var boolean use current WebApplication css file if equal false 
     * otherwise the extension will generate css from the assest folder
     */
    public $useMyCss = false;

    /**
     * @var array the initial JavaScript options that should be passed to the plugin.
     */
    private $_options = array(
        'imagePath' => null,
        'imageClass' => 'image',
        'switcherAction' => null,
        'dropDownClass' => 'dropdown',
        'switcherValue' => '_switcher_value_',
    );

    /**
     * Initializes the menu widget.
     * This method mainly normalizes the {@link items} property.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = 'dropdownSelect';
        parent::init();
    }

    /**
     * sets the initial JavaScript options that should be passed to the plugin.
     * @param array $options
     */
    public function setOptions($options) {
        $this->_options = CMap::mergeArray($this->_options, $options);
    }

    /**
     * Render the widget.
     */
    public function run() {
        $cs = Yii::app()->getClientScript();
        $baseScriptUrl = Yii::app()->getAssetManager()->publish($this->basePath . DIRECTORY_SEPARATOR . 'assets', 0, -1, $this->forcePublish);
        if ($this->useMyCss) {
            $cs->registerCssFile($baseScriptUrl . '/style.css');
        }
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseScriptUrl . '/jquery.dropDownSwitcher.js');
        
        $output = CHtml::openTag("div", $this->htmlOptions);
        $params = array();
        if (is_array($this->switcherRouteAction)) {
            $params = $this->switcherRouteAction;
            $route = array_shift($params);
        } else {
            $route = $this->switcherRouteAction;
        }
        if($this->_options['switcherValue'])
            $params[$this->selecteName] = $this->_options['switcherValue'];
        
        if (!isset($this->_options['switcherAction'])) {
            $this->_options['switcherAction'] = Html::createUrl($route, $params);
        }
        if (isset($this->_options['imagePath'])) {
            $this->_options['imagePath'] = AmcWm::app()->request->baseUrl . $this->_options['imagePath'];
        }
        
        $jsCode = "";
        if($this->ajaxAction['targetDiv']){
            $jsCode .= "
                $('#{$this->selecteName}').change(function(){
                    jQuery.ajax({
                        'url':'{$this->_options['switcherAction']}',
                        'data':{'city':$('#{$this->selecteName}').val()},
                        'type':'get',
                        'error':function(jqXHR, textStatus, errorThrown){alert(errorThrown)},
                        'success':function(data){
                            // data will contain the xml data passed by the controller
                            if (data){
                                $('#{$this->ajaxAction['targetDiv']}').html(data);
                            } 
                        } ,'cache':false});
                });  
                $('#frm-{$this->htmlOptions['id']}').submit(function(){return false;});
            ";
            $this->_options['redirectOnClick'] = false;
        }
        $jsCode .= "$('#{$this->htmlOptions['id']}').dropDownSwitcher(" . CJavaScript::encode($this->_options) . ");";
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        
        $output .= '<form action="' . $this->_options['switcherAction'] . '" id="frm-'.$this->htmlOptions['id'].'">';
        $output .= CHtml::dropDownList($this->selecteName, $this->selected, $this->items, $this->selectHtmlOptions);
        $output .= '<input value="' . $this->submitLabel . '" type="submit" />';
        $output .= '</form>';
        $output .= CHtml::closeTag("div");
        echo $output;
    }

}