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
class MediaListData extends SiteData {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    protected static $settings = null;

    /**
     * Content language
     * @var integer 
     */
    protected $language = null;

    /**
     *
     * @var integer 
     */
    protected $galleryId = null;

    /**
     *
     * @var string 
     */
    protected $mediaTable = null;

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
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $galleryId, The gallery id to get contents from
     * @param integer $$mediaType videos or images 
     * @param integer $period, Period time in seconds. 
     * @param integer $limit, The numbers of items to fetch from table     
     * @access public
     */
    public function __construct($galleryId = null, $mediaType = SiteData::VIDEO_TYPE, $period = 0, $limit = 10) {
        $this->dateCompareField = "creation_date";
        if (!$this->language) {
            $this->language = Yii::app()->getLanguage();
        }
        $this->galleryId = (int) $galleryId;
        $this->route = "";
        $this->period = $period;
        if ($limit !== NULL) {
            $this->limit = (int) $limit;
        } else {
            $this->limit = null;
        }
        $this->type = $mediaType;
        if ($this->type == SiteData::VIDEO_TYPE) {
            $this->mediaTable = "videos";
            $this->mediaSetterMethod = "setVideosItems";
            $this->route = '/multimedia/videos/view';
        } else if ($this->type == SiteData::IAMGE_TYPE) {
            $this->mediaTable = "images";
            $this->mediaSetterMethod = "setImagesItems";
            $this->route = '/multimedia/images/view';
        }
        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths[$this->mediaTable]['path'] . "/";
        if (isset(self::getSettings()->mediaPaths[$this->mediaTable]['thumb'])) {
            $this->thumbMediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths[$this->mediaTable]['thumb']['path'] . "/";
        }
    }   

    /**
     * return current gallery id used in the system
     * @return ingteger
     */
    public function getGalleryId() {
        return $this->galleryId;
    }

