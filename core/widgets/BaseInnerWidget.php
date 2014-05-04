<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * InnerWidget extension class
 * @package AmcWebManager
 * @subpackage Extensions
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class BaseInnerWidget extends SideWidget {

    /**
     * Initializes the player widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->class = "inner_wdg_box";
        $this->contentClass = "inner_wdg_box_content";
        $this->contentClassWrapper = "inner_wdg_box_wrapper";
        $this->titleClass = "inner_wdg_box_head";
        parent::init();
    }

}