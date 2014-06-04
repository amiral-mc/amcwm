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
class MostReadAndComment extends SideWidget {

    /**
     * Most read articles tab title
     * @var string title
     */
    public $readTitle = 'Views';

    /**
     * Most read articles tab title
     * @var string title
     */
    public $sharedTitle = 'Shared';

    /**
     * Most comments articles tab title
     * @var string title
     */
    public $commentTitle = 'Comments';

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
     * shared articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @var array 
     */
    public $sharedArticles;

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
     *
     * @var array tabs dispaly orders
     */
    public $tabsOrders = array('read', "comments", "shared");

    /**
     *
     * @var current active tab 
     */
    public $activeTab = null;

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {

        foreach ($this->tabsOrders as $i => $tabOrder) {

            $widgetTabs["{$this->getId()}-{$tabOrder}"] = array();
        }

        if (count($this->readArticles)) {
            $widgetTabs["{$this->getId()}-read"] = array('title' => $this->readTitle, 'content' => '');
            $widgetTabs["{$this->getId()}-read"]['content'] = '<ul>';
            foreach ($this->readArticles As $article) {
                $widgetTabs["{$this->getId()}-read"]['content'].= "<li><a href={$article['link']}>" . $article['title'] . '</a></li>';
            }
            $widgetTabs["{$this->getId()}-read"]['content'] .='</ul>';
        } else {
            unset($widgetTabs["{$this->getId()}-read"]);
        }
        if (count($this->commentsArticles)) {
            $widgetTabs["{$this->getId()}-comments"] = array('title' => $this->commentTitle, 'content' => '', 'active');
            $widgetTabs["{$this->getId()}-comments"]['content'] .= '<ul>';
            foreach ($this->commentsArticles As $article) {
                $widgetTabs["{$this->getId()}-comments"]['content'].= "<li><a href={$article['link']}>" . $article['title'] . '</a></li>';
            }
            $widgetTabs["{$this->getId()}-comments"]['content'] .= '</ul>';
        } else {
            unset($widgetTabs["{$this->getId()}-comments"]);
        }
        if (count($this->sharedArticles)) {
            $widgetTabs["{$this->getId()}-shared"] = array('title' => $this->sharedTitle, 'content' => '');
            $widgetTabs["{$this->getId()}-shared"]['content'] .= '<ul>';
            foreach ($this->sharedArticles As $article) {
                $widgetTabs["{$this->getId()}-shared"]['content'].= "<li><a href={$article['link']}>" . $article['title'] . '</a></li>';
            }
            $widgetTabs["{$this->getId()}-shared"]['content'] .= '</ul>';
        } else {
            unset($widgetTabs["{$this->getId()}-shared"]);
        }

        if ($widgetTabs) {
            $this->drawTabs($widgetTabs);
        }
    }

    /**
     * Draw tabs 
     * @param array $widgetTabs
     */
    protected function drawTabs($widgetTabs) {
        $tabsWidgetsOptions = array('tabs' => $widgetTabs, 'cssFile' => Yii::app()->request->baseUrl . '/css/tabs.css',);
        if ($this->activeTab) {
            $tabsWidgetsOptions['activeTab'] = "{$this->getId()}-{$this->activeTab}";
        }
        $this->contentData .= $this->widget('TabView', $tabsWidgetsOptions, true);
    }

}
