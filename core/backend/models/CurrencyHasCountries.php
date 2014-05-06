<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "currency_has_countries".
 *
 * The followings are the available columns in table 'currency_has_countries':
 * @property integer $currency_id
 * @property string $country_code
 * @property string $rate
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class CurrencyHasCountries extends ActiveRecord {
    
    /**
     * Returns the static model of the specified AR class.
     * @return CurrencyHasCountries the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'currency_has_countries';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('currency_id, country_code', 'required'),
            array('currency_id', 'numerical', 'integerOnly' => true),
            array('country_code', 'length', 'max' => 2),
            array('rate', 'length', 'max' => 5),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('currency_id, country_code, rate', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'currency_id' => 'Currency',
            'country_code' => 'Country Code',
            'rate' => 'Rate',
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

        $criteria->compare('currency_id', $this->currency_id);
        $criteria->compare('country_code', $this->country_code, true);
        $criteria->compare('rate', $this->rate, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}