<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "maillist_articles_log".
 *
 * The followings are the available columns in table 'maillist_articles_log':
 * @property string $article_id
 * @property integer $channel_id
 * @property string $subscriber_id
 * @property string $ip
 * @property string $log_date
 *
 * The followings are the available model relations:
 * @property Articles $article
 * @property MaillistMessage $subscriber
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MaillistArticlesLog extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MaillistArticlesLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'maillist_articles_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article_id, channel_id, subscriber_id, ip, log_date', 'required'),
            array('channel_id', 'numerical', 'integerOnly' => true),
            array('article_id, subscriber_id', 'length', 'max' => 10),
            array('ip', 'length', 'max' => 11),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('article_id, channel_id, subscriber_id, ip, log_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'article' => array(self::BELONGS_TO, 'Articles', 'article_id'),
            'subscriber' => array(self::BELONGS_TO, 'MaillistMessage', 'subscriber_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' => AmcWm::t('msgsbase.core', 'Article'),
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

        $criteria->compare('article_id', $this->article_id, true);
        $criteria->compare('channel_id', $this->channel_id);
        $criteria->compare('subscriber_id', $this->subscriber_id, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('log_date', $this->log_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}