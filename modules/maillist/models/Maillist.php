<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "maillist".
 *
 * The followings are the available columns in table 'maillist':
 * @property string $id
 * @property string $person_id
 * @property integer $status
 * @property string $ip

 *
 * The followings are the available model relations:
 * @property Persons $person
 * @property MaillistChannelsSubscribe[] $maillistChannels
 * @property MaillistUsers $maillistUsers
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Maillist extends ActiveRecord {

    public $email;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Maillist the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'maillist';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('status', 'numerical', 'integerOnly' => true),
            array('person_id', 'length', 'max' => 10),
            array('ip', 'length', 'max' => 15),
            array('email', 'safe'),
            array('ip', 'default',
                'value' => Yii::app()->request->getUserHostAddress(),
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, person_id, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'person' => array(self::BELONGS_TO, 'Persons', 'person_id'),
            //'maillistChannels' => array(self::MANY_MANY, 'MaillistChannels', 'maillist_channels_subscribe(subscriber_id, channel_id)'),
            //'maillistChannels' => array(self::MANY_MANY, 'MaillistChannels', 'maillist_channels_subscribe(subscriber_id, channel_id)'),
            'maillistChannels' => array(self::HAS_MANY, 'MaillistChannelsSubscribe', 'subscriber_id'),
            'maillistUsers' => array(self::HAS_ONE, 'MaillistUsers', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => AmcWm::t('msgsbase.core', 'ID'),
            'person_id' => AmcWm::t('msgsbase.core', 'Person'),
            'ip' => AmcWm::t('msgsbase.core', 'IP'),
            'status' => AmcWm::t('msgsbase.core', 'Status'),
            'email' => AmcWm::t('msgsbase.core', 'Email'),
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
        $criteria->compare('person_id', $this->person_id, true);        
        $criteria->compare('status', $this->status);
        $criteria->compare('u.email', $this->email, true, 'or');
        $criteria->compare('p.email', $this->email, true, 'or');
        $criteria->compare('ip', $this->ip, true);
        $criteria->join .= ' left join maillist_users u on t.id=u.user_id';
        $criteria->join .= ' left join persons p on t.person_id=p.person_id';
//        echo $criteria->condition;
//        die();
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function saveAllChannels() {
        $removeQuery = sprintf("delete from maillist_channels_subscribe where subscriber_id = %d", $this->id);
        Yii::app()->db->createCommand($removeQuery)->execute();

        $cmdQuery = sprintf("select id from maillist_channels where content_lang = %s", Yii::app()->db->quoteValue(Yii::app()->user->getCurrentLanguage()));
        $channels = Yii::app()->db->createCommand($cmdQuery)->queryAll();
        $success = true;
        if (count($channels)) {
            foreach ($channels as $index => $channel) {
                $sub = new MaillistChannelsSubscribe();
                $sub->subscriber_id = $this->id;
                $sub->channel_id = $channel['id'];
                $this->addRelatedRecord("maillistChannels", $sub, $index);
                $success &= $sub->save();
            }
        }
        return $success;
    }

    public function saveSelectedChannels() {
        $channels = Yii::app()->request->getParam('channels');
        $success = true;
        if (count($channels)) {
            foreach ($channels as $index => $channel) {
                $sub = new MaillistChannelsSubscribe();
                $sub->subscriber_id = $this->id;
                $sub->channel_id = $channel;
                $this->addRelatedRecord("maillistChannels", $sub, $index);
                $success &= $sub->save();
            }
        }
        return $success;
    }

    public function sendActivationLink() {
        $serverName = Yii::app()->request->getHostInfo();
        $aKey = $this->maillistUsers->generateKey();
        $subject = AmcWm::t("app", "subscribe_subject");
        $from = Yii::app()->params["adminEmail"];
        $linkUrl = $serverName . Html::createUrl("/maillist/default/activate", array("k" => $aKey, "m" => $this->maillistUsers->email));
        $body = AmcWm::t("app", "subscribe_body") . "<br /><a href='{$linkUrl}'>{$linkUrl}</a>";
        //$headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";                                        
        //Yii::app()->mail->sender->ClearAllRecipients();                                
        Yii::app()->mail->sender->Subject = $subject;
        Yii::app()->mail->sender->AddAddress($this->maillistUsers->email);
        Yii::app()->mail->sender->SetFrom($from);
        Yii::app()->mail->sender->IsHTML();
        $ok = Yii::app()->mail->sendView("application.views.email.newslitter", array('body' => $body));
        return $ok;
    }

    /**
     * Return maillist channels
     * @param MaillistChannels $channel
     * @return array
     */
    public static function getTemplatesList(MaillistChannels $channel = null) {
        if ($channel && $channel->auto_generate) {
            $cmdQuery = 'select template_id, subject, null msg from maillist_channels_templates t where channel_id = ' . $channel->id;
        } else {
            $cmdQuery = 'select template_id, subject, body msg from maillist_channels_templates t
                left join maillist_channels c on t.channel_id = c.id
                where c.id is null or c.auto_generate = 0';
        }
        $dataSet = Yii::app()->db->createCommand($cmdQuery)->queryAll();
        $templates = array(
            'list' => array(),
            'data' => array(),
        );
        foreach ($dataSet as $row) {
            $templates['list'][$row['template_id']] = ($row['subject']) ? "{$row['template_id']}- {$row['subject']}" : $row['template_id'];

            $templates['data'][$row['template_id']] = $row;
        }
        return $templates;
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the {@link onAfterFind} event.
     * You may override this method to do postprocessing after each newly found record is instantiated.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind() {
        $db = $this->dbConnection;
        if ($this->person_id) {
            $this->email = $db->createCommand('select email from persons where person_id = ' . (int) $this->person_id)->queryScalar();
        } else {
            $this->email = $db->createCommand('select email from maillist_users where user_id = ' . (int) $this->id)->queryScalar();
        }
        $this->displayTitle = $this->email;
        parent::afterFind();
    }

    /**
     * Get user name 
     * @return string
     */
    public function getName($appended = null) {
        $name = null;
        $db = $this->dbConnection;
        if ($this->person_id) {
            $names = $db->createCommand('select name, content_lang from persons_translation where person_id = ' . (int) $this->person_id)->queryAll();
            if ($names) {
                $name = $names[0]['name'] .  $appended;
                foreach ($names as $nameRow) {
                    if ($nameRow['content_lang'] == Controller::getContentLanguage()) {
                        $name = $nameRow['name'] .  $appended;
                    }
                }
            }
        } else {
            $name = $db->createCommand('select name from maillist_users where user_id = ' . (int) $this->id)->queryScalar();
        }
        return $name;
    }

}