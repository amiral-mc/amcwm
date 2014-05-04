<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "sections_translation".
 *
 * The followings are the available columns in table 'sections_translation':
 * @property integer $section_id
 * @property string $content_lang
 * @property string $section_name
 * @property string $supervisor
 * @property string $description
 * @property string $tags
 *
 * The followings are the available model relations:
 * @property Sections $parentContent
 * @property Persons supervisorPerson
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionsTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SectionsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sections_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, section_name', 'required'),
            array('supervisor, section_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('section_name', 'length', 'max' => 150),
            array('description', 'safe'),
            array('tags', 'length', 'max' => 1024),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('section_id, content_lang, section_name, supervisor, tags', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'supervisorPerson' => array(self::BELONGS_TO, 'Persons', 'supervisor'),
            'parentContent' => array(self::BELONGS_TO, 'Sections', 'section_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'section_id' => AmcWm::t("msgsbase.core", 'Section ID'),
            'section_name' => AmcWm::t("msgsbase.core", 'Section Name'),
            'description' => AmcWm::t("msgsbase.core", 'Description'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'tags' => AmcWm::t("msgsbase.core", 'Tags'),
            'supervisor' => AmcWm::t("msgsbase.core", 'Supervisor'),
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
        $criteria->compare('section_name', $this->section_name, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('supervisor', $this->supervisor);
        $criteria->compare('p.parent_section', $this->parentContent->parent_section);
        $criteria->compare('p.published', $this->parentContent->published);
        $criteria->compare('p.section_sort', $this->parentContent->section_sort);
        $criteria->join .="inner join sections p on t.section_id = p.section_id";
        if (isset($this->parentContent->parent_section) && $this->parentContent->parent_section) {
            $criteria->addCondition('p.parent_section = ' . $this->parentContent->parent_section);
        } else {
            $criteria->addCondition('p.parent_section is null');
        }
        $sort = new CSort();
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        if (isset($sorting['sections'])) {
            $order = "{$sorting['sections']['sortField']} {$sorting['sections']['order']}";
            $sort->defaultOrder = "{$order}";
        }
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    /**
     * This method is invoked after each record has been saved
     * @access public
     * @return boolean
     */
    public function beforeSave() {
        if (!$this->supervisor) {
            $this->supervisor = null;
        }
        return parent::beforeSave();
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->displayTitle = $this->section_name;
        parent::afterFind();
    }

}