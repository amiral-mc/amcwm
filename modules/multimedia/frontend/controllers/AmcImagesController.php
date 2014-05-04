<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @todo implement sharethis and comments
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcImagesController extends FrontendController {

    public function actionIndex() {
        $multiMedia = new MediaListData(Yii::app()->request->getParam('gid'), SiteData::IAMGE_TYPE);
        $mediaPaging = new PagingDataset($multiMedia, 10, Yii::app()->request->getParam('page'));
        $multiMedia->generate();
        $galleries = $multiMedia->getGalleries();
        $galleryId = $multiMedia->getGalleryId();
        $activeGallery = null;
        if (count($galleries) && array_key_exists($galleryId, $galleries)) {
            $activeGallery = $mediaPaging->getData();
        }
        $this->render('index', array(
            'route'=> $multiMedia->getRoute(),
            'galleries' => $galleries,
            'galleryId' => $galleryId,
            'activeGallery' => $activeGallery)
        );
    }

    public function actionView($id, $ajax = 0) {        
        $media = new MediaDetailsData($id, SiteData::IAMGE_TYPE);
        if ($media->getData()) {
            if ($ajax) {
                $this->renderPartial("view", array("media" => $media->getData()));
                Yii::app()->end();
            } else {
                $this->render("view", array("media" => $media->getData()));
            }
        } else {
            throw new CHttpException(404, AmcWm::t('msgsbase.core', 'The requested page does not exist'));
        }
    }

    public function actionComments() {
        //$this->render('index');
    }

    public function actionReplies() {
        //$this->render('index');
    }

}

?>
