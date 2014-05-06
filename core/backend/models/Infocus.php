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
 * @property integer $published
 * @property string $create_date
 * @property integer $section_id
 * @property string $country_code
 * @property string $expire_date
 * @property string $thumb
 * @property string $background
 * @property string $banner
 * @property string $publish_date
 * @property integer $archive
 * @property integer $dont_show
 * @property string $bgcolor
 *
 * The followings are the available model relations:
 * @property Countries $countryCode
 * @property Sections $section
 * @property Articles[] $articles
 * @property Images[] $images
 * @property Videos[] $videoses
 * @property InfocusTranslation[] $infocusTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Infocus extends ParentTranslatedActiveRecord {

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
     * @param string $className active record class name.
     * @return Infocus the static model class
     */
    public static function model($className = __CLASS__) {
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
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array('', 'required'),
            array('published, section_id, archive, dont_show', 'numerical', 'integerOnly' => true),
            array('country_code', 'length', 'max' => 2),
            array('thumb, background, banner', 'length', 'max' => 3),
            array('bgcolor', 'length', 'max' => 6),
            array('expire_date', 'safe'),
            array('expire_date', 'compare', 'compareAttribute' => 'publish_date', 'operator' => '>', 'allowEmpty' => true),
            array('publish_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            array('bannerFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['paths']['banners']['info']),
            array('bannerFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['banners']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('backgroundFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['paths']['backgrounds']['maxImageSize']),
            array('backgroundFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['backgrounds']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('imageFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxImageSize']),
            array('imageFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['images']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('create_date', 'default',
                'value' => date('Y-m-d'),
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('infocus_id, published, create_date, section_id, country_code, expire_date, thumb, background, banner, publish_date, archive, dont_show, bgcolor', 'safe', 'on' => 'search'),
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
            'translationChilds' => array(self::HAS_MANY, 'InfocusTranslation', 'infocus_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'infocus_id' => AmcWm::t("msgsbase.core", 'Infocus'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'create_date' => AmcWm::t("msgsbase.core", 'Create Date'),
            'section_id' => AmcWm::t("msgsbase.core", 'Section'),
            'country_code' => AmcWm::t("msgsbase.core", 'Country'),
            'expire_date' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'thumb' => AmcWm::t("msgsbase.core", 'Image File'),
            'imageFile' => AmcWm::t("msgsbase.core", 'Image File'),
            'background' => AmcWm::t("msgsbase.core", 'Page Background'),
            'backgroundFile' => AmcWm::t("msgsbase.core", 'Page Background'),
            'banner' => AmcWm::t("msgsbase.core", 'Page Banner'),
            'bannerFile' => AmcWm::t("msgsbase.core", 'Page Banner'),
            'publish_date' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'archive' => AmcWm::t("msgsbase.core", 'Archive'),
            'dont_show' => AmcWm::t("msgsbase.core", 'Dont Show'),
            'bgcolor' => AmcWm::t("msgsbase.core", 'Bgcolor'),
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
        $criteria->compare('published', $this->published);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('country_code', $this->country_code, true);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('thumb', $this->thumb, true);
        $criteria->compare('background', $this->background, true);
        $criteria->compare('banner', $this->banner, true);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('archive', $this->archive);
        $criteria->compare('dont_show', $this->dont_show);
        $criteria->compare('bgcolor', $this->bgcolor, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}