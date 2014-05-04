<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesListing extension class, displays section articles
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcArticlesListing extends PageContentWidget {

    /**
     * Description index key
     * @var string 
     */
    public $descriptionKey = "article_detail";

    /**
     * row view options 
     * @var array
     */
    public $viewOptions = array();

    /**
     * @var array list of name-value pairs dataset.
     * Possible list names include the following:
     * <ul>
     * <li>records: array, specifies the records of the current page</li>    
     * <li>top: array, specifies the top articles displayed before list</li>    
     * <li>sectionTitle: string section name
     * <li>pager: array list of name-value pairs dataset.
     * Possible list names include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * </ul>
     */
    public $items = null;

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        if (!isset($this->items['top'])) {
            $this->items['top'] = array();
        }        
        $viewOptions = array('listingRowOrders' => array('header' => 'header', 'infoBar' => 'infoBar', 'image' => 'image', 'details' => 'details'), 'showSource' => true, 'showSectionName' => true, 'showPrimaryHeader' => false, 'showDate' => true, 'showListingTitle' => false);
        foreach ($viewOptions as $option => $optionValue) {
            if (!array_key_exists($option, $this->viewOptions)) {
                $this->viewOptions[$option] = $optionValue;
            }
        }
        
        parent::init();
    }

    /**
     * Draw header 
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawHeader($row) {
        return null;
    }

    /**
     * Draw info bar
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawInfoBar($row) {
        return null;
    }

    protected function drawDetails($row) {
        return null;
    }

    /**
     * Draw Image
     * @access protected
     * @param array $row
     * @return string
     */
    protected function drawImage($row) {
        return null;
    }

    /**
     * Draw Top articles
     * @access protected
     * @return string
     */
    protected function drawTop() {
        return null;
    }

    /**
     * Draw listing title
     * @access protected
     * @return string
     */
    protected function drawListingTitle() {
        return null;
    }   
}
