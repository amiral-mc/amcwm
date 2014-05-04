<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * VideosSliderData class,  Gets the videos list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VideosListData extends SiteData {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * date field to compare  SiteData.toDate or SiteData.fromDate with
     * @var string 
     */
    protected $dateCompareField = "creation_date";

    /**
     * Counstructor, default content type is video
     * If the constructor is overridden, make sure the parent implementation is invoked.     
     * @access public
     */
    public function __construct() {
        $this->moduleName = "videos";
        $this->route = '/multimedia/videos/index';
        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths['videos']['thumb']['path'];
        $this->type = self::VIDEO_TYPE;
    }

    /**
     * Get articles setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("multimedia", false);
        }
        return self::$_settings;
    }

    /**
     * Generate the videos list array, each item is associated  array
     * @access public
     * @return void
     */
    public function generate() {
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
        $sorting = self::getSettings()->getTablesSoringOrders();

        if (!count($this->orders)) {
            if (isset($sorting['videos'])) {
                $this->addOrder("{$sorting['videos']['sortField']} {$sorting['videos']['order']}");
            } else {
                $this->addOrder("t.creation_date desc");
            }            
        }
//        switch ($this->archive) {
//            case 1:
//                $this->addWhere('(t.archive = 0 or t.archive is null)');
//                break;
//            case 2:
//                $this->addWhere('t.archive = 1');
//                break;
//        }      
        $this->setItems();
    }

    /**
     * Set the videos array list    
     * @todo explain the query
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $currentDate = date("Y-m-d H:i:s");
        if ($this->sectionId) {
            if ($this->useSubSections) {
                $sections = Data::getInstance()->getSectionSubIds($this->sectionId);
                $sections[$this->sectionId] = $this->sectionId;
                $this->addWhere("(t.section_id in (" . implode(',', $sections) . "))");
            } else {
                $this->addWhere("t.section_id = {$this->sectionId}");
            }
        }
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT sql_calc_found_rows 
            tt.video_header 
            ,t.video_id
            ,t.gallery_id
            ,ev.video
            ,iv.video_ext
            ,iv.img_ext   
            {$cols}
            from videos t 
            inner join videos_translation tt on tt.video_id = t.video_id
            inner join galleries g on g.gallery_id = t.gallery_id             
            {$this->joins}
            left join external_videos ev on t.video_id = ev.video_id 
            left join internal_videos iv on t.video_id = iv.video_id             
            where tt.content_lang = %s
            and t.published = %d
            and t.publish_date <= '{$currentDate}'            
            and g.published = %d
            and (t.expire_date  >= '{$currentDate}' or t.expire_date is null)  
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ", 
                Yii::app()->db->quoteValue($siteLanguage),
                ActiveRecord::PUBLISHED,
                ActiveRecord::PUBLISHED
            );
        $videos = Yii::app()->db->createCommand($this->query)->queryAll();
        $index = -1;
        foreach ($videos As $video) {
            if ($this->recordIdAsKey) {
                $index = $video['video_id'];
            } else {
                $index++;
            }
            if ($this->titleLength) {
                $this->items[$index]['title'] = Html::utfSubstring($video["video_header"], 0, $this->titleLength);
            } else {
                $this->items[$index]['title'] = $video["video_header"];
            }
            $this->items[$index]['id'] = $video["video_id"];
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), array('id' => $video['video_id'], 'title' => $video["video_header"]));
            if (isset($video['img_ext'])) {
                $videoImage = $this->mediaPath . "/{$video['video_id']}.{$video['img_ext']}";
                $videoImage = str_replace("{gallery_id}", $video['gallery_id'], $videoImage);
                $this->items[$index]['video'] = str_replace("{gallery_id}", $video['gallery_id'], $this->mediaPath . "/{$video['video_id']}.{$video['video_ext']}");
            } else {
                $videoCode = Html::getVideoCode($video['video']);
                $videoImage = "http://i.ytimg.com/vi/$videoCode/0.jpg";
                $this->items[$index]['video'] = $video['video'];
            }
            $this->items[$index]['imageExt'] = $video['img_ext'];
            $this->items[$index]['image'] = $videoImage;
            $this->items[$index]['type'] = $this->type;
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $video[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

    public function _generate($limit = 5, $start = 0, $sectionId = NULL) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $sectionsWheres = NULL;
        if ($this->sectionId) {
            $sectionsTree = Data::getInstance()->getSectionSubIds($this->sectionId);
            $sectionsTree[$this->sectionId] = $this->sectionId;
            $sectionsWheres = ' and (v.section_id in (' . implode(',', $sectionsTree) . ')) ';
        }

        $query = sprintf("select 
            v.video_header 
            ,v.video_id
            ,v.in_slider
            ,v.gallery_id
            ,ev.video
            ,iv.video_ext
            ,iv.img_ext            
            from videos v
            inner join galleries g on g.gallery_id = v.gallery_id             
            left join external_videos ev on v.video_id = ev.video_id 
            left join internal_videos iv on v.video_id = iv.video_id 
            left join sections s on g.section_id = s.section_id 
            where v.published = %d
            and v.publish_date <= NOW() 
            and g.published = %d
            and (s.published = 1 or s.section_id is null)
            and (v.content_lang = %s or v.content_lang is null or v.content_lang = '')
            and v.in_slider = 1 
            and (v.expire_date >= NOW() or v.expire_date is null)
            {$sectionsWheres}
            order by v.creation_date desc limit %d", 
                ActiveRecord::PUBLISHED, 
                ActiveRecord::PUBLISHED,
                Yii::app()->db->quoteValue($siteLanguage), 
                $this->limit);
        $items = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($items as $itemKey => $item) {
            if (isset($item['video_ext'])) {
                $video = Yii::app()->request->baseUrl . "/" . self::getSettings()->mediaPaths['videos']['path'] . "/{$item['video_id']}.{$item['video_ext']}";
                $video = str_replace("{gallery_id}", $item['gallery_id'], $video);
            } else {
                $videoCode = Html::getVideoCode($item['video']);
                $video = "http://i.ytimg.com/vi/$videoCode/0.jpg";
            }

            $this->items[$itemKey]['title'] = $item['video_header'];
            $this->items[$itemKey]['link'] = Html::createUrl($this->route, array($this->idForParams => $item['video_id'], 'gid' => $item['gallery_id']));
            $this->items[$itemKey]['image'] = $video;
            $this->items[$itemKey]['type'] = $this->type;
        }
    }

}