<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "currency".
 *
 * The followings are the available columns in table 'currency':
 * @property string $currency_code
 * @property string $currency_name
 * 
 * The followings are the available model relations:
 * @property Countries[] $countries
 * @property CurrencyCompare[] $currencyCompares
 * @property CurrencyCompare[] $currencyCompares1
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Currency extends ActiveRecord {
    
    /**
     * Returns the static model of the specified AR class.
     * @return Currency the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'currency';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('currency_code, currency_name', 'required'),
            array('currency_code', 'length', 'max' => 3),
            array('currency_name', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('currency_code, currency_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'countries' => array(self::HAS_MANY, 'Countries', 'currency_code'),
            'currencyCompares' => array(self::HAS_MANY, 'CurrencyCompare', 'compare_from'),
            'currencyCompares1' => array(self::HAS_MANY, 'CurrencyCompare', 'compare_to'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'currency_code' => 'Currency Code',
            'currency_name' => 'Currency Name',
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

        $criteria->compare('currency_code', $this->currency_code, true);
        $criteria->compare('currency_name', $this->currency_name, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}