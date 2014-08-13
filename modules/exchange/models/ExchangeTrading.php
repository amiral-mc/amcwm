<?php

/**
 * This is the model class for table "exchange_trading".
 *
 * The followings are the available columns in table 'exchange_trading':
 * @property integer $exchange_id
 * @property string $exchange_date
 * @property string $trading_value
 * @property string $shares_of_stock
 * @property string $closing_value
 * @property string $difference_value
 * @property string $difference_percentage
 *
 * The followings are the available model relations:
 * @property Exchange $exchange
 * @property tradingCompanies[] $exchangeTradingCompanies
 */
class ExchangeTrading extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'exchange_trading';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('exchange_id, exchange_date, trading_value, shares_of_stock, closing_value, difference_value, difference_percentage', 'required'),
            array('exchange_id', 'numerical', 'integerOnly'=>true),
            array('trading_value, shares_of_stock', 'length', 'max'=>16),
            array('closing_value', 'length', 'max'=>12),
            array('difference_value, difference_percentage', 'length', 'max'=>8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('exchange_id, exchange_date, trading_value, shares_of_stock, closing_value, difference_value, difference_percentage', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'exchange' => array(self::BELONGS_TO, 'Exchange', 'exchange_id'),
            'tradingCompanies' => array(self::HAS_MANY, 'ExchangeTradingCompanies', 'exchange_trading_exchange_id, exchange_trading_exchange_date'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'exchange_id' => AmcWm::t('msgsbase.core', 'Exchange ID'),
            'exchange_date' => AmcWm::t('msgsbase.core', 'Exchange Date'),
            'trading_value' => AmcWm::t('msgsbase.tradings', 'Exchange Trading Value'),
            'shares_of_stock' => AmcWm::t('msgsbase.tradings', 'Exchange Shares of Stock'),
            'closing_value' => AmcWm::t('msgsbase.tradings', 'Exchange Closing Value'),
            'difference_value' => AmcWm::t('msgsbase.tradings', 'Exchange Difference in Value'),
            'difference_percentage' => AmcWm::t('msgsbase.tradings', 'Exchange Percentage Difference %'),
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('exchange_id',$this->exchange_id);
        $criteria->compare('exchange_date',$this->exchange_date,true);
        $criteria->compare('trading_value',$this->trading_value,true);
        $criteria->compare('shares_of_stock',$this->shares_of_stock,true);
        $criteria->compare('closing_value',$this->closing_value,true);
        $criteria->compare('difference_value',$this->difference_value,true);
        $criteria->compare('difference_percentage',$this->difference_percentage,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ExchangeTrading the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}