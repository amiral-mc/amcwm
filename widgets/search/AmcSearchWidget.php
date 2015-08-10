<?php
/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SearchLiWidget extension class, displays site contents search result
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcSearchWidget extends PageContentWidget {

    /**
     * @var array items
     */
    public $items = array();
    /**
     * content type to search in
     * @var string
     */
    public $contentType = 'news';
    /**
     * Current page
     * @var int 
     */
    public $page = 1;
    /**
     * Searching Keywords 
     * @var string 
     */
    public $keywords = "";
    /**
     * modules routes array
     * @todo discribe array elements here
     * @var array
     */
    public $routers = array();
    
    /**
     *
     * @var string search route 
     */
    public $searchRoute = array('/site/search');
   
    /**
     * Advanced parameters array , used in adavanced search
     * @todo discribe array elements here
     * @var array 
     */
    public $advancedParams = array();

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        $this->keywords = CHtml::encode($this->keywords);
        parent::init();
    }       
    
    /**
     * Create search url
     * @param integer $page
     */
    public function createUrl($page = 1){
        
        $params = $this->searchRoute;
        $route = array_shift($params);
        return Html::createUrl($route, $params);
         
    }
}
