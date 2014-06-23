<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * RelatedVideosData class, gets related videos to a given video
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RelatedVideosData extends MediaListData {

    /**
     * Current video id, equal null when RelatedVideosData.setTagsById is not used
     * @var integer
     */
    private $_videoId = null;
    /**
     * Associative array containing a list of tags to find related video according to list values.
     * @var array 
     */
    private $_tags = array();

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param int $period, Period time in seconds. 
     * @param int $limit, The numbers of items to fetch from table     
     * @param int $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct($galleryId = null, $mediaType = SiteData::VIDEO_TYPE, $period = 0, $limit = 10) {
        parent::__construct($galleryId, $mediaType, $period, $limit);        
    }

    /**
     * tags setter
     * @param array|string $tags
     * @access public
     * @return void
     */
    public function setTags($tags) {
        $keywords = null;
        if (is_string($tags)) {
            $keywords = explode(PHP_EOL, $tags);
        } else if (is_array($tags)) {
            $keywords = $tags;
        }        
        if(is_array($keywords)) {
            foreach ($keywords as $tag) {
                $this->addTag($tag);
            }
        }
    }

    /**
     * add tag to RelatedVideosData.tags array
     * 
     * @param string $tag 
     * @access public 
     * @return void
     */
    public function addTag($tag) {
        $this->_tags[md5($tag)] = $tag;
    }

    /**
     * Set tags by getting article tags from table articles for the given article $id
     * @param intget $id 
     * @access public
     * @return void
     */
    public function setTagsById($id) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $this->_videoId = (int) $id;
        $tagsQuery = sprintf("SELECT tt.tags
            from videos_translation tt
            inner join videos t on tt.video_id = t.video_id
            where tt.video_id =%d
            and tt.content_lang =%s
            and t.published = %d",
                $this->_videoId,
                Yii::app()->db->quoteValue($siteLanguage),
                ActiveRecord::PUBLISHED);
        $relatetTags = Yii::app()->db->createCommand($tagsQuery)->queryRow();
        $keywords = explode(PHP_EOL, $relatetTags['tags']);
        foreach ($keywords as $keyword) {
            $this->addTag($keyword);
        }
    }

    /**
     *
     * Generate articles lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $tagsWheres = array();
        $this->setTitleLength(70);
        foreach ($this->_tags as $keyword) {
            $keyword = trim($keyword);
            if ($keyword) {
                $keyword = str_replace("%", "\%%", $keyword);
                $keywordLike = "like " . Yii::app()->db->quoteValue("%%{$keyword}%%");
                $keywordLocate = Yii::app()->db->quoteValue($keyword);
                $tagsWheres[] = "tt.tags {$keywordLike}";
                $tagsWheres[] = "tt.video_header {$keywordLike}";
                $weights[] = "if(tt.tags {$keywordLike},5,0) ";
                $weights[] = "if(tt.video_header {$keywordLike},10,0) ";
            }
        }
        if (count($tagsWheres)) {
            $this->addColumn(sprintf("%s", implode("+", $weights)), "hits");
            $this->addOrder(" hits desc ");
            if ($this->_videoId) {
                $this->addWhere("t.video_id <> " . $this->_videoId);
            }
            $this->addWhere("(" . implode(" or ", $tagsWheres) . ")");
            parent::generate();
        }
    }
}