<?php

/**
 * This is the model class for table "exchange_companies_translation".
 *
 * The followings are the available columns in table 'exchange_companies_translation':
 * @property integer $exchange_companies_id
 * @property string $company_name
 * @property string $content_lang
 *
 * The followings are the available model relations:
 * @property ExchangeCompanies $exchangeCompanies
 */
class ExchangeCompaniesTranslation extends ChildTranslatedActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'exchange_companies_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company_name, content_lang', 'required'),
            array('exchange_companies_id', 'numerical', 'integerOnly'=>true),
            array('company_name', 'length', 'max'=>100),
            array('content_lang', 'length', 'max'=>2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('exchange_companies_id, company_name, content_lang', 'safe', 'on'=>'search'),
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
            'parentContent' => array(self::BELONGS_TO, 'ExchangeCompanies', 'exchange_companies_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'exchange_companies_id' => AmcWm::t('msgsbase.companies', 'ID'),
            'company_name' => AmcWm::t('msgsbase.companies', 'Company Name'),
            'content_lang' => AmcWm::t('msgsbase.amcTools', 'Language'),
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
        $criteria->compare('company_name',$this->company_name,true);
        $criteria->compare('content_lang',$this->content_lang,true);
        $criteria->join .= " inner join exchange_companies e on t.exchange_companies_id = e.exchange_companies_id";
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ExchangeCompaniesTranslation the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->displayTitle = $this->company_name;
        parent::afterFind();
    }
}