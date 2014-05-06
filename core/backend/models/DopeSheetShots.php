<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dope_sheet_shots".
 *
 * The followings are the available columns in table 'dope_sheet_shots':
 * @property integer $shot_id
 * @property string $video_id
 * @property integer $type_id
 * @property integer $length_minutes
 * @property integer $length_seconds
 *
 * The followings are the available model relations:
 * @property DopeSheet $video
 * @property DopeSheetShotsTypes $type
 * @property DopeSheetShotsTranslation[] $translationChilds
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DopeSheetShots extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DopeSheetShots the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dope_sheet_shots';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type_id, length_minutes, length_seconds', 'required'),
            array('type_id, length_minutes, length_seconds', 'numerical', 'integerOnly' => true),
            array('video_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('shot_id, video_id, type_id, length_minutes, length_seconds', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'video' => array(self::BELONGS_TO, 'DopeSheet', 'video_id'),
            'type' => array(self::BELONGS_TO, 'DopeSheetShotsTypes', 'type_id'),
            'translationChilds' => array(self::HAS_MANY, 'DopeSheetShotsTranslation', 'shot_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'shot_id' => 'Shot',
            'video_id' => 'Video',
            'type_id' => AmcWm::t("msgsbase.core", 'Shot Type'),
            'length_seconds' => AmcWm::t("msgsbase.core", 'ss'),
            'length_minutes' => AmcWm::t("msgsbase.core", 'mm'),
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

        $criteria->compare('shot_id', $this->shot_id);
        $criteria->compare('video_id', $this->video_id, true);
        $criteria->compare('type_id', $this->type_id);
        $criteria->compare('length_minutes', $this->length_minutes);
        $criteria->compare('length_seconds', $this->length_seconds);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }        
}