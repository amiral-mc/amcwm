<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dope_sheet_translation".
 *
 * The followings are the available columns in table 'dope_sheet_translation':
 * @property string $video_id
 * @property string $content_lang
 * @property string $reporter
 * @property string $source
 * @property string $location
 * @property string $sound
 * @property string $story
 *
 * The followings are the available model relations:
 * @property DopeSheet $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DopeSheetTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DopeSheetTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dope_sheet_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, reporter, source, location, sound', 'required'),
            array('video_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('reporter, source, location, sound', 'length', 'max' => 45),
            array('story', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('video_id, content_lang, reporter, source, location, sound, story', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'DopeSheet', 'video_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'video_id' => 'Video',
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'reporter' => AmcWm::t("msgsbase.core", 'Reporter'),
            'source' => AmcWm::t("msgsbase.core", 'Source'),
            'location' => AmcWm::t("msgsbase.core", 'Location'),
            'sound' => AmcWm::t("msgsbase.core", 'Sound'),
            'story' => AmcWm::t("msgsbase.core", 'Story'),
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
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('reporter', $this->reporter, true);
        $criteria->compare('source', $this->source, true);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('sound', $this->sound, true);
        $criteria->compare('story', $this->story, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}