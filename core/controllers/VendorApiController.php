<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * VendorApi
 * @package  AmcWm.core.controllers
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VendorApiController extends SystemController {

    /**
     * Default action for the editor
     */
    public function actionIndex($lib, $action = null) {
        if (!$action) {
            $action = $this->getAction()->getId();
        }
        VendorApiManager::getApi($lib, $action, array(), AmcWm::app()->params['proxy']);
    }

}