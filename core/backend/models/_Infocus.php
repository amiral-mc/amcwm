<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "infocus".
 *
 * The followings are the available columns in table 'infocus':
 * @property string $infocus_id
 * @property string $header
 * @property integer $published
 * @property string $create_date
 * @property integer $section_id
 * @property string $content_lang
 * @property string $country_code
 * @property string $expire_date
 * @property string $publish_date
 * @property string $thumb
 * @property string $background
 * @property string $banner
 * @property string $brief
 *
 * The followings are the available model relations:
 * @property Countries $countryCode
 * @property Sections $section
 * @property Articles[] $articles
 * @property Images[] $images
 * @property Videos[] $videoses
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Infocus extends ActiveRecord {

    /**
     * Image uploader file used to upload article thumb image
     * @var array
     * @access public
     */
    public $imageFile = null;
    public $backgroundFile = null;
    public $bannerFile = null;
    /**
     * @var array contain name of parent and sub section.
     */
    public $sectionNames = array('parent' => null, 'sub' => null,);
    /**
     * sub section
     * @var int
     * @access public
     */
    public $subSection = null;
    /**
     * parent section
     * @var int
     * @access public
     */
    public $parentSection = null;

    /**
     * Returns the static model of the specified AR class.
     * @return infocus the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'infocus';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('header, publish_date, brief', 'required'),
            array('published, section_id, archive', 'numerical', 'integerOnly' => true),
            array('header', 'length', 'max' => 500),
            array('expire_date', 'compare', 'compareAttribute' => 'publish_date', 'operator' => '>', 'allowEmpty' => true),
            array('publish_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            array('content_lang, country_code', 'length', 'max' => 2),
            array('thumb, background, banner', 'length', 'max' => 3),
            array('expire_date', 'safe'),
            
            array('bannerFile', 'file', 'types' => Yii::app()->params['imageTypes'], 'allowEmpty' => true, 'maxSize' => Yii::app()->params["multimedia"]['infocus']['banners']["maxImageSize"]),            
            array('bannerFile', 'ValidateImage', 'checkValues' => Yii::app()->params["multimedia"]['infocus']['banners']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('backgroundFile', 'file', 'types' => Yii::app()->params['imageTypes'], 'allowEmpty' => true, 'maxSize' => Yii::app()->params["multimedia"]['infocus']['backgrounds']["maxImageSize"]),
            array('backgroundFile', 'ValidateImage', 'checkValues' => Yii::app()->params["multimedia"]['infocus']['backgrounds']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            
            
            
            
            array('imageFile', 'file', 'types' => Yii::app()->params['imageTypes'], 'allowEmpty' => true, 'maxSize' => Yii::app()->params["maxImageSize"]),
            array('imageFile', 'ValidateImage', 'checkValues' => Yii::app()->params['multimedia']['infocus']['list']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('create_date', 'default',
                'value' => new CDbExpression('now()'),
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('infocus_id, header, published, create_date, section_id, content_lang, country_code, expire_date, publish_date, thumb, background, banner, brief', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'countryCode' => array(self::BELONGS_TO, 'Countries', 'country_code'),
            'section' => array(self::BELONGS_TO, 'Sections', 'section_id'),
            'articles' => array(self::MANY_MANY, 'Articles', 'infocus_has_articles(infocus_id, article_id)'),
            'images' => array(self::MANY_MANY, 'Images', 'infocus_has_images(infocus_id, image_id)'),
            'videoses' => array(self::MANY_MANY, 'Videos', 'infocus_has_videos(infocus_id, video_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'infocus_id' => AmcWm::t("msgsbase.core", 'ID'),
            'header' => AmcWm::t("msgsbase.core", 'Header'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'create_date' => 'Create Date',
            'section_id' => AmcWm::t("msgsbase.core", 'Section'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'country_code' => AmcWm::t("msgsbase.core", 'Country'),
            'expire_date' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'publish_date' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'archive' => AmcWm::t("msgsbase.core", 'Archive'),
            'thumb' => AmcWm::t("msgsbase.core", 'Article Photo'),
            'imageFile' => AmcWm::t("msgsbase.core", 'Article Photo'),
            'backgroundFile' => AmcWm::t("msgsbase.core", 'Page Background'),
            'background' => AmcWm::t("msgsbase.core", 'Page Background'),
            'bannerFile' => AmcWm::t("msgsbase.core", 'Page Banner'),
            'banner' => AmcWm::t("msgsbase.core", 'Page Banner'),
            'parentSection' => AmcWm::t("msgsbase.core", 'Parent Section'),
            'subSection' => AmcWm::t("msgsbase.core", 'Sub Section'),
            'brief' => AmcWm::t("msgsbase.core", 'Brief'),
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

        $criteria->compare('infocus_id', $this->infocus_id, true);
        $criteria->compare('header', $this->header, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('country_code', $this->country_code, true);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('thumb', $this->thumb, true);
        $criteria->compare('background', $this->background, true);
        $criteria->compare('banner', $this->banner, true);
        $criteria->compare('brief', $this->brief, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    /**
     * Check if article section id is sub section or not.
     * @access public
     * @return boolean
     */
    public function setParentSection() {
        $query = sprintf('select parent_section from sections where section_id = %d', $this->section_id);
        $parentSectionId = Yii::app()->db->createCommand($query)->queryScalar();
        $this->sectionNames['parent'];
        $this->sectionNames['sub'];
        if ($parentSectionId) {
            $this->parentSection = $parentSectionId;
            $this->subSection = $this->section_id;
            $this->sectionNames['parent'] = $this->section->parentSection->section_name;
            $this->sectionNames['sub'] = $this->section->section_name;
        } else {
            if ($this->section_id) {
                $this->parentSection = $this->section_id;
                $this->sectionNames['parent'] = $this->section->section_name;
            }
            $this->sectionNames['sub'] = NULL;
        }
    }

    /**
     * get parent section name
     * @access public
     * @return string
     */
    public function getParentSectionName() {
        $query = sprintf('select section_name, parent_section from sections where section_id = %d', $this->section_id);
        $parentSection = Yii::app()->db->createCommand($query)->queryRow();
        $name = NULL;
        if (count($parentSection)) {
            $name = $parentSection['section_name'];
        } else if ($this->section_id) {
            $name = $this->parentSection = $this->section->section_name;
        }
    }

    /**
     * Get Sections list
     * @access public
     * @return array 
     */
    public function getParentSections() {
        $sections = Sections::model()->getParentSections();
        return $sections;
    }    

    public function afterFind() {
        $this->displayTitle = $this->header;
        $this->subSection = $this->section_id;
        $this->setParentSection();
        parent::afterFind();
    }

}