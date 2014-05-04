<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of TopGalleriesData
 * @author Amiral Management Corporation amc.amiral.com
 */
abstract class TopGalleriesData {

    private $galleries = array();
    protected $moreLimit;
    protected $pageSize;
    protected $pageNo;
    protected $totalVideos = 0;

    /**
     * Media types : (images, videos)
     * @var type 
     */
    protected $mediaType = NULL;

    abstract protected function setChilds($activeGalleryId);

    public function __construct($pageNo = 1, $pageSize = 20, $moreLimit = 3) {
        $this->moreLimit = $moreLimit;
        $this->pageSize = $pageSize;
        $this->pageNo = ((int) Yii::app()->request->getParam("page")) ? Yii::app()->request->getParam("page") : $pageNo;
        $this->setGalleries();
    }

    /**
     * @todo explain the query
     */
    private function setGalleries() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $subCount = ", 0 as mediaCount";
        if ($this->mediaType) {
            $subCount = ", (select count(*) from {$this->mediaType} t where t.gallery_id = g.gallery_id) as mediaCount";
        }
        $galleryQuery = sprintf("select 
                g.gallery_id, gallery_header, tags
                $subCount
            from galleries g
            inner join galleries_translation gt on g.gallery_id = gt.gallery_id
            where g.published = %d
            and gt.content_lang = %s
            having mediaCount > 0
            order by mediaCount desc", 
                ActiveRecord::PUBLISHED, 
                Yii::app()->db->quoteValue($siteLanguage));
        $galleries = Yii::app()->db->createCommand($galleryQuery)->queryAll();
        if (count($galleries)) {
            $galleriesIds = array();
            foreach ($galleries as $gallery) {
                $galleriesIds[] = $gallery['gallery_id'];
            }

            if (count($galleriesIds)) {
                $this->galleries = $this->setChilds(implode(",", $galleriesIds));
            }
        }
    }

    public function getMediaType() {
        return $this->mediaType;
    }

    public function getTotalTopVideos() {
        return $this->totalVideos;
    }

    public function getGalleries($galleryId = NULL) {
        $galleriesData = $this->galleries;
        if ($galleryId && array_key_exists($galleryId, $this->galleries)) {
            $galleriesData = $this->galleries[$galleryId];
        }

        return $galleriesData;
    }

}

?>
