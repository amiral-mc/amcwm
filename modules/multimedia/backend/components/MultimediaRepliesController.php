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

class MultimediaRepliesController extends RepliesCommentsController {

    /**
     *
     * Current gallery model instance
     * @var GalleriesTranslation 
     */
    protected $gallery;

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    protected function loadCommentsData($id) {
        parent::loadCommentsData($id);
        $galleryId = null;
        if (isset($_GET['gid']))
            $galleryId = $_GET['gid'];
        else
        if (isset($_POST['gid']))
            $galleryId = $_POST['gid'];
        $this->loadGallery($galleryId);
    }

    /**
     * Gets params to be added to the tools
     * @access public
     * @return array
     */
    public function getParams() {
        $params = parent::getParams();
        $params['gid'] = $this->gallery->gallery_id;
        return $params;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadGallery($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        if ($this->gallery === null) {
            $this->gallery = GalleriesTranslation::model()->findByPk(array("gallery_id" => $pk['id'], 'content_lang' => $pk['lang']));
            if ($this->gallery === null) {
                throw new CHttpException(404, 'The requested gallery does not exist.');
            }
        }
        return $this->gallery;
    }

}
