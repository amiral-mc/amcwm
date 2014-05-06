<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "persons_translation".
 *
 * The followings are the available columns in table 'persons_translation':
 * @property string $person_id
 * @property string $content_lang
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Persons $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class PersonsTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PersonsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'persons_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, name', 'required'),
            array('content_lang', 'length', 'max' => 2),
            array('person_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 65),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('person_id, content_lang, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Persons', 'person_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'person_id' => AmcWm::t("msgsbase.core", 'Person ID'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'name' => AmcWm::t("msgsbase.core", 'Name'),
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

        $criteria->compare('person_id', $this->person_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function supervisors() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('person_id', $this->person_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('p.email', $this->getParentContent()->email, true);
        $criteria->compare('p.inserted_date', $this->getParentContent()->inserted_date, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->join .=" inner join persons p on t.person_id = p.person_id";
        $criteria->join .=" left join writers on writers.writer_id = p.person_id";
        $criteria->join .=" left join users on users.user_id = p.person_id";
        $criteria->addCondition("writers.writer_id is null");
        $criteria->addCondition("users.user_id is null");
        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function writers() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('person_id', $this->person_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('p.email', $this->getParentContent()->email, true);
        $criteria->compare('p.inserted_date', $this->getParentContent()->inserted_date, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->join .=" inner join persons p on t.person_id = p.person_id";
        $criteria->join .=" inner join writers on writers.writer_id = p.person_id";
        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function users() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('person_id', $this->person_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('p.email', $this->getParentContent()->email, true);
        $criteria->compare('p.inserted_date', $this->getParentContent()->inserted_date, true);
        $criteria->compare('users.username', $this->getParentContent()->users->username, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->join .=" inner join persons p on t.person_id = p.person_id";
        $criteria->join .=" inner join users on users.user_id = p.person_id";
        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

}