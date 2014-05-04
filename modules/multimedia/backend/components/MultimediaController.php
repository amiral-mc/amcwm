<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * MultimediaController base controller class for managing media
 * @package  AmcWm.modules.multimedia.backend
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MultimediaController extends BackendController {

    /**
     * Current gallery model
     * @var Galleries 
     */
    private $_gallery = null;

    /**
     * Create gallery directory
     * @param Galleries $galleryModel
     * @return void
     */
    protected function createGalleryDir(Galleries $galleryModel) {
        $mediaPaths = $this->getModule()->appModule->mediaPaths;
        $galleryFolder = $galleryModel->gallery_id;
        $path = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['galleries']['path']) . DIRECTORY_SEPARATOR;
        $imgPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['images']['path']) . DIRECTORY_SEPARATOR;
        $imgPath = str_replace("{gallery_id}", $galleryFolder, $imgPath);
        $bgPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['backgrounds']['path']) . DIRECTORY_SEPARATOR;
        $bgPath = str_replace("{gallery_id}", $galleryFolder, $bgPath);
        $videoPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . DIRECTORY_SEPARATOR;
        $videoPath = str_replace("{gallery_id}", $galleryFolder, $videoPath);
        $videoThumbPath = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['thumb']['path']) . DIRECTORY_SEPARATOR;
        $videoThumbPath = str_replace("{gallery_id}", $galleryFolder, $videoThumbPath);

        $galleryPath = $path . $galleryFolder;
        if (is_dir($path)) {
            if (!is_dir($galleryPath)) {
                mkdir($galleryPath);
                chmod($galleryPath, 0777);
            }
            if (!is_dir($videoPath)) {
                mkdir($videoPath);
                chmod($videoPath, 0777);
            }
            if (!is_dir($videoThumbPath)) {
                mkdir($videoThumbPath);
                chmod($videoThumbPath, 0777);
            }
            if (!is_dir($imgPath)) {
                mkdir($imgPath);
                chmod($imgPath, 0777);
            }
            if (!is_dir($bgPath)) {
                mkdir($bgPath);
                chmod($bgPath, 0777);
            }
        }
    }

    /**
     * Returns the Gallery model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @access public
     * @return Galleries     
     */
    public function loadGallery($id) {
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        if ($this->_gallery === null) {
            $this->_gallery = GalleriesTranslation::model()->findByPk(array("gallery_id" => $pk['id'], 'content_lang' => $pk['lang']));
            if ($this->_gallery === null) {
                throw new CHttpException(404, 'The requested gallery does not exist.');
            }
        }
        return $this->_gallery;
    }

    /**
     * Returns the gallery model instance
     * @access public
     * @return Galleries
     */
    public function getGallery() {
        return $this->_gallery;
    }

    /**
     * Get infocus list
     * @access public
     * @return array 
     */
    public function getInfocus() {
        $query = sprintf("
            select t.infocus_id, tt.header
            from infocus t
            inner join infocus_translation tt on t.infocus_id = tt.infocus_id
            where content_lang = %s", Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        $infocus = CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'infocus_id', 'header');
        $infocus[""] = Yii::t('zii', 'Not set');
        return $infocus;
    }

    /**
     * Get infocus name for the given $id
     * @access public
     * @return array 
     */
    public function getInfocucName($id) {
        $infocusName = null;
        if ($id) {
            $query = sprintf("
            select tt.header
            from infocus_translation tt 
            where tt.infocus_id = %d and content_lang = %s", $id, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
            $infocusName = Yii::app()->db->createCommand($query)->queryScalar();
        }
        return $infocusName;
    }

    public function ajaxattachment() {
        $route = str_replace("ajax", "manageFiles", $route = $this->getRoute());
        $access = AmcWm::app()->user->checkRouteAccess($route);
        if ($access) {
            $assets = AmcWm::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.modules.uploads.assets'));
            $component = AmcWm::app()->request->getParam("component");
            switch ($component) {
                case 'multimediaVideos':
                    $list = new VideosListData();
                    break;
                case 'multimediaImages':
                    $list = new MultimediaImagesList();
                    break;
                case 'multimediaBackgrounds':
                    $list = new MultimediaBackgroundList();
                    break;
            }
            
            if (count($list)) {
                $page = (int) AmcWm::app()->request->getParam("page");
                $pagingDataset = new PagingDataset($list, 10, $page);
                $this->renderPartial("list", array("msg" => '', "iconsPath" => $assets . "/images", 'list' => $pagingDataset->getData()));
            }
        }
        exit;
    }

}
