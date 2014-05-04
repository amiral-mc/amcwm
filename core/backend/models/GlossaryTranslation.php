<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "glossary_translation".
 *
 * The followings are the available columns in table 'glossary_translation':
 * @property string $expression_id
 * @property string $content_lang
 * @property string $meaning
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Glossary $expression
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GlossaryTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GlossaryTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'glossary_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, meaning', 'required'),
            array('expression_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('meaning', 'length', 'max' => 100),
            array('description', 'length', 'max' => 1024),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('expression_id, content_lang, meaning, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Glossary', 'expression_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'expression_id' => AmcWm::t("msgsbase.core", 'ID'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'meaning' => AmcWm::t("msgsbase.core", 'Meaning'),
            'description' => AmcWm::t("msgsbase.core", 'Description'),
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

        $criteria->compare('expression_id', $this->expression_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('meaning', $this->meaning, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}