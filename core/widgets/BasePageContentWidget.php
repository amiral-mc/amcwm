<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * PageContentWidget extension class
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class BasePageContentWidget extends BaseWebWidget {

    /**
     * path class name
     * @var string 
     */
    public $pagePathClass = "page_path";

    /**
     * @var array of data to display it
     */
    public $subItems = array();

    /**
     * Page content title
     * @var string 
     */
    public $pageContentTitle;

    /**
     * breadcrumbs data to displayed in this widget
     * @var array 
     */
    public $breadcrumbs;

    /**
     * Image 
     * @var string 
     */
    public $image;

    /**
     * @var string append content before widget
     */
    public $appendBefore = null;

    /**
     * internal css class
     * @var string 
     */
    public $internalClass = null;

    /**
     * Initializes widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        if ($this->image && $this->appendBefore === null) {
            $this->appendBefore = '<div id="pageContentImage">';
            $this->appendBefore .= CHtml::image($this->image, $this->pageContentTitle, array("class" => "top_photo"));
            $this->appendBefore .= '<div id="pageContentTitle">' . $this->pageContentTitle . '</div></div>';
        }
        parent::init();
    }

    /**
     * Set the widget header
     * @access protected
     * @return string
     */
    protected function drawWidgetHeader() {
        $output = '<div class="' . $this->pagePathClass . '">';
        if ($this->breadcrumbs) {
            $output .= $this->widget('Breadcrumbs', array('links' => $this->breadcrumbs), true);
        }
        $output .='</div>';
        $output .= CHtml::openTag('div', array('class' => 'pageTopContentData'));
        $output .= parent::drawWidgetHeader();
        $output .= $this->setSubItems();
        $output .= CHtml::closeTag("div");
        return $output;
    }

    /**
     * Append content before widget
     * @access protected
     * @return string
     */
    protected function appendBefore() {

        return $this->appendBefore;
    }

    protected function setSubItemLink($title = '', $url = '#', $active = false) {

        $output = '<div class="sub_item">
                    <div class="right"><img width="8" height="23" border="0" src="' . Yii::app()->baseUrl . '/images/front/sub_tag_r' . ($active ? "_a" : "") . '.png"></div>
                    <div class="bg ' . ($active ? "active" : "") . '">';
        $output .= ' <h2>' . Html::link($title, $url) . '</h2>';
        $output .= ' </div>
                    <div class="left"><img width="8" height="23" border="0" src="' . Yii::app()->baseUrl . '/images/front/sub_tag_l' . ($active ? "_a" : "") . '.png"></div>
                  </div>';
        return $output;
    }

    protected function setSubItems() {
        $output = null;
        if (count($this->subItems)) {
            $output .= CHtml::openTag('div', array('class' => 'sub_items'));
            foreach ($this->subItems as $subItem) {
                $output .= $this->setSubItemLink($subItem['title'], $subItem['url'], $subItem['active']);
            }
            $output .= CHtml::closeTag("div");
        }
        return $output;
    }

}
