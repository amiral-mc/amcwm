<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * BaseSideWidget extension class
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class BaseSideWidget extends Widget {

    /**
     * Widget title
     * @var string title
     */
    public $title = null;

    /**
     * Css class name
     * @var string 
     */
    public $class = null;

    /**
     * Content class name
     * @var string 
     */
    public $contentClass = null;

    /**
     * Content class name
     * @var string 
     */
    public $contentClassWrapper = null;

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * title class name
     * @var string 
     */
    public $titleClass = null;
    
    /**
     * header class name, if drawTitle is false the we used it instead of titleClass attribute
     * @var string 
     */
    public $headerClass = null;

    /**
     * if set to true then we draw the h2 html tag
     * @var boolean 
     */
    public $drawTitle = true;

    /**
     * @var mixed of data to display it
     */
    public $contentData = null;

    /**
     * @var boolean if equal true then display the of the widget without header , footer and border
     */
    public $contentOnly = false;

    /**
     * Constructor.
     * @param CBaseController $owner owner/creator of this widget. It could be either a widget or a controller.
     * If constructor is overridden, make sure the parent implementation is invoked.
     */
    public function __construct($owner = null) {
        $this->class = "wdg_box";
        $this->contentClass = "wdg_box_content";
        $this->contentClassWrapper = "wdg_box_wrapper";
        $this->titleClass = "wdg_box_head";
        $this->headerClass = "wdg_box_head_only";        
        parent::__construct($owner);
    }

    /**
     * Initializes the player widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = $this->class;
        if (!$this->title) {
            $this->title = "&nbsp;";
        }        
        parent::init();
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function run() {
        $this->setContentData();
        if ($this->contentOnly) {
            $output = $this->contentData;
        } else {
            $output = CHtml::openTag('div', $this->htmlOptions);
            $output .= $this->drawWidgetHeader();
            $output .='<div class="' . $this->contentClassWrapper . '">';
            $output .= $this->drawWidgetBody();
            $output .='</div>';
            $output .= $this->drawWidgetFooter();
            $output .= CHtml::closeTag("div");
        }
        echo $output;
    }

    /**
     * Draw widget body
     * @return string
     */
    protected function drawWidgetBody() {        
        $output = '<div class="' . $this->contentClass . '">';
        $output .='<span id="' . $this->htmlOptions['id'] . '_content">';
        $output .= $this->contentData;
        $output .='</span>';
        $output .= $this->appendAfterContent();
        $output .='</div>';
        return $output;
    }

    /**
     * Draw widget header
     * @return string
     */
    protected function drawWidgetHeader() {
        if ($this->drawTitle) {
            $output = '<div class="' . $this->titleClass . '"><h2><strong>' . $this->title . '</strong></h2></div>';
        } else {
            $output = '<div class="' . $this->headerClass . '">&nbsp;</div>';
        }
        return $output;
    }

    /**
     * Draw widget footer
     * @return string
     */
    protected function drawWidgetFooter() {
        
    }

    /**
     * Append text after content
     * @access protected
     * @return string
     */
    protected function appendAfterContent() {
        
    }

    /**
     * Set the data content of this widget
     * @return void
     */
    protected function setContentData() {
        
    }

}
