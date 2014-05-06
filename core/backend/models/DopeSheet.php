<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dope_sheet".
 *
 * The followings are the available columns in table 'dope_sheet':
 * @property string $video_id
 * @property string $event_date
 * @property integer $length_hours
 * @property integer $length_minutes
 * @property integer $length_seconds
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property Videos $video
 * @property DopeSheetShots[] $shots
 * @property DopeSheetTranslation[] $translationChilds
 * 
 * @author Amiral Management Corporation
 * @version 1.0

 */
class DopeSheet extends ParentTranslatedActiveRecord {

    /**
     * Time field , time format must equal to "HH::MM::SS"
     * @var string
     */
    public $timeLength = '';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DopeSheet the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dope_sheet';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('event_date, length_hours, length_minutes, length_seconds', 'required'),
            array('length_hours, length_minutes, length_seconds, published', 'numerical', 'integerOnly' => true),
            array('video_id', 'length', 'max' => 10),
            array('timeLength', 'validateTimeLength'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('video_id, event_date, length_hours, length_minutes, length_seconds, published', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Valide time length format
     * @param string $attribute
     * @param array $params
     * @access public
     * @return void
     * 
     */
    public function validateTimeLength($attribute, $params) {
        if ($this->length_hours == '' || $this->length_minutes == '' || $this->length_seconds == '') {
            $this->addError('timeLength', Yii::t('yii', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel('timeLength'))));
            if ($this->length_hours == '') {
                $this->addError('length_hours', Yii::t('yii', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel('length_hours'))));
            }
            if ($this->length_minutes == '') {
                $this->addError('length_minutes', Yii::t('yii', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel('length_minutes'))));
            }
            if ($this->length_seconds == '') {
                $this->addError('length_seconds', Yii::t('yii', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel('length_seconds'))));
            }
        } else {
            $errorsHours = $this->getErrors('length_hours');
            $errorsMinuts = $this->getErrors('length_minutes');
            $errorsSeconds = $this->getErrors('length_seconds');
            if ($errorsHours || $errorsMinuts || $errorsSeconds) {
                $this->addError('timeLength', AmcWm::t("msgsbase.core", 'Inncorect values for time.'));
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'video' => array(self::BELONGS_TO, 'Videos', 'video_id'),
            'shots' => array(self::HAS_MANY, 'DopeSheetShots', 'video_id'),
            'translationChilds' => array(self::HAS_MANY, 'DopeSheetTranslation', 'video_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'video_id' => 'Video',
            'event_date' => AmcWm::t("msgsbase.core", 'Event Date'),
            'timeLength' => AmcWm::t("msgsbase.core", 'Time length hh:mm:ss'),
            'length_hours' => AmcWm::t("msgsbase.core", 'hh'),
            'length_minutes' => AmcWm::t("msgsbase.core", 'mm'),
            'length_seconds' => AmcWm::t("msgsbase.core", 'ss'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
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
        $criteria->compare('event_date', $this->event_date, true);
        $criteria->compare('length_hours', $this->length_hours);
        $criteria->compare('length_minutes', $this->length_minutes);
        $criteria->compare('length_seconds', $this->length_seconds);
        $criteria->compare('published', $this->published);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Get shots types list
     * @param boolean $generateListData, if equal true then return list used in combobox otherwise return mysql result
     * @access public
     * @static
     * @return array
     */
    public static function getShotsTypes($generateListData = true) {
        $query = "select type_id , type from dope_sheet_shots_types";
        $rows = Yii::app()->db->createCommand($query)->queryAll();
        if ($generateListData) {
            $output = CHtml::listData($rows, 'type_id', 'type');
        } else {
            $output = $rows;
        }
        return $output;
    }

      /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->displayTitle = $this->video->getCurrent()->video_header;
        $this->timeLength = "{$this->length_hours} : {$this->length_minutes} : {$this->length_seconds}";
        parent::afterFind();
    }        

}