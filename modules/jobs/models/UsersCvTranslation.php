<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "users_cv_translation".
 *
 * The followings are the available columns in table 'users_cv_translation':
 * @property string $cv_id
 * @property string $content_lang
 * @property string $city
 * @property string $educations
 * @property string $work_experiences
 * @property string $computer_skills
 * @property string $professional_certifications
 * @property string $career_objective
 * @property string $address
 * 
 * The followings are the available model relations:
 * @property UsersCv $cv
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class UsersCvTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UsersCvTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users_cv_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang', 'required'),
            array('cv_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('city', 'length', 'max' => 100),
            array('educations, work_experiences, computer_skills, professional_certifications, career_objective, address', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cv_id, content_lang, city, educations, work_experiences, computer_skills, professional_certifications, career_objective, address', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'UsersCv', 'cv_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'cv_id' => 'Cv',
            'content_lang' => 'Content Lang',
            'city' => 'City',
            'educations' => 'Educations',
            'work_experiences' => 'Work Experiences',
            'computer_skills' => 'Computer Skills',
            'professional_certifications' => 'Professional Certifications',
            'career_objective' => 'Career Objective',
            'address' => 'Address',
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

        $criteria->compare('cv_id', $this->cv_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('educations', $this->educations, true);
        $criteria->compare('work_experiences', $this->work_experiences, true);
        $criteria->compare('computer_skills', $this->computer_skills, true);
        $criteria->compare('professional_certifications', $this->professional_certifications, true);
        $criteria->compare('career_objective', $this->career_objective, true);
        $criteria->compare('address', $this->address, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}