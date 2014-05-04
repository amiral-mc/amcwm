<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dir_categories".
 *
 * The followings are the available columns in table 'dir_categories':
 * @property integer $category_id
 * @property integer $parent_category
 * @property integer $published
 * @property integer $is_system
 * @property string $settings
 * 
 * @property string $hits
 *
 * The followings are the available model relations:
 * @property DirCategories $parentCategory
 * @property DirCategories[] $dirCategories
 * @property DirCategoriesTranslation[] $translationChilds
 * @property DirCompanies[] $dirCompanies
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirCategories extends ParentTranslatedActiveRecord {

    /**
     *
     * @var settings booleans options 
     */
    public $settingsOptions;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DirCategories the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);   
    }
    
    /**
    * This method is invoked after each record has been saved
     */
    public function afterSave() {
        parent::afterSave();
        if(!$this->settingsList['check']['useTicker']){
           $query = "update dir_companies set in_ticker = 0 where category_id = " . (int)$this->category_id;
           AmcWm::app()->db->createCommand($query)->execute();
        }
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dir_categories';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('published, is_system', 'numerical', 'integerOnly' => true),
            array('hits', 'length', 'max' => 10),
            array('settings', 'length', 'max' => 255),
            array('settingsOptions', 'isArray', 'allowEmpty' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('category_id, parent_category, published, hits', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentCategory' => array(self::BELONGS_TO, 'DirCategories', 'parent_category'),
            'dirCategories' => array(self::HAS_MANY, 'DirCategories', 'parent_category'),
            'translationChilds' => array(self::HAS_MANY, 'DirCategoriesTranslation', 'category_id', "index" => "content_lang"),
            'dirCompanies' => array(self::HAS_MANY, 'DirCompanies', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'category_id' => AmcWm::t("msgsbase.core", 'ID'),
            'parent_category' => AmcWm::t("msgsbase.core", 'Parent Category'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'settings' => AmcWm::t("msgsbase.core", 'Category settings'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
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
        $criteria->compare('parent_category', $this->parent_category);
        $criteria->compare('published', $this->published);
        $criteria->compare('hits', $this->hits, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Get setting options
     * @access public
     * @return array
     */
    public function getSettingsList() {
        $settingsOptions = CJSON::decode($this->settings);
        $defaultOptions = AmcWm::app()->appModule->options['default'];
        if (!$settingsOptions) {
            $settingsOptions = $defaultOptions;
        }
        return $settingsOptions;
    }

    /**
     * This method is invoked before each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        $settingsOptions = $defaultOptions = AmcWm::app()->appModule->options['default'];
        $notChanged = true;
        foreach ($defaultOptions as $optionType => $options) {
            switch ($optionType) {
                case 'check':
                    foreach ($options as $optionKey => $optionValue) {                        
                        if (isset($this->settingsOptions[$optionType][$optionKey])) {
                            $settingsOptions[$optionType][$optionKey] = true;    
                        }
                        else{
                           $settingsOptions[$optionType][$optionKey] = false;     
                        }
                        $notChanged &= ($settingsOptions[$optionType][$optionKey] == $defaultOptions[$optionType][$optionKey]);
                    }
                    break;
            }
        }
        if($notChanged){
            $this->settingsOptions = null;
        }
        else{
            $this->settingsOptions = $settingsOptions;
        }        
        $this->settings = CJSON::encode($this->settingsOptions);
        return parent::beforeSave();
    }

}