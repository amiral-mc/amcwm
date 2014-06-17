<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MediaDetailsData, Gets the article record for a given article id
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MediaDetailsData extends Dataset {

    /**
     * media id, to get record based on it
     * @var integer
     */
    private $_id;

    /**
     * primary key name
     * @var integer
     */
    private $_pkName;

    /**
     * Type of content item line, this could be one of the following, text, image or video
     * @var integer 
     */
    protected $type = 2;

    /**
     *
     * path to content images
     * @var string
     */
    protected $mediaPath;

    /**
     *
     * @var string 
     */
    protected $mediaTable = null;

    /**
     * content language
     * @var integer 
     */
    protected $language = null;

    /**
     *
     * @var string 
     */
    protected $mediaSetterMethod = null;

    /**
     *
     * @var string thumb media path 
     */
    protected $thumbMediaPath = null;

    /**
     * Counstructor, the content type
     * @param integer $id 
     * @param integer $$mediaType videos or images 
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($id, $mediaType, $autoGenerate = true) {
        $this->_id = (int) $id;
        if (!$this->language) {
            $this->language = Yii::app()->getLanguage();
        }
        $this->type = $mediaType;
        if ($this->type == SiteData::VIDEO_TYPE) {
            $this->mediaTable = "videos";
            $this->mediaSetterMethod = "setVideoData";
            $this->route = '/multimedia/videos/index';
            $this->_pkName = "video_id";
        } else if ($this->type == SiteData::IAMGE_TYPE) {
            $this->mediaTable = "images";
            $this->mediaSetterMethod = "setImageData";
            $this->route = '/multimedia/images/index';
            $this->_pkName = "image_id";
        }
        $this->mediaPath = Yii::app()->baseUrl . "/" . MediaListData::getSettings()->mediaPaths[$this->mediaTable]['path'] . "/";
        if (isset(MediaListData::getSettings()->mediaPaths[$this->mediaTable]['thumb'])) {
            $this->thumbMediaPath = Yii::app()->baseUrl . "/" . MediaListData::getSettings()->mediaPaths[$this->mediaTable]['thumb']['path'] . "/";
        }
        if ($autoGenerate) {
            $this->generate();
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
        $this->addWhere("t.{$this->_pkName} ={$this->_id}");
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the multimedia array list    
     * @return void
     */
    protected function setItems() {
        $this->{$this->mediaSetterMethod}();
    }

    /**
     * @todo explain the query
     * Set the multimedia array list    
     * @return void
     */
    protected function setVideoData() {
        $cols = "t.video_id item_id
                ,t.hits
                ,t.creation_date created
                ,t.comments
                ,t.gallery_id
                ,t.update_date updated
                ,tt.video_header title
                ,tt.description
                ,tt.tags
                ,it.video_ext
                , it.img_ext
                , et.video
                , comments {$this->generateColumns()}";
        $wheres = $this->generateWheres();
        $this->query = sprintf("select {$cols} from videos t
            inner join videos_translation tt on t.video_id = tt.video_id
            left join internal_videos it on it.video_id = t.video_id
            left join external_videos et on et.video_id = t.video_id
            {$this->joins}
            where tt.content_lang = %s
            and t.published = %d                
            $wheres
            ", Yii::app()->db->quoteValue($this->language), ActiveRecord::PUBLISHED);


        $row = Yii::app()->db->createCommand($this->query)->queryRow();
        if ($row) {
            $this->count = 1;
            $this->setDataset($row);
        }
    }

    /**
     * @todo explain the query
     * Set the multimedia array list    
     * @return void
     */
    protected function setImageData() {
        $cols = "t.image_id item_id
                ,t.hits
                , t.comments 
                ,t.creation_date created
                ,t.comments
                ,t.gallery_id
                ,t.ext
                ,t.update_date updated
                ,tt.image_header title
                ,tt.description {$this->generateColumns()}";
        $wheres = $this->generateWheres();
        $this->query = sprintf("select {$cols} from images t
            inner join images_translation tt on t.image_id = tt.image_id
            {$this->joins}
            where tt.content_lang = %s
            and t.published = %d                
            $wheres
            ", Yii::app()->db->quoteValue($this->language), ActiveRecord::PUBLISHED);


        $row = Yii::app()->db->createCommand($this->query)->queryRow();
        if ($row) {
            $this->count = 1;
            $this->setDataset($row);
        }
    }

    /**
     *
     * Sets the the items array      
     * @param array $row
     * @access protected     
     * @return void
     */
    protected function setDataset($row) {
        $this->items['title'] = $row["title"];
        $this->items['id'] = $row["item_id"];
        $this->items['created'] = $row["created"];
        $this->items['updated'] = $row["updated"];
        $this->items['comments'] = $row["comments"];
        $this->items['hits'] = $row["hits"];
        $this->items['tags'] = $row["tags"];
        $this->items['description'] = $row["description"];
        $this->items['type'] = $this->type;
        switch ($this->type) {
            case SiteData::VIDEO_TYPE:
                if (isset($row['video_ext'])) {
                    $this->items['url'] = $this->mediaPath . "/{$row['item_id']}.{$row['video_ext']}";
                    $this->items['url'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items['url']);
                    $this->items['thumb'] = $this->thumbMediaPath . "/{$row['item_id']}.{$row['img_ext']}?t=" . time();
                    $this->items['thumb'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items['thumb']);
                } else {
                    $this->items['url'] = $row['video'];
                    $this->items['thumb'] = "http://img.youtube.com/vi/" . MediaListData::getVideoCode($row['video']) . "/default.jpg";
                }
                break;
            case SiteData::IAMGE_TYPE:
                $this->items['url'] = $this->mediaPath . "/{$row['item_id']}.{$row['ext']}?t=" . time();
                $this->items['url'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items['url']);
                $this->items['thumb'] = $this->mediaPath . "/{$row['item_id']}-th.{$row['ext']}?t=" . time();
                $this->items['thumb'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items['thumb']);
                break;
        }
        if ($this->checkIsActive) {
            $this->items['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("id" => $row['item_id']));
        }

        foreach ($this->cols as $colIndex => $col) {
            $this->items[$colIndex] = $row[$colIndex];
        }       
    }

}
