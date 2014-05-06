<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "events".
 *
 * The followings are the available columns in table 'events':
 * @property string $event_id
 * @property integer $section_id
 * @property string $country_code
 * @property string $votes
 * @property double $votes_rate
 * @property string $hits
 * @property integer $published
 * @property string $create_date
 * @property string $event_date
 * @property string $update_date
 *
 * The followings are the available model relations:
 * @property Sections $section
 * @property Countries $country
 * @property EventsTranslation[] $translationChilds
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Events extends ParentTranslatedActiveRecord {    

    /**
     * parent section
     * @var int
     * @access public
     */
    public $parentSection = null;

    /**
     * sub section
     * @var int
     * @access public
     */
    public $subSection = null;

    /**
     * Social ids added to this active record
     * @var array
     * @access public
     */
    public $socialIds = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Events the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'events';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $date = date("Y-m-d H:i:s");
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('event_date', 'required'),
            array('section_id, published, parentSection, subSection', 'numerical', 'integerOnly' => true),
            array('votes_rate', 'numerical'),
            array('country_code', 'length', 'max' => 2),
            array('votes, hits', 'length', 'max' => 10),
            array('update_date', 'safe'),            
            array('update_date', 'default',
                'value' => new CDbExpression("'$date'"),
                'setOnEmpty' => false),
            array('create_date', 'default',
                'value' => new CDbExpression("'$date'"),
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('event_id, section_id, country_code, votes, votes_rate, hits, published, create_date, event_date, update_date', 'safe', 'on' => 'search'),
        );
    }   

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'section' => array(self::BELONGS_TO, 'Sections', 'section_id'),
            'country' => array(self::BELONGS_TO, 'Countries', 'country_code'),
            'translationChilds' => array(self::HAS_MANY, 'EventsTranslation', 'event_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {

        return array(
            'event_id' => AmcWm::t("msgsbase.core", 'ID'),
            'votes' => AmcWm::t("msgsbase.core", 'Votes'),
            'votes_rate' => AmcWm::t("msgsbase.core", 'Votes Rate'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'create_date' => AmcWm::t("msgsbase.core", 'Create Date'),
            'section_id' => AmcWm::t("msgsbase.core", 'Section'),
            'event_date' => AmcWm::t("msgsbase.core", 'Event Date'),
            'parentSection' => AmcWm::t("msgsbase.core", 'Parent Section'),
            'subSection' => AmcWm::t("msgsbase.core", 'Sub Section'),
            'country_code' => AmcWm::t("msgsbase.core", 'Country Code'),
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

        $criteria->compare('event_id', $this->event_id, true);
        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('country_code', $this->country_code, true);
        $criteria->compare('votes', $this->votes, true);
        $criteria->compare('votes_rate', $this->votes_rate);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('event_date', $this->event_date, true);
        $criteria->compare('update_date', $this->update_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        if (!$this->event_date || $this->event_date == '0000-00-00 00:00:00') {
            $this->event_date = NULL;
        }
        $current = $this->getCurrent();
        if ($current instanceof ChildTranslatedActiveRecord) {
            $this->displayTitle = $current->event_header;
        }
        $this->subSection = $this->section_id;
        $query = sprintf('select parent_section from sections where section_id = %d', $this->section_id);
        $this->parentSection = (int) Yii::app()->db->createCommand($query)->queryScalar();
        parent::afterFind();
    }

    protected function beforeSave() {
        if (!$this->event_date || $this->event_date == '0000-00-00 00:00:00') {
            $this->event_date = NULL;
        }
        if (!$this->country_code) {
            $this->country_code = NULL;
        }
        return parent::beforeSave();
    }

}