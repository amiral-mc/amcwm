<?php

/**
 * This is the model class for table "exchange_trading_companies".
 *
 * The followings are the available columns in table 'exchange_trading_companies':
 * @property integer $exchange_trading_exchange_id
 * @property string $exchange_trading_exchange_date
 * @property integer $exchange_companies_exchange_companies_id
 * @property string $opening_value
 * @property string $closing_value
 * @property string $difference_percentage
 *
 * The followings are the available model relations:
 * @property ExchangeTrading $exchangeTradingExchange
 * @property ExchangeTrading $exchangeTradingExchangeDate
 * @property ExchangeCompanies $exchangeCompaniesExchangeCompanies
 */
class ExchangeTradingCompanies extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'exchange_trading_companies';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('exchange_trading_exchange_id, exchange_trading_exchange_date, exchange_companies_exchange_companies_id', 'required'),
            array('exchange_trading_exchange_id, exchange_companies_exchange_companies_id', 'numerical', 'integerOnly'=>true),
            array('opening_value, closing_value', 'length', 'max'=>12),
            array('difference_percentage', 'length', 'max'=>8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('exchange_trading_exchange_id, exchange_trading_exchange_date, exchange_companies_exchange_companies_id, opening_value, closing_value, difference_percentage', 'safe', 'on'=>'search'),
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
            'exchangeTradingExchange' => array(self::BELONGS_TO, 'ExchangeTrading', 'exchange_trading_exchange_id'),
            'exchangeTradingExchangeDate' => array(self::BELONGS_TO, 'ExchangeTrading', 'exchange_trading_exchange_date'),
            'exchangeCompaniesExchangeCompanies' => array(self::BELONGS_TO, 'ExchangeCompanies', 'exchange_companies_exchange_companies_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'exchange_trading_exchange_id' => 'Exchange Trading Exchange',
            'exchange_trading_exchange_date' => 'Exchange Trading Exchange Date',
            'exchange_companies_exchange_companies_id' => 'Exchange Companies Exchange Companies',
            'opening_value' => 'Opening Value',
            'closing_value' => 'Closing Value',
            'difference_percentage' => 'Difference Percentage',
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

        $criteria->compare('exchange_trading_exchange_id',$this->exchange_trading_exchange_id);
        $criteria->compare('exchange_trading_exchange_date',$this->exchange_trading_exchange_date,true);
        $criteria->compare('exchange_companies_exchange_companies_id',$this->exchange_companies_exchange_companies_id);
        $criteria->compare('opening_value',$this->opening_value,true);
        $criteria->compare('closing_value',$this->closing_value,true);
        $criteria->compare('difference_percentage',$this->difference_percentage,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ExchangeTradingCompanies the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}