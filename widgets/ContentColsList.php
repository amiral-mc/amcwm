<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * PageContentColsList extension class, displays the most articles list widget
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ContentColsList extends PageContentWidget {

    /**
     * row view options 
     * @var array
     */
    public $viewOptions = array();

    /**
     * @var string widget title
     */
    public $items = null;

    /**
     * internal css class
     * @var string 
     */
    public $internalClass = 'content_many_cols_items';

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        $viewOptions = array('showPrimaryHeader' => false, 'showDate' => true);
        foreach ($viewOptions as $option => $optionValue) {
            if (!array_key_exists($option, $this->viewOptions)) {
                $this->viewOptions[$option] = $optionValue;
            }
        }
        parent::init();
    }

    /**
     * Set the data content of this widget
     * @access protected
     * @return void
     */
    protected function setContentData() {
        if (count($this->items)) {
            $this->contentData .= '<div class="content_many_cols_items" >';
            foreach ($this->items As $item) {
                $this->contentData.= '<div class="content_many_cols_item">';
                $this->contentData.= CHtml::link('<img src="' . $item['image'] . '" title="' . CHtml::encode($item['title']) . '" />', $item['link']);
                if(array_key_exists("priHeader", $item) && $item["priHeader"]){
                    $this->contentData.= CHtml::link("<h2> {$item['priHeader']}  </h2>", $item['link']);
                    $this->contentData.= CHtml::link("<div> {$item['title']}  </div>", $item['title']);
                } else {
                    $this->contentData.= CHtml::link("<h2> {$item['title']}  </h2>", $item['link']);
                }
                $this->contentData.= '</div>';
            }
            $this->contentData .='</div>';
        }
    }

}
