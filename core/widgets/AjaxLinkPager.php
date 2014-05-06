<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * LinkPager
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */

class AjaxLinkPager extends CLinkPager {

    public $updatedContainer = "null";
    /**
     * Creates a page button.     
     * You may override this method to customize the page buttons.
     * @todo change selected page number color
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
     * @param boolean $hidden whether this page button is visible
     * @param boolean $selected whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label, $page, $class, $hidden, $selected) {
        //parent::createPageButton($label, $page, $class, $hidden, $selected);

        if ($hidden || $selected)
            $class.=' ' . ($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
        
        //            $jsCodePaging = "$('#{$this->htmlOptions['id']}Pager').pager({ pagenumber: 1, pagecount: {$pagecount}, buttonClickCallback: PageClick });";
        return '<li class="' . $class . '">' . CHtml::ajaxLink($label, $this->createPageUrl($page), array('success' => 'js:function(data) {
                                                jQuery("#'.$this->updatedContainer.'").html(data);}'), array('live' => false)) . '</li>';
    }        
}
