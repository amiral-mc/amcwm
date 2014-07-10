<?php

/**
 * This is the model class for table "default_ads_zones".
 *
 * The followings are the available columns in table 'default_ads_zones':
 * @property integer $zone_id
 * @property string $zone_name
 * @property integer $width
 * @property integer $height
 *
 * The followings are the available model relations:
 * @property AdsZones[] $adsZones
 */
class DefaultAdsZones extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'default_ads_zones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zone_id, zone_name, width, height', 'required'),
			array('zone_id, width, height', 'numerical', 'integerOnly'=>true),
			array('zone_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('zone_id, zone_name, width, height', 'safe', 'on'=>'search'),
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
			'adsZones' => array(self::HAS_MANY, 'AdsZones', 'zone_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'zone_id' => AmcWm::t('msgsbase.core', 'Zone'),
			'zone_name' => AmcWm::t('msgsbase.core', 'Zone Name'),
			'width' => AmcWm::t('msgsbase.core', 'Width'),
			'height' => AmcWm::t('msgsbase.core', 'Height'),
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('zone_id',$this->zone_id);
		$criteria->compare('zone_name',$this->zone_name,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DefaultAdsZones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
