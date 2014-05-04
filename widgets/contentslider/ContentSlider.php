<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ContentSlider extends CWidget {

    /**
     * @var array list of new sticker items.
     */
    public $items = array();

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * @var boolean whether the news items should be HTML-encoded. Defaults to true.
     */
    public $encodeItem = false;

    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    public $baseScriptUrl;

    /**
     * news class name
     * @var string 
     */
    public $className = 'slider-wrapper';

    /**
     * @var title css class 
     */
    public $titleClass = "slider-title";

    /**
     * @var title css class 
     */
    public $sliderClass = "slider";

    /**
     * @var item css class 
     */
    public $itemOddClass = "slider-odd-item";

    /**
     * @var item css class 
     */
    public $itemEvenClass = "slider-even-item";

    /**
     *
     * @var string Slider title 
     */
    public $title = null;

    /**
     * @var boolean weather the custom controllers displayed or not
     */
    public $customControllers = false;

    /**
     * @var  boolean weather the slider is  ticker or not 
     */
    public $isTicker = false;
    public $isSlider = false;

    /**
     * @var array the initial JavaScript options that should be passed to the plugin.
     */
    protected $options = array(
        "autoDelay" => 0
        , "captions" => false
        , "controls" => false
        , "mode" => 'fade'
        , "auto" => true
        , "autoHover" => true
    );

    public function setOptions($options){
        $this->options = array_merge($options, $this->options);
    }
    /**
     * Initializes the scroller widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = $this->className;
        if (!isset($this->options['prevText'])) {
            $this->options['prevText'] = "";
        }
        if (!isset($this->options['nextText'])) {
            $this->options['nextText'] = "";
        }
        $assetsFolder = "";
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.contentslider.assets'));
            $this->baseScriptUrl = $assetsFolder . "/contentslider";
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.bxSlider.min.js');
        //$cs->registerScriptFile($this->baseScriptUrl . '/jquery.easing.js');
        
        if ($this->isTicker == true) {
            $this->customControllers = false;
            $this->options['ticker'] = true;
            unset($this->options['mode']);
            unset($this->options['auto']);
            unset($this->options['controls']);
        } else {
            if ($this->isSlider == false) {
                //$this->options['auto'];
                if (!isset($this->options['mode'])) {
                    $this->options['mode'] = 'fade';
                }
                if (!isset($this->options['autoHover'])) {
                    $this->options['autoHover'] = true;
                }
            }
        }
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $output = "";
        if (count($this->items)) {
            $cs = Yii::app()->getClientScript();
            $cs->registerCssFile($this->baseScriptUrl . '/style.css', 'screen');

            $controlsOptions = array('class' => 'slider-controls');
            $controlsOptionsWrapper = array('id' => $this->htmlOptions['id'] . '-slider-controls-wrapper');
            $sliderOptions = array('id' => $this->htmlOptions['id'] . '-slider', 'class' => $this->sliderClass);
            $wrapperOptions = array('id' => $this->htmlOptions['id'] . '-wrapper');
            if ($this->isTicker == true) {
                $wrapperOptions['dir'] = 'ltr';
            }
            $titleOptions = array('id' => $this->htmlOptions['id'] . '-title', 'class' => $this->titleClass);
            if (Yii::app()->getLocale()->getOrientation() == 'rtl') {
                //$controlsOptions['style'] = 'float:left;';
                $controlsOptionsWrapper['style'] = 'float:left;';
                $wrapperOptions['style'] = 'float:right;padding-right:6px;';
                $titleOptions['style'] = 'float:right;';
            } else {
                $controlsOptionsWrapper['style'] = 'float:right;';
                //$controlsOptions['style'] = 'float:right';
                $titleOptions['style'] = 'float:left;';
                $wrapperOptions['style'] = 'float:left;padding-left:6px;';
            }
            $output .= CHtml::openTag('div', $this->htmlOptions) . "\n";
            if ($this->title !== NULL) {
                $output .= CHtml::openTag('div', $titleOptions) . "\n";
                $output .= $this->title;
                $output .= CHtml::closeTag('div') . "\n";
            }
            $output .= CHtml::openTag('div', $wrapperOptions) . "\n";
            $output .= CHtml::openTag('ul', $sliderOptions) . "\n";
            $itemIndex = 1;
            foreach ($this->items as $item) {
                if ($this->encodeItem) {
                    $item['title'] = CHtml::encode($item['title']);
                }
                $output .= CHtml::openTag('li', array('class' => ($itemIndex % 2 ) ? $this->itemOddClass : $this->itemEvenClass));
                if (isset($item['link'])) {
                    $output .= Html::link($item['title'], $item['link']);
                } else {
                    $output .= $item['title'];
                }
                $output .= CHtml::closeTag('li') . "\n";
                $itemIndex++;
            }
            $output .= CHtml::closeTag('ul') . "\n";
            $output .= CHtml::closeTag('div') . "\n";
            if ($this->customControllers) {
                $output .= CHtml::openTag('div', $controlsOptionsWrapper) . "\n";
                $output .= CHtml::openTag('ul', $controlsOptions) . "\n";
                $pauseOptions = array('class' => 'play-pause', 'id' => $this->htmlOptions['id'] . '-play-pause');
                $stopOptions = array('class' => 'play-start', 'id' => $this->htmlOptions['id'] . '-play-stop');
                if (isset($this->options['auto'])) {
                    $stopOptions['style'] = 'display:none';
                } else {
                    $pauseOptions['style'] = 'display:none';
                }
                $output .= CHtml::openTag('li', $pauseOptions);
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li', $stopOptions);
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li', array('class' => 'prev', 'id' => $this->htmlOptions['id'] . '-prev'));
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li', array('class' => 'next', 'id' => $this->htmlOptions['id'] . '-next'));
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::closeTag('ul') . "\n";
                $output .= CHtml::closeTag('div') . "\n";
                $this->options['controls'] = false;
                $this->options['autoControls'] = false;
                //$output .= '<div style="clear:both;"></div>';
            }
            $output .= CHtml::closeTag('div') . "\n";
            $jsCode = "        
            {$this->htmlOptions['id']}Slider = $('#{$this->htmlOptions['id']}-slider').bxSlider(" . CJavaScript::encode($this->options) . ");
            $('#{$this->htmlOptions['id']}-play-pause').click(function(){
                    $('#{$this->htmlOptions['id']}-play-pause').hide();
                    $('#{$this->htmlOptions['id']}-play-stop').show();
                    {$this->htmlOptions['id']}Slider.stopShow();
                    return false;
            });                
            $('#{$this->htmlOptions['id']}-play-stop').click(function(){
                    $('#{$this->htmlOptions['id']}-play-stop').hide();
                    $('#{$this->htmlOptions['id']}-play-pause').show();
                    {$this->htmlOptions['id']}Slider.startShow();
                    return false;
            });       
            $('#{$this->htmlOptions['id']}-prev').click(function(){
                    {$this->htmlOptions['id']}Slider.goToPreviousSlide();
                    return false;
            });                
            $('#{$this->htmlOptions['id']}-next').click(function(){
                    {$this->htmlOptions['id']}Slider.goToNextSlide();
                    return false;
            });   
            " . PHP_EOL;
            //
            $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        }
        echo $output;
    }

}
