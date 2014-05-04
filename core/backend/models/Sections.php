<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "sections".
 *
 * The followings are the available columns in table 'sections':
 * @property integer $section_id
 * @property integer $parent_section
 * @property integer $published
 * @property string $image_ext
 * @property integer $section_sort
 * @property string $settings
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 * @property Events[] $events
 * @property Galleries[] $galleries
 * @property Infocus[] $infocuses
 * @property MailistChannels[] $maillistMessages
 * @property RelatedSections[] $relatedFromSections
 * @property RelatedSections[] $relatedToSections
 * @property Sections $parentSection
 * @property Sections[] $sections
 * @property Issues[] $issues
 * @property SectionsTranslation[] $translationChilds
 * @property Services[] $services
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Sections extends ParentTranslatedActiveRecord {

    /**
     * Social ids associated with this active record
     * @var array 
     */
    protected $socialIds = array();

    /**
     * Sort Dependency attributes
     * @var string 
     */
    protected $sortDependencyFields = array('parent_section');

    /**
     * Sort field name
     * @var string 
     */
    protected $sortField = "section_sort";

    /**
     * Image uploader file used to upload article thumb image
     * @var array
     * @access public
     */
    public $imageFile = null;

    /**
     *
     * @var settings booleans options 
     */
    public $settingsOptions;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Sections the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Gets social ids associated with this active record
     * @return array 
     */
    public function getSocialIds() {
        return $this->socialIds;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sections';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $mediaSettings = SectionsData::getSettings()->mediaSettings;
        return array(
            array('parent_section, published, section_sort', 'numerical', 'integerOnly' => true),
            array('image_ext', 'length', 'max' => 3),
            array('imageFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxImageSize']),
            array('imageFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['images']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('settings', 'length', 'max' => 1024),
            array('settingsOptions', 'isArray', 'allowEmpty' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('section_id, parent_section, published, section_sort', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'articles' => array(self::HAS_MANY, 'Articles', 'section_id'),
            'events' => array(self::HAS_MANY, 'Events', 'section_id'),
            'galleries' => array(self::HAS_MANY, 'Galleries', 'section_id'),
            'infocuses' => array(self::HAS_MANY, 'Infocus', 'section_id'),
            'maillistMessages' => array(self::HAS_MANY, 'MaillistMessagesSetions', 'section_id', 'index' => 'channel_id'),
            'relatedFromSections' => array(self::HAS_MANY, 'RelatedSections', 'section'),
            'relatedToSections' => array(self::HAS_MANY, 'RelatedSections', 'related_section'),
            'parentSection' => array(self::BELONGS_TO, 'Sections', 'parent_section'),
            'sections' => array(self::HAS_MANY, 'Sections', 'parent_section'),
            'issues' => array(self::HAS_MANY, 'SectionsIssues', 'section_id', 'index' => 'issue_id'),
            'translationChilds' => array(self::HAS_MANY, 'SectionsTranslation', 'section_id', 'index' => 'content_lang'),
            'services' => array(self::HAS_MANY, 'ServicesSections', 'section_id', 'index' => 'service_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'section_id' => AmcWm::t("msgsbase.core", 'Section ID'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'parent_section' => AmcWm::t("msgsbase.core", 'Parent Section'),
            'section_sort' => AmcWm::t("msgsbase.core", 'Sort'),
            'image_ext' => AmcWm::t("msgsbase.core", 'Extension'),
            'imageFile' => AmcWm::t("msgsbase.core", 'Section Photo'),
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

        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('parent_section', $this->parent_section);
        $criteria->compare('published', $this->published);
        $criteria->compare('section_sort', $this->section_sort);
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
     * Sort the given model acording to $direction order
     * @param string $direction
     * @param string $language content language
     * @param string $condition condition to be added to update query
     * @access protected
     * @return boolean
     */
    public function sort($direction = "up", $condition = null) {
        if ($this->parent_section) {
            $condition = "parent_section = " . (int) $this->parent_section;
        } else {
            $condition = "parent_section is null";
        }
        parent::sort($direction, $condition);
    }

    /**
     * This method is invoked after deleting a active record translion child.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return void
     */
    protected function afterDeleteChild($childAttributes) {
        return $this->correctSort();
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->parentSection = $this->parent_section;
        $current = $this->getCurrent();
        if ($current instanceof SectionsTranslation) {
            $this->displayTitle = $current->section_name;
        }
        parent::afterFind();
    }

    /**
     * This method is invoked after each record has been saved
     * @access public
     * @return void
     */
    public function afterSave() {
        $cache = Yii::app()->getComponent('cache');
        if ($cache !== null) {
            $cache->delete('data');
        }
        parent::afterSave();
    }

    /**
     * This method is invoked after each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        // preparing media settings
        $mediaSettings = SectionsData::getSettings()->mediaSettings;
        $types = explode(",", $mediaSettings['info']['extensions']);
        foreach ($types as &$type) {
            $type = trim($type);
        }
        if (array_search($this->image_ext, $types) === false) {
            $this->image_ext = null;
        }

        // preparing the section settings
        $settingsOptions = $defaultOptions = AmcWm::app()->appModule->options['default'];
        $notChanged = true;
//        die(print_r($this->settingsOptions));
        foreach ($defaultOptions as $optionType => $options) {
            switch ($optionType) {
                case 'radio':
                    foreach ($options as $optionKey => $optionValue) {
                        if (isset($this->settingsOptions[$optionType][$optionKey])) {
                            $settingsOptions[$optionType][$optionKey] = true;
                        } else {
                            $settingsOptions[$optionType][$optionKey] = false;
                        }
                        $notChanged &= ($settingsOptions[$optionType][$optionKey] == $defaultOptions[$optionType][$optionKey]);
                    }
                    break;
            }
        }
        if ($notChanged) {
            $this->settingsOptions = null;
        } else {
            $this->settingsOptions = $settingsOptions;
        }
        $this->settings = CJSON::encode($this->settingsOptions);

        return parent::beforeSave();
    }

    /**
     * Get section parent and child
     * @param integer $parentSectionId
     * @param aray $tree tree list to return
     * @param string $language if not equal null then get supervisors according to the given $language,      
     * @access public
     * @return array
     */
    static public function getSectionTree($parentSectionId, &$tree = array(), $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $orderBy = SectionsData::getDefaultSortOrder();
        if ($orderBy) {
            $orderBy = "order by {$orderBy}";
        }
        $sectionQuery = sprintf(
                "select 
                    s.section_id,
                    s.parent_section,
                    t.section_name                                
                from sections s force index (idx_section_sort)
                inner join sections_translation t on s.section_id = t.section_id
                where s.section_id = %d and t.content_lang = %s
                {$orderBy}", $parentSectionId, Yii::app()->db->quoteValue($language));
        $section = Yii::app()->db->createCommand($sectionQuery)->queryRow();
        if ($section) {
            $tree[] = $section['section_name'];
            if ($section['parent_section']) {
                self::getSectionTree($section['parent_section'], $tree, $language);
            }
        }
        return $tree;
    }

    /**
     * Get section path for the given $sectionId
     * 
     * @param integer $sectionId
     * @access public
     * @return string
     */
    static function drawSectionPath($sectionId, $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $parentPath = array();
        self::_getSectionPath($sectionId, $language, $parentPath);
        $sectionPath = null;
        if (count($parentPath)) {
            $parentPath = array_reverse($parentPath);
            $sectionPath = implode(" / ", $parentPath);
        }
        return $sectionPath;
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
        if ($parent && $parent['parent_section'] != $parent['section_id']) {
            $parentPath[] = $parent['section_name'];
            self::_getSectionPath($parent['parent_section'], $language, $parentPath);
        }
        return $parentPath;
    }

    /**
     * Get Sections list
     * @param string $language if not equal null then get sections according to the given $language,
     * @param array|integer $execludeSections
     * @access public
     * @return array 
     */
    static public function getSectionsList($language = null, $execludeSections = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $orderBy = SectionsData::getDefaultSortOrder();
        if ($orderBy) {
            $orderBy = ", {$orderBy}";
        }
        $excludeQuery = null;
        if (is_array($execludeSections)) {
            $excludeQuery = " and (s.section_id not in(" . implode(",", $execludeSections) . "))";
        } else if ((int) $execludeSections) {
            $excludeQuery = ' and s.section_id <> ' . (int) $execludeSections;
        }
        $sectionsQuery = sprintf(
                "select 
                    s.section_id,
                    s.parent_section,
                    t.section_name                                
                from sections s force index (idx_section_sort)
                inner join sections_translation t on s.section_id = t.section_id
                where t.content_lang = %s {$excludeQuery}
                order by parent_section $orderBy ", Yii::app()->db->quoteValue($language));
        $sectionsRows = Yii::app()->db->createCommand($sectionsQuery)->queryAll();
        $sections = array();
        foreach ($sectionsRows as $section) {
            $parentPath = array();
            if ($section['parent_section'] && $section['parent_section'] != $section['section_id']) {
                self::_getSectionPath($section['parent_section'], $language, $parentPath);
                $parentPath = array_reverse($parentPath);
                $sectionPath = implode(" / ", $parentPath) . " / ";
            } else {
                $sectionPath = null;
            }
            $sections[$section['section_id']] = $sectionPath . $section['section_name'];
        }
        return $sections;
    }

    /**
     * Get sections tree for the given section $id 
     * @param int $id
     * @param string $language,     
     * @static
     * @access public
     * @return array     
     */
    public static function getTree($id, $language = null, &$sections = array()) {
        return Data::getInstance()->getSectionPath($id, $language, $sections);
    }

}