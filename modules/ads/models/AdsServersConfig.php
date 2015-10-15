<?php

/**
 * This is the model class for table "ads_servers_config".
 *
 * The followings are the available columns in table 'ads_servers_config':
 * @property integer $server_id
 * @property string $header_code
 * @property string $server_name
 *
 * The followings are the available model relations:
 * @property AdsZones[] $adsZones
 */
class AdsServersConfig extends ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ads_servers_config';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('server_name', 'required'),
            array('header_code', 'safe'),
            array('server_name', 'length', 'max' => 35),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('server_id, header_code, server_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'adsZones' => array(self::HAS_MANY, 'AdsZones', 'server_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'server_id' => AmcWm::t('msgsbase.core', 'Server'),
            'header_code' => AmcWm::t('msgsbase.core', 'Header Code'),
            'server_name' => AmcWm::t('msgsbase.core', 'Server Name'),
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

        $criteria->compare('server_id', $this->server_id);
        $criteria->compare('header_code', $this->header_code, true);
        $criteria->compare('server_name', $this->server_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdsServersConfig the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
