<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "menu_item_translation".
 *
 * The followings are the available columns in table 'menu_item_translation':
 * @property integer $item_id
 * @property string $content_lang
 * @property string $label
 * @property string $page_title
 * 
 * The followings are the available model relations:
 * @property MenuItems $item
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MenuItemTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MenuItemTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'menu_item_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, label', 'required'),
            array('item_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('label', 'length', 'max' => 100),
            array('page_title', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('item_id, content_lang, label, page_title', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'MenuItems', 'item_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'item_id' => AmcWm::t("msgsbase.core", 'Item'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'label' => AmcWm::t("msgsbase.core", 'Label'),
            'page_title' => AmcWm::t("msgsbase.core", 'Page Title'),
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

        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('label', $this->label, true);
        $criteria->compare('page_title', $this->page_title, true);

        $criteria->join .="inner join menu_items m on t.item_id = m.item_id";
        if (isset($this->parentContent->parent_item) && $this->parentContent->parent_item) {
            $criteria->addCondition('m.parent_item = ' . $this->parentContent->parent_item);
        } else {
            $criteria->addCondition('m.parent_item is null');
        }

        if (isset($this->parentContent->menu_id) && $this->parentContent->menu_id) {
            $criteria->addCondition('m.menu_id = ' . $this->parentContent->menu_id);
        }

        $sort = new CSort();
        $sort->defaultOrder = 'sort_item, content_lang';

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->displayTitle = $this->label;
        parent::afterFind();
    }

}