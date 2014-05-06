<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "sms_videos".
 *
 * The followings are the available columns in table 'sms_videos':
 * @property string $video_id
 * @property string $video_header
 * @property integer $published
 * @property string $creation_date
 * @property string $content_lang
 * @property string $description
 * @property string $ext
 * @property string $update_date
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SmsVideos extends ActiveRecord {

    public $videoFile = null;

    /**
     * Returns the static model of the specified AR class.
     * @return SmsVideos the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sms_videos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $day = date('Y-m-d');
        return array(
            array('video_header', 'required'),
            array('videoFile', 'required', 'on' => 'insert'),
            array('published', 'numerical', 'integerOnly' => true),
            array('video_header', 'length', 'max' => 500),
            array('content_lang', 'length', 'max' => 2),
            array('ext', 'length', 'max' => 4),
            array('creation_date, description, update_date', 'safe'),
            array('creation_date', 'unique'),
            array('creation_date', 'validateDateTime'),
            array('videoFile', 'file', 'types' => '3gp', 'allowEmpty' => true, 'maxSize' => Yii::app()->params["multimedia"]['smsVideos']['size']),
            array('creation_date', 'default',
                'value' => $day,
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('video_id, video_header, published, creation_date, content_lang, description, update_date', 'safe', 'on' => 'search'),
        );
    }
    public function validateDateTime($attribute, $parmas){
        $allowed = Yii::app()->params["multimedia"]["smsVideos"]["allowed"];       
        $hour = (int)date('H');
        if($hour < $allowed['from'] || $hour >= $allowed['to']){
            $this->addError($attribute, Yii::t('sms', 'Allowed time between {from} and {to}', array('{from}'=>$allowed['from'], '{to}'=>$allowed['to'])));            
        }
        if(!$this->isNewRecord && $this->$attribute != date('Y-m-d') ){
            $this->addError($attribute, Yii::t('sms', 'Update old video is not allowed'));
        }
        
    }
    public function getVideoName($ext = null){
        if(!$ext){
            $ext = $this->ext;
        }
        $savedId = Yii::app()->params["multimedia"]["smsVideos"]["savedId"];
        $name = $this->$savedId . '.' . $ext;
        return $name;
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'video_id' => AmcWm::t("msgsbase.core", 'id'),
            'video_header' => AmcWm::t("msgsbase.core", 'Video Header'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'creation_date' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'videoFile' => AmcWm::t("msgsbase.core", 'Video File'),
            'description' => AmcWm::t("msgsbase.core", 'Description'),
            'update_date' => AmcWm::t("msgsbase.core", 'Update Date'),
            'exy' => AmcWm::t("msgsbase.core", 'Ext'),
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

        $criteria->compare('video_id', $this->video_id, true);
        $criteria->compare('video_header', $this->video_header, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('update_date', $this->update_date, true);
        $sort = new CSort();
        $sort->defaultOrder = 'creation_date desc';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>$sort,
        ));
    }

}