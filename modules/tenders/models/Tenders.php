<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "tenders".
 *
 * The followings are the available columns in table 'tenders':
 * @property string $tender_id
 * @property integer $department_id
 * @property integer $tender_type
 * @property integer $tender_status
 * @property string $rfp_start_date
 * @property string $rfp_end_date
 * @property string $submission_start_date
 * @property string $submission_end_date
 * @property string $technical_date
 * @property string $financial_date
 * @property string $rfp_price1
 * @property string $rfp_price2
 * @property string $primary_insurance
 * @property integer $published
 * @property string $hits
 * @property string $file_ext
 * @property string $create_date
 *
 * The followings are the available model relations:
 * @property TendersDepartment $department
 * @property Comments[] $comments
 * @property TendersActivities[] $tendersActivities
 * @property TendersTranslation[] $tendersTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Tenders extends ParentTranslatedActiveRecord {

    public $docFile = null;
    public $activities = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Tenders the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenders';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $date = date("Y-m-d H:i:s");
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('department_id, tender_type, tender_status, rfp_start_date, rfp_end_date, submission_start_date, submission_end_date, technical_date, financial_date, rfp_price1, primary_insurance', 'required'),
            array('department_id, tender_type, tender_status, published', 'numerical', 'integerOnly' => true),
            array('rfp_price1, rfp_price2, primary_insurance, hits', 'length', 'max' => 10),
            array('file_ext', 'length', 'max' => 4),
            array('activities, rfp_start_date, rfp_end_date, submission_start_date, submission_end_date, technical_date, financial_date', 'safe'),
            
            array('rfp_end_date', 'compare', 'compareAttribute' => 'rfp_start_date', 'operator' => '>'),
            array('submission_end_date', 'compare', 'compareAttribute' => 'submission_start_date', 'operator' => '>'),
            
            array('rfp_start_date', 'compare', 'compareValue' => $date, 'operator' => '>=', 'on' => 'insert'),
            array('submission_start_date', 'compare', 'compareValue' => $date, 'operator' => '>=', 'on' => 'insert'),
            
            array('docFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxFileSize']),
            array('create_date', 'default',
                'value' => $date,
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('tender_id, department_id, tender_type, tender_status, rfp_start_date, rfp_end_date, submission_start_date, submission_end_date, technical_date, financial_date, rfp_price1, rfp_price2, primary_insurance, published, hits, file_ext, create_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'department' => array(self::BELONGS_TO, 'TendersDepartment', 'department_id'),
            'comments' => array(self::MANY_MANY, 'Comments', 'tenders_comments(tender_id, comment_id)'),
            'tendersActivities' => array(self::MANY_MANY, 'TendersActivities', 'tenders_has_activities(tender_id, activity_id)'),
            'translationChilds' => array(self::HAS_MANY, 'TendersTranslation', 'tender_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'tender_id' => AmcWm::t("msgsbase.core", 'Tender'),
            'department_id' => AmcWm::t("msgsbase.core", 'Department'),
            'tender_type' => AmcWm::t("msgsbase.core", 'Tender Type'),
            'tender_status' => AmcWm::t("msgsbase.core", 'Tender Status'),
            'rfp_start_date' => AmcWm::t("msgsbase.core", 'Rfp Start Date'),
            'rfp_end_date' => AmcWm::t("msgsbase.core", 'Rfp End Date'),
            'submission_start_date' => AmcWm::t("msgsbase.core", 'Submission Start Date'),
            'submission_end_date' => AmcWm::t("msgsbase.core", 'Submission End Date'),
            'technical_date' => AmcWm::t("msgsbase.core", 'Technical Date'),
            'financial_date' => AmcWm::t("msgsbase.core", 'Financial Date'),
            'rfp_price1' => AmcWm::t("msgsbase.core", 'Rfp Price1'),
            'rfp_price2' => AmcWm::t("msgsbase.core", 'Rfp Price2'),
            'primary_insurance' => AmcWm::t("msgsbase.core", 'Primary Insurance'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'file_ext' => AmcWm::t("msgsbase.core", 'File Ext'),
            'docFile' => AmcWm::t("msgsbase.core", 'File Ext'),
            'create_date' => AmcWm::t("msgsbase.core", 'Create Date'),
            'activities' => AmcWm::t("msgsbase.core", 'Activities'),
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

        $criteria->compare('tender_id', $this->tender_id, true);
        $criteria->compare('department_id', $this->department_id);
        $criteria->compare('tender_type', $this->tender_type);
        $criteria->compare('tender_status', $this->tender_status);
        $criteria->compare('rfp_start_date', $this->rfp_start_date, true);
        $criteria->compare('rfp_end_date', $this->rfp_end_date, true);
        $criteria->compare('submission_start_date', $this->submission_start_date, true);
        $criteria->compare('submission_end_date', $this->submission_end_date, true);
        $criteria->compare('technical_date', $this->technical_date, true);
        $criteria->compare('financial_date', $this->financial_date, true);
        $criteria->compare('rfp_price1', $this->rfp_price1, true);
        $criteria->compare('rfp_price2', $this->rfp_price2, true);
        $criteria->compare('primary_insurance', $this->primary_insurance, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('file_ext', $this->file_ext, true);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function afterFind() {
        $activities = Yii::app()->db->createCommand(sprintf('select activity_id from tenders_has_activities where tender_id = %d', $this->tender_id))->queryAll();
        if (count($activities)) {
            foreach ($activities as $a) {
                $this->activities[] = $a['activity_id'];
            }
        }
        parent::afterFind();
    }

    public function afterSave() {
//        die(print_r($this->activities));
        if (count($this->activities)) {
            Yii::app()->db->createCommand(sprintf('delete from tenders_has_activities where tender_id = %d', $this->tender_id))->execute();
            $insert = 'insert into tenders_has_activities (activity_id, tender_id) values ';
            $q = array();
            foreach ($this->activities as $a) {
                $q[] = sprintf('(%d, %d)', $a, $this->tender_id);
            }
            $insert .= implode(', ', $q);
            Yii::app()->db->createCommand($insert)->execute();
        }
        parent::afterSave();
    }

    public function getTenderTypes($type = null) {
        $types = array(
            '1' => Amcwm::t('msgsbase.core', 'Public Tender'),
            '2' => Amcwm::t('msgsbase.core', 'Limited Tender'),
            '3' => Amcwm::t('msgsbase.core', 'Public Reverse Auction'),
            '4' => Amcwm::t('msgsbase.core', 'Limited Reverse Auction'),
            '5' => Amcwm::t('msgsbase.core', 'Public Auction'),
            '6' => Amcwm::t('msgsbase.core', 'Closed Envelopes Auction'),
            '7' => Amcwm::t('msgsbase.core', 'Local Tender'),
            '8' => Amcwm::t('msgsbase.core', 'Local Auction'),
        );

        if ($type)
            return $types[$type];
        else
            return $types;
    }

    public function getTenderStatus($s = null) {
        $status = array(
            '1' => Amcwm::t('msgsbase.core', 'Public'),
            '2' => Amcwm::t('msgsbase.core', 'Opened'),
            '3' => Amcwm::t('msgsbase.core', 'Closed'),
            '4' => Amcwm::t('msgsbase.core', 'Technical Results'),
            '5' => Amcwm::t('msgsbase.core', 'Awarded'),
        );

        if ($s)
            return $status[$s];
        else
            return $status;
    }

}