<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MediaListData class, gets videos or images as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GalleriesMediaListData extends MediaListData {
   
    /**
     *
     * @var boolean calculate media count in each gallery 
     */
    protected $calcGalleryCount = true;

    /**
     *
     * @var galleries used in the system
     */
    protected $galleries = array();

    /**
     * @todo explain the query   
     * Set galleries array
     * @param boolean $filterOnStart if true then set galleryId attribute equal the first gallery id in galleries list
     */
    private function _setGalleries($filterOnStart) {
        $subCount = "";
        $having = "";
        $order = "gallery_id desc";
        if ($this->calcGalleryCount) {
            $subCount = ", (select count(*) from {$this->mediaTable} t where t.gallery_id = g.gallery_id) as media_count";
            $having = "having media_count > 0";
            $order = "media_count desc";
        }
        $galleryQuery = sprintf("select 
                g.gallery_id, gallery_header, tags $subCount
            from galleries g
            inner join galleries_translation gt on g.gallery_id = gt.gallery_id
            where g.published = 1
            and gt.content_lang = %s            
            $having
            order by $order ", Yii::app()->db->quoteValue($this->language));
        $galleries = Yii::app()->db->createCommand($galleryQuery)->queryAll();
        if (count($galleries)) {
            foreach ($galleries as $gallery) {
                $this->galleries[$gallery['gallery_id']] = array(
                    "id" => $gallery['gallery_id'],
                    "title" => $gallery['gallery_header'],
                );
            }
            if (!isset($this->galleries[$this->galleryId]) && $filterOnStart) {
                $this->galleryId = $galleries[0]['gallery_id'];
            }
        }
    }

    /**
     * return galleries used in the system
     * @return array
     */
    public function getGalleries() {
        if (!$this->galleries) {
            $this->_setGalleries(false);
        }
        return $this->galleries;
    }

    /**
     * get current working gallery
     * @return array
     */
    public function getCurrentGallery() {
        if ($this->galleryId && isset($this->galleries[$this->galleryId])) {
            return $this->galleries[$this->galleryId];
        }
    }

   

    /**
     *
     * Generate media lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $options = self::getSettings()->getOptions();
        $filterOnStart = false;
        switch ($this->type) {
            case SiteData::VIDEO_TYPE:
                $filterOnStart = !isset($options['default']['check']['videosGalleryFilterOnStart']) ? true : $options['default']['check']['videosGalleryFilterOnStart'];
                break;
            case SiteData::IAMGE_TYPE:
                $filterOnStart = !isset($options['default']['check']['imagesGalleryFilterOnStart']) ? true : $options['default']['check']['imagesGalleryFilterOnStart'];
                break;
        }        
        if($filterOnStart){
            $this->_setGalleries($filterOnStart);
        }
        parent::generate();
    }
}