    /**
     * Get articles setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {       
        if (self::$settings == null) {
            self::$settings = new Settings("multimedia", false);
        }
        return self::$settings;
    }

    /**
     *
     * Generate media lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {       
        if ($this->galleryId) {
            $this->addWhere("t.gallery_id ={$this->galleryId}");
        }
        if ($this->period) {
            $this->toDate = date('Y-m-d 23:59:59');
            $this->fromDate = date('Y-m-d 00:00:01', time() - $this->period);
        }
        if ($this->fromDate) {
            $this->addWhere("t.{$this->dateCompareField} >= '{$this->fromDate}'");
        }
        if ($this->toDate) {
            $this->addWhere("t.{$this->dateCompareField} <='{$this->toDate}'");
        }
        if (!count($this->orders)) {
            $sorting = self::getSettings()->getTablesSoringOrders();
            if (isset($sorting[$this->mediaTable])) {
                $this->addOrder("{$sorting[$this->mediaTable]['sortField']} {$sorting[$this->mediaTable]['order']}");
            } else {
                $this->addOrder("creation_date desc");
            }
        }
        $this->setSectionWhere();
        $this->setItems();
    }

    /**
     * set article $language
     * @access public
     * @return void
     */
    public function setLanguage($language) {
        $this->language = $language;
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
    protected function setVideosItems() {
        $currentDate = date("Y-m-d H:i:s");        
        $orders = $this->generateOrders();
        $cols = "t.video_id item_id
                ,t.hits
                ,t.creation_date created
                ,t.comments
                ,t.gallery_id
                ,t.publish_date
                ,tt.video_header title
                ,it.video_ext
                , it.img_ext
                , et.video
                , comments {$this->generateColumns()}";
        $wheres = $this->generateWheres();
        $limit = null;
        if ($this->limit !== null) {
            $limit = "LIMIT {$this->fromRecord} , {$this->limit}";
        }
        $querySearch = sprintf("from videos t
            inner join galleries g on g.gallery_id = t.gallery_id
            inner join videos_translation tt on t.video_id = tt.video_id
            left join internal_videos it on it.video_id = t.video_id
            left join external_videos et on et.video_id = t.video_id
            {$this->joins}
            where tt.content_lang = %s
            and t.publish_date <= '{$currentDate}'            
            and (t.expire_date  >= '{$currentDate}' or t.expire_date is null)  
            and g.published = 1                
            and t.published = %d                
            $wheres            
            ", Yii::app()->db->quoteValue($this->language), ActiveRecord::PUBLISHED);

        $this->query = "select {$cols} $querySearch $orders $limit";
        $this->count = Yii::app()->db->createCommand("select count(*) {$querySearch}")->queryScalar();
        $rows = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($rows);
    }

    /**
     * add section id and its childs to where
     */
    protected function setSectionWhere() {
        if ($this->sectionId) {
            $sectionList = array();
            if ($this->useSubSections) {
                if (is_array($this->sectionId)) {
                    $sections = $this->sectionId;
                    foreach ($sections as $section) {
                        $sectionList = Data::getInstance()->getSectionSubIds($section);
                        $sectionList[] = (int) $section;
                        if (is_array($sectionList) && $sectionList) {
                            $sectionsList = array_merge($sectionsList, $sectionList);
                        }
                    }
                } else {
                    $sectionsList = Data::getInstance()->getSectionSubIds($this->sectionId);
                    $sectionsList[] = (int) $this->sectionId;
                }
                if(count($sectionsList) > 1){
                    $this->addWhere("(g.section_id in (" . implode(',', $sectionsList) . "))");
                }
                else{
                    $this->addWhere("g.section_id = {$this->sectionId}");
                }                                    
            } else {
                $this->addWhere("g.section_id = {$this->sectionId}");
            }
        }
    }

    /**
     * @todo explain the query
     * Set the multimedia array list    
     * @return void
     */
    protected function setImagesItems() {
        $currentDate = date("Y-m-d H:i:s");
        $orders = $this->generateOrders();
        $cols = "t.image_id item_id
                ,t.hits
                ,t.ext
                ,t.creation_date created
                ,t.comments
                ,t.publish_date
                ,t.gallery_id
                ,tt.image_header title
                , comments {$this->generateColumns()}";
        $wheres = $this->generateWheres();
        $limit = null;
        if ($this->limit !== null) {
            $limit = "LIMIT {$this->fromRecord} , {$this->limit}";
        }
        $querySearch = sprintf("from images t
            inner join images_translation tt on t.image_id = tt.image_id
            inner join galleries g on g.gallery_id = t.gallery_id
            {$this->joins}
            where tt.content_lang = %s
            and t.publish_date <= '{$currentDate}'            
            and (t.expire_date  >= '{$currentDate}' or t.expire_date is null)  
            and t.published = %d      
            and g.published = 1        
            $wheres            
            ", Yii::app()->db->quoteValue($this->language), ActiveRecord::PUBLISHED);

        $this->query = "select {$cols} $querySearch $orders $limit";
        $this->count = Yii::app()->db->createCommand("select count(*) {$querySearch}")->queryScalar();
        $rows = Yii::app()->db->createCommand($this->query)->queryAll();
        $this->setDataset($rows);
    }

    /**
     *
     * Sets the the items array      
     * @param array $rows 
     * @access protected     
     * @return void
     */
    protected function setDataset($rows) {
        $index = -1;
        foreach ($rows As $row) {
            if ($this->recordIdAsKey) {
                $index = $row['item_id'];
            } else {
                $index++;
            }
            if ($this->titleLength) {
                $this->items[$index]['title'] = Html::utfSubstring($row["title"], 0, $this->titleLength);
            } else {
                $this->items[$index]['title'] = $row["title"];
            }
            $this->items[$index]['id'] = $row["item_id"];
            $urlParams = array('id' => $row["item_id"], 'title' => $row["title"]);
            foreach ($this->params as $paramIndex => $paramValue) {
                $urlParams[$paramIndex] = $paramValue;
            }
            //$this->items[$index]['route'] = Html::createUrl($this->getRoute(), $urlParams);
            $this->items[$index]['params'] = $urlParams;
            $this->items[$index]['type'] = $this->type;
            $this->items[$index]['created'] = $row["created"];
            $this->items[$index]['published'] = $row["publish_date"];
            $this->items[$index]['comments'] = $row["comments"];
            $this->items[$index]['hits'] = $row["hits"];
            $this->items[$index]['internal'] = true;
            //$this->items[$index]['shared'] = $row["shared"];
            switch ($this->type) {
                case SiteData::VIDEO_TYPE:
                    if (isset($row['video_ext'])) {                        
                        $this->items[$index]['url'] = $this->mediaPath . "{$row['item_id']}.{$row['video_ext']}";
                        $this->items[$index]['url'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items[$index]['url']);
                        $this->items[$index]['thumb'] = $this->thumbMediaPath . "{$row['item_id']}.{$row['img_ext']}?t=" . time();
                        $this->items[$index]['thumb'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items[$index]['thumb']);
                    } else {
                        $this->items[$index]['internal'] = false;
                        $this->items[$index]['url'] = $row['video'];
                        $this->items[$index]['thumb'] = "http://img.youtube.com/vi/" . self::getVideoCode($row['video']) . "/default.jpg";
                    }
                    break;
                case SiteData::IAMGE_TYPE:
                    $this->items[$index]['url'] = $this->mediaPath . "{$row['item_id']}.{$row['ext']}?t=" . time();
                    $this->items[$index]['url'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items[$index]['url']);
                    $this->items[$index]['thumb'] = $this->mediaPath . "{$row['item_id']}-th.{$row['ext']}?t=" . time();
                    $this->items[$index]['thumb'] = str_replace("{gallery_id}", $row['gallery_id'], $this->items[$index]['thumb']);
                    break;
            }
            if ($this->checkIsActive) {
                $this->items[$index]['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("id" => $row['item_id']));
            }

            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $row[$colIndex];
            }
        }
    }

    /**
     * Get video id from video url
     * @access public
     * @return string
     */
    public static function getVideoCode($video) {
        return Html::getVideoCode($video);
    }

}
