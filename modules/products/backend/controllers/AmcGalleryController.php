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
class AmcGalleryController extends BackendController {

    public function actionIndex() {
        $pid = (int) ($_GET['pid']);
        $galleryId = AmcWm::app()->db->createCommand()
                ->select('gallery_id')
                ->from('products')
                ->where("product_id = {$pid}")
                ->queryScalar();
        if (!$galleryId)
            throw new CHttpException(404, 'The requested page does not exist.');
        $this->redirect(array('/' . AmcWm::app()->backendName . '/multimedia/default/view', 'id' => $galleryId));
    }

}
