<?php

AmcWm::import('widgets.AmcArticlesListing');

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesListing extension class, displays section articles
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesListing extends AmcArticlesListing {
   

    /**
     * Draw header 
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawHeader($row) {
        $titleHeading = "h2";
        $output = '<div>';
        if ($this->viewOptions['showPrimaryHeader']) {
            $output .= '<div class="wd_content_disc"><h2 class="wd_content_title">';
            $output .= Html::link("{$row['priHeader']}", $row['link']) . "\n";
            $output .= '</h2></div>';
            $titleHeading = "h3";
        }

        // title container 
        $output .= '<div class="wd_content_disc"><' . $titleHeading . ' class="wd_content_title">';
        $output .= Html::link("{$row['title']}", $row['link']) . "\n";
        $output .= "</{$titleHeading}></div>";
        $output .= '</div>';
        return $output;
    }

    /**
     * Draw info bar
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawInfoBar($row) {
        $output = '<div class="wd_news_info">';
        if ($this->viewOptions['showDate']) {
            $output .= $row['publish_date'] . "\n";
        }
        if (isset($row['source']) && $row['source'] && $this->viewOptions['showSource']) {
            $output .= '<span class="wd_news_sp"> | </span>';
            $output .= '<span>' . AmcWm::t("amcwm.modules.articles.frontend.messages.news", "Source") . " : ";
            $output .= $row['source'] . '</span>';
        }
        if (isset($row['section_name']) && $this->viewOptions['showSectionName']) {
            $output .= '<span class="wd_news_sp"> | </span>';
            $output .= '<span>' . AmcWm::t("amcwm.modules.articles.frontend.messages.core", "Section Name") . " : ";
            $output .= $row['section_name'] . '</span>';
        }
        $output .= '</div>';
        return $output;
    }

    protected function drawDetails($row) {
        $output = '<div class="wd_content_disc">';
        $output .= Html::utfSubstring($row['article_detail'], 0, 150, true);
        $output .= '<div>';
        return $output;
    }

    /**
     * Draw Image
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawImage($row) {
        $output = '<div class="wd_content_img"><div class="wd_content_img_inner">';
        if ($row['image']) {
            $output .= Html::link(CHtml::tag('img', array("src" => $row['image'])), $row['link']) . "\n";
        } else {
            //$output .= Html::link(CHtml::tag('img', array("src" => Yii::app()->baseUrl . "/" . "images/front/" . Yii::app()->getLanguage() . "/no_image.jpg")), $sectionArticle['link']) . "\n";
            if (isset($this->viewOptions['noImageListing'])) {
                $output .= Html::link(CHtml::tag('img', array("src" => $this->viewOptions['noImageListing'])), $row['link']) . "\n";
            }
        }
        $output .= '</div></div>';
        return $output;
    }

    /**
     * Draw Top articles
     * @access protected
     * @return string
     */
    protected function drawTop() {
        $settings = Settings::getModuleSettings("articles");
        $mediaFirstPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $settings['media']['paths']['sections']['path']) . DIRECTORY_SEPARATOR;
        $mediaFirst = Yii::app()->baseUrl . "/" . $settings['media']['paths']['sections']['path'] . "/";
        $output = null;
        if (count($this->items['top'])) {
            $output .= CHtml::openTag('div', array("class" => "sec_main_item")) . "\n";
            $output .= CHtml::openTag('table') . "\n";
            $output .= CHtml::openTag('tr') . "\n";
            $output .= CHtml::openTag('td', array("colspan" => 2)) . "\n";
            $output .= CHtml::openTag('h1', array("class" => "sec_main_item_title")) . "\n";
            $output .= Html::link($this->items['top'][0]['title'], $this->items['top'][0]['link']) . "\n";
            $output .= CHtml::closeTag('div') . "\n";
            $output .= CHtml::closeTag('td') . "\n";
            $output .= CHtml::closeTag('tr') . "\n";
            $output .= CHtml::openTag('tr') . "\n";

            $output .= CHtml::openTag('td', array("style" => "vertical-align:top")) . "\n";
            $output .= CHtml::openTag('div') . "\n";
            if ($this->items['top'][0]['thumb'] && file_exists($mediaFirstPath . $this->items['top'][0]['id'] . "." . $this->items['top'][0]["thumb"])) {
                $output .= CHtml::tag('img', array("src" => $mediaFirst . $this->items['top'][0]['id'] . "." . $this->items['top'][0]["thumb"])) . "\n";
            }
            $output .= CHtml::closeTag('div') . "\n";
            $output .= CHtml::closeTag('td') . "\n";
            $output .= CHtml::openTag('td', array("style" => "vertical-align:top")) . "\n";
            $output .= CHtml::openTag('div', array("class" => "sec_main_item_brief")) . "\n";
            $output .= Html::utfSubstring($this->items['top'][0]['article_detail'], 0, 300, true);
            $output .= CHtml::closeTag('div') . "\n";
            $output .= CHtml::openTag('div', array("class" => "dotted_line")) . "\n";
            $output .= CHtml::closeTag('div') . "\n";
            $output .= CHtml::openTag('div', array("class" => "sec_main_item_readmore")) . "\n";
            $output .= AmcWm::t("amcwm.modules.articles.frontend.messages.core", "Read also");
            $output .= CHtml::closeTag('div') . "\n";
            $output .= CHtml::openTag('div') . "\n";
            $output .= CHtml::openTag('ul', array("class" => "sec_main_item_list"));
            $articlesTopCount = count($this->items['top']);
            for ($articleIndex = 1; $articleIndex < $articlesTopCount; $articleIndex++) {
                $output .= CHtml::openTag('li', array("class" => "sec_main_item_list"));
                $output .= Html::link($this->items['top'][$articleIndex]['title'], $this->items['top'][$articleIndex]['link']);
                $output .= CHtml::closeTag('li') . "\n";
            }
            $output .= CHtml::closeTag('ul') . "\n";
            $output .= CHtml::closeTag('div') . "\n";

            $output .= CHtml::closeTag('td') . "\n";
            $output .= CHtml::closeTag('tr') . "\n";

            $output .= CHtml::closeTag('table') . "\n";
            $output .= CHtml::closeTag('div') . "\n";
            /////////////////////          
        }
        return $output;
    }

    /**
     * Draw listing title
     * @access protected
     * @return string
     */
    protected function drawListingTitle() {
        $output = null;
        if ($this->viewOptions['showListingTitle']) {
            $output .= CHtml::openTag('tr') . "\n";
            $output .= CHtml::openTag('td', array("class" => "sec_border", "colspan" => 2)) . "\n";
            $output .= CHtml::openTag('span', array("class" => "sec_bg")) . "\n";
            $output .= AmcWm::t("amcwm.modules.articles.frontend.messages.core", "More About:") . " " . $this->items['sectionTitle'];
            $output .= CHtml::closeTag('span') . "\n";
            $output .= CHtml::closeTag('td') . "\n";
            $output .= CHtml::closeTag('tr') . "\n";
        }
        return $output;
    }

    /**
     * Set the data content of this widget
     * @access protected
     * @return void
     */
    protected function setContentData() {
        $this->contentData = $this->drawTop();
        if (count($this->items['records'])) {
            $this->contentData .= CHtml::openTag('table', array("class" => "wdr_table", "cellpadding" => "0")) . "\n";
             $this->contentData .=$this->drawListingTitle();
            $class = false;
            foreach ($this->items['records'] As $sectionArticle) {
                $bg = ($class) ? "sec_content_even" : "sec_content_odd";
                $class = !$class;
                $this->contentData .= CHtml::openTag('tr') . "\n";
                $this->contentData .= CHtml::openTag('td', array("class" => "wdr_table_right_col {$bg}", "colspan" => 2)) . "\n";
                $this->contentData .= '<div style="clear:both;">';
                // primary header container        
                foreach($this->viewOptions['listingRowOrders'] as $rowPart){
                    $methodPart = "draw{$rowPart}";
                    $this->contentData .= $this->$methodPart($sectionArticle);
                }                              
                $this->contentData .= '<div>';
                $this->contentData .= CHtml::closeTag('td') . "\n";
                $this->contentData .= CHtml::closeTag('tr') . "\n";
            }
            $this->contentData .= CHtml::closeTag('table') . "\n";
            $pages = new CPagination($this->items['pager']['count']);
            $pages->setPageSize($this->items['pager']['pageSize']);
            $this->contentData .= '<div class="pager_container">';
            $this->contentData .= $this->widget('CLinkPager', array('pages' => $pages), true);
            $this->contentData .= '</div>';
        }
    }

}
