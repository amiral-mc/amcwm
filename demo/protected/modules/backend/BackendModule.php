<?php

class BackendModule extends AmcBackendModule {

    public function init() {
        //$this->layoutPath = "amcwm.modules.layouts.backend.main";
        //echo $this->layoutPath;
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'backend.models.*',
            'application.modules.backend.components.*',
        ));
        parent::init();
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }

}
