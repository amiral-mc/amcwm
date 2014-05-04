<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SiteMapWidget extension class, displays sitemap contents result
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SiteMapWidget extends PageContentWidget {

    /**
     *number of cells displayed in each row
     * @var integer 
     */
    public $rowCellsCount = 4;
      /**
     * internal css class
     * @var string 
     */
    public $internalClass = 'sitemap';

    /**
     * Maps list items
     * @var array items
     */
    public $items;
   
     /**
     * Set the data content of this widget
     * @access protected
     * @return void
     */
    protected function setContentData() {
        $this->contentData .= '<div class="' . $this->internalClass . '">';
        $imageIcon = Yii::app()->request->baseUrl . "/images/front/sitemap_icon.gif";
        $i = 1;
        foreach ($this->items as $menuId=>$item) {
            $this->contentData.='<div class="sitemap_root">';
            $url = null;
            $htmlOptions = array();
            if (is_array($item['url']) && count($item['url'])) {
                $url = $item['url'];
            } else if ($item['url']) {
                $url = $item['url'];
                $linkData = parse_url($url);
                if (isset($linkData['scheme'])) {
                    $htmlOptions['target'] = "_blank";
                }
            }
            if ($url) {
                $rootItem = Html::link($item['label'], $url, $htmlOptions);
            } else {
                $rootItem = $item['label'];
            }
            $this->contentData.='<h1><img src="' . $imageIcon . '" alt="' . CHtml::encode($item['label']) . '" /><span>' .$rootItem . '</span></h1>';
            if (isset($item['items'])) {
                $this->contentData.= $this->_drawLevelChilds($item['items']);
            }
            $this->contentData.='</div>';
            if(!($i % $this->rowCellsCount)){
                $this->contentData .= '<br class="clearfloat" />' ;
            }
            $i++;      
        }
        $this->contentData .= '</div>';        
    }

    /**
     * Draw childs 
     * @param array $childs
     * @access private
     * @return void
     */
    private function _drawLevelChilds($childs, $i = 2) {
        $this->contentData .= '<ul class="sitemap_L' . $i . '">';
        foreach ($childs as $item) {
            $url = null;
            $htmlOptions = array();
            if (is_array($item['url']) && count($item['url'])) {
                $url = $item['url'];
            } else if ($item['url']) {
                $url = $item['url'];
                $linkData = parse_url($url);
                if (isset($linkData['scheme'])) {
                    $htmlOptions['target'] = "_blank";
                }
            }
            if ($url) {
                $childItem = Html::link($item['label'], $url, $htmlOptions);
            } else {
                $childItem = $item['label'];
            }
            $this->contentData.='<li>' . $childItem ;
            if (isset($item['items'])) {
                $this->contentData.= $this->_drawLevelChilds($item['items'], $i + 1);
            }
            $this->contentData.= '</li>';
        }
        $this->contentData.='</ul>';        
    }

}