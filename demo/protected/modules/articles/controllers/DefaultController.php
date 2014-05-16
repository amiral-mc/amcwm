<?php

/**
 * Copyright 2012, Amiral Management Corporation. All Rights Reserved.
 * @package  AmcWebManager
 */

/**
 * DefaultController for articles module
 * @package  AmcWebManager
 * @copyright   2012, Amiral Management Corporation. All Rights Reserved..
 * @author      Amiral Management Corporation
 * @version     1.0
 */
class DefaultController extends AmcArticlesController {

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     */
    public function init() {
        $this->viewPath = AmcWm::getPathOfAlias('application.modules.articles.views');
        parent::init();
    }
}
