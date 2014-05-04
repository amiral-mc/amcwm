<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "maillist_channels".
 *
 * The followings are the available columns in table 'maillist_channels':
 * @property integer $id
 * @property string $channel
 * @property string $content_lang
 * @property string $channel_command
 * @property integer $section_id
 * @property integer $auto_generate
 *
 * The followings are the available model relations:
 * @property MaillistChannelsSubscribe[] $maillists 
 * @property MaillistChannelsTemplates[] $templates
 * @property MaillistMessage[] $maillistMessages
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MaillistChannels extends ActiveRecord {
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MaillistChannels the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'maillist_channels';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('published, channel', 'required'),
            array('published,  auto_generate, is_system', 'numerical', 'integerOnly' => true),
            array('channel', 'length', 'max' => 45),
            array('content_lang', 'length', 'max' => 2),
            array('channel_command', 'length', 'max' => 15),            
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, channel, content_lang, channel_command', 'safe', 'on' => 'search'),
        );
    }

   
   

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(            
            //'maillists' => array(self::MANY_MANY, 'Maillist', 'maillist_channels_subscribe(channel_id, subscriber_id)'),
            'maillists' => array(self::HAS_MANY, 'MaillistChannelsSubscribe', 'channel_id', 'index' => 'subscriber_id'),
            'maillistMessages' => array(self::HAS_MANY, 'MaillistMessage', 'channel_id'),
            'templates' => array(self::HAS_MANY, 'MaillistChannelsTemplates', 'channel_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => AmcWm::t('msgsbase.channels', 'ID'),
            'channel' => AmcWm::t('msgsbase.channels', 'Channel'),
            'content_lang' => AmcWm::t('msgsbase.channels', 'Content Lang'),
            'channel_command' => AmcWm::t('msgsbase.channels', 'Channel Command'),            
            'published' => AmcWm::t('msgsbase.core', 'Publish'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('channel', $this->channel, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('channel_command', $this->channel_command, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the {@link onAfterFind} event.
     * You may override this method to do postprocessing after each newly found record is instantiated.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind() {             
        $this->displayTitle = $this->channel;
        parent::afterFind();
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * The default implementation raises the {@link onBeforeSave} event.
     * You may override this method to do any preparation work for record saving.
     * Use {@link isNewRecord} to determine whether the saving is
     * for inserting or updating record.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave() {      
        return parent::beforeSave();
    }   
}