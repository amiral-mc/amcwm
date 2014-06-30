<?php

/**
 * This is the model class for table "ads_zones".
 *
 * The followings are the available columns in table 'ads_zones':
 * @property integer $ad_id
 * @property integer $server_id
 * @property integer $zone_id
 *
 * The followings are the available model relations:
 * @property AdsServersConfig $server
 * @property DefaultAdsZones $zone
 * @property Sections[] $sections
 */
class AdsZones extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ads_zones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('server_id, zone_id', 'required'),
			array('server_id, zone_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ad_id, server_id, zone_id', 'safe', 'on'=>'search'),
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
			'server' => array(self::BELONGS_TO, 'AdsServersConfig', 'server_id'),
			'zone' => array(self::BELONGS_TO, 'DefaultAdsZones', 'zone_id'),
			'sections' => array(self::MANY_MANY, 'Sections', 'ads_zones_has_sections(ad_id, section_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ad_id' => 'Ad',
			'server_id' => 'Server',
			'zone_id' => 'Zone',
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

		$criteria->compare('ad_id',$this->ad_id);
		$criteria->compare('server_id',$this->server_id);
		$criteria->compare('zone_id',$this->zone_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdsZones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
