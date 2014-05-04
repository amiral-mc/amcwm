<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "access_rights".
 *
 * The followings are the available columns in table 'access_rights':
 * @property integer $role_id
 * @property integer $controller_id
 * @property integer $access
 *
 * The followings are the available model relations:
 * @property UsersAccessRights[] $usersAccessRights
 * @property UsersAccessRights[] $usersAccessRights1
 * 
 * @author Amiral Management Corporation
 * @version 1.0 
 */
class AccessRights extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return AccessRights the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'access_rights';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id, controller_id', 'required'),
			array('role_id, controller_id, access', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('role_id, controller_id, access', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'usersAccessRights' => array(self::HAS_MANY, 'UsersAccessRights', 'role_id'),
			'usersAccessRights1' => array(self::HAS_MANY, 'UsersAccessRights', 'controller_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'role_id' => 'Role',
			'controller_id' => 'Controller',
			'access' => 'Access',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('controller_id',$this->controller_id);
		$criteria->compare('access',$this->access);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}