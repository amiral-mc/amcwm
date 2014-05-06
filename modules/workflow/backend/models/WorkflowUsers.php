<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "workflow_users".
 *
 * The followings are the available columns in table 'workflow_users':
 * @property string $user_id
 * @property integer $step_id
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WorkflowUsers extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WorkflowUsers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'workflow_users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, step_id', 'required'),
            array('step_id', 'numerical', 'integerOnly' => true),
            array('user_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, step_id', 'safe', 'on' => 'search'),
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
            'user_id' => AmcWm::t("msgsbase.core", 'User'),
            'step_id' => AmcWm::t("msgsbase.core", 'Step'),
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

        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('step_id', $this->step_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}