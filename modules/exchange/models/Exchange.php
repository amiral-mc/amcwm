<?php

/**
 * This is the model class for table "exchange".
 *
 * The followings are the available columns in table 'exchange':
 * @property integer $exchange_id
 * @property string $exchange_name
 * @property string $currency
 * @property int $published
 *
 * The followings are the available model relations:
 * @property ExchangeCompanies[] $exchangeCompanies
 * @property ExchangeTrading[] $exchangeTradings
 */
class Exchange extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'exchange';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('exchange_name, currency', 'length', 'max' => 45),
            array('published', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('exchange_id, exchange_name, currency, published', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'exchangeCompanies' => array(self::HAS_MANY, 'ExchangeCompanies', 'exchange_id'),
            'exchangeTradings' => array(self::HAS_MANY, 'ExchangeTrading', 'exchange_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'exchange_id' => AmcWm::t('msgsbase.core', 'Exchange ID'),
            'exchange_name' => AmcWm::t('msgsbase.core', 'Exchange Name'),
            'currency' => AmcWm::t('msgsbase.core', 'Currency'),
            'published' => AmcWm::t('msgsbase.core', 'Publish'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('exchange_id', $this->exchange_id);
        $criteria->compare('exchange_name', $this->exchange_name, true);
        $criteria->compare('currency', $this->currency, true);
        $criteria->compare('published', $this->published);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Exchange the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * Check table integrity in delete process
     * @return boolean
     */
    public function integrityCheck() {
        return count($this->exchangeCompanies) || count($this->exchangeTradings);
    }

}
