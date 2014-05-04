<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "videos_translation".
 *
 * The followings are the available columns in table 'videos_translation':
 * @property string $video_id
 * @property string $content_lang
 * @property string $video_header
 * @property string $tags
 * @property string $description
 * @property string $inserted_date
 *
 * The followings are the available model relations:
 * @property Videos $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VideosTranslation extends ChildTranslatedActiveRecord {

    /**
     *
     * @var string section name  , this attribute append to query in search 
     */
    public $section_name;

    /**
     *
     * @var string section name  , this attribute append to query in search 
     */
    public $username;

    /**
     *
     * @var integer published  , this attribute append to query in search 
     */
    public $published;

    /**
     *
     * @var integer hits  , this attribute append to query in search 
     */
    public $hits;

    /**
     *
     * @var integer gallery_id  , this attribute append to query in search 
     */
    public $gallery_id;

    /**
     *
     * @var integer in_slider  , this attribute append to query in search 
     */
    public $in_slider;

    /**
     *
     * @var integer creation_date  , this attribute append to query in search 
     */
    public $creation_date;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VideosTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'videos_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, video_header', 'required'),
            array('published, gallery_id, hits', 'numerical', 'integerOnly' => true),
            array('creation_date, inserted_date, username, in_slider', 'safe'),
            array('video_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('video_header', 'length', 'max' => 500),
            array('tags', 'length', 'max' => 1024),
            array('description', 'safe'),
            array('inserted_date', 'default',
                'value' => date("Y-m-d H:i:s"),
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('video_id, content_lang, video_header, tags, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Videos', 'video_id'),          
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'video_id' => AmcWm::t("msgsbase.core", 'id'),
            'tags' => AmcWm::t("msgsbase.core", 'Tags'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'video_header' => AmcWm::t("msgsbase.core", 'Video Header'),
            'description' => AmcWm::t("msgsbase.core", 'Description'),
            'inserted_date' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'creation_date' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'username' => AmcWm::t("msgsbase.core", 'User'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'in_slider' => AmcWm::t("msgsbase.core", 'In Slider'),
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
        $criteria->select = "t.video_id , t.content_lang, t.video_header , t.tags , t.description , p.published , p.creation_date, p.in_slider, p.hits , p.gallery_id, u.username";
        $criteria->compare('video_id', $this->video_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('video_header', $this->video_header, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('creation_date', $this->creation_date);
        $criteria->compare('gallery_id', $this->gallery_id);
        $criteria->compare('p.published', $this->published);
        $criteria->join .=" inner join videos p on t.video_id = p.video_id ";
        $criteria->join .=" left join users u on p.user_id = u.user_id ";
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        $order = "{$sorting[$this->parentContent->tableName()]['sortField']} {$sorting[$this->parentContent->tableName()]['order']}";
        $sort = new CSort();        
        $sort->defaultOrder ="p.gallery_id asc, {$order}";        
        $sort->attributes = array(
            'video_header' => array(
                'asc' => 'video_header',
                'desc' => 'video_header desc',
            ),
            'video_id' => array(
                'asc' => 'video_id',
                'desc' => 'video_id desc',
            ),
            'username' => array(
                'asc' => 'username',
                'desc' => 'username desc',
            ),
            'hits' => array(
                'asc' => 'hits',
                'desc' => 'hits desc',
            ),
            'in_slider' => array(
                'asc' => 'in_slider',
                'desc' => 'in_slider desc',
            ),
            'published' => array(
                'asc' => 'published',
                'desc' => 'published desc',
            ),
        );
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->displayTitle = $this->video_header;
        $this->gallery_id = $this->parentContent->gallery_id;
        parent::afterFind();
    } 
    
    /**
     * Init the dopesheet active record for this video
     * @param integer $shots , number of shots added to the dope sheet
     * @access public
     * @return DopeSheetTranslation
     */
    public function initDopeSheet($shots = 2) {        
        $contentModel = null;
        if (!$this->isNewRecord) {            
            if ($this->parentContent->dopeSheet === NULL) {
                $this->parentContent->dopeSheet = new DopeSheet();
                $this->parentContent->dopeSheet->video_id = $this->video_id;
                $contentModel = new DopeSheetTranslation();
                $contentModel->video_id = $this->video_id;
                $this->parentContent->dopeSheet->addTranslationChild($contentModel, $this->content_lang);
                for ($i = 0; $i < $shots; $i++) {
                    $shot = new DopeSheetShots;
                    $shotContent = new DopeSheetShotsTranslation();                    
                    $shot->addTranslationChild($shotContent, $this->content_lang);
                    $shot->video_id = $this->video_id;
                    $this->parentContent->dopeSheet->addRelatedRecord("shots", $shot, $i);
                }
            } else {
                $contentModel = DopeSheetTranslation::model()->findByPk(array("video_id" => $this->video_id, 'content_lang' => $this->content_lang));                
                $this->parentContent->dopeSheet = $contentModel->getParentContent();
                $this->parentContent->dopeSheet->addTranslationChild($contentModel, $this->content_lang);               
            }
        }
        return $contentModel;
    }

}