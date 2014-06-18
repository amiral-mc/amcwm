<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MediaSliderData class,  Gets the media to displayed inside slider area.
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MediaSliderData extends MediaListData {

    /**
     * Generate the videos list array, each item is associated  array
     * @access public
     * @return void
     */
    public function generate() {        
        $this->addOrder("creation_date desc");
        $this->addWhere('in_slider = 1');
        $this->addColumn("in_slider");        
        parent::generate();        
    }
}