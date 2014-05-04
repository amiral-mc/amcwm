<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "maillist_channels_templates".
 *
 * The followings are the available columns in table 'maillist_channels_templates':
 * @property integer $channel_id
 * @property integer $template_id
 * @property integer $template
 * @property string $subject
 * @property string $body
 *
 * The followings are the available model relations:
 * @property MaillistMessage[] $maillistMessages
 * @property MaillistChannels $channel
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MaillistChannelsTemplates extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MaillistChannelsTemplates the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'maillist_channels_templates';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('channel_id, template', 'numerical', 'integerOnly' => true),
            array('subject', 'length', 'max' => 255),
            array('body', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('template_id, subject, body', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'maillistMessages' => array(self::HAS_MANY, 'MaillistMessage', 'template_id'),
            'channel' => array(self::BELONGS_TO, 'MaillistChannels', 'channel_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'template_id' => AmcWm::t('msgsbase.core', 'Template'),
            'template' => AmcWm::t('msgsbase.core', 'Template'),
            'subject' => AmcWm::t('msgsbase.core', 'Subject'),
            'body' => AmcWm::t('msgsbase.core', 'Body'),
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

        $criteria->compare('template_id', $this->template_id);
        $criteria->compare('template', $this->template);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('body', $this->body, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}