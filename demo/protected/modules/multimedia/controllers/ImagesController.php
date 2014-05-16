<?php

/**
 * Copyright 2012, Amiral Management Corporation. All Rights Reserved.
 * @package  AmcWebManager
 */

/**
 * DefaultController for images module
 * @package  AmcWebManager
 * @copyright   2012, Amiral Management Corporation. All Rights Reserved..
 * @author      Amiral Management Corporation
 * @version     1.0
 */
class ImagesController extends AmcImagesController{
    /**
     * Change the view alias of this method
     * @param string $view
     * @param array $data
     */
    public function beforeRenderIndex(&$view, &$data) {
        $view = 'application.modules.multimedia.views.images.index';        
    }
}
