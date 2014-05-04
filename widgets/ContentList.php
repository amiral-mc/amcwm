<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ContentList extension class, displays the most articles list wiedget
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ContentList extends PageContentWidget {

    /**
     * Description index key
     * @var string 
     */
    public $descriptionKey = "article_detail";

    /**
     * row view options 
     * @var array
     */
    public $viewOptions = array();

    /**
     * internal css class
     * @var string 
     */
    public $internalClass = 'content_list_wrapper_h';

    /**
     * description maximum string length, default equal 200
     * @var string
     */
    public $descriptionLength = 200;

    /**
     * @var content list
     */
    public $items = array();

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
        $dataCount = count($this->items['records']);
        if ($dataCount) {
            $this->contentData .= '<div class="' . $this->internalClass . '">';
            $index = 0;
            for ($colIndex = 0; $colIndex < $dataCount; $colIndex = $colIndex + 2) {
                for ($index = 0; $index < 2; $index++) {
                    $rowIndex = $colIndex + $index;
                    if (isset($this->items['records'][$rowIndex])) {
                        $this->contentData.= '<div class="content_list_item">';
                        if(array_key_exists("priHeader", $this->items['records'][$rowIndex]) && $this->items['records'][$rowIndex]["priHeader"]){
                            $titleHeader = $this->items['records'][$rowIndex]['priHeader'];    
                        }
                        else{
                            $titleHeader = $this->items['records'][$rowIndex]['title'];
                        }
                        
                        $this->contentData.= '<h2 class="content_list_item_pr_title">' . Html::link($titleHeader, $this->items['records'][$rowIndex]['link']) . '</h2>';
                        $this->contentData.= '<h3 class="content_list_item_title">' . $this->items['records'][$rowIndex]['title'] . '</h2>';
                        if ($this->items['records'][$rowIndex]['image']) {
                            $image = CHtml::image($this->items['records'][$rowIndex]['image'], $this->items['records'][$rowIndex]['title'], array("class" => "photo"));
                            $this->contentData.= CHtml::link($image, $this->items['records'][$rowIndex]['link']);
                        }
                        else{
                            if (isset($this->viewOptions['noImageListing']) && $this->viewOptions['showDefaultImage']) {
                                $image = CHtml::image($this->viewOptions['noImageListing'], $this->items[$rowIndex]['title'], array("class" => "photo"));
                                $this->contentData .= Html::link($image, $urlLink);
                            }
                        }
                        $this->contentData.= '<div class="content_list_item_content">' . Html::utfSubstring($this->items['records'][$rowIndex][$this->descriptionKey], 0, $this->descriptionLength, true) . '</div>';
                        $this->contentData.= '</div>';
                    }
                }
                if ($colIndex != $dataCount - 2) {
                    $this->contentData .= '<div class="content_list_item_sp"></div>';
                } else {
                    $this->contentData .= '<div style="clear:both;"></div>';
                }
            }
            $this->contentData .='</div>';
            if ($this->items['pager']['pageSize']) {
                $pages = new CPagination($this->items['pager']['count']);
                $pages->setPageSize($this->items['pager']['pageSize']);
                $this->contentData .= '<div class="pager_container">';
                $this->contentData .= $this->widget('CLinkPager', array('pages' => $pages), true);
                $this->contentData .= '</div>';
            }
        }
    }

}
