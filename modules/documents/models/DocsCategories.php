<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "docs_categories".
 *
 * The followings are the available columns in table 'docs_categories':
 * @property integer $category_id
 * @property integer $parent_category
 * @property integer $published
 * @property integer $is_system
 * @property string $hits
 * @property string $image_ext
 *
 * The followings are the available model relations:
 * @property Docs[] $docs
 * @property DocsCategories $parentCategory
 * @property DocsCategories[] $docsCategories
 * @property DocsCategoriesTranslation[] $docsCategoriesTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DocsCategories extends ParentTranslatedActiveRecord {

    public $imageFile;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DocsCategories the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'docs_categories';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parent_category, published, is_system', 'numerical', 'integerOnly' => true),
            array('hits', 'length', 'max' => 10),
            array('image_ext', 'length', 'max' => 3),
            
            array('imageFile', 'file', 'types' => $mediaSettings['categories']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['categories']['maxImageSize']),
            array('imageFile', 'ValidateImage', 'checkValues' => $mediaSettings['categories']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('category_id, parent_category, published, hits, image_ext', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'docs' => array(self::HAS_MANY, 'Docs', 'category_id'),
            'parentCategory' => array(self::BELONGS_TO, 'DocsCategories', 'parent_category'),
            'docsCategories' => array(self::HAS_MANY, 'DocsCategories', 'parent_category'),
            'translationChilds' => array(self::HAS_MANY, 'DocsCategoriesTranslation', 'category_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'category_id' => AmcWm::t("msgsbase.core", 'Category'),
            'parent_category' => AmcWm::t("msgsbase.core", 'Parent Category'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'image_ext' => AmcWm::t("msgsbase.core", 'Image Ext'),
            'imageFile' => AmcWm::t("msgsbase.core", 'Image File'),
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
        $criteria->compare('image_ext', $this->image_ext, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * return categories titles in array recurcive
     * @param type $parentId
     * @param type $parentPath
     * @param type $language
     * @param type $onlyParent
     * @return array
     */
    static private function _getCategoriesLabel($parentId, &$parentPath = array(), $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }

        $query = sprintf("select c.category_id, c.parent_category, t.category_name
                from docs_categories c
                inner join docs_categories_translation t on c.category_id = t.category_id
                where c.category_id = %d 
                and t.content_lang = %s
               ", $parentId, Yii::app()->db->quoteValue($language));
        $parent = Yii::app()->db->createCommand($query)->queryRow();
        if ($parent && $parent['parent_category'] != $parent['category_id']) {
            $parentPath[] = $parent['category_name'];
            self::_getCategoriesLabel($parent['parent_category'], $parentPath);
        }
        return $parentPath;
    }
    
    
    /**
     * Get categories list
     * @param string $language
     * @param array|integer $execludeCategories
     * @access public
     * @return array
     */
    static public function getCategoriesList($language = null , $execludeCategories = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $excludeQuery = null;
        if(is_array($execludeCategories)){
            $excludeQuery = " and (c.category_id not in(" . implode(",", $execludeCategories). "))";            
        }
        else if((int)$execludeCategories){
            $excludeQuery = ' and c.category_id <> ' . (int)$execludeCategories;            
        }
        $query = sprintf("select c.category_id, c.parent_category, t.category_name
                from docs_categories c
                inner join docs_categories_translation t on c.category_id = t.category_id
                and t.content_lang = %s {$excludeQuery}
               ", Yii::app()->db->quoteValue($language));
        $categoriesRows = Yii::app()->db->createCommand($query)->queryAll();
        $items = array();       
        foreach ($categoriesRows as $item) {
            $parentPath = array();
            if ($item['parent_category'] && $item['parent_category'] != $item['category_id']) {
                self::_getCategoriesLabel($item['parent_category'], $parentPath);
                $parentPath = array_reverse($parentPath);
                $itemPath = implode(" / ", $parentPath) . " / ";
            } else {
                $itemPath = null;
            }
            $items[$item['category_id']] = $itemPath . $item['category_name'];
        }
        return $items;
    }

}