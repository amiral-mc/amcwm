<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "tenders_activities".
 *
 * The followings are the available columns in table 'tenders_activities':
 * @property integer $activity_id
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property TendersActivitiesTranslation[] $tendersActivitiesTranslations
 * @property Tenders[] $tenders
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersActivities extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TendersActivities the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenders_activities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('published', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('activity_id, published', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'translationChilds' => array(self::HAS_MANY, 'TendersActivitiesTranslation', 'activity_id', "index" => "content_lang"),
            'tenders' => array(self::MANY_MANY, 'Tenders', 'tenders_has_activities(activity_id, tender_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'activity_id' => AmcWm::t("msgsbase.core", 'Activity'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
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
        $criteria->compare('published', $this->published);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getActivitiesList() {
        return CHtml::listData(Yii::app()->db->createCommand(sprintf("select activity_id, activity_name from tenders_activities_translation where content_lang=%s order by activity_name  ", Yii::app()->db->quoteValue(Controller::getContentLanguage())))->queryAll(), 'activity_id', "activity_name");
    }

    public function getTenderActivity($tenderId) {
        $tenderActivities = Yii::app()->db->createCommand(sprintf("select activity_id from tenders_has_activities where tender_id=%d", $tenderId))->queryAll();
        $activityList = $this->getActivitiesList();
        $myActivity = array();
        if ($activityList) {
            foreach ($tenderActivities as $activity) {
                $myActivity[$activity['activity_id']] = $activityList[$activity['activity_id']];
            }
        }
        return $myActivity;
    }

}