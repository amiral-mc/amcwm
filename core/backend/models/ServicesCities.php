<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "services_cities".
 *
 * The followings are the available columns in table 'services_cities':
 * @property integer $city_id
 * @property string $country_code
 * @property string $city
 * @property double $latitude
 * @property double $longitude
 * @property integer $timezone
 *
 * The followings are the available model relations:
 * @property PrayerTimes $prayerTimes
 * @property Countries $countryCode
 * @property WeatherCities $weatherCities
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ServicesCities extends ActiveRecord {
    
    /**
     * Returns the static model of the specified AR class.
     * @return ServicesCities the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'services_cities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('country_code, city, latitude, longitude', 'required'),
            array('timezone', 'numerical', 'integerOnly' => true),
            array('latitude, longitude', 'numerical'),
            array('country_code', 'length', 'max' => 2),
            array('city', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('city_id, country_code, city, latitude, longitude, timezone', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'prayerTimes' => array(self::HAS_ONE, 'PrayerTimes', 'city_id'),
            'countryCode' => array(self::BELONGS_TO, 'Countries', 'country_code'),
            'weatherCities' => array(self::HAS_ONE, 'WeatherCities', 'city_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'city_id' => 'City',
            'country_code' => 'Country Code',
            'city' => 'City',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'timezone' => 'Timezone',
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

        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('country_code', $this->country_code, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('latitude', $this->latitude);
        $criteria->compare('longitude', $this->longitude);
        $criteria->compare('timezone', $this->timezone);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}