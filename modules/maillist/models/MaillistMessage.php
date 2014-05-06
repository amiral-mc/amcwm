<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "maillist_message".
 *
 * The followings are the available columns in table 'maillist_message':
 * @property string $id
 * @property integer $channel_id
 * @property integer $template_id
 * @property string $subject
 * @property string $body
 * @property string $cron_condition
 * @property string $cron_time
 * @property integer $cron_step
 * @property string $cron_start
 * @property string $cron_end
 *
 * The followings are the available model relations:
 * @property MaillistMessagesSetions[] $sections
 * @property MaillistChannels $channel
 * @property MaillistChannelsTemplates $template
 * @property MaillistArticlesLog[] $maillistArticlesLogs
 * @property MaillistLog[] $maillistLogs
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MaillistMessage extends ActiveRecord {

    public $sectionsIds = array();
    public $lastSent = null;
    public $withoutCronEnd = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MaillistMessage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'maillist_message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cron_start, published, subject', 'required'),
            array('body', 'required', 'on' => 'allowEditingBody4Insert, allowEditingBody4Update'),
            array('template_id', 'required', 'on' => 'requireTemplate4Insert, requireTemplate4Update'),
            array('published, withoutCronEnd, channel_id, template_id, cron_step', 'numerical', 'integerOnly' => true),
            array('subject', 'length', 'max' => 255),
            array('cron_condition', 'length', 'max' => 5),
            array('cron_time', 'length', 'max' => 10),
            array('cron_condition', 'validateCronCondition'),
            array('cron_start', 'compare', 'compareValue' => date("Y-m-d H:i"), 'operator' => '>=', 'on' => 'insert, allowEditingBody4Insert', 'allowEmpty' => true),
            array('cron_end', 'compare', 'compareAttribute' => 'cron_start', 'operator' => '>', 'allowEmpty' => true),
            array('cron_start', 'safe'),
            array('cron_end', 'safe'),
            array('sectionsIds', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, channel_id, template_id, subject, body, cron_condition, cron_time, cron_step', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Get the cron conditions list    
     * @param string $cond
     * @return mixed
     */
    public static function cronConditionsList($cond = -1) {
        $cronConditions = array('hour' => AmcWm::t("msgsbase.core", "hour"), 'day' => AmcWm::t("msgsbase.core", "day"), 'week' => AmcWm::t("msgsbase.core", "week"), 'month' => AmcWm::t("msgsbase.core", "month"), 'year' => AmcWm::t("msgsbase.core", "year"));
        if (isset($cronConditions[$cond])) {
            return $cronConditions[$cond];
        } else if ($cond == -1) {
            return $cronConditions;
        } else {
            return null;
        }
    }

    /**
     * Validate condition
     * @param string $attribute
     * @param array $params
     */
    public function validateCronCondition($attribute, $params) {
        if (!$this->isEmpty($this->cron_end) && $this->isEmpty($this->cron_condition)) {
            $this->addError($attribute, AmcWm::t('msgsbase.channels', 'Please enter Cron Starting values'));
        } else if ($this->isEmpty($this->cron_condition) && !$this->isEmpty($this->cron_step)) {
            $this->addError($attribute, AmcWm::t('msgsbase.channels', 'Please enter Cron Starting values'));
        } else if (!$this->isEmpty($this->cron_condition) && $this->isEmpty($this->cron_step)) {
            $this->addError($attribute, AmcWm::t('msgsbase.channels', 'Please enter Cron Starting values'));
        }
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the {@link onAfterFind} event.
     * You may override this method to do postprocessing after each newly found record is instantiated.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind() {
        $this->sectionsIds = array_keys($this->sections);
        
        if (!$this->cron_end || $this->cron_end == "0000-00-00 00:00:00") {
            $this->withoutCronEnd = 1;
        } else {
            $this->withoutCronEnd = 0;
        }
        $this->displayTitle = $this->subject;
        if ($this->cron_time) {
            $this->lastSent = date("Y-m-d", $this->cron_time);
        }
        parent::afterFind();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'channel' => array(self::BELONGS_TO, 'MaillistChannels', 'channel_id'),
            'template' => array(self::BELONGS_TO, 'MaillistChannelsTemplates', 'template_id'),
            'maillistArticlesLogs' => array(self::HAS_MANY, 'MaillistArticlesLog', 'subscriber_id'),
            'maillistLogs' => array(self::HAS_MANY, 'MaillistLog', 'subscriber_id'),
            'sections' => array(self::HAS_MANY, 'MaillistMessagesSetions', 'message_id', 'index' => 'section_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => AmcWm::t('msgsbase.mailing', 'ID'),
            'channel_id' => AmcWm::t('msgsbase.mailing', 'Channel'),
            'template_id' => AmcWm::t('msgsbase.mailing', 'Template'),
            'subject' => AmcWm::t('msgsbase.mailing', 'Subject'),
            'body' => AmcWm::t('msgsbase.mailing', 'Body'),
            'cron_time' => AmcWm::t('msgsbase.mailing', 'Last Send Date'),
            'cron_condition' => AmcWm::t('msgsbase.mailing', 'Cron Condition'),
            'cron_step' => AmcWm::t('msgsbase.mailing', 'Cron Step'),
            'cron_start' => AmcWm::t('msgsbase.core', 'Cron Start Date'),
            'cron_end' => AmcWm::t('msgsbase.core', 'Cron End Date'),
            'withoutCronEnd' => AmcWm::t('msgsbase.core', 'Without end date'),            
            'sectionsIds' => AmcWm::t('msgsbase.core', 'Sections'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('channel_id', $this->channel_id);
        $criteria->compare('template_id', $this->template_id);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('cron_condition', $this->cron_condition, true);
        $criteria->compare('cron_time', $this->cron_time, true);
        $criteria->compare('cron_step', $this->cron_step);
        if (!$this->channel_id) {
            $criteria->addCondition("t.channel_id is null");
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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
        if ($this->withoutCronEnd) {
            $this->cron_end = null;
        }
        if (!$this->cron_start || $this->cron_start == "0000-00-00 00:00:00") {
            $this->cron_start = null;
        }
        else{
            $this->cron_start = date("Y-m-d H:i:00", strtotime($this->cron_start));
        }
        if (!$this->cron_end || $this->cron_end == "0000-00-00 00:00:00") {
            $this->cron_end = null;
        }
        if (!$this->cron_condition) {
            $this->cron_condition = null;
        }
        return parent::beforeSave();
    }
   
    /**
     * Save sections
     */
    public function saveSections() {
        if ($this->channel && $this->channel->auto_generate) {
            if (count($this->sectionsIds)) {
                $qd = sprintf("delete from maillist_messages_setions where message_id = %d", $this->id);
                Yii::app()->db->createCommand($qd)->execute();
                $qAdd = "insert into maillist_messages_setions (section_id, message_id) values ";
                $qAddItems = array();
                foreach ($this->sectionsIds as $sectionId) {
                    $qAddItems[] = "({$sectionId}, {$this->id})";
                }
                $qAdd .= implode(", ", $qAddItems);
                Yii::app()->db->createCommand($qAdd)->execute();
            } else {
                $qd = sprintf("delete from maillist_messages_setions where message_id = %d", $this->id);
                Yii::app()->db->createCommand($qd)->execute();
            }
        } else {
            $qd = sprintf("delete from maillist_messages_setions where message_id = %d", $this->id);
            Yii::app()->db->createCommand($qd)->execute();
        }
    }

}