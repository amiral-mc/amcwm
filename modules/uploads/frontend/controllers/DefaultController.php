<?php
AmcWm::import("amcwm.modules.uploads.components.UploadsController");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * RTE Manager
 * @package AmcWm.core.controllers
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DefaultController extends UploadsController {
   /**
     * Default action
     */
    public function actionIndex($op, $dialog = null) {             
        $this->renderFileManager($op, $dialog, false);
    }

}