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

class AmcGalleriesController extends FrontendController {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionVideos() {
        $this->forward("videos/");
    }

    public function actionImages() {
        $this->forward("images/");
    }

    public function actionPresentations() {
        $allOptions = $this->module->appModule->options;
        if (isset($allOptions['default']['integer']['presentationId']) && $allOptions['default']['integer']['presentationId']) {
            $_GET['c'] = $allOptions['default']['integer']['presentationId'];
            $this->forward("/documents/default/index");
        }
        else{
            throw new CHttpException(404, AmcWm::t('msgsbase.core', 'The requested page does not exist'));
        }
        
    }

    public function actionSelect($lib) {
        $this->forward($lib);
    }

}
