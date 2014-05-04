<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * VideosSliderData class,  Gets the videos to displayed inside slider area.
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VideosSliderData extends VideosListData {

    /**
     * Generate the videos list array, each item is associated  array
     * @access public
     * @return void
     */
    public function generate() {        
        $this->addWhere("t.in_slider = 1");        
        parent::generate();        
    }
}