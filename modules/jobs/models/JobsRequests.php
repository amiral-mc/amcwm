<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "jobs_requests".
 *
 * The followings are the available columns in table 'jobs_requests':
 * @property string $request_id
 * @property integer $job_id
 * @property string $content_lang
 * @property string $nationality
 * @property integer $accepted
 * @property string $date_of_birth
 * @property string $sex
 * @property integer $military
 * @property integer $marital
 * @property integer $have_children
 * @property integer $driving_license
 * @property integer $car_owner
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property string $email
 * @property string $name
 * @property string $city
 * @property string $educations
 * @property string $work_experiences
 * @property string $computer_skills
 * @property string $professional_certifications
 * @property string $career_objective
 * @property string $attach_ext
 * @property string $address
 * @property integer $short_list
 *
 * The followings are the available model relations:
 * @property Jobs $job
 * @property Countries $nationality0
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class JobsRequests extends ActiveRecord {

    public $verifyCode;
    public $dobYear;
    public $dobMonth;
    public $dobDay;
    public $attachedFile = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return JobsRequests the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'jobs_requests';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        // will receive user inputs.
        return array(
            array('content_lang, nationality, date_of_birth, sex, military, marital, email, name', 'required'),
            array('verifyCode', 'required', 'on' => 'request'),
            array('job_id, accepted, military, marital, have_children, driving_license, car_owner, dobYear, dobMonth, dobDay', 'numerical', 'integerOnly' => true),
            array('content_lang, nationality', 'length', 'max' => 2),
            array('sex, short_list', 'length', 'max' => 1),
            array('date_of_birth', 'checkDateOfBirth'),
            array('dobYear, dobMonth, dobDay', 'length', 'max' => 4),
            array('phone, mobile, fax', 'length', 'max' => 15),
            array('email', 'unique'),
            array('email', 'length', 'max' => 65),
            array('name', 'length', 'max' => 45),
            array('city', 'length', 'max' => 100),
            array('attach_ext', 'length', 'max' => 4),
            array('educations, work_experiences, computer_skills, professional_certifications, career_objective, address', 'safe'),
            array('attachedFile', 'file', 'types' => $mediaSettings['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['maxFileSize']),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'request'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('request_id, job_id, content_lang, nationality, accepted, short_list, date_of_birth, sex, military, marital, have_children, driving_license, car_owner, phone, mobile, fax, email, name, city, educations, work_experiences, computer_skills, professional_certifications, career_objective, attach_ext, address, dobYear, dobMonth, dobDay', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Check date
     * @param string $attribute
     * @param array $params
     */
    public function checkDateOfBirth($attribute, $params){
        if ((int)$this->dobMonth && (int)$this->dobDay && (int)$this->dobYear && !checkdate($this->dobMonth, $this->dobDay, $this->dobYear)) {
            $this->addError($attribute, AmcWm::t("msgsbase.request", 'Invalid date'));
        }
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'job' => array(self::BELONGS_TO, 'Jobs', 'job_id'),
            'nationalityCode' => array(self::BELONGS_TO, 'Countries', 'nationality'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'request_id' => AmcWm::t("msgsbase.request", 'Request'),
            'job_id' => AmcWm::t("msgsbase.request", 'Job'),
            'content_lang' => AmcWm::t("msgsbase.request", 'Content Lang'),
            'nationality' => AmcWm::t("msgsbase.request", 'Nationality'),
            'accepted' => AmcWm::t("msgsbase.request", 'Accepted'),
            'date_of_birth' => AmcWm::t("msgsbase.request", 'Date Of Birth'),
            'sex' => AmcWm::t("msgsbase.request", 'Sex'),
            'military' => AmcWm::t("msgsbase.request", 'Military'),
            'marital' => AmcWm::t("msgsbase.request", 'Marital'),
            'have_children' => AmcWm::t("msgsbase.request", 'Have Children'),
            'driving_license' => AmcWm::t("msgsbase.request", 'Driving License'),
            'car_owner' => AmcWm::t("msgsbase.request", 'Car Owner'),
            'phone' => AmcWm::t("msgsbase.request", 'Phone'),
            'mobile' => AmcWm::t("msgsbase.request", 'Mobile'),
            'fax' => AmcWm::t("msgsbase.request", 'Fax'),
            'email' => AmcWm::t("msgsbase.request", 'Email'),
            'name' => AmcWm::t("msgsbase.request", 'Name'),
            'city' => AmcWm::t("msgsbase.request", 'City'),
            'educations' => AmcWm::t("msgsbase.request", 'Educations'),
            'work_experiences' => AmcWm::t("msgsbase.request", 'Work Experiences'),
            'computer_skills' => AmcWm::t("msgsbase.request", 'Computer Skills'),
            'professional_certifications' => AmcWm::t("msgsbase.request", 'Professional Certifications'),
            'career_objective' => AmcWm::t("msgsbase.request", 'Career Objective'),
            'attach_ext' => AmcWm::t("msgsbase.request", 'Attach Ext'),
            'address' => AmcWm::t("msgsbase.request", 'Address'),
            'verifyCode' => AmcWm::t('msgsbase.request', 'Verification Code'),
            'attachedFile' => AmcWm::t('msgsbase.request', 'Attached File'),
            'short_list' => AmcWm::t('msgsbase.request', 'Short List'),
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

        $criteria->compare('request_id', $this->request_id, true);
        $criteria->compare('job_id', $this->job_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('nationality', $this->nationality, true);
        $criteria->compare('accepted', $this->accepted);
        $criteria->compare('date_of_birth', $this->date_of_birth, true);
        $criteria->compare('sex', $this->sex, true);
        $criteria->compare('military', $this->military);
        $criteria->compare('marital', $this->marital);
        $criteria->compare('have_children', $this->have_children);
        $criteria->compare('driving_license', $this->driving_license);
        $criteria->compare('car_owner', $this->car_owner);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('educations', $this->educations, true);
        $criteria->compare('work_experiences', $this->work_experiences, true);
        $criteria->compare('computer_skills', $this->computer_skills, true);
        $criteria->compare('professional_certifications', $this->professional_certifications, true);
        $criteria->compare('career_objective', $this->career_objective, true);
        $criteria->compare('attach_ext', $this->attach_ext, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('short_list', $this->short_list);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function afterFind() {
        if ($this->date_of_birth == "0000-00-00") {
            $this->date_of_birth = null;
        }
        $dobTime = strtotime($this->date_of_birth);
        $this->dobDay = (int) date('d', $dobTime);
        $this->dobMonth = (int) date('m', $dobTime);
        $this->dobYear = date('Y', $dobTime);
        parent::afterFind();
    }

    public function beforeValidate() {
        if (!$this->job_id)
            $this->job_id = null;
        $this->date_of_birth = "{$this->dobYear}-{$this->dobMonth}-{$this->dobDay}";
        parent::beforeValidate();
        return true;
    }

    public function beforeSave() {
        $ok = parent::beforeSave();
        if ($ok) {
            if (!$this->job_id)
                $this->job_id = null;
            if (!checkdate($this->dobMonth, $this->dobDay, $this->dobYear)) {
                $this->date_of_birth = "{$this->dobYear}-{$this->dobMonth}-{$this->dobDay}";
            }
        }
        return $ok;
    }

}