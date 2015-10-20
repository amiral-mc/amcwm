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
class BxSlider4 extends Widget {

    /**
     * @var array list of new sticker items.
     */
    public $items = array();

    /**
     * @var boolean whether the news items should be HTML-encoded. Defaults to true.
     */
    public $encodeItem = false;

    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    public $baseScriptUrl;

    /**
     * bxslider class name
     * @var string 
     */
    protected $className = 'bxslider';

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     *
     * @var string easing script 
     */
    public $easingScript = null;

    /**
     *
     * @var use site css or widget css
     */
    public $useSiteCss = false;

    /**
     *
     * @var string fitvids script 
     */
    public $fitvidsScript = null;

    /**
     *
     * @var boolean draw thumbs;
     */
    public $drawThumbs = true;
    
    /**
     *
     * @var string item class  
     */
    public $itemClass = null;
    
    /**
     *
     * @var string tag container 
     */
    public $tagContainer = "ul";
    
    /**
     *
     * @var string popup index in images array default is null to disable popup 
     */
    public $popupIndex = null;

    /**
     * @var array the initial JavaScript options that should be passed to the plugin.
     */
    protected $options = array(
        "autoDelay" => 0
        , "captions" => true
        , "controls" => true
        , "mode" => 'fade'
        , "auto" => false
        , "autoHover" => true
        , 'easing' => 'linear'
        , 'video' => true
    );

    /**
     * Set jquery plugin options
     * @param array $options
     */
    public function setOptions($options) {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Set css class name
     * @param string $className
     */
    public function setClassName($className) {
        $this->className = "{$this->className} {$className}";
    }

    /**
     * Initializes the scroller widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = $this->className;
        $this->options['pager'] = $this->drawThumbs;
        $this->basePath = __DIR__;
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        if (count($this->items)) {            
            $cs = Yii::app()->getClientScript();
            $cs->registerCoreScript('jquery');
            if (YII_DEBUG) {
                $min = "";
            } else {
                $min = ".min";
            }
            if ($this->baseScriptUrl === null) {
                $this->baseScriptUrl = Yii::app()->getAssetManager()->publish($this->basePath . DIRECTORY_SEPARATOR . "assets") . "/bxslider4";
            }
            $cs->registerScriptFile($this->baseScriptUrl . "/jquery.bxslider{$min}.js");
            if (!$this->useSiteCss) {
                $cs->registerCssFile($this->baseScriptUrl . '/jquery.bxslider.css');
            }
            if (isset($this->options['easing'])) {
                if ($this->easingScript) {
                    $cs->registerScriptFile($this->easingScript, CClientScript::POS_HEAD);
                } else {
                    $cs->registerScriptFile($this->baseScriptUrl . '/plugins/jquery.easing.min.js', CClientScript::POS_HEAD);
                }
            }
            if (isset($this->options['video']) && $this->options['video']) {
                if ($this->fitvidsScript) {
                    $cs->registerScriptFile($this->fitvidsScripts, CClientScript::POS_HEAD);
                } else {
                    $cs->registerScriptFile($this->baseScriptUrl . '/plugins/jquery.fitvids.js', CClientScript::POS_HEAD);
                }
            }

            $images = CHtml::openTag($this->tagContainer, $this->htmlOptions);
            $thumbs = '';
            if ($this->drawThumbs) {
                $thumbs = '<div id="' . $this->id . '-pager" class="bx-pager">';
            }

            $index = 0;
            $imageTagContainer = $this->tagContainer == 'ul' ? "li" : $this->tagContainer;
            $itemClass = $this->itemClass ? ' class="' . $this->itemClass . '"' : "";
            foreach ($this->items as $image) {
                $image['title'] = CHtml::encode($image['title']);
                $title = ($image['title']) ? ' title="' . $image['title'] . '"' : '';
                $linkClosedTag = "";
                $linkOpenTag = "";
                if($this->popupIndex && isset($image[$this->popupIndex])){
                      $linkOpenTag = '<a data-caption="' . CHtml::encode($image['title']) . '" data-target="#image-lightbox" data-toggle="lightbox" href="'.  $image[$this->popupIndex] .'">';
                      $linkClosedTag = "</a>";
                }
                else if(!empty($image['link'])){
                    $linkOpenTag = '<a href="' .$image['link']. '">';
                    $linkClosedTag = "</a>";
                }
                $images .= $linkOpenTag . '<' . $imageTagContainer . $itemClass . '><img src="' . $image['url'] . '" ' . $title . '  /></' . $imageTagContainer . '>' . $linkClosedTag;
                if ($this->drawThumbs) {
                    $thumbs .= '<a data-slide-index="' . $index . '" href=""><img src="' . $image['thumb'] . '" ' . $title . ' /></a>';
                }
                $index ++;
            }
            if ($this->drawThumbs) {
                $thumbs .= '</div>';    
                $this->options['pagerCustom'] = "#{$this->id}-pager";
            }            
            $images .= CHtml::closeTag($this->tagContainer);
            echo "{$images} {$thumbs}";            
            $jsCode = "$('#{$this->id}').bxSlider(" . CJavaScript::encode($this->options) . ");";
            $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        }
    }
}
