<?php

/**
 * This is the model class for table "exchange".
 *
 * The followings are the available columns in table 'exchange':
 * @property integer $exchange_id
 * @property integer $company_id
 * @property string $index
 * @property string $percentage
 * @property string $net
 *
 * The followings are the available model relations:
 * @property ExchangeCompanies $company
 */
class Exchange extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'exchange';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company_id, index, percentage, net', 'required'),
            array('company_id', 'numerical', 'integerOnly'=>true),
            array('index, percentage, net', 'length', 'max'=>12),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('exchange_id, company_id, index, percentage, net', 'safe', 'on'=>'search'),
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
            'company' => array(self::BELONGS_TO, 'ExchangeCompanies', 'company_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'exchange_id' => AmcWm::t('msgsbase.core', 'Exchange'),
            'company_id' => AmcWm::t('msgsbase.core', 'Company'),
            'index' => AmcWm::t('msgsbase.core', 'Index'),
            'percentage' => AmcWm::t('msgsbase.core', 'Percentage'),
            'net' => AmcWm::t('msgsbase.core', 'Net'),
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
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('index',$this->index,true);
        $criteria->compare('percentage',$this->percentage,true);
        $criteria->compare('net',$this->net,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Exchange the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}