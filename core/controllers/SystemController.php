<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * FrontendController, Controller is the base controller class.
 * All controller classes for this application should extend from this base class.
 * @package AmcWm.core.controllers
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SystemController extends Controller {

    public function init() {        
        AmcWm::app()->setLayoutPath(AmcWm::getPathOfAlias("amcwm.core.system.views.layouts"));
        $this->viewPath = AmcWm::getPathOfAlias("amcwm.core.system.views");
        parent::init();
        
    }
}
