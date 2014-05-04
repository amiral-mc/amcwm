<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * MostReadAndComment extension class, displays the most read and comments articles
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MostReadAndComment extends CWidget {

    /**
     * Widget title
     * @var string title
     */
    public $title = null;
    /**
     * Most read articles tab title
     * @var string title
     */
    public $readTitle = null;
    /**
     * Most comments articles tab title
     * @var string title
     */
    public $commentTitle = null;
    /**
     *  HTML attributes for the menu's root container tag
     * @var array
     */
    public $htmlOptions = array();
    /**
     * Most comments articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @var array 
     */
    public $commentsArticles;
    /**
     * Most read articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @var array 
     */
    public $readArticles;

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        parent::init();
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function run() {
        $draw = false;
        $commentContent = "";
        $readContent = "";
        $readContent = '	   	
              <ul class="wd_news_list">';
        if (count($this->readArticles)) {
            $draw = true;
            foreach ($this->readArticles As $article) {
                $readContent.= "<li><a href={$article['link']}>" . $article['title'] . '</a></li>';
            }
        }

        $readContent .='</ul>';

        $commentContent = '	   	
              <ul class="wd_news_list">';
        if (count($this->commentsArticles)) {
            $draw = true;
            foreach ($this->commentsArticles As $article) {
                $commentContent.= "<li><a href={$article['link']}>" . $article['title'] . '</a></li>';
            }
        }

        $commentContent .='</ul>';

        $widgetTabs = array(
            'comment' => array('title' => $this->commentTitle, 'content' => $commentContent),
            'read' => array('title' => $this->readTitle, 'content' => $readContent),
        );
        if ($draw) {
            echo '<div class="wdl_title">' . $this->title . '</div><div style="height:6px;"></div>';
            $this->widget('TabView', array('tabs' => $widgetTabs, 'cssFile' => Yii::app()->request->baseUrl . '/css/tabs.css',));
        }
    }
}