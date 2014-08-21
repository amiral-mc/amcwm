<?php

/**
 * This is the model class for table "exchange_companies".
 *
 * The followings are the available columns in table 'exchange_companies':
 * @property integer $exchange_companies_id
 * @property integer $exchange_id 
 * @property string $code
 * @property integer $published
 * @property string $currency
 *
 * The followings are the available model relations:
 * @property Exchange $exchange
 * @property ExchangeTradingCompanies[] $exchangeTradingCompanies
 */
class ExchangeCompanies extends ParentTranslatedActiveRecord {

    const REF_PAGE_SIZE = 30;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'exchange_companies';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('exchange_id', 'required'),
            array('exchange_id, published', 'numerical', 'integerOnly' => true),
            array('code, currency', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('exchange_companies_id, exchange_id, code, currency', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'exchange' => array(self::BELONGS_TO, 'Exchange', 'exchange_id'),
            'exchangeTradingCompanies' => array(self::HAS_MANY, 'ExchangeTradingCompanies', 'exchange_companies_exchange_companies_id'),
            'translationChilds' => array(self::HAS_MANY, 'ExchangeCompaniesTranslation', 'exchange_companies_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'exchange_companies_id' => AmcWm::t('msgsbase.companies', 'Company ID'),
            'exchange_id' => AmcWm::t('msgsbase.companies', 'Exchange ID'),
            'code' => AmcWm::t('msgsbase.companies', 'Code'),
            'published' => AmcWm::t('amcBack', 'Published'),
            'currency' => AmcWm::t('amcCore', 'Currency'),
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
        $criteria->compare('exchange_companies_id', $this->exchange_companies_id);
        $criteria->compare('exchange_id', $this->exchange_id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('currency', $this->currency);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ExchangeCompanies the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Get companies
     * @return array
     */
    public static function getCompanies($eid, $asObject = false) {
        $query = sprintf("SELECT et.exchange_companies_id, company_name FROM exchange_companies e INNER JOIN exchange_companies_translation et on e.exchange_companies_id = et.exchange_companies_id WHERE exchange_id = %d and content_lang = %s", $eid, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        $rows = AmcWm::app()->db->createCommand($query)->queryAll();
        if ($asObject) {
            return $rows;
        } else {
            return CHtml::listData($rows, 'exchange_companies_id', 'company_name');
        }
    }

    /**
     * Get companies list
     * @return array
     * @access public
     */
    static public function getCompaniesList($keywords = null, $pageNumber = 1, $prompt = null, $eid) {
        if (!$pageNumber) {
            $pageNumber = 1;
        }
        $queryWhere = null;
        $pageNumber = (int) $pageNumber;
        $keywords = trim($keywords);
        $queryCount = "SELECT count(*) 
            FROM exchange_companies e
            INNER JOIN exchange_companies_translation et on e.exchange_companies_id = et.exchange_companies_id
        ";
        $command = AmcWm::app()->db->createCommand();
        $command->select("et.exchange_companies_id, company_name");
        $command->from = "exchange_companies e";
        $command->join("exchange_companies_translation et", 'e.exchange_companies_id = et.exchange_companies_id');
        $where = sprintf("content_lang = %s and exchange_id = %d", AmcWm::app()->db->quoteValue(Controller::getContentLanguage()), $eid);
        if ($keywords) {
            $keywords = "%{$keywords}%";
            $where .= sprintf(" and (company_name like %s)", AmcWm::app()->db->quoteValue($keywords));
        }
        $command->where($where);
        $queryCount.=" where {$where}";
        $command->limit(self::REF_PAGE_SIZE, self::REF_PAGE_SIZE * ($pageNumber - 1));
        $data = $command->queryAll();
        $list = array('records' => array(), 'total' => 0);
        if ($prompt) {
            $list['records'][] = array("id" => null, "text" => $prompt);
        }
        foreach ($data as $row) {
            $label = "[{$row['company_name']}]";
            $list['records'][] = array("id" => $row['exchange_companies_id'], "text" => $label);
        }
        $list['total'] = AmcWm::app()->db->createCommand($queryCount)->queryScalar();
        return $list;
    }

}
