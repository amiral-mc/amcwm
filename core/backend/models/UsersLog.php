<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "users_log".
 *
 * The followings are the available columns in table 'users_log':
 * @property string $log_id
 * @property string $ip
 * @property integer $action_id
 * @property string $user_id
 * @property string $action_date
 *
 * The followings are the available model relations:
 * @property DeletedUsers[] $deletedUsers
 * @property LogData $logData
 * @property Users $user
 * @property UserActions $action
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class UsersLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UsersLog the static model class
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
		return 'users_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip, action_id, action_date', 'required'),
			array('action_id', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>15),
			array('user_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('log_id, ip, action_id, user_id, action_date', 'safe', 'on'=>'search'),
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
			'deletedUsers' => array(self::MANY_MANY, 'DeletedUsers', 'deleted_users_log(log_id, deleted_id)'),
			'logData' => array(self::HAS_ONE, 'LogData', 'log_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'action' => array(self::BELONGS_TO, 'UserActions', 'action_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'log_id' => 'Log',
			'ip' => 'Ip',
			'action_id' => 'Action',
			'user_id' => 'User',
			'action_date' => 'Action Date',
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

		$criteria->compare('log_id',$this->log_id,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('action_id',$this->action_id);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('action_date',$this->action_date,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}