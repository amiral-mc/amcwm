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
class TabView extends CTabView {

    public $useCustomCSS = true;
     /**
     * Initializes the scroller widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        parent::init();
    }
    /**
     * Renders the header part.
     */
    public function run(){
        if($this->useCustomCSS){
            $this->cssFile = Yii::app()->request->baseUrl . '/css/'.Yii::app()->getLanguage() .'/tabs.css';
        }
        parent::run();
    }


    protected function renderHeader() {
        echo "<ul class=\"tabs\">\n";
        foreach ($this->tabs as $id => $tab) {
            $title = isset($tab['title']) ? $tab['title'] : 'undefined';
            $active = $id === $this->activeTab ? ' class="active"' : '';
            $url = isset($tab['url']) ? $tab['url'] : "#{$id}";
            echo "<li><a href=\"{$url}\"{$active} id=\"{$id}Link\">{$title}</a></li>\n";
        }
        echo "</ul>\n";
    }
}
