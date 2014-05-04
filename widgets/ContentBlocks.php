<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ContentBlocks extension class, displays content as blocks
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ContentBlocks extends PageContentWidget {

    /**
     * row view options 
     * @var array
     */
    public $viewOptions = array();

    /**
     * internal css class
     * @var string 
     */
    public $internalClass = 'content_blocks';

    /**
     * @var array list of name-value pairs dataset.
     *  Possible list names include the following:
     * <ul>
     * <li>title: string block title
     * <li>description: string block description
     * <li>link: string, more details link </li>
     * <li>image: string, image path </li>
     * <li>imageExt: string, image extension </li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     */
    public $items = array();

    /**
     * More text 
     * @var string 
     */
    public $moreText = "More";

    /**
     * description maximum string length, default equal 200
     * @var string
     */
    public $descriptionLength = 200;

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
        $dataCount = count($this->items);
        if ($dataCount) {
            $this->contentData .= '<div class="' . $this->internalClass . '">';
            $siteLanguage = Yii::app()->user->getCurrentLanguage();
            $index = 0;
            for ($colIndex = 0; $colIndex < $dataCount; $colIndex = $colIndex + 2) {
                $this->contentData .= '<div>';
                for ($index = 0; $index < 2; $index++) {
                    $rowIndex = $colIndex + $index;
                    if (isset($this->items[$rowIndex])) {
                        $urlLink = $this->items[$rowIndex]['link'];
                        $class = ($index / 2 ) ? "content_col_left" : "content_col_right";
                        $this->contentData .= '<div class="' . $class . '">';
                        $this->contentData .= '<h2>' . $link = Html::link($this->items[$rowIndex]['title'], $urlLink) . '</h2>';
                        $moreImage = CHtml::image(Yii::app()->baseUrl . '/images/front/' . $siteLanguage . '/icon_more.gif', $this->items[$rowIndex]['title'], array("class" => "read_more_icon", "width" => "19", "height" => "13"));
                        $link = Html::link(AmcWm::t("amcFront", $this->moreText) . $moreImage, $urlLink);
                        if ($this->items[$rowIndex]['image']) {
                            $image = CHtml::image($this->items[$rowIndex]['image'], $this->items[$rowIndex]['title'], array("class" => "photo"));
                            $this->contentData .= Html::link($image, $urlLink);
                        }
                        else {
                            if (isset($this->viewOptions['noImageListing']) && $this->viewOptions['showDefaultImage']) {
                                $image = CHtml::image($this->viewOptions['noImageListing'], $this->items[$rowIndex]['title'], array("class" => "photo"));
                                $this->contentData .= Html::link($image, $urlLink);
                            }
                        }
                        $this->contentData .= '<p>';
                        $this->contentData .= Html::utfSubstring($this->items[$rowIndex]['description'], 0, $this->descriptionLength, true);
                        $this->contentData .= '</p>';
                        $this->contentData .= '<p class="readmore">';
                        $this->contentData .= $link;
                        $this->contentData .= '</p>';
                        $this->contentData .= '</div>';
                    }
                }
                $this->contentData .= '</div>';
                $this->contentData .= '<br class="clearfloat" />';
            }
            $this->contentData .='</div>';
        }
    }

}