<?php

AmcWm::import("widgets.search.AmcSearchWidget");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SearchWidget extension class, displays site contents search result
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SearchWidget extends AmcSearchWidget {

    /**
     *
     * @var string search route 
     */
    public $searchRoute = array('/site/search/');

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {        
        $this->contentData = '<div id="news_search">';
        $this->contentData .= '<div class="news_search_brief">';
        if ($this->items['pager']['count']) {
            $this->contentData .= AmcWm::t("{$this->basePath}.core", 'Found {items} results', array('{items}' => "<span>{$this->items['pager']['count']}</span>"));
        } else {
            $this->contentData .= AmcWm::t("{$this->basePath}.core", 'Your search {keywords} did not match any results', array('{keywords}' => " (<u>{$this->keywords}</u>) "));
        }
        $this->contentData .= '</div>';

        $this->contentData .= '<form class="well form-search" id="internal_s    earch_form" method="get" action="' . $this->createUrl($this->searchRoute, array('ct' => "news")) . '">';
        $this->contentData .= '<div class="input-append">';
        $this->contentData .= '<input name="q" style="width: 150px;" id="form_search_q" type="text" placeholder="' . AmcWm::t('app', "Enter Search Keywords") . '" value="' . $this->keywords . '" />';
        $this->contentData .= '<span class="add-on">';
        $this->contentData .= '<button type="submit" class="append-button">';
        $this->contentData .= '<i class="icon-search"></i>';
        $this->contentData .= '</button>';
        $this->contentData .= '</span>';
        $this->contentData .= '</div>';
        $this->contentData .= '</form>';
        $this->contentData .= '</div>';
        $this->contentData .= '<div id="news_list">';

        $searchTabs = array();
        $activeTab = $this->contentType . "SearchTab";
        $this->searchRoute['q'] = $this->keywords;
        if ($this->advancedParams['contentType']['news']) {            
            $this->searchRoute['ct'] = 'news';
            $searchTabs["newsSearchTab"] = array('label' => AmcWm::t("{$this->basePath}.core", 'News'));
            $searchTabs["newsSearchTab"]['url'] = $this->createUrl();
        }

        if ($this->advancedParams['contentType']['articles']) {
            $this->searchRoute['ct'] = 'articles';
            $searchTabs["articlesSearchTab"] = array('label' => AmcWm::t("{$this->basePath}.core", 'Articles'));
            $searchTabs["articlesSearchTab"]['url'] = $this->createUrl();
        }

        if ($this->advancedParams['contentType']['multimedia']) {
            $this->searchRoute['ct'] = 'multimedia';
            $searchTabs["multimediaSearchTab"] = array('label' => AmcWm::t("{$this->basePath}.core", 'Multimedia'));
            $searchTabs["multimediaSearchTab"]['url'] = $this->createUrl();
        }

        if ($this->items['pager']['count']) {
            $tabOutput = '<div><ul>';
            foreach ($this->items['records'] as $row) {
                $tabOutput .='<li style="clear:both;">';
                if ($this->contentType == 'multimedia') {
                    $link = array($this->routers[$row['module']]['view'], 'id' => $row['id'], 'gid' => $row['gallery_id']);
                } else {
                    $link = array($this->routers[$row['module']]['view'], 'id' => $row['id']);
                }
                $tabOutput .= '<div class="date">';
                $tabOutput .= Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $row['publish_date']) . "\n";
                $tabOutput .= '</div>';
                $tabOutput .= '<h1 class="title">';
                $tabOutput .= Html::link("{$row['title']}", $link) . "\n";
                $tabOutput .= '</h1>';
                $tabOutput .= '<div class="disc">';
                $tabOutput .= Html::utfSubstring($row['detail'], 0, 150, true);
                $tabOutput .= '<div>';

                $tabOutput .='<div class="show_more">';
                $tabOutput .= Html::link(AmcWm::t("amcwm.modules.articles.frontend.messages.core", "show more") . '<span class="icon"></span>', $link) . "\n";
                $tabOutput .= '</div>';
                $tabOutput .='</li>';
            }
            $tabOutput .='</ul></div>';
            $searchTabs[$activeTab]['content'] = $tabOutput;
            $pages = new CPagination($this->items['pager']['count']);
            $pages->setPageSize($this->items['pager']['pageSize']);
            $pagesOutput = '<div class="pager_container">';
            $pagesOutput .= $this->widget('CLinkPager', array('pages' => $pages), true);
            $pagesOutput .= '</div>';
        } else {
            $searchTabs[$activeTab]['content'] = '&nbsp;';
            $pagesOutput = '';
        }
        $searchTabs[$activeTab]['active'] = true;
        $this->contentData .= $this->widget('bootstrap.widgets.TbTabs', array(
            'tabs' => $searchTabs,
                ), true);
        $this->contentData .= '</div>';
        $this->contentData .= $pagesOutput;
    }

}
