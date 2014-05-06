<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "writers".
 *
 * The followings are the available columns in table 'writers':
 * @property string $writer_id
 * @property integer $writer_type
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 * @property Persons $person
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Writers extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Writers the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'writers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('writer_id', 'required'),
            array('writer_type', 'numerical', 'integerOnly' => true),
            array('writer_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('writer_id, writer_type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'articles' => array(self::HAS_MANY, 'Articles', 'writer_id'),
            'person' => array(self::BELONGS_TO, 'Persons', 'writer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'writer_id' => 'Writer',
            'writer_type' => 'Writer Type',
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

        $criteria->compare('writer_id', $this->writer_id, true);
        $criteria->compare('writer_type', $this->writer_type);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }   
}