<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionsList extends PageContentWidget {

    /**
     * @var array  with predefind colors     
     */
    public $sectionColors = array("#567692", "#585858", "#1B8225", "#73337D", "#F89D23");

    /**
     * @var array items
     */
    public $items = array();

    /**
     * the widget between the sections list
     * @var array wdgtSeparator
     */
    public $wdgtSeparator = array("data" => array(), "after" => false);

    /**
     * internal css class
     * @var string 
     */
    public $internalClass = "wdr_table";

    /**
     * Set the data content of this widget
     * @access protected
     * @return void
     */
    protected function setContentData() {
        $sectionColor = 0;
        if (count($this->items)) {
            $itemRef = 0;
            foreach ($this->items as $item) {

                $this->contentData .= CHtml::openTag('table', array("class" => $this->internalClass, "cellspacing" => "0", "cellpadding" => "0")) . "\n";
                // start WD header
                $this->contentData .= CHtml::openTag('tr') . "\n";
                $this->contentData .= CHtml::openTag('td', array('class' => 'news_section_name_border', "style" => "border-bottom:2px {$this->sectionColors[$sectionColor]} solid;", "colspan" => 2)) . "\n";
                $this->contentData .= CHtml::openTag('h4', array("class" => "news_section_name", "style" => "margin:0; background:" . $this->sectionColors[$sectionColor])) . "\n";
                $this->contentData .= '<a href="' . $item['data']['link'] . '">' . $item['data']['title'] . '</a>';
                $this->contentData .= CHtml::closeTag('h4') . "\n";
                $this->contentData .= CHtml::closeTag('td') . "\n";
                $this->contentData .= CHtml::closeTag('tr') . "\n";
                // end the WD header
                // the WD content
                $this->contentData .= CHtml::openTag('tr') . "\n";
                $this->contentData .= CHtml::openTag('td', array("class" => "wdr_table_right_col")) . "\n";
                $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_title")) . "\n";
                $this->contentData .= CHtml::openTag('h1') . "\n";
                $this->contentData .= Html::link(CHtml::encode($item['childs'][0]["title"]), $item['childs'][0]["link"]);
                $this->contentData .= CHtml::closeTag('h1') . "\n";
                $this->contentData .= CHtml::closeTag('div') . "\n";

                if ($item['childs'][0]["image"]) {
                    $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_img")) . "\n";
                    $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_img_inner")) . "\n";
                    $this->contentData .= CHtml::tag('img', array("src" => $item['childs'][0]["image"])) . "\n";
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                    $this->contentData .= CHtml::closeTag('div') . "\n";
                }

                $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_disc")) . "\n";
                $this->contentData .= Html::utfSubstring($item['childs'][0]["article_detail"], 0, 200, true);
                $this->contentData .= CHtml::closeTag('div') . "\n";

                $this->contentData .= CHtml::openTag('div', array("class" => "wd_news_more")) . "\n";
                $this->contentData .= Html::link(AmcWm::t("amcFront", "More"), $item['childs'][0]["link"]);
                $this->contentData .= CHtml::closeTag('div') . "\n";

                $this->contentData .= CHtml::closeTag('td') . "\n";

                // ------------------------
                if (count($item['childs']) > 1) {
                    $this->contentData .= CHtml::openTag('td', array("class" => "wdr_table_left_col", "valign" => "top")) . "\n";
                    $this->contentData .= CHtml::openTag('ul', array("class" => "wdr_news_sub")) . "\n";
                    for ($i = 1; $i < count($item['childs']); $i++) {
                        $this->contentData .= CHtml::openTag('li') . "\n";
                        $this->contentData .= CHtml::openTag('h2') . "\n";
                        $this->contentData .= Html::link(CHtml::encode($item['childs'][$i]["title"]), $item['childs'][$i]["link"]);
                        $this->contentData .= CHtml::closeTag('h2') . "\n";
                        $this->contentData .= CHtml::closeTag('li') . "\n";
                    }
                    $this->contentData .= CHtml::closeTag('ul') . "\n";
                    $this->contentData .= CHtml::closeTag('td') . "\n";
                }
                $this->contentData .= CHtml::closeTag('tr') . "\n";

                $this->contentData .= CHtml::closeTag('table') . "\n";


                if ($itemRef == (int) $this->wdgtSeparator["after"]) {
                    $this->contentData .= $this->wdgtSeparator["data"];
                }
                $itemRef++;

                if ($sectionColor == count($this->sectionColors) - 1) {
                    $sectionColor = 0;
                } else {
                    $sectionColor++;
                }
            }
        }
    }
}