<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "galleries".
 *
 * The followings are the available columns in table 'galleries':
 * @property string $gallery_id
 * @property integer $published
 * @property integer $section_id
 * @property integer $show_gallery
 * @property string $country_code
 *
 * The followings are the available model relations:
 * @property Countries $countryCode
 * @property Sections $section
 * @property GalleriesTranslation[] $translationChilds
 * @property Images[] $images
 * @property Videos[] $videos
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Galleries extends ParentTranslatedActiveRecord {

    /**
     * Social ids added to this active record
     * @var array
     * @access public
     */
    public $socialIds = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Galleries the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'galleries';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('published, section_id, show_gallery', 'numerical', 'integerOnly' => true),
            array('country_code', 'length', 'max' => 2),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('gallery_id, published, section_id, show_gallery, country_code', 'safe', 'on' => 'search'),
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
            'translationChilds' => array(self::HAS_MANY, 'GalleriesTranslation', 'gallery_id', "index" => "content_lang"),
            'images' => array(self::HAS_MANY, 'Images', 'gallery_id'),
            'videos' => array(self::HAS_MANY, 'Videos', 'gallery_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'section_id' => AmcWm::t("msgsbase.core", 'Section'),
            'gallery_id' => AmcWm::t("msgsbase.core", 'id'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'country_code' => AmcWm::t("msgsbase.core", 'Country'),
            'socialIds' => AmcWm::t("msgsbase.core", 'socialIds'),
            'show_gallery' => 'Show',
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

        $criteria->compare('gallery_id', $this->gallery_id, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('show_gallery', $this->show_gallery);
        $criteria->compare('country_code', $this->country_code, true);
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
        parent::afterFind();
    }
    
/**
     * Get Sections list
     * @param string $emptyLabel if not equal null then add empty item with the given $emptyLabel
     * @param string $language if not equal null then get sections according to the given $language,
     * @access public
     * @return array 
     */
    static public function getGalleriesList($emptyLabel = null, $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $query = sprintf(
                "select 
                    p.gallery_id,
                    t.gallery_header                                
                from galleries p
                inner join galleries_translation t on p.gallery_id = t.gallery_id
                where t.content_lang = %s
                order by t.gallery_header", Yii::app()->db->quoteValue($language));
        $galleriesRows = Yii::app()->db->createCommand($query)->queryAll();
        $galleries = array();
        if ($emptyLabel) {
            $galleries[""] = $emptyLabel;
        }
        foreach ($galleriesRows as $gallery) {
            $galleries[$gallery['gallery_id']] = $gallery['gallery_header'];
        }
        return $galleries;
    }
    
}