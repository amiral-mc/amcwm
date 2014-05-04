<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "prayer_times".
 *
 * The followings are the available columns in table 'prayer_times':
 * @property integer $city_id
 * @property integer $fajr
 * @property integer $sunrise
 * @property integer $dhuhr
 * @property integer $asr
 * @property integer $maghrib
 * @property integer $isha
 *
 * The followings are the available model relations:
 * @property ServicesCities $city
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class PrayerTimes extends ActiveRecord {
        
    /**
     * Returns the static model of the specified AR class.
     * @return PrayerTimes the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'prayer_times';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('city_id, fajr, sunrise, dhuhr, asr, maghrib, isha', 'required'),
            array('city_id, fajr, sunrise, dhuhr, asr, maghrib, isha', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('city_id, fajr, sunrise, dhuhr, asr, maghrib, isha', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'city' => array(self::BELONGS_TO, 'ServicesCities', 'city_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'city_id' => 'City',
            'fajr' => 'Fajr',
            'sunrise' => 'Sunrise',
            'dhuhr' => 'Dhuhr',
            'asr' => 'Asr',
            'maghrib' => 'Maghrib',
            'isha' => 'Isha',
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
        $criteria->compare('fajr', $this->fajr);
        $criteria->compare('sunrise', $this->sunrise);
        $criteria->compare('dhuhr', $this->dhuhr);
        $criteria->compare('asr', $this->asr);
        $criteria->compare('maghrib', $this->maghrib);
        $criteria->compare('isha', $this->isha);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}