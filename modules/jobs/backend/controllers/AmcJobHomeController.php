<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcJobHomeController extends BackendController {

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $options = $this->module->appModule->options;
        if ($options['default']['integer']['allowJobs']) {
            $this->render('index', array('options' => $options));
        } else {
            $this->forward('requests/');
        }
    }    
}