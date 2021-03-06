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

class AmcRepliesImagesController extends MultimediaRepliesController {

    protected $componentName = "images";

    /**
     * @create new model
     * @access protected
     * @return ActiveRecord
     */
    public function newModel() {
        $model = new ImagesComments();
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    protected function loadCommentsData($id) {
        parent::loadCommentsData($id);
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $this->commentsType = ImagesTranslation::model()->findByPk(array("image_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($this->commentsType == null) {
            throw new CHttpException(404, 'The requested type does not exist.');
        } else {
            $this->breadcrumbs = array(
                AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
                $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
                AmcWm::t("msgsbase.core", "_{$this->componentName}_title_") => array("/backend/multimedia/default/{$this->componentName}", 'gid' => $this->gallery->gallery_id),
                $this->commentsType->displayTitle => array("/backend/multimedia/{$this->componentName}/view", 'gid' => $this->gallery->gallery_id, 'id' => $this->getItemId()),
            );
            $this->backRoute = array("/backend/multimedia/{$this->componentName}Comments/index", 'item' => $this->getItemId(), 'gid' => $this->gallery->gallery_id);
        }
    }

    /**
     * Gets the comments type id
     * @access public
     * @return integer
     */
    public function getItemId() {
        $id = 0;
        if ($this->commentsType != null) {
            $id = $this->commentsType->image_id;
        }
        return $id;
    }

}
