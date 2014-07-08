
<?php

/**
 * This is the model class for table "exchange_companies".
 *
 * The followings are the available columns in table 'exchange_companies':
 * @property integer $company_id
 * @property string $company_name
 * @property integer $published
 *  
 * The followings are the available model relations:
 * @property Exchange[] $exchanges
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
            array('company_name', 'required'),
            array('company_id', 'numerical', 'integerOnly'=>true),
            array('company_name', 'length', 'max'=>45),
            array('published', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('company_id, company_name, published', 'safe', 'on'=>'search'),
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
            'exchanges' => array(self::HAS_MANY, 'Exchange', 'company_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'company_id' => AmcWm::t('msgsbase.core', 'Company'),
            'company_name' => AmcWm::t('msgsbase.core', 'Company Name'),
            'published' => AmcWm::t('msgsbase.core', 'Activate'),
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

        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('company_name',$this->company_name,true);
        $criteria->compare('published',$this->published);
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