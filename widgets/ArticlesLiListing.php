<?php

AmcWm::import("widgets.AmcArticlesListing");

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
class ArticlesLiListing extends AmcArticlesListing {

    public $listingId = "articles_list";

    /**
     * Draw header 
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawHeader($row) {
        $output = '<h1 class="title">';
        $output .= Html::link("{$row['title']}", $row['link']) . "\n";
        $output .= '</h1>';
        if ($this->viewOptions['showPrimaryHeader']) {
            $output .= '<h2 class="title">';
            $output .= Html::link("{$row['priHeader']}", $row['link']) . "\n";
            $output .= '</h2>';
        }


        return $output;
    }

    /**
     * Draw info bar
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawInfoBar($row) {
        $output = '<div class="date">';
        if ($this->viewOptions['showDate']) {
            $output .= Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $row['publish_date']) . "\n";
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
        $output = '<div class="disc">';
        $output .= Html::utfSubstring($row[$this->descriptionKey], 0, 150, true);
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
            $output .= '<div class="top-items">';
            $output .= '<h1>';
            $output .= Html::link($this->items['top'][0]['title'], $this->items['top'][0]['link']) . "\n";
            $output .= '</h1>';
            if ($this->items['top'][0]['thumb'] && file_exists($mediaFirstPath . $this->items['top'][0]['id'] . "." . $this->items['top'][0]["thumb"])) {
                $output .= CHtml::tag('img', array("src" => $mediaFirst . $this->items['top'][0]['id'] . "." . $this->items['top'][0]["thumb"])) . "\n";
            }
            $output .= '<div>';
            $output .= AmcWm::t("amcwm.modules.articles.frontend.messages.core", "Read also");
            $output .= '</div>';
            $output .= '<ul>';
            $articlesTopCount = count($this->items['top']);
            for ($articleIndex = 1; $articleIndex < $articlesTopCount; $articleIndex++) {
                $output .= '<li>';
                $output .= Html::link($this->items['top'][$articleIndex]['title'], $this->items['top'][$articleIndex]['link']);
                $output .= '</li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
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
            $output .= '<div class="articles_section_title">';
            $output .= AmcWm::t("amcwm.modules.articles.frontend.messages.core", "More About:") . " " . $this->items['sectionTitle'];
            $output .= "</div>";
        }
        return $output;
    }

    /**
     * Set the data content of this widget
     * @access protected
     * @return void
     */
    protected function setContentData() {
        $this->contentData .= $this->drawTop();
        if (count($this->items['records'])) {
            $this->contentData .= CHtml::openTag('div', array("id" => $this->listingId)) . "\n";
            $this->contentData .='<ul>';
            $this->contentData .=$this->drawListingTitle();
            foreach ($this->items['records'] As $sectionArticle) {
                $this->contentData .='<li style="clear:both;">';
                foreach ($this->viewOptions['listingRowOrders'] as $rowPart) {
                    $methodPart = "draw{$rowPart}";
                    $this->contentData .= $this->$methodPart($sectionArticle);
                }
                $this->contentData .='<div class="show_more">';
                $this->contentData .= Html::link(AmcWm::t("amcwm.modules.articles.frontend.messages.core", "show more") . '<span class="icon"></span>', $sectionArticle['link']) . "\n";
                $this->contentData .= '</div>';
                $this->contentData .='</li>';
            }
            $this->contentData .='</ul>';
            $this->contentData .= CHtml::closeTag('div') . "\n";
            $pages = new CPagination($this->items['pager']['count']);
            $pages->setPageSize($this->items['pager']['pageSize']);
            $this->contentData .= '<div class="pager_container">';
            $this->contentData .= $this->widget('CLinkPager', array('pages' => $pages), true);
            $this->contentData .= '</div>';
        }
    }

}
