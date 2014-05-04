<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * MostReadInSections extension class, displays the most read article for each "N" numbers of sections
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MostReadInSections extends CWidget {

    /**
     * class name, css class name
     * @var string 
     */
    public $className = 'mostread-wrapper';
    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();
    /**
     * @var array of data to display it
     */
    public $data = array();

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
     * Calls {@link runItem} to render each article row.
     * @access public
     * @return void
     */
    public function run() {
        $output = " ";
        $output .= CHtml::openTag('div', $this->htmlOptions) . "\n";
        //foreach ($this->data as $article) {
        $dataCount = count($this->data);
        if($dataCount){
            for ($i = 0; $i < $dataCount - 1; $i++) {
                $output.= $this->_runItem($this->data[$i]);
                $output .= CHtml::openTag('div', array("class" => "main_topics_v_line")) . "\n";
                $output .= CHtml::closeTag('div') . "\n";
            }
            $output.= $this->_runItem($this->data[$dataCount - 1]);
        }
        $output .= CHtml::closeTag('div') . "\n";        
        echo $output;
    }
    /**
     * Renders each article output
     * each article sent to this method is associated  array that contain's the following items:
     * <ul>
     * <li>sectionLink: string, link for displaying section list</li>
     * <li>sectionName: string, article section name</li>
     * <li>headerText: string, article title</li>
     * <li>headerImage: string, link for article image</li>
     * </ul>
     * @param array $article, article dataset
     * @access private
     * @return void
     */
    private function _runItem($article) {
        $output = CHtml::openTag('div', array("class" => "main_topics_item")) . "\n";
        $output .= CHtml::openTag('div', array("class" => "main_topics_pic")) . "\n";
        $output .= CHtml::openTag('div', array("class" => "main_topics_pic_inner")) . "\n";
        $output .= CHtml::tag('img', array("src" => $article["headerImage"])) . "\n";
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('div');
        $output .= CHtml::openTag('div', array("id" => "topic_content")) . "\n";
        $output .= CHtml::openTag('div', array("class" => "main_topics_title")) . "\n";
        $output .= Html::link($article["sectionName"], $article['sectionLink']) . "\n";
        $output .= CHtml::closeTag('div');
        $output .= CHtml::openTag('div', array("class" => "main_topics_dics")) . "\n";
        $output .= Html::link($article["headerText"], $article["link"]) . "\n";
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('div');
        $output .= CHtml::closeTag('div');
        return $output;
    }
}