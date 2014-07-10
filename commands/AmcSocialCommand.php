<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */
/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
AmcWm::import("amcwm.commands.components.social.*");

class AmcSocialCommand extends CConsoleCommand {

    private $articlesLink = array(
        'news' => 'articles/default/view',
        'breaking' => 'articles/default/view',
        'agency' => 'articles/default/view',
        'articles' => 'articles/default/view',
    );
    private $multimediaLink = array(
        'videos' => 'multimedia/videos/index',
        'images' => 'multimedia/images/index'
    );
    private $_updateDate = null;
    private $_moduleId = 1;
    public function init() {
        $this->_updateDate = date("Y-m-d H:i:s");
        set_time_limit(0);
        ignore_user_abort(true);
    }

    public function actionIndex($module = 'news', $limit = 1, $lang = null, $post = true) {        
        $moduleClass = ucfirst($module) . "SocialData";
        
        $query = sprintf('select module_id from modules where module = %s and parent_module=1', AmcWm::app()->db->quoteValue($module));        
        $this->_moduleId = AmcWm::app()->db->createCommand($query)->queryScalar();
        if (!$lang) {
            $lang = AmcWm::app()->getLanguage();
        }

        $socialsQuery = "select * from social_networks";
        $socialsDataset = Yii::app()->db->createCommand($socialsQuery)->queryAll();
        $moduleClassData = ucfirst($module) . "SocialData";        
        $msg = '';
        foreach ($socialsDataset as $social) {
            $socialClass = "Amc" . ucfirst($social['class_name']) . "Social";
            if (count(Yii::app()->params[strtolower($social['class_name'])][$lang])) {
                $socialObject = new $socialClass(!$post, Yii::app()->params[strtolower($social['class_name'])][$lang]);
                $socialObject->connect();
                $moduleObject = new $moduleClassData($module, $social['social_id'], $socialObject, $lang , $limit);
                $moduleObject->post();
                $msg .= "{$module} has been posted to {$social['network_name']}". PHP_EOL;
            }
            else{
                $msg .= "{$social['network_name']} not configured" . PHP_EOL;
            }
        }

        echo $msg . PHP_EOL;
        exit;
    }

    /**
     *
     * @param type $galleriesDataset
     * @param type $socialId
     * @return type 
     */
    private function generateGalleriesQuery($galleriesDataset, $socialId) {
        $langQuery = ($this->lang) ? sprintf(" and m.content_lang = %s", Yii::app()->db->quoteValue($this->lang)) : "";
        foreach ($galleriesDataset as $gallery) {
            $multimediaQueries[] = "select 
                m.video_header media_header
                , m.video_id media_id
                , m.content_lang
                , m.creation_date
                , m.gallery_id
                , iv.video_ext media_action
                , iv.img_ext 
                , 'videos' module
                , '0' external
                , '0' is_background
                from videos m
                inner join internal_videos iv on m.video_id = iv.video_id
                left join videos_in_social_networks s on m.video_id = s.video_id and s.social_id = {$socialId}
                where (m.creation_date > '{$gallery['added_date']}' and s.video_id is null)
                $langQuery
                and m.gallery_id = {$gallery['gallery_id']} and m.published = 1";
            $multimediaQueries[] = "select 
                m.video_header media_header
                , m.video_id media_id
                , m.content_lang
                , m.creation_date
                , m.gallery_id
                , ev.video media_action
                , '0' img_ext
                , 'videos' module
                , '1' external
                , '0' is_background
                from videos m
                inner join external_videos ev on m.video_id = ev.video_id
                left join videos_in_social_networks s on m.video_id = s.video_id and s.social_id = {$socialId}
                where (m.creation_date > '{$gallery['added_date']}' and s.video_id is null)
                $langQuery
                and m.gallery_id = {$gallery['gallery_id']} and m.published = 1";
            $multimediaQueries[] = "select 
                m.image_header media_header
                , m.image_id media_id
                , m.content_lang
                , m.creation_date
                , m.gallery_id
                , m.ext media_action
                , m.ext img_ext
                , 'images' module
                , '0' external
                , is_background
                from images m                
                left join images_in_social_networks s on m.image_id = s.image_id and s.social_id = {$socialId}
                where (m.creation_date > '{$gallery['added_date']}' and s.image_id is null)
                $langQuery
                and m.gallery_id = {$gallery['gallery_id']} and m.published = 1";
        }
        return $multimediaQueries;
    }

    /**
     *     
     * @param type $socialId
     * @return type 
     */
    private function generateMultimediQuery($socialId) {
        $langQuery = ($this->lang) ? sprintf(" and m.content_lang = %s", Yii::app()->db->quoteValue($this->lang)) : "";
        $multimediaQueries[] = "select 
                m.video_header media_header
                , m.video_id media_id
                , m.content_lang
                , m.creation_date
                , m.gallery_id
                , iv.video_ext media_action
                , iv.img_ext
                , 'videos' module
                , '0' external
                , '0' is_background
                from videos m
                inner join internal_videos iv on m.video_id = iv.video_id
                inner join videos_in_social_networks s on m.video_id = s.video_id and s.social_id = {$socialId}
                where (s.added_date is null or s.added_date = '0000-00-00')
                $langQuery
                and m.published = 1";
        $multimediaQueries[] = "select 
                m.video_header media_header
                , m.video_id media_id
                , m.content_lang
                , m.creation_date
                , m.gallery_id
                , ev.video media_action
                , '0' img_ext
                , 'videos' module
                , '1' external
                , '0' is_background
                from videos m
                inner join external_videos ev on m.video_id = ev.video_id
                inner join videos_in_social_networks s on m.video_id = s.video_id and s.social_id = {$socialId}
                where (s.added_date is null or s.added_date = '0000-00-00')
                $langQuery
                and m.published = 1";
        $multimediaQueries[] = "select 
                m.image_header media_header
                , m.image_id media_id
                , m.content_lang
                , m.creation_date
                , m.gallery_id
                , m.ext media_action
                , m.ext img_ext
                , 'images' module
                , '0' external
                , is_background
                from images m                
                inner join images_in_social_networks s on m.image_id = s.image_id and s.social_id = {$socialId}
                where (s.added_date is null or s.added_date = '0000-00-00')
                $langQuery
                and m.published = 1";

        return $multimediaQueries;
    }

