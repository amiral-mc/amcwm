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
class ContentTitlesList extends PageContentWidget {

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
                        $this->contentData.= '<div class="content_list_item_style2">';
                        $this->contentData.= '<div class="article-img">';
                        $this->contentData.= '<img class="article-img-icon" alt="" src="' . Yii::app()->request->baseUrl . '/images/front/article-arrow-icon.png"/>';
                        if ($this->items['records'][$rowIndex]['image']) {                            
                            $image = CHtml::image($this->items['records'][$rowIndex]['image'], $this->items['records'][$rowIndex]['title'], array("class" => "photo"));
                            $this->contentData.= Html::link($image, $this->items['records'][$rowIndex]['link']);                        
                        }
                        else{
                            $this->contentData.= '&nbsp;';
                        }
                        $this->contentData.= '</div>';
                        $titleHeader = $this->items['records'][$rowIndex]['title'];
                        $this->contentData.= '<div class="article-img-title"><h2>' . Html::link($titleHeader, $this->items['records'][$rowIndex]['link']) . '</h2></div>';
                        $this->contentData.= '</div>';
                    }
                }
                if ($colIndex == $dataCount - 2) {
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