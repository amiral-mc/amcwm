<?php

AmcWm::import("widgets.search.SearchWidget");
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
class InfocusWidget extends SearchWidget {
        
    /**
     *
     * @var string search route 
     */
    public $searchRoute = array('/infocus/default/view');
    
    /**
    * @var array
    */
    public $infocusData;
    /**
     * Render the widget and display the result
     * @todo implement background / and banner in this widget
     * @access public
     * @return void
     */
    public function setContentData() {
        $this->searchRoute['id'] = $this->infocusData['infocus_id'];
        parent::setContentData();
    }

}
