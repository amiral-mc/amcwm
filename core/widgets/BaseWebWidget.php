<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * BaseWebWidget extension class
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class BaseWebWidget extends Widget {

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
     * bottom class name
     * @var string 
     */
    public $bottomClass = null;

    /**
     * title class name
     * @var string 
     */
    public $titleClass = null;

    /**
     * Widget title
     * @var string title
     */
    public $title = null;

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

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
        $this->class = "internal_content_wrapper";
        $this->contentClass = "page_content";
        $this->bottomClass = "page_content_footer";
        $this->titleClass = "page_title";
        parent::__construct($owner);
    }

    /**
     * Initializes widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = $this->class;        
        parent::init();
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function run() {
        $output = $this->appendBefore();
        $this->setContentData();
        if ($this->contentOnly) {
            $output .= $this->contentData;
        } else {
            $this->contentData .= '<br class="clearfloat" />';
            $output .= CHtml::openTag('div', $this->htmlOptions);
            $output .= $this->drawWidgetHeader();
            $output .= $this->drawWidgetBody();
            $output .= $this->drawWidgetFooter();
            $output .= CHtml::closeTag("div");
        }
        $output .= $this->appendAfter();
        echo $output;
    }

    /**
     * Draw widget body
     * @return string
     */
    protected function drawWidgetBody() {
        $output ='<div class="' . $this->contentClass . '">';
        $output .='<span id="' . $this->htmlOptions['id'] . '_content">';
        $output .= $this->contentData;
        $output .='</span>';
        $output .='</div>';
        return $output; 
    }

    /**
     * Set the widget header
     * @access protected
     * @return string
     */
    protected function drawWidgetHeader() {
        $output = '<h1 class="' . $this->titleClass . '">' . $this->title . '</h1>';
        return $output;
    }

    /**
     * Set the widget footer
     * @access protected
     * @return string
     */
    protected function drawWidgetFooter() {
        $output = '<div class="' . $this->bottomClass . '"></div>';
        return $output;
    }

    /**
     * Append text after content
     * @access protected
     * @return string
     */
    protected function appendAfterContent() {
        return null;
    }

    /**
     * Append content after widget
     * @access protected
     * @return string
     */
    protected function appendAfter() {
        return null;
    }

    /**
     * Append content before widget
     * @access protected
     * @return string
     */
    protected function appendBefore() {
        return null;
    }

    /**
     * Set the data content of this widget
     * @access protected
     * @return void
     */
    protected function setContentData() {
        
    }

}
