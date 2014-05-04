<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "videos".
 *
 * The followings are the available columns in table 'videos':
 * @property string $video_id
 * @property string $votes
 * @property double $votes_rate
 * @property string $hits
 * @property integer $published
 * @property string $user_id
 * @property string $gallery_id
 * @property string $tags
 * @property string $creation_date
 * @property string $publish_date
 * @property string $expire_date
 * @property string $video_sort
 * @property integer $in_slider
 * @property integer $show_media
 * @property string $update_date
 * @property string $comments
 *
 * The followings are the available model relations:
 * @property ExternalVideos $externalVideos
 * @property Infocus[] $infocuses
 * @property InternalVideos $internalVideos
 * @property Galleries $gallery
 * @property Users $user
 * @property VideosComments[] $videosComments
 * @property VideosTranslation[] $translationChilds
 * @property DopeSheet $dopeSheet
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Videos extends ParentTranslatedActiveRecord {

    const EXTERNAL = 'externalVideos';
    const INTERNAL = 'internalVideos';
    /**
     * Social ids added to this active record
     * @var array
     * @access public
     */
    public $socialIds = array();
    public $videoURL = null;
    public $videoFile = null;
    public $youtubeFile = null;
    public $videoThumb = null;
    public $videoType = null;
    public $infocusId;

    /**
     * Sort field name
     * @var string 
     */
    protected $sortField = "video_sort";

    /**
     * Sort Dependency attributes
     * @var string 
     */
    protected $sortDependencyFields = array('gallery_id');

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Videos the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'videos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $mediaPaths = AmcWm::app()->getController()->getModule()->appModule->mediaPaths;
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $date = date("Y-m-d H:i:s");
        return array(
            array('published, in_slider, show_media', 'numerical', 'integerOnly' => true),
            array('votes_rate', 'numerical'),
            array('votes, hits, user_id, gallery_id, video_sort, comments', 'length', 'max' => 10),
            array('tags', 'length', 'max' => 1024),
            array('creation_date, publish_date, expire_date, update_date', 'safe'),
            array('update_date', 'default',
                'value' => $date,
                'setOnEmpty' => false),
            array('creation_date', 'default',
                'value' => $date,
                'setOnEmpty' => false, 'on' => 'insert'),
            array('expire_date', 'compare', 'compareAttribute' => 'publish_date', 'operator' => '>', 'allowEmpty' => true),
            array('publish_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            array('videoType', 'length', 'max' => 50),
            array('videoURL', 'length', 'max' => 255),
            array('youtubeFile', 'file', 'types' => $mediaPaths['videos']['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaPaths['videos']['info']['size']),
            array('videoFile', 'validateVideo'),
            array('videoURL', 'url'),
            array('videoFile', 'file', 'types' => $mediaPaths['videos']['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaPaths['videos']['info']['size']),
            array('videoThumb', 'file', 'types' => $mediaPaths['videos']['thumb']['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaPaths['videos']['thumb']['info']['size']),
            array('videoThumb', 'validateThumb'),
            array('videoThumb', 'ValidateImage', 'checkValues' => $mediaPaths['videos']['thumb']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('video_id, votes, votes_rate, hits, published, user_id, gallery_id, tags, creation_date, publish_date, expire_date, video_sort, in_slider, show_media, update_date, comments', 'safe', 'on' => 'search'),
        );
    }

    public function validateThumb($attribute, $params) {
        $mediaPaths = AmcWm::app()->getController()->getModule()->appModule->mediaPaths;
        $childError = false;
        if ($this->internalVideos === NULL) {
            $childError = true;
        } else if ($this->internalVideos->isNewRecord) {
            $childError = true;
        } else {
            $thumbFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['thumb']['path']) . "/" . $this->video_id . "." . $this->internalVideos->img_ext;
            $thumbFile = str_replace("{gallery_id}", $this->gallery_id, $thumbFile);
            $childError = !(is_file($thumbFile));
        }

        if ($this->published == 1) {
            if ($this->videoType == Videos::INTERNAL && $this->videoThumb == '' && $childError) {
                $this->addError($attribute, Yii::t('yii', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel($attribute))));
            }
        }
    }

    public function validateVideo($attribute, $params) {
        $mediaPaths = AmcWm::app()->getController()->getModule()->appModule->mediaPaths;
        $error = false;
        if ($this->published) {
            switch ($this->videoType) {
                case Videos::EXTERNAL:
                    $videoUrl = trim(str_replace("http://www.youtube.com/watch?v=", "", strtolower($this->videoURL)), "/");
                    if (!$this->youtubeFile instanceof CUploadedFile && !$videoUrl) {
                        $error = true;
                        $attributeName = 'youtubeFile';
                    }
                    break;
                case Videos::INTERNAL:
                    $attributeName = 'videoFile';
                    if (!($this->videoFile instanceof CUploadedFile)) {
                        if (!$this->internalVideos) {
                            $error = true;
                        } else if ($this->internalVideos->isNewRecord) {
                            $error = true;
                        } else {
                            $videoFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . "/" . $this->video_id . "." . $this->internalVideos->video_ext;
                            $videoFile = str_replace("{gallery_id}", $this->gallery_id, $videoFile);
                            $error = !(is_file($videoFile));
                        }
                    }
                    break;
            }
        }

        if ($error) {
            $this->addError($attributeName, Yii::t('yii', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel($attributeName))));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'externalVideos' => array(self::HAS_ONE, 'ExternalVideos', 'video_id'),
            'infocuses' => array(self::HAS_MANY, 'InfocusHasVideos', 'video_id', "index" => "infocus_id"),
            'internalVideos' => array(self::HAS_ONE, 'InternalVideos', 'video_id'),
            'gallery' => array(self::BELONGS_TO, 'Galleries', 'gallery_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'videosComments' => array(self::HAS_MANY, 'VideosComments', 'video_id'),
            'translationChilds' => array(self::HAS_MANY, 'VideosTranslation', 'video_id', "index" => "content_lang"),
            'dopeSheet' => array(self::HAS_ONE, 'DopeSheet', 'video_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'video_id' => AmcWm::t("msgsbase.core", 'id'),
            'votes' => AmcWm::t("msgsbase.core", 'Votes'),
            'votes_rate' => AmcWm::t("msgsbase.core", 'Votes Rate'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'user_id' => AmcWm::t("msgsbase.core", 'User'),
            'gallery_id' => AmcWm::t("msgsbase.core", 'Gallery'),
            'creation_date' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'publish_date' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'expire_date' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'video_sort' => AmcWm::t("msgsbase.core", 'Sort'),
            'in_slider' => AmcWm::t("msgsbase.core", 'In Slider'),
            'socialIds' => AmcWm::t("msgsbase.core", 'socialIds'),
            'videoURL' => AmcWm::t("msgsbase.core", 'Video URL'),
            'videoFile' => AmcWm::t("msgsbase.core", 'Video File'),
            'videoThumb' => AmcWm::t("msgsbase.core", 'Video Thumb'),
            'youtubeFile' => AmcWm::t("msgsbase.core", 'Youtube video File'),
            'infocusId' => AmcWm::t("msgsbase.core", 'In Focus File'),
            'comments' => AmcWm::t("msgsbase.core", 'Comments Counts'),
            'show_media' => 'Show',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('video_id', $this->video_id, true);
        $criteria->compare('votes', $this->votes, true);
        $criteria->compare('votes_rate', $this->votes_rate);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('gallery_id', $this->gallery_id, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('video_sort', $this->video_sort, true);
        $criteria->compare('in_slider', $this->in_slider);
        $criteria->compare('show_media', $this->show_media);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('comments', $this->comments, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        if (isset($this->externalVideos->video)) {
            $this->videoURL = $this->externalVideos->video;
            $this->videoType = Videos::EXTERNAL;
        }

        if (isset($this->internalVideos->video_ext)) {
            $this->videoType = Videos::INTERNAL;
        }
        if (count($this->infocuses)) {
            $this->infocusId = $this->infocuses[0]->infocus_id;
        }
        parent::afterFind();
    }

    /**
     * @todo check sort logic if the section id has been changes
     * This method is invoked after each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        if (!$this->expire_date || $this->expire_date == '0000-00-00 00:00:00') {
            $this->expire_date = NULL;
        }
        if (!$this->publish_date || $this->publish_date == '0000-00-00 00:00:00') {
            $this->publish_date = NULL;
        }
        return parent::beforeSave();
    }

    /**
     * Sort the given model acording to $direction order
     * @param string $direction
     * @param string $language content language
     * @param string $condition condition to be added to update query
     * @access protected
     * @return boolean
     */
    public function sort($direction = "up", $condition = null) {
        if ($this->gallery_id) {
            $condition = "gallery_id = " . (int) $this->gallery_id;
        } else {
            $condition = "gallery_id is null";
        }
        parent::sort($direction, $condition);
    }

    /**
     * This method is invoked after deleting a active record translion child.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return void
     */
    protected function afterDeleteChild($childAttributes) {
        return $this->correctSort();
    }

    /**
     * Get first inserted row from videos_translation
     * @access public
     * @return string
     * 
     */
    public function getFirstInsertedLang() {
        $query = "select content_lang from videos_translation where video_id = " . (int) $this->video_id . " order by inserted_date asc limit 1";
        $lang = Yii::app()->db->createCommand($query)->queryScalar();
        return $lang;
    }

    /**
     * Check if video is uploaded useing api or not
     * @return boolean
     * @access public
     */
    public function uploadedViaApi(){      
        return ($this->isEexternal() && $this->externalVideos->uploaded_via_api && $this->externalVideos->video);
    }
    
    /**
     * Check if video is internal video or not
     * @return boolean
     * @access public
     */
    public function isInternal(){
        return $this->videoType == Videos::INTERNAL;
    }
    
    /**
     * Check if video is external video or not
     * @return boolean
     * @access public
     */
    
    public function isEexternal(){
        return $this->videoType == Videos::EXTERNAL;
    }
}