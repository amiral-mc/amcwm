<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "menus".
 *
 * The followings are the available columns in table 'menus':
 * @property integer $menu_id
 * @property string $menu_name
 * @property integer $levels
 *
 * The followings are the available model relations:
 * @property MenuItems[] $menuItems
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MenusModel extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Menus the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'menus';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('levels', 'numerical', 'integerOnly' => true),
            array('menu_name', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('menu_id, menu_name, levels', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'menuItems' => array(self::HAS_MANY, 'MenuItems', 'menu_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'menu_id' => AmcWm::t("msgsbase.core", 'Menu'),
            'menu_name' => AmcWm::t("msgsbase.core", 'Menu Name'),
            'levels' => AmcWm::t("msgsbase.core", 'Levels'),
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

        $criteria->compare('menu_id', $this->menu_id);
        $criteria->compare('menu_name', $this->menu_name, true);
        $criteria->compare('levels', $this->levels);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Get menu parent and child
     * @param integer $parentMenuItemId
     * @param aray $tree tree list to return
     * @param string $language if not equal null according to the given $language,      
     * @access public
     * @return array
     */
    static public function getMenuList($toArray = false) {
        $menus = array();
        if ($toArray) {
            $menus = Yii::app()->db->createCommand("select menu_id, menu_name from menus")->queryAll();
        } else {
            $menus = CHtml::listData(Yii::app()->db->createCommand("select menu_id, menu_name from menus")->queryAll(), 'menu_id', 'menu_name');
        }
        return $menus;
    }

    
     /**
     * Check menu item level for the given $itemId
     * the current level must be less than maxLevels in menu setting and level in Menu.level
     * @param integer $itemId
     * @param integer $menuId
     */
    public function checkLevel($itemId){
        $level = count(MenuItems::getTree($itemId, $this->menu_id));
        $maxLevels = AmcWm::app()->appModule->options['default']['integer']['maxLevels'];                
        return ($level < $maxLevels - 1 && $level < (int)$this->levels -1);
    }
}