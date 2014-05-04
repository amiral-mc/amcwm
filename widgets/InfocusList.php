<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * InfocusList, displays the infocus list
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InfocusList extends SideWidget {

    /**
     * Infocus list items
     * Possible list names include the following:
     * <ul>
     * <li>records: array, specifies the records of the current page</li>    
     * <li>pager: array list of name-value pairs dataset.
     * Possible list names include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * </ul> 
     * @var array items
     */
    public $items = array();   

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {        
        $items = $this->items;
        $this->contentData = null;
        if ($items['pager']['count']) {
            $itemsData = $items["records"];
            if (count($itemsData)) {
                $this->contentData = CHtml::openTag('table', array("class" => "wdr_table")) . "\n";
                $this->contentData .= CHtml::openTag('tr') . "\n";
                $this->contentData .= CHtml::openTag('td', array("class" => "sec_border", "colspan" => 2)) . "\n";
                $this->contentData .= CHtml::openTag('span', array("class" => "sec_bg")) . "\n";
                $this->contentData .= Yii::t("infocus", "Indepth Articles");
                $this->contentData .= CHtml::closeTag('span') . "\n";
                $this->contentData .= CHtml::closeTag('td') . "\n";
                $this->contentData .= CHtml::closeTag('tr') . "\n";
                $class = false;
                foreach ($itemsData As $item) {
                    $bg = ($class) ? "sec_news_even" : "sec_news_odd";
                    $class = !$class;
                    //$itemImage = $media . $item['id'] . "." . $item["thumb"];
                    $this->contentData .= CHtml::openTag('tr') . "\n";
                    $this->contentData .= CHtml::openTag('td', array("class" => "wdr_table_right_col {$bg}", "colspan" => 2)) . "\n";
                    $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_img")) . "\n";
                    $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_img_inner")) . "\n";
                    if (file_exists(Yii::app()->basePath . "/../.." . $item['image'])) {
                        $this->contentData .= Html::link(CHtml::tag('img', array("src" => $item['image'])), $item['link']) . "\n";
                    } else {
                        $this->contentData .= Html::link(CHtml::tag('img', array("src" => Yii::app()->baseUrl . "/" . "images/front/" . Yii::app()->getLanguage() . "/no_image.jpg")), $item['link']) . "\n";
                    }
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                    $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_disc")) . "\n";
                    $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_title")) . "\n";
                    $this->contentData .= Html::link($item['title'], $item['link']) . "\n";
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                    $this->contentData .= CHtml::openTag('div', array("class" => "sec_news_date_time")) . "\n";
                    $this->contentData .= $item['publish_date'] . "\n";
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                    $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_disc")) . "\n";
                    $this->contentData .= Html::utfSubstring($item['brief'], 0, 150, true);
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                    $this->contentData .= CHtml::closeTag('td') . "\n";
                    $this->contentData .= CHtml::closeTag('tr') . "\n";
                }
                $this->contentData .= CHtml::closeTag('table') . "\n";
                $pages = new CPagination($items['pager']['count']);
                $pages->setPageSize($items['pager']['pageSize']);
                $this->contentData .= '<div class="pager_container">';
                $this->contentData .= $this->widget('CLinkPager', array('pages' => $pages), true);
                $this->contentData .= '</div>';
            }
        }
    }
}
