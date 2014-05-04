<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ContentLinks extension class, displays content as article with the related section articles as list item.
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ContentLinks extends PageContentWidget {

    /**
     * row view options 
     * @var array
     */
    public $viewOptions = array();

    /**
     * internal css class
     * @var string 
     */
    public $internalClass = 'content_links';

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
            $this->contentData .= '<div>';
            if (isset($this->items[0])) {
                $urlLink = $this->items[0]['link'];
                $class = ($index / 2 ) ? "content_col_left" : "content_col_right";
                $this->contentData .= '<div class="' . $class . '">';
                $this->contentData .= '<h2>' . $this->items[0]['title'] . '</h2>';
                if ($this->items[0]['image']) {
                    $image = CHtml::image($this->items[0]['image'], $this->items[0]['title'], array("class" => "photo"));
                    $this->contentData .= Html::link($image, $urlLink);
                } else {
                    if (isset($this->viewOptions['noImageListing']) && $this->viewOptions['showDefaultImage']) {
                        $image = CHtml::image($this->viewOptions['noImageListing'], $this->items[0]['title'], array("class" => "photo"));
                        $this->contentData .= Html::link($image, $urlLink);
                    }
                }
                $this->contentData .= '<p>';
                $this->contentData .= $this->items[0]['description'];
                $this->contentData .= '</p>';
                $this->contentData .= '</div>';
            }
            $this->contentData .= '</div>';
            $this->contentData .= '<br class="clearfloat" />';
            unset($this->items[0]);

            if(count($this->items)){
                $this->contentData .= '<ul class="articles_links">';
                foreach ($this->items as $item){
                    $this->contentData .= '<li>';
                    $this->contentData .= Html::link($item['title'], $item['link']);
                    $this->contentData .= '</li>';
                }
                $this->contentData .= '</ul>';
            }
            
            $this->contentData .='</div>';
        }
    }

}