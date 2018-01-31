<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ConsoleApplication extends CConsoleApplication by providing AamcWm functionalities
 * @package AmcWm.web
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ConsoleApplication extends CConsoleApplication
{

    /**
     *
     * @var string default content language; 
     */
    public $contentLang = null;

    /**
     * Initializes the application.
     * This method overrides the parent implementation by preloading the 'request' component.
     * @access protected
     * @return void
     */
    protected function init() {
        if (function_exists("mb_internal_encoding")) {
            mb_internal_encoding($this->charset);
        } 
        parent::init();
    }

}
