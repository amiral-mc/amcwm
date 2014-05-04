<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * InfocusWidget extension class, displays infocus contents
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InfocusWidget extends CWidget {

    /**
     * @var array items
     */
    public $data = array();
    /**
     * content type to search in
     * @var string
     */
    public $contentType = 'text';
    /**
     * Current page
     * @var int 
     */
    public $page = 1;
    /**
     * Infocus id, append it to infocus router. The infocus DefaultControler whill create instance InFocusData class based on this id
     * @var integer 
     */
    public $focusId = 0;
    /**
     * modules routes array
     * @todo discribe array elements here
     * @var array
     */
    public $routers = array();
    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

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
        $output = "";
        $infocusTabs = array();
        $activeTab = $this->contentType . "InfocusTab";
        $infocusTabs["textInfocusTab"] = array('title' => Yii::t('search', 'Text News'));
        $infocusTabs["multimediaInfocusTab"] = array('title' => Yii::t('search', 'Multimedia News'));
        switch ($this->contentType) {
            case 'text':
                $media = Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths['list']['path'] . "/";
                $mediaPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ArticlesListData::getSettings()->mediaPaths['list']['path']) . DIRECTORY_SEPARATOR;
                $infocusTabs["multimediaInfocusTab"]['url'] = Html::createUrl('/infocus/default/view', array("id" => $this->focusId, 'ct' => 'multimedia', 'page' => 1));
                break;
            case 'multimedia':
                $infocusTabs["textInfocusTab"]['url'] = Html::createUrl('/infocus/default/view', array("id" => $this->focusId, 'ct' => 'text', 'page' => 1));
                break;
        }
        
        if ($this->data['pager']['count']) {
            $articlesOutput = CHtml::openTag('table', array("class" => "wdr_table")) . "\n";
            $class = false;
            foreach ($this->data['records'] as $row) {
                if($row["image"]){
                    $itemImage =  $row["image"];       
                }
                else{
                    $itemImage = Yii::app()->baseUrl . "/" . "images/front/" . Yii::app()->getLanguage() . "/no_image.jpg";                    
                }
                
                $bg = ($class) ? "sec_news_even" : "sec_news_odd";
                $class = !$class;
                if ($this->contentType == 'multimedia') {
                    $link = array($this->routers[$row['module']]['view'], 'id' => $row['id'], 'gid' => $row['gallery_id']);
                } else {
                    $link = array($this->routers[$row['module']]['view'], 'id' => $row['id']);
                }                
                $articlesOutput .= CHtml::openTag('tr') . "\n";
                $articlesOutput .= CHtml::openTag('td', array("class" => "wdr_table_right_col {$bg}", "colspan" => 2)) . "\n";
                if ($itemImage && $row['imageExt']) {
                    $articlesOutput .= CHtml::openTag('div', array("class" => "wd_news_img")) . "\n";
                    $articlesOutput .= CHtml::openTag('div', array("class" => "wd_news_img_inner")) . "\n";
                    $articlesOutput .= CHtml::tag('img', array("src" => $itemImage)) . "\n";
                    $articlesOutput .= CHtml::closeTag('div') . "\n";
                    $articlesOutput .= CHtml::closeTag('div') . "\n";
                }
                $articlesOutput .= CHtml::openTag('div', array("class" => "wd_news_disc")) . "\n";
                $articlesOutput .= CHtml::openTag('div', array("class" => "wd_news_title")) . "\n";
                $articlesOutput .= Html::link("{$row['title']}", $link) . "\n";
                $articlesOutput .= CHtml::closeTag('div') . "\n";
                $articlesOutput .= CHtml::openTag('div', array("class" => "sec_news_date_time")) . "\n";
                $articlesOutput .= $row['publish_date'] . "\n";
                $articlesOutput .= CHtml::closeTag('div') . "\n";
                $articlesOutput .= CHtml::openTag('div', array("class" => "wd_news_disc")) . "\n";
                $articlesOutput .= Html::utfSubstring($row['detail'], 0, 150, true);
                $articlesOutput .= Html::link(Yii::t('search', 'More'), $link, array('class' => 'search_more')) . "\n";
                $articlesOutput .= CHtml::closeTag('div') . "\n";
                $articlesOutput .= CHtml::closeTag('div') . "\n";
                $articlesOutput .= CHtml::closeTag('td') . "\n";
                $articlesOutput .= CHtml::closeTag('tr') . "\n";
            }

            $articlesOutput .= CHtml::closeTag('table') . "\n";
            $pages = new CPagination($this->data['pager']['count']);
            $pages->setPageSize($this->data['pager']['pageSize']);
            $articlesOutput .= '<div class="pager_container">';
            $articlesOutput .= $this->widget('CLinkPager', array('pages' => $pages), true);
            $articlesOutput .= '</div>';
            $infocusTabs[$activeTab]['content'] = $articlesOutput;
        } else {
            $articlesOutput = '<table border="0" cellspacing="1" cellpadding="2" width="100%">';
            $articlesOutput .= '<tr>';
            $articlesOutput .= '<td>';
            $articlesOutput .= Yii::t('infocus', 'No results has been founds');
            $articlesOutput .= '</tr>';
            $articlesOutput .= '</table>';
            $infocusTabs[$activeTab]['content'] = $articlesOutput;
        }
        echo CHtml::openTag('div', $this->htmlOptions) . "\n";
        $this->widget('TabView', array('activeTab' => $activeTab, 'tabs' => $infocusTabs));
        echo CHtml::closeTag('div') . "\n";
    }

    /**
     * Get video id from video url
     * @access public
     * @return string
     */
    public static function getVideoCode($video) {
        return Html::getVideoCode($video);
    }

}
