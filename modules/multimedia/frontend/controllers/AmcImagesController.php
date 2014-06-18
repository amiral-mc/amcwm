<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
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
        $multiMedia = new GalleriesMediaListData(Yii::app()->request->getParam('gid'), SiteData::IAMGE_TYPE);
        $mediaPaging = new PagingDataset($multiMedia, 10, Yii::app()->request->getParam('page'));
        $useGalleriesList = !isset(MediaListData::getSettings()->options['default']['check']['useGalleriesList']) ? true : MediaListData::getSettings()->options['default']['check']['useGalleriesList'];
        $this->render('index', array(
            'route' => $multiMedia->getRoute(),
            'galleries' => ($useGalleriesList) ? $multiMedia->getGalleries() : array(),
            'galleryId' => $multiMedia->getGalleryId(),
            'activeGallery' => $mediaPaging->getData())
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
