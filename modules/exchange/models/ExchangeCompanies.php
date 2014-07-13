<?php

/**
 * This is the model class for table "exchange_companies".
 *
 * The followings are the available columns in table 'exchange_companies':
 * @property integer $exchange_companies_id
 * @property integer $exchange_id
 * @property string $company_name
 * @property string $code
 *
 * The followings are the available model relations:
 * @property Exchange $exchange
 * @property ExchangeTradingCompanies[] $exchangeTradingCompanies
 */
class ExchangeCompanies extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'exchange_companies';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('exchange_id, company_name', 'required'),
            array('exchange_id', 'numerical', 'integerOnly'=>true),
            array('company_name, code', 'length', 'max'=>45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('exchange_companies_id, exchange_id, company_name, code', 'safe', 'on'=>'search'),
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
            'exchangeTradingCompanies' => array(self::HAS_MANY, 'ExchangeTradingCompanies', 'exchange_companies_exchange_companies_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'exchange_companies_id' => AmcWm::t('msgsbase.core', 'Exchange Companies'),
            'exchange_id' => AmcWm::t('msgsbase.core', 'Exchange Name'),
            'company_name' => AmcWm::t('msgsbase.core', 'Company Name'),
            AmcWm::t('msgsbase.core', 'Code'),
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

        $criteria->compare('exchange_companies_id',$this->exchange_companies_id);
        $criteria->compare('exchange_id',$this->exchange_id);
        $criteria->compare('company_name',$this->company_name,true);
        $criteria->compare('code',$this->code,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ExchangeCompanies the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}