    /**
     * @todo for add link for each module 
     * change article links after URL general modifications 
     * @param type $socialId
     * @param AmcSocial $socialObject 
     */
    private function postMedia($socialId, AmcSocial $socialObject) {
        $galleriesQuery = "select g.gallery_id , gs.added_date 
            from galleries_in_social_networks gs 
            inner join galleries g on g.gallery_id = gs.gallery_id            
            where g.published = 1 and gs.social_id = {$socialId}
        ";
        $galleriesDataset = Yii::app()->db->createCommand($galleriesQuery)->queryAll();
        $multimediaQueries = array_merge($this->generateGalleriesQuery($galleriesDataset, $socialId), $this->generateMultimediQuery($socialId));
        $multimediaQuery = implode(" union ", $multimediaQueries) . " order by creation_date asc limit {$this->limit}";

        $multimediaDataset = Yii::app()->db->createCommand($multimediaQuery)->queryAll();
        //print_r($multimediaDataset);
        $mediaFile = NULL;
        $mediaUrl = NULL;
        $mediaPath = NULL;
        $type = NULL;
        $isUrl = false;
        $posted = false;
        $mediaPaths = MediaListData::getSettings()->mediaPaths;
        foreach ($multimediaDataset as $multimedia) {
            $data = array();
            switch ($multimedia['module']) {
                case 'videos':
                    $type = 'video';
                    if ($multimedia['external']) {
                        $videoCode = Html::getVideoCode($multimedia['media_action']);
                        if ($videoCode) {
                            $mediaUrl = "http://img.youtube.com/vi/{$videoCode}/default.jpg";
                        }
                        $isUrl = true;
                    } else {
                        $isUrl = false;
                        //$mediaPath = Yii::app()->params['multimedia']["videos"]['path'] . DIRECTORY_SEPARATOR . $multimedia['media_id'] . "." . $multimedia['media_action'];
                        if ($multimedia['img_ext']) {
                            $mediaPath = $mediaPaths['videos']['thumb']['path'] . "/{$multimedia['video_id']}.{$multimedia['img_ext']}";
                            $mediaPath = str_replace("{gallery_id}", $data['gallery_id'], $mediaPath);
                            $mediaFile = Yii::app()->baseUrl . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $mediaPath);
                            $mediaUrl = Yii::app()->params['siteUrl'] . '/' . DIRECTORY_SEPARATOR . $mediaPath;
                        }
                    }
                    break;
                case 'images':
                    $type = 'image';
                    if ($multimedia['external']) {
                        $isUrl = true;
                    } else {
                        $isUrl = false;
                        if ($multimedia['is_background']) {//      
                            $mediaPath = $mediaPaths['backgrounds']['path'] . "/" . $multimedia['media_id'] . "." . $multimedia['media_action'];
                            $mediaPath = str_replace("{gallery_id}", $multimedia['gallery_id'], $mediaPath);
                            $mediaFile = Yii::app()->baseUrl . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $mediaPath);
                        } else {
                            $mediaPath = $mediaPaths['images']['path'] . "/" . $multimedia['media_id'] . "." . $multimedia['media_action'];
                            $mediaPath = str_replace("{gallery_id}", $multimedia['gallery_id'], $mediaPath);
                            $mediaFile = Yii::app()->baseUrl . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $mediaPath);
                            $mediaUrl = Yii::app()->params['siteUrl'] . '/' . DIRECTORY_SEPARATOR . $mediaPath;
                        }
                    }
                    break;
            }

            if ($isUrl || is_file($mediaFile)) {
                $posted = true;
                $data['type'] = $type;
                $data['data']['header'] = $multimedia['media_header'];
                $data['data']['image'] = $mediaUrl;
                $data['data']['link'] = $this->createPostUrl($this->multimediaLink[$multimedia['module']], array('gid' => $multimedia['gallery_id'], 'id' => $multimedia['media_id'], 'lang' => $multimedia['content_lang'], 'title' => $multimedia['media_header']));
                $socialObject->postData($data);
                $this->updateDate = $multimedia['creation_date'];
            }
            $updateSql = "replace {$multimedia['module']}_in_social_networks 
                    ({$type}_id, social_id, added_date) 
                    values ('{$multimedia['media_id']}', '{$socialId}', '{$this->updateDate}')
            ";
            Yii::app()->db->createCommand($updateSql)->execute();
        }
        if ($posted) {
            foreach ($galleriesDataset as $gallery) {
                Yii::app()->db->createCommand("UPDATE galleries_in_social_networks
                        SET added_date='{$this->updateDate}'
                        WHERE gallery_id='{$gallery['gallery_id']}'
                        AND social_id='{$socialId}'")->execute();
            }
        }
    }
}
