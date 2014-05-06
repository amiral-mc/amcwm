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
class AmcBackgroundsController extends AmcImagesController {
    public function init() {
        parent::init();
        $this->isBackground = 1;
        $mediaPaths = $this->getModule()->appModule->mediaPaths;
        $this->imageInfo = $mediaPaths['backgrounds'];
        $this->imageInfo['errorMessage']['exact'] = AmcWm::t("amcFront", 'Supported image dimensions between  "{width} x {height}"');
        $this->imageInfo['errorMessage']['notexact'] = AmcWm::t("amcFront", 'Image width must be less than {width}, Image height must be less than {height}');        
    }

}
