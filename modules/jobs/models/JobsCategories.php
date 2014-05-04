<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "jobs_categories".
 * 
 * The followings are the available columns in table 'jobs_categories':
 * @property integer $category_id
 * @property integer $published
 * 
 * The followings are the available model relations:
 * @property Jobs[] $jobs
 * @property JobsCategoriesTranslation[] $jobsCategoriesTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class JobsCategories extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return JobsCategories the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'jobs_categories';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('published', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('category_id, published', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'jobs' => array(self::HAS_MANY, 'Jobs', 'category_id'),
            'translationChilds' => array(self::HAS_MANY, 'JobsCategoriesTranslation', 'category_id', 'index' => 'content_lang'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'category_id' => AmcWm::t("msgsbase.core", 'Category'),
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

        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('published', $this->published);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}