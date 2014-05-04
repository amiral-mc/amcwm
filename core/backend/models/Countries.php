<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "countries".
 *
 * The followings are the available columns in table 'countries':
 * @property string $code
 * @property string $currency_code
 * @property double $latitude
 * @property double $longitude
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 * @property Currency $currencyCode
 * @property CountriesTranslation[] $translationChilds
 * @property Events[] $events
 * @property Galleries[] $galleries
 * @property Infocus[] $infocuses
 * @property Persons[] $persons
 * @property Regions[] $regions
 * @property ServicesCities[] $servicesCities
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Countries extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Countries the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'countries';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code', 'required'),
            array('published', 'numerical', 'integerOnly' => true),
            array('latitude, longitude', 'numerical'),
            array('code', 'length', 'max' => 2),
            array('currency_code', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('code, currency_code, latitude, longitude, published', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'articles' => array(self::HAS_MANY, 'Articles', 'country_code'),
            'currencyCode' => array(self::BELONGS_TO, 'Currency', 'currency_code'),
            'translationChilds' => array(self::HAS_MANY, 'CountriesTranslation', 'code', 'index' => 'content_lang'),
            'events' => array(self::HAS_MANY, 'Events', 'country_code'),
            'galleries' => array(self::HAS_MANY, 'Galleries', 'country_code'),
            'infocuses' => array(self::HAS_MANY, 'Infocus', 'country_code'),
            'persons' => array(self::HAS_MANY, 'Persons', 'country_code'),
            'regions' => array(self::MANY_MANY, 'Regions', 'regions_has_countries(country_code, region_id)'),
            'servicesCities' => array(self::HAS_MANY, 'ServicesCities', 'country_code'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'code' => 'Code',
            'currency_code' => 'Currency Code',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'published' => 'Published',
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

        $criteria->compare('code', $this->code, true);
        $criteria->compare('currency_code', $this->currency_code, true);
        $criteria->compare('latitude', $this->latitude);
        $criteria->compare('longitude', $this->longitude);
        $criteria->compare('published', $this->published);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Get country name according to application language
     * @access public
     * @return string
     */
    public function getCountryName() {
        return $this->getCurrent()->country;
    }

}