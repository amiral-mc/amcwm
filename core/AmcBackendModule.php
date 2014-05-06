<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AmcBackendModule Backend module
 * @package AmcWm.modules
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcBackendModule extends CWebModule {

    /**
     * @var mixed the views layout alias path used in error that is shared by the controllers inside this module.
     */
    public $viewsBaseAlias = null;

    /**
     * Initializes the WebModule.
     * This method is called by the application before the WebModule starts to execute.
     * You may override this method to perform the needed initialization for the WebModule.
     * @access public
     * @return void
     */
    public function init() {
        AmcWm::app()->setIsBackend(true);
        $this->viewsBaseAlias = "amcwm.core.backend.views";
        $this->layout = $this->viewsBaseAlias . ".layouts.main";
        $this->setViewPath(AmcWm::getPathOfAlias($this->viewsBaseAlias));
        Yii::app()->errorHandler->errorAction = '/backend/default/error';
        Yii::app()->homeUrl = Html::createUrl("/backend/default/index");
        Yii::app()->user->loginUrl = array('/backend/default/login');


        AmcWm::app()->setLayoutPath(AmcWm::getPathOfAlias("{$this->viewsBaseAlias}.layouts"));
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'amcwm.core.backend.*',
            'amcwm.core.backend.controllers.*',
            'amcwm.core.backend.components.*',
            'amcwm.core.backend.models.*',
        ));
        parent::init();
    }

}
