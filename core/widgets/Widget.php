<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Widget extension class
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Widget extends CWidget {

    /**
     * @var string base path for extension
     */
    protected $basePath = false;
    
     /**
     * @var string base path for extension
     */
    protected $messageFile = null;
   
    /**
     * Constructor.
     * @param CBaseController $owner owner/creator of this widget. It could be either a widget or a controller.
     * If constructor is overridden, make sure the parent implementation is invoked.
     */
    public function __construct($owner = null) {
        $class = new ReflectionClass($this);
        $this->basePath = dirname($class->getFileName());
        $this->messageFile = "{$this->basePath}.core";
        parent::__construct($owner);
    }

}