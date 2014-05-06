<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dope_sheet_shots_types".
 *
 * The followings are the available columns in table 'dope_sheet_shots_types':
 * @property integer $type_id
 * @property string $type
 *
 * The followings are the available model relations:
 * @property DopeSheetShots[] $shots
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DopeSheetShotsTypes extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DopeSheetShotsTypes the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dope_sheet_shots_types';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type', 'required'),
            array('type', 'length', 'max' => 4),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('type_id, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'shots' => array(self::HAS_MANY, 'DopeSheetShots', 'type_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'type_id' => 'Type',
            'type' => 'Type',
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

        $criteria->compare('type_id', $this->type_id);
        $criteria->compare('type', $this->type, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}