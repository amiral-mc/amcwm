<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "reset_passwods".
 * 
 * The followings are the available columns in table 'reset_passwods':
 * @property integer $reset_id
 * @property string $user_id
 * @property string $reset_key
 * @property string $reset_date
 * 
 * The followings are the available model relations:
 * @property Users $user
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ResetPasswods extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'reset_passwods';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, reset_key, reset_date', 'required'),
            array('user_id', 'length', 'max' => 10),
            array('reset_key', 'length', 'max' => 8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('reset_id, user_id, reset_key, reset_date', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'reset_id' => 'Reset',
            'user_id' => 'User',
            'reset_key' => 'Reset Key',
            'reset_date' => 'Reset Date',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('reset_id', $this->reset_id);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('reset_key', $this->reset_key, true);
        $criteria->compare('reset_date', $this->reset_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ResetPasswods the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }   
}
