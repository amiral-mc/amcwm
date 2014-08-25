<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * NewsSideList extension class, displays the most articles list widget
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class NewsSideList extends SideWidget {

    /**
     * @var news list
     */
    public $items = array();  

    /**
     * Render the widget and display the result
     * Calls {@link runItem} to render each article row.
     * @access public
     * @return void
     */
    public function setContentData() {        
        $settings = new Settings("articles", false);
        $virtualId = $settings->getVirtualId('news');
        if (count($this->items)) {
            $this->contentData = '<ul class="News_list">';
            foreach ($this->items as $article) {
                $lnk = CHtml::link($article['title'], $article['link'], array('title' => CHtml::encode($article['title'])));
                $this->contentData .= '<li>
                                <div><span class="timeago" title="' . $article['publish_date'] . '">' . Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $article['publish_date']) . '</span></div>
                                <h2>' . $lnk . '</h2>
                            </li>';
            }
            $this->contentData .='</ul>';
            $this->contentData .='<div class="readmore">'.Html::link(AmcWm::t("amcFront", 'Read more'), array('/articles/default/sections/', 'module'=>$virtualId)).'</div>';
        }else{
             $this->contentData .= AmcWm::t("amcFront", 'Sorry, no news found');
        }
    }

}
