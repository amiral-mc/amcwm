<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "maillist_log".
 *
 * The followings are the available columns in table 'maillist_log':
 * @property integer $channel_id
 * @property string $subscriber_id
 * @property string $ip
 * @property string $log_date
 *
 * The followings are the available model relations:
 * @property MaillistChannelsSubscribe $channel
 * @property MaillistChannelsSubscribe $subscriber
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MaillistLog extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MaillistLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'maillist_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('channel_id, subscriber_id, ip, log_date', 'required'),
            array('channel_id', 'numerical', 'integerOnly' => true),
            array('subscriber_id', 'length', 'max' => 10),
            array('ip', 'length', 'max' => 11),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('channel_id, subscriber_id, ip, log_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'channel' => array(self::BELONGS_TO, 'MaillistChannelsSubscribe', 'channel_id'),
            'subscriber' => array(self::BELONGS_TO, 'MaillistMessage', 'subscriber_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'channel_id' => AmcWm::t('msgsbase.core', 'Channel'),
            'subscriber_id' => AmcWm::t('msgsbase.core', 'Subscriber'),
            'ip' => AmcWm::t('msgsbase.core', 'Ip'),
            'log_date' => AmcWm::t('msgsbase.core', 'Log Date'),
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

        $criteria->compare('channel_id', $this->channel_id);
        $criteria->compare('subscriber_id', $this->subscriber_id, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('log_date', $this->log_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}