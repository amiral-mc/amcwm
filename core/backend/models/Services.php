<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "services".
 *
 * The followings are the available columns in table 'services':
 * @property integer $service_id
 * @property string $service_name
 * @property string $class_name
 * @property integer $enabled
 * @property string $cron_condition
 * @property integer $cron_time
 * @property integer $cron_step
 *
 * The followings are the available model relations:
 * @property Sections[] $sections
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Services extends ActiveRecord {
    
    /**
     * Returns the static model of the specified AR class.
     * @return Services the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'services';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('enabled, cron_time, cron_step', 'numerical', 'integerOnly' => true),
            array('service_name', 'length', 'max' => 45),
            array('class_name', 'length', 'max' => 15),
            array('cron_condition', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('service_id, service_name, class_name, enabled, cron_condition, cron_time, cron_step', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'sections' => array(self::MANY_MANY, 'Sections', 'services_sections(service_id, section_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'service_id' => 'Service',
            'service_name' => 'Service Name',
            'class_name' => 'Class Name',
            'enabled' => 'Enabled',
            'cron_condition' => 'Cron Condition',
            'cron_time' => 'Cron Time',
            'cron_step' => 'Cron Step',
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

        $criteria->compare('service_id', $this->service_id);
        $criteria->compare('service_name', $this->service_name, true);
        $criteria->compare('class_name', $this->class_name, true);
        $criteria->compare('enabled', $this->enabled);
        $criteria->compare('cron_condition', $this->cron_condition, true);
        $criteria->compare('cron_time', $this->cron_time);
        $criteria->compare('cron_step', $this->cron_step);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }
    
    /**
     * Check if corn ready to run or not.
     * @param int  $id
     * @access public
     * @return bool
     */
    public function cronIsReady() {
        switch ($this->cron_condition) {
            case "day":
                $currentTime = strtotime(date("Y-m-d"));
                $cronTime = strtotime(date("Y-m-d", $this->cron_time));
                $cronStep = $this->cron_step * 24 * 60 * 60;
                break;
            case "min":
                $cronTime = $this->cron_time;
                $cronStep = $this->cron_step;
                $currentTime = time();
                break;
        }
        return ($currentTime >= $cronTime + $cronStep);
    }

    /**
     * Update the cron job datetime.
     * Set cron_time in cron_config table equal to the current datetime.
     * @access public
     * @return void
     */
    public function updateCronLastRun() {
        $currentTime = time();
        $this->cron_time = $currentTime;
        $this->save();
    }

    /**
     * get cron last date time run for the given $id
     * @param int  $id
     * @access public
     * @return string
     */
    public function getCronLastRun() {
        return $this->cron_time;
    }

}