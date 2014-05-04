<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "jobs_translation".
 *
 * The followings are the available columns in table 'jobs_translation':
 * @property integer $job_id
 * @property string $content_lang
 * @property string $job
 * @property string $job_description
 *
 * The followings are the available model relations:
 * @property Jobs $job0
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class JobsTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return JobsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'jobs_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, job', 'required'),
            array('job_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('job', 'length', 'max' => 100),
            array('job_description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('job_id, content_lang, job, job_description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Jobs', 'job_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'job_id' => AmcWm::t("msgsbase.core", 'Job'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'job' => AmcWm::t("msgsbase.core", 'Job Name'),
            'job_description' => AmcWm::t("msgsbase.core", 'Job Description'),
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

        $criteria->compare('job_id', $this->job_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('job', $this->job, true);
        $criteria->compare('job_description', $this->job_description, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}