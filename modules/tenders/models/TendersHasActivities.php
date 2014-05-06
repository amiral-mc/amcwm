<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "tenders_has_activities".
 *
 * The followings are the available columns in table 'tenders_has_activities':
 * @property integer $activity_id
 * @property string $tender_id
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersHasActivities extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TendersHasActivities the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenders_has_activities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_id, tender_id', 'required'),
            array('activity_id', 'numerical', 'integerOnly' => true),
            array('tender_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('activity_id, tender_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'activity_id' => AmcWm::t("msgsbase.core", 'Activity'),
            'tender_id' => AmcWm::t("msgsbase.core", 'Tender'),
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

        $criteria->compare('activity_id', $this->activity_id);
        $criteria->compare('tender_id', $this->tender_id, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}