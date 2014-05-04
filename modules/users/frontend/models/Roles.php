<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "roles".
 *
 * The followings are the available columns in table 'roles':
 * @property integer $role_id
 * @property string $role
 * @property integer $parent_role_id
 * @property string $role_desc
 * @property integer $$is_system
 *
 * The followings are the available model relations:
 * @property Functional[] $functionals
 * @property Roles $parentRole
 * @property Roles[] $roles
 * @property Users[] $users
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Roles extends ActiveRecord {    

    /**
     * Returns the static model of the specified AR class.
     * @return Roles the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'roles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('role, $is_system', 'required'),
            array('parent_role_id, $is_system', 'numerical', 'integerOnly' => true),
            array('role', 'length', 'max' => 20),
            array('role_desc', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('role_id, role, parent_role_id, role_desc, $is_system', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'functionals' => array(self::MANY_MANY, 'Functional', 'access_rights(role_id, function_id)'),
            'parentRole' => array(self::BELONGS_TO, 'Roles', 'parent_role_id'),
            'roles' => array(self::HAS_MANY, 'Roles', 'parent_role_id'),
            'users' => array(self::HAS_MANY, 'Users', 'role_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role_id' => 'Role',
            'role' => 'Role',
            'parent_role_id' => 'Parent Role',
            'role_desc' => 'Role Desc',
            '$is_system' => 'Is System Role',
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

        $criteria->compare('role_id', $this->role_id);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('parent_role_id', $this->parent_role_id);
        $criteria->compare('role_desc', $this->role_desc, true);
        $criteria->compare('$is_system', $this->$is_system);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}