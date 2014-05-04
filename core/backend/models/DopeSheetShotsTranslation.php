<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dope_sheet_shots_translation".
 *
 * The followings are the available columns in table 'dope_sheet_shots_translation':
 * @property integer $shot_id
 * @property string $content_lang
 * @property string $description
 * @property string $sound
 *
 * The followings are the available model relations:
 * @property DopeSheetShots $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DopeSheetShotsTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DopeSheetShotsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dope_sheet_shots_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, description, sound', 'required'),
            array('shot_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('sound', 'length', 'max' => 45),
            array('description', 'length', 'max' => 150),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('shot_id, content_lang, description, sound', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'DopeSheetShots', 'shot_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'shot_id' => 'Shot',
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),            
            'description' => AmcWm::t("msgsbase.core", 'Shot Description'),
            'sound' => AmcWm::t("msgsbase.core", 'Sound'),
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
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('sound', $this->sound, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}