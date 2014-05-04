<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * InternalColsList extension class, displays the most articles list wiedget
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InternalColsList extends CWidget {

    /**
     * @var string widget title
     */
    public $items = null;

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * Css class name
     * @var string 
     */
    public $class = "content_many_cols_wdg";    
    /**
     *
     * @var string widget title 
     */
    public $title = null;

    /**
     * Initializes the player widget.
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
        $content = "";
        if (count($this->items)) {
            $content .= CHtml::openTag("div", $this->htmlOptions);
            if ($this->title) {
                $content .= '<h1>' . $this->title . '</h1>';
            }
            $content .= '<div class="content_many_cols_items" >';
            foreach ($this->items As $item) {
                $content.= '<div class="content_many_cols_item">';
                $lnk = '<img src="' . $item['image'] . '" title="' . $item['title'] . '" />
                            <h2>' . @$item['priHeader'] . '</h2>
                            <div>' . $item['title'] . '</div>';
                $content.= CHtml::link($lnk, $item['link']);
                $content.= '</div>';
            }
            $content .='</div>';
            $content .= CHtml::closeTag("div");
            echo $content;
        }
    }

}
