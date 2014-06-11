<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of TopVideosGalleries
 * @author Amiral Management Corporation amc.amiral.com
 */
class TopVideosGalleries extends TopGalleriesData {

    
    private $route = '';
    private $firstVideo = '';
    
    public function __construct($pageNo = 1, $pageSize = 7, $moreLimit = 3) {
        $this->mediaType = "videos";
        parent::__construct($pageNo, $pageSize, $moreLimit);
    }

     /**
     * Get route router for viewing content details
     * @access public 
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }
    
    public function getFirstVideo() {
        return $this->firstVideo;
    }

    
    /**
     * @todo explain the query
     */
    protected function setChilds($galleryId) {        
        $mediaPaths = VideosListData::getSettings()->mediaPaths;
        $this->route = '/multimedia/videos/view';
        $count = 0;
        $childs = array();
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $videosQuery = sprintf("select sql_calc_found_rows 
                v.video_id, v.hits , v.gallery_id, v.creation_date, v.comments, vt.video_header, 
                iv.video_ext, iv.img_ext, ev.video, comments
            from videos v
            inner join videos_translation vt on vt.video_id = v.video_id
            left join internal_videos iv on iv.video_id = v.video_id
            left join external_videos ev on ev.video_id = v.video_id
            where v.gallery_id IN (%s) 
            and v.published = %d
            and v.publish_date <= NOW() 
            and vt.content_lang = %s
            and (v.expire_date >= NOW() or v.expire_date is null)
            order by v.publish_date desc limit %d , %d", 
                $galleryId, 
                ActiveRecord::PUBLISHED, 
                Yii::app()->db->quoteValue($siteLanguage), 
                ($this->pageNo - 1 ) * $this->pageSize, 
                $this->pageSize
        );
        $galleryItems = Yii::app()->db->createCommand($videosQuery)->queryAll();
        $count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
        $this->totalVideos = $count;
        if (count($galleryItems)) {

            foreach ($galleryItems as $v) {
                $moreVideos = array();
                $moreVideos['id'] = $v['video_id'];
                $moreVideos['params'] = array("gid" => $v['gallery_id'], "id" => $v['video_id'], "page" => $this->pageNo);
                $moreVideos['title'] = $v['video_header'];
                $moreVideos['hits'] = $v['hits'];
                $moreVideos['created'] = Yii::app()->dateFormatter->format("dd/MM/y", $v['creation_date']);
                $moreVideos['comments'] = $v['comments'];
                if (isset($v['video_ext'])) {
                    $moreVideos['url'] = Yii::app()->request->baseUrl . "/" . $mediaPaths['videos']['path'] . "/{$v['video_id']}.{$v['video_ext']}";
                    $moreVideos['url'] = str_replace("{gallery_id}", $v['gallery_id'], $moreVideos['url']);
                    $moreVideos['thumb'] = Yii::app()->baseUrl . "/" . $mediaPaths['videos']['thumb']['path'] . "/{$v['video_id']}.{$v['img_ext']}?t=" . time();
                    $moreVideos['thumb'] = str_replace("{gallery_id}", $v['gallery_id'], $moreVideos['thumb']);                    
                } else {
                    $moreVideos['url'] = $v['video'];
                    $moreVideos['thumb'] = "http://img.youtube.com/vi/" . self::getVideoCode($v['video']) . "/default.jpg";
                }
                $moreVideos['type'] = $this->mediaType;

                $childs[] = $moreVideos;
            }
        }

        return $childs;
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

?>
