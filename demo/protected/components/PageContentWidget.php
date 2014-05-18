<?php

class PageContentWidget extends BasePageContentWidget {

    /**
     *
     * @var string  
     */
    public $pagePreTitleClass = "page_pre_title";

    /**
     *
     * @var string  pre header
     */
    public $preHeader = "";

    /**
     *
     * @var string  content pre
     */
    public $pageContentPreTitle = "";

    /**
     *
     * @var string  content pre
     */
    public $pageContentDesc = "";

    /**
     *
     * @var string  content pre
     */
    public $pageContentDescLength = 150;

    /**
     * Initializes widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        
        if ($this->image && $this->appendBefore === null) {
            $this->appendBefore = '<div id="section_landing_page" style="background:url(' . $this->image . ') no-repeat center center;">';
            $this->appendBefore .= '<div class="section_title">' . $this->pageContentTitle . '</div>';            
            $this->appendBefore .= '<div class="section_pre_title">' . $this->pageContentPreTitle . '</div>';            
            $this->appendBefore .= '<p class="section_brief">';
            if ($this->pageContentDesc) {
                if($this->pageContentDescLength){
                    $this->appendBefore .= Html::utfSubstring($this->pageContentDesc, 0, $this->pageContentDescLength, true);    
                }
                else{
                    $this->appendBefore .= strip_tags($this->pageContentDesc);
                }                
            }
            $this->appendBefore .= '</p>';
            $this->appendBefore .= '</div>';
        }

        $this->owner->breadcrumbs = $this->breadcrumbs;
        parent::init();
    }

    
    /**
     * Set the widget header
     * @access protected
     * @return string
     */
    protected function drawWidgetHeader() {
        $output = '<h1 class="' . $this->titleClass . '">' . $this->title . '</h1>';
        if ($this->preHeader) {
            $output .= '<h2 class="' . $this->pagePreTitleClass . '">' . $this->preHeader . '</h2>';
        }
        $output .= $this->setSubItems();
        return $output;
    }

}
