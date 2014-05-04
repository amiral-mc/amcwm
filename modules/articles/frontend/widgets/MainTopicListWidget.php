<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * MainTopicListWidget extension draw events list
 * @todo add javascript options to get more results
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MainTopicListWidget extends InnerWidget {

    /**
     * @var array of data to display it
     */
    public $items = array();

    
    /**
     * @var integer
     */
    public $detailsMaxChars = 500;
    /**
     * @var first item record
     */
    public $firstItem = array();

    public function init() {
        parent::init();
        $this->contentClassWrapper .= " height_limit";
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {
        $this->contentData = '<div class="main_topic">';
        $this->contentData .= '<div class="main_topic_wrapper">';
        if ($this->firstItem['imageExt']) {
            $firstImage = CHtml::image($this->firstItem['image'], $this->firstItem['title']);
            $this->contentData .= Html::link($firstImage, $this->firstItem['link'], array('title' => $this->firstItem['title']));
        }
        $this->contentData .= '<h1>';
        $this->contentData .= Html::link($this->firstItem["title"], $this->firstItem["link"]);
        $this->contentData .= '</h1>';
        $this->contentData .= '</div>';
        $this->contentData .= '<div class="desc">';
        $this->contentData .= Html::utfSubstring($this->firstItem["detail"], 0, $this->detailsMaxChars);
        $this->contentData .= '</div>';
        $this->contentData .= '<div class="readmore">';
        $this->contentData .= Html::link(AmcWm::t($this->messageFile, "_main_topic_read_more_"), $this->firstItem["link"]);
        $this->contentData .= '</div>';
        $this->contentData .= '</div>';
        $this->contentData .= '<div class="sub_topics">';
        $this->contentData .= '<ul class="News_list">';
        foreach ($this->items as $item) {
            $this->contentData .= '<li>';
            $this->contentData .= '<h2>';
            if ($item['imageExt']) {
                $this->contentData .= CHtml::image($item['image'], $item['title']);
            }
            $this->contentData .= Html::link($item["title"], $item["link"]);
            $this->contentData .= '</h2>';
            $this->contentData .= '</li>';
        } 
        $this->contentData .= '</ul>';
        $this->contentData .= '<div class="pager_container"><img src="/aspf/images/front/nxt.png" /> <img src="/aspf/images/front/prev.png" /></div>';
        $this->contentData .= '</div>';
    }
}

?>
