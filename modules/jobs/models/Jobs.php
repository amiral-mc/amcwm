<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "jobs".
 *
 * The followings are the available columns in table 'jobs':
 * @property integer $job_id
 * @property integer $category_id
 * @property integer $published
 * @property string $expire_date
 * @property string $publish_date
 *
 * The followings are the available model relations:
 * @property JobsCategories $category
 * @property JobsRequests[] $jobsRequests
 * @property JobsTranslation[] $jobsTranslations
 * @property UsersCv[] $usersCvs
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Jobs extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Jobs the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'jobs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category_id, publish_date', 'required'),
            array('category_id, published', 'numerical', 'integerOnly' => true),
            
            array('expire_date, update_date', 'safe'),
            array('expire_date', 'compare', 'compareAttribute' => 'publish_date', 'operator' => '>', 'allowEmpty' => true),
            array('publish_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('job_id, category_id, published, expire_date, publish_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category' => array(self::BELONGS_TO, 'JobsCategories', 'category_id'),
            'jobsRequests' => array(self::HAS_MANY, 'JobsRequests', 'job_id'),
            'translationChilds' => array(self::HAS_MANY, 'JobsTranslation', 'job_id', 'index' => 'content_lang'),
            'usersCvs' => array(self::MANY_MANY, 'UsersCv', 'users_cv_has_jobs(job_id, cv_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'job_id' => AmcWm::t("msgsbase.core", 'Job'),
            'category_id' => AmcWm::t("msgsbase.core", 'Category'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'expire_date' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'publish_date' => AmcWm::t("msgsbase.core", 'Publish Date'),
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

        $criteria->compare('job_id', $this->job_id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('published', $this->published);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('publish_date', $this->publish_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    protected function afterFind() {
        if (!$this->expire_date || $this->expire_date == '0000-00-00 00:00:00') {
            $this->expire_date = NULL;
        }
        
        if (!$this->publish_date || $this->publish_date == '0000-00-00 00:00:00') {
            $this->publish_date = NULL;
        }
        parent::afterFind();
    }
    
    protected function beforeSave() {
        if (!$this->expire_date || $this->expire_date == '0000-00-00 00:00:00') {
            $this->expire_date = NULL;
        }
        if (!$this->publish_date || $this->publish_date == '0000-00-00 00:00:00') {
            $this->publish_date = NULL;
        }
        return parent::beforeSave();
    }

    /**
     * Get Sections list
     * @param string $emptyLabel if not equal null then add empty item with the given $emptyLabel
     * @param string $language if not equal null then get categories according to the given $language,
     * @access public
     * @return array 
     */
    static public function getCategoriesList($emptyLabel = null, $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        
        $categoriesQuery = sprintf(
                "select 
                    c.category_id,
                    t.category_name                                
                from jobs_categories c
                inner join jobs_categories_translation t on c.category_id = t.category_id
                where t.content_lang = %s
                order by BINARY category_name  ", Yii::app()->db->quoteValue($language));
        $categoriesRows = Yii::app()->db->createCommand($categoriesQuery)->queryAll();
        $categories = array();
        if ($emptyLabel) {
            $categories[""] = $emptyLabel;
        }
        foreach ($categoriesRows as $category) {
            $categories[$category['category_id']] = $category['category_name'];
        }
        return $categories;
    }
    
    /**
     * Get section path for the given $sectionId
     * 
     * @param integer $sectionId
     * @param integer $sectionId
     * @access private
     * @return string
     */
    static private function _getSectionPath($sectionId, $language, &$parentPath = array()) {
        $orderBy = SectionsData::getDefaultSortOrder();
        if ($orderBy) {
            $orderBy = "order by {$orderBy}";
        }        
        $query = sprintf(
                "select 
                    s.section_id,
                    s.parent_section,
                    t.section_name                                
                from sections s force index (idx_section_sort)
                inner join sections_translation t on s.section_id = t.section_id
                where s.section_id = %d and t.content_lang = %s
               {$orderBy}", $sectionId, Yii::app()->db->quoteValue($language));

        $parent = Yii::app()->db->createCommand($query)->queryRow();
        if ($parent) {
            $parentPath[] = $parent['section_name'];
            self::_getSectionPath($parent['parent_section'], $language, $parentPath);
        }
        return $parentPath;
    }
}