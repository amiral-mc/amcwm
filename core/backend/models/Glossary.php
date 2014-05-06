<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "glossary".
 *
 * The followings are the available columns in table 'glossary':
 * @property string $expression_id
 * @property integer $category_id
 * @property string $expression
 *
 * The followings are the available model relations:
 * @property GlossaryCategories $category
 * @property GlossaryTranslation[] $glossaryTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Glossary extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Glossary the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'glossary';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category_id, expression', 'required'),
            array('category_id', 'numerical', 'integerOnly' => true),
            array('expression', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('expression_id, category_id, expression', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category' => array(self::BELONGS_TO, 'GlossaryCategories', 'category_id'),
            'translationChilds' => array(self::HAS_MANY, 'GlossaryTranslation', 'expression_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'expression_id' => AmcWm::t("msgsbase.core", 'ID'),
            'category_id' => AmcWm::t("msgsbase.core", 'Category'),
            'expression' => AmcWm::t("msgsbase.core", 'Expression'),
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
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('expression', $this->expression, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    public static function getCategories() {
        $categories = CHtml::listData(Yii::app()->db->createCommand(sprintf("select category_id, category_name from glossary_categories_translation where content_lang=%s order by category_name ", Yii::app()->db->quoteValue(Controller::getContentLanguage())))->queryAll(), 'category_id', "category_name");
        return $categories;
    }

}