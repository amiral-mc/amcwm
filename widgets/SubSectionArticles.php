<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SubSectionArticles extension class, displays section childs sub sections tabs, each tab contain's articles list for each section
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SubSectionArticles extends SideWidget {    
    /**
     * @var array of data to display it
     */
    public $items = array();
   
    /**
     * Render the widget and display the result
     * Calls {@link runItem} to render each article row.
     * @access public
     * @return void
     */
    public function setContentData() {        
        $this->contentData = "";
        $subSectionArticles = $this->items;
        $sectionsTabs = array();
        if (count($subSectionArticles)) {
            foreach ($subSectionArticles As $subSectionId => $subSection) {
                $this->contentData = '	   	
                    <ul class="wd_news_list">';
                foreach ($subSection['articles'] As $subSectionArticle) {

                    $this->contentData.= "<li><a href={$subSectionArticle['link']}>" . $subSectionArticle['title'] . '</a></li>';
                }
                $this->contentData .='</ul>';

                $this->contentData .= '<div class="wd_news_more">';
                $this->contentData .= "<a href={$subSection['sectionLink']}>" . AmcWm::t("amcFront", 'More') . '</a>';
                $this->contentData .= '</div>';


                $sectionsTabs['subSection' . $subSectionId] = array('title' => $subSection['sectionTitle'], 'content' => $this->contentData);
            }
            $this->contentData .= $this->widget('TabView', array('tabs' => $sectionsTabs, 'cssFile' => Yii::app()->request->baseUrl . '/css/tabs.css',), true);
        }
    }
}
