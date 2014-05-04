<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "tenders_activities_translation".
 *
 * The followings are the available columns in table 'tenders_activities_translation':
 * @property integer $activity_id
 * @property string $content_lang
 * @property string $activity_name
 *
 * The followings are the available model relations:
 * @property TendersActivities $activity
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersActivitiesTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TendersActivitiesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenders_activities_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, activity_name', 'required'),
            array('activity_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('activity_name', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('activity_id, content_lang, activity_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'TendersActivities', 'activity_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'activity_id' => AmcWm::t("msgsbase.core", 'Activity'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'activity_name' => AmcWm::t("msgsbase.core", 'Activity Name'),
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
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('activity_name', $this->activity_name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}