<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "regions_has_countries".
 *
 * The followings are the available columns in table 'regions_has_countries':
 * @property integer $region_id
 * @property string $country_code
 * @property integer $region_sort
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RegionsHasCountries extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RegionsHasCountries the static model class
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
		return 'regions_has_countries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('region_id, country_code', 'required'),
			array('region_id, region_sort', 'numerical', 'integerOnly'=>true),
			array('country_code', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('region_id, country_code, region_sort', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'region_id' => 'Region',
			'country_code' => 'Country Code',
			'region_sort' => 'Region Sort',
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

		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('region_sort',$this->region_sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
