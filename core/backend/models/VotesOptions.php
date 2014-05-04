<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "votes_options".
 *
 * The followings are the available columns in table 'votes_options':
 * @property integer $option_id
 * @property integer $ques_id
 * @property string $content_lang
 * @property string $value
 *
 * The followings are the available model relations:
 * @property Voters[] $voters
 * @property Voters[] $voters1
 * @property VotesQuestionsTranslation $ques
 * @property VotesQuestionsTranslation $contentLang
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VotesOptions extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VotesOptions the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'votes_options';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('value', 'required'),
            array('ques_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('value', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('option_id, ques_id, content_lang, value', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'voters' => array(self::HAS_MANY, 'Voters', 'option_id'),
            'voters1' => array(self::HAS_MANY, 'Voters', 'content_lang'),
            'ques' => array(self::BELONGS_TO, 'VotesQuestionsTranslation', 'ques_id'),
            'contentLang' => array(self::BELONGS_TO, 'VotesQuestionsTranslation', 'content_lang'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'option_id' => AmcWm::t("msgsbase.core", 'ID'),
            'ques_id' => AmcWm::t("msgsbase.core", 'ID'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'value' => AmcWm::t("msgsbase.core", 'Answer'),
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

        $criteria->compare('option_id', $this->option_id);
        $criteria->compare('ques_id', $this->ques_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('value', $this->value, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}