<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "docs_categories_translation".
 *
 * The followings are the available columns in table 'docs_categories_translation':
 * @property integer $category_id
 * @property string $content_lang
 * @property string $category_name
 * @property string $category_description
 *
 * The followings are the available model relations:
 * @property DocsCategories $category
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DocsCategoriesTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DocsCategoriesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'docs_categories_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, category_name', 'required'),
            array('category_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('category_name', 'length', 'max' => 100),
            array('category_description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('category_id, content_lang, category_name, category_description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'DocsCategories', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'category_id' => AmcWm::t("msgsbase.core", 'Category'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'category_name' => AmcWm::t("msgsbase.core", 'Category Name'),
            'category_description' => AmcWm::t("msgsbase.core", 'Category Description'),
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

        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('category_name', $this->category_name, true);
        $criteria->compare('category_description', $this->category_description, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}