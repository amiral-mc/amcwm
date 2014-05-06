<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "users_cv".
 *
 * The followings are the available columns in table 'users_cv':
 * @property string $cv_id
 * @property string $user_id
 * @property integer $military
 * @property integer $marital
 * @property integer $have_children
 * @property integer $driving_license
 * @property integer $car_owner
 * @property string $attach_ext
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Jobs[] $jobs
 * @property UsersCvTranslation[] $usersCvTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class UsersCv extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UsersCv the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users_cv';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, military, marital', 'required'),
            array('military, marital, have_children, driving_license, car_owner', 'numerical', 'integerOnly' => true),
            array('user_id', 'length', 'max' => 10),
            array('attach_ext', 'length', 'max' => 4),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cv_id, user_id, military, marital, have_children, driving_license, car_owner, attach_ext', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'jobs' => array(self::MANY_MANY, 'Jobs', 'users_cv_has_jobs(cv_id, job_id)'),
            'translationChilds' => array(self::HAS_MANY, 'UsersCvTranslation', 'cv_id', 'index' => 'content_lang'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'cv_id' => 'Cv',
            'user_id' => 'User',
            'military' => 'Military',
            'marital' => 'Marital',
            'have_children' => 'Have Children',
            'driving_license' => 'Driving License',
            'car_owner' => 'Car Owner',
            'attach_ext' => 'Attach Ext',
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
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('military', $this->military);
        $criteria->compare('marital', $this->marital);
        $criteria->compare('have_children', $this->have_children);
        $criteria->compare('driving_license', $this->driving_license);
        $criteria->compare('car_owner', $this->car_owner);
        $criteria->compare('attach_ext', $this->attach_ext, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}