<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * EventsList extension draw events list
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class EventsTopListWidget extends InnerWidget {

    /**
     * @var array of data to display it
     */
    public $items = array();

    /**
     * media default width
     * @var integer 
     */
    public $mediaWidth = 290;
    /**
     * media default height
     * @var integer 
     */
    public $mediaHeight = 174;

    /**
     * @var first item record
     */
    public $firstItem = array();

    /**
     * @var integer
     */
    public $detailsMaxChars = 100;

    public function init() {

        parent::init();
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {
//        /aspf/multimedia/galleries/1/videos/1.flv
//        http://www.youtube.com/watch?v=sqp4BLG6XsE
        $this->contentData = '<div class="main_event">';
        $this->contentData .= '<div class="main_event_wrapper">';
        if (isset($this->firstItem["attachment"][0])) {
            $this->contentData .= '<div class="media_event_wrapper">';
            $firstMedia = $this->firstItem["attachment"][0];
            unset($this->firstItem["attachment"][0]);
            if ($firstMedia["content_type"] == AttachmentList::INTERNAL_VIDEO || $firstMedia["content_type"] == AttachmentList::EXTERNAL_VIDEO) {
                $this->contentData .= $this->widget('amcwm.widgets.videoplayer.VideoPlayer', array(
                    'id' => 'event_attach_video',
                    'width' => $this->mediaWidth,
                    'height' => $this->mediaHeight,
                    'title' => $firstMedia['title'],
                    'video' => $firstMedia['link'],
                        ), true
                );
            } else if ($firstMedia["content_type"] == AttachmentList::IMAGE) {
                $this->contentData .= CHtml::image($firstMedia['link'], $this->firstItem['title'], array('width'=>$this->mediaWidth));
            }
            $this->contentData .= '</div>';
        }
        $this->contentData .= '<h1>';
        $this->contentData .= Html::link("{$this->firstItem["title"]} (" . AmcWm::app()->dateFormatter->format('MMMM yyyy', $this->firstItem["event_date"]) . ") {$this->firstItem["location"]} - {$this->firstItem["country"]}", $this->firstItem["link"]);
        $this->contentData .= '</h1>';
        $this->contentData .= '<div class="desc">';
        $this->contentData .= Html::utfSubstring($this->firstItem["detail"], 0, $this->detailsMaxChars);
        $this->contentData .= '</div>';
        if (isset($this->firstItem["attachment"])) {
            foreach ($this->firstItem["attachment"] as $attach) {
                if ($attach["content_type"] == AttachmentList::LINK) {
                    $this->contentData .= '<div class="attach">';
                    $this->contentData .= Html::link($attach["title"], $attach["link"]);
                    $this->contentData .= '</div>';
                }
            }
        }
        $this->contentData .= '</div>';

        $this->contentData .= '</div>';
        $this->contentData .= '<div class="sub_event">';
        $this->contentData .= '<ul class="News_list">';
        foreach ($this->items as $item) {
            $this->contentData .= '<li>';
            $this->contentData .= '<h2>';
            $this->contentData .= Html::link("{$item["title"]} (" . AmcWm::app()->dateFormatter->format('MMMM yyyy', $item["event_date"]) . ") {$item["location"]} - {$item["country"]}", $item["link"]);
            $this->contentData .= '</h2>';
            $this->contentData .= '</li>';
        }
        $this->contentData .= '</ul>';
        $this->contentData .= '</div>';
    }

}

?>
