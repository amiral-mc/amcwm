<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "menu_items".
 *
 * The followings are the available columns in table 'menu_items':
 * @property integer $item_id
 * @property integer $parent_item
 * @property integer $menu_id
 * @property integer $sort_item
 * @property string $link
 * @property string $icon
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property MenuItemTranslation[] $menuItemTranslations
 * @property Menus $menu
 * @property MenuItems $parentItem
 * @property MenuItems[] $menuItems
 * @property MenuItemsParams[] $menuItemsParams
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MenuItems extends ParentTranslatedActiveRecord {

    public $component_id = null;
    public $paramsMenuItemsParams = null;
    public $iconImage = null;
    public $pageImg = null;

    /**
     * Sort field name
     * @var string 
     */
    protected $sortField = "sort_item";

    /**
     * Sort Dependency attributes
     * @var string 
     */
    protected $sortDependencyFields = array('parent_item', 'menu_id');

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MenuItems the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'menu_items';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('menu_id', 'required'),
            array('parent_item, menu_id, sort_item, published', 'numerical', 'integerOnly' => true),
            array('link', 'length', 'max' => 100),
            array('icon, page_img', 'length', 'max' => 3),
            array('component_id', 'validateComponent'),
            array('iconImage', 'file', 'types' => $mediaSettings['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['maxImageSize']),
            array('iconImage', 'ValidateImage', 'checkValues' => $mediaSettings['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('pageImg', 'file', 'types' => $mediaSettings['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['maxImageSize']),
            array('pageImg', 'ValidateImage', 'checkValues' => $mediaSettings['pageImage']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('item_id, parent_item, menu_id, sort_item, link, icon, published', 'safe', 'on' => 'search'),
        );
    }

    public function validateComponent($attribute, $param) {
        $errors = array();
        if (intval($this->component_id)) {
            $params = MenuItems::getParamsList($this->component_id);
            $paramData = isset($this->paramsMenuItemsParams[$this->component_id]) ? $this->paramsMenuItemsParams[$this->component_id] : null;
            $valid = ParamsTaskManager::validateParams($params, $paramData);
            if (!$valid) {
                $errors[] = AmcWm::t("msgsbase.core", "Please fill the required items");
            }
        } else {
            if ($this->component_id == 'url') {
                if ($this->link == '') {
                    $errors[] = AmcWm::t("msgsbase.core", "Please type the menu link");
                }
            }
        }
        if (count($errors))
            $this->addError('component_id', implode('<br />', $errors));
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'translationChilds' => array(self::HAS_MANY, 'MenuItemTranslation', 'item_id', 'index' => 'content_lang'),
            'menu' => array(self::BELONGS_TO, 'MenusModel', 'menu_id'),
            'parentItem' => array(self::BELONGS_TO, 'MenuItems', 'parent_item'),
            'menuItems' => array(self::HAS_MANY, 'MenuItems', 'parent_item'),
            'menuItemsParams' => array(self::HAS_MANY, 'MenuItemsParams', 'item_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'item_id' => AmcWm::t("msgsbase.core", 'Item'),
            'parent_item' => AmcWm::t("msgsbase.core", 'Parent Item'),
            'menu_id' => AmcWm::t("msgsbase.core", 'Menu'),
            'sort_item' => AmcWm::t("msgsbase.core", 'Sort Item'),
            'link' => AmcWm::t("msgsbase.core", 'Link'),
            'iconImage' => AmcWm::t("msgsbase.core", 'Icon Image'),
            'page_img' => AmcWm::t("msgsbase.core", 'Page Image'),
            'pageImg' => AmcWm::t("msgsbase.core", 'Page Image'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'component_id' => AmcWm::t("msgsbase.core", 'Menu Type'),
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
        $criteria->compare('parent_item', $this->parent_item);
        $criteria->compare('menu_id', $this->menu_id);
        $criteria->compare('sort_item', $this->sort_item);
        $criteria->compare('link', $this->link);
        $criteria->compare('icon', $this->icon);
        $criteria->compare('page_img', $this->page_img);
        $criteria->compare('published', $this->published);

        $sort = new CSort();
        $sort->defaultOrder = 'sort_item asc';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort,
        ));
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
        if ($this->parent_item) {
            $condition = "parent_item = " . (int) $this->parent_item;
            $condition .= " and menu_id = " . (int) $this->menu_id;
        } else {
            $condition = "parent_item is null";
            $condition .= " and menu_id = " . (int) $this->menu_id;
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
        $this->correctSort();
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->parentItem = $this->parent_item;
        $current = $this->getCurrent();
        if ($current instanceof MenuItemTranslation) {
            $this->displayTitle = $current->label;
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
            $cache->delete('menus');
        }
        parent::afterSave();
    }

    /**
     * This method is invoked after each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        //return false;
        return parent::beforeSave();
    }

    /**
     * Get menu parent and child
     * @param integer $parentMenuItemId
     * @param aray $tree tree list to return
     * @param string $language if not equal null according to the given $language,      
     * @access public
     * @return array
     */
    static public function getMenuItemTree($parentItemId, &$tree = array(), $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $menuQuery = sprintf(
                "select 
                    m.item_id,
                    m.parent_item,
                    t.label
                from menu_items m force index (sort_item)
                inner join menu_item_translation t on m.item_id = t.item_id
                where m.item_id = %d and t.content_lang = %s
                order by m.sort_item", $parentItemId, Yii::app()->db->quoteValue($language));
        $menuItems = Yii::app()->db->createCommand($menuQuery)->queryRow();
        if ($menuItems) {
            $tree[] = $menuItems['label'];
            if ($menuItems['parent_item']) {
                self::getMenuItemTree($menuItems['parent_item'], $tree, $language);
            }
        }
        return $tree;
    }

    /**
     * 
     * @param type $itemId
     * @param type $menu_id
     * @param type $language
     * @return string
     */
    static function drawMenuItemPath($itemId, $menu_id, $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $parentPath = array();
        self::_getMenuItems($itemId, $menu_id, $language, $parentPath);
        $menuItemPath = null;
        if (count($parentPath)) {
            $parentPath = array_reverse($parentPath);
            $sepa = "";
            foreach ($parentPath as $item) {
                $menuItemPath .= $sepa . $item['label'];
                $sepa = "/";
            }
        }
        return $menuItemPath;
    }

    /**
     * Get menu item row for the given $menu_id
     * @param type $itemId
     * @param type $menu_id
     * @param type $language
     * @param type $parentPath
     * @param type $onlyParent
     * @return Array
     */
    static private function _getMenuItems($itemId, $menu_id, $language, &$parentPath = array(), $onlyParent = false) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }

        $query = sprintf(
                "select 
                    m.item_id,
                    m.parent_item,
                    m.menu_id,
                    t.label
                from menu_items m force index (sort_item)
                inner join menu_item_translation t on m.item_id = t.item_id
                where m.item_id = %d 
                and m.menu_id = %d
                and t.content_lang = %s
               ", $itemId, $menu_id, Yii::app()->db->quoteValue($language));

        $parent = Yii::app()->db->createCommand($query)->queryRow();
        if ($parent) {
            $parentPath[] = $parent;
            if (!$onlyParent)
                self::_getMenuItems($parent['parent_item'], $menu_id, $language, $parentPath);
        }
        return $parentPath;
    }

    /**
     * Get menu item labels for the given $menu_id
     * @param type $itemId
     * @param type $menu_id
     * @param type $language
     * @param type $parentPath
     * @return Array
     */
    static private function _getMenuItemsLabel($itemId, $menu_id, $language, &$parentPath = array()) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }

        $query = sprintf("select m.parent_item, t.label
                from menu_items m force index (sort_item)
                inner join menu_item_translation t on m.item_id = t.item_id
                where m.item_id = %d 
                and m.menu_id = %d
                and t.content_lang = %s
               ", $itemId, $menu_id, Yii::app()->db->quoteValue($language));

        $parent = Yii::app()->db->createCommand($query)->queryRow();
        if ($parent && $parent['parent_item'] != $itemId) {
            $parentPath[] = $parent['label'];
            self::_getMenuItemsLabel($parent['parent_item'], $menu_id, $language, $parentPath);
        }
        return $parentPath;
    }

    /**
     * Get childs tree for the given menu item
     * @param integer $itemId
     * @param array $tree
     */
    public static function getChildsIdsTree($itemId, &$tree = array()) {                
        $query = sprintf(
                "select 
                    m.item_id,
                    m.parent_item
                from menu_items m force index (sort_item)
                where m.parent_item = %d                
               ", $itemId);
        $menuItem = Yii::app()->db->createCommand($query)->queryRow();
        $tree[] = $itemId;                 
        if ($menuItem) {                        
            self::getChildsIdsTree($menuItem['item_id'], $tree);                 
        }
    }
    
    
    /**
     * Get childs tree for the current menu it item
     */
    public function getChildsIds() {                
        $tree = array((int)$this->item_id);
        $query = sprintf(
                "select 
                    m.item_id,
                    m.parent_item
                from menu_items m force index (sort_item)
                where m.parent_item = %d                
               ", $this->item_id);
        $childsItems = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($childsItems as $childItem){
            self::getChildsIdsTree($childItem['item_id'], $tree);
        }     
        return $tree;
    }

    /**
     * Get menu items list
     * @todo add checklevel here
     * @param type $menu_id
     * @param type $levelsAllowed
     * @param type $emptyLabel
     * @param type $language
     * @access public
     * @return array
     */
    public function getMenuItemsList($levelsAllowed = 0, $emptyLabel = null, $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $childsIds = $this->getChildsIds();                        
        $exclude = implode(',', $childsIds);
        $query = sprintf(
                "select 
                    m.item_id,
                    m.parent_item,
                    m.menu_id,
                    t.label
                from menu_items m force index (sort_item)
                inner join menu_item_translation t on m.item_id = t.item_id
                where m.menu_id = %d and m.item_id not in({$exclude})
                and t.content_lang = %s
               ", $this->menu_id, Yii::app()->db->quoteValue($language));

        $menuItemsRows = Yii::app()->db->createCommand($query)->queryAll();
        $menuItems = array();
        if ($emptyLabel) {
            $menuItems[""] = $emptyLabel;
        }
        foreach ($menuItemsRows as $item) {
            $parentPath = array();
            if ($item['parent_item']) {
                self::_getMenuItemsLabel($item['parent_item'], $this->menu_id, $language, $parentPath);
                $parentPath = array_reverse($parentPath);
                $itemPath = implode(" / ", $parentPath) . " / ";
            } else {
                $itemPath = null;
            }
            $menuItems[$item['item_id']] = $itemPath . $item['label'];
        }
        return $menuItems;
    }

    /**
     * Get menu items tree for the given menu item $id 
     * @param int $id
     * @param string $language,     
     * @static
     * @access public
     * @return array     
     */
    public static function getTree($id, $menu_id, $language = null) {
        $tree = self::_getMenuItems($id, $menu_id, $language);
        return array_reverse($tree);
    }

    public static function getMenusParams($getArray = true) {
        $menuParams = array();
        $query = "select param_id, concat(param, ' (', param_type, ')') as title from menus_params";
        if ($getArray) {
            $menuParams = Yii::app()->db->createCommand($query)->queryAll();
        } else {
            $menuParams = CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'param_id', 'title');
        }
        return $menuParams;
    }

    public static function getComponents($getArray = true) {
        $components = array();
        $query = sprintf("select mc.component_id, mct.component_name 
                    from modules_components mc
                    inner join modules_components_translation mct on mct.component_id = mc.component_id
                    where mct.content_lang=%s
                    order by mc.component_id
                 ", Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        if ($getArray) {
            $components = Yii::app()->db->createCommand($query)->queryAll();
        } else {
            $components = CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'component_id', 'component_name');
        }
        return $components;
    }

    /**
     * Get menu component for the given $componentId
     * @param integer $componentId
     * @return array
     */
    public static function getComponent($componentId) {
        $query = sprintf("select c.component_id , route
                    from modules_components c
                    where c.component_id=%d
                 ", $componentId);
        return Yii::app()->db->createCommand($query)->queryRow();
    }

    /**
     * Get component if for the given if the component have not any parameters
     * @param integer $componentId
     * @return array
     */
    public function getComponentIdFroumRoute() {
        $route = trim($this->link, "/");
        $query = sprintf("select c.component_id
                    from modules_components c
                    where c.route=%s                  
                 ", Yii::app()->db->quoteValue($route));
        $this->component_id = Yii::app()->db->createCommand($query)->queryScalar();
        if (!$this->component_id && $this->link) {
            $this->component_id = "url";
        }
        return $this->component_id;
    }

    static public function getParamsList($component_id) {
        $query = sprintf(
                "select mp.*, mcpt.*, c.route, m.module, m.module_id
                from menus_params mp
                inner join moduls_components_params_translation mcpt on mcpt.param_id = mp.param_id
                inner join modules_components c on c.component_id = mcpt.component_id
                inner join modules m on m.module_id = c.module_id
                where mcpt.component_id = %d
                and mcpt.content_lang = %s
                ", $component_id, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        return Yii::app()->db->createCommand($query)->queryAll();
    }

    /**
     * function to return parameter type
     * @param integer $paramId
     */
    public static function getParamType($paramId) {
        $query = sprintf("select param_type from menus_params where param_id = %d", $paramId);
        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    /**
     * set parameter items, function to save menu parameter
     * @param type $itemParams
     * @return boolean
     */
    public function setItemParams() {
        $success = $this->deleteItemParams($this->item_id);
        $itemParams = $this->paramsMenuItemsParams;
        $component = MenuItems::getComponent($this->component_id);
        $correct = false;
        if ($this->component_id && is_array($itemParams)) {
            $queries = array();
            $addQuery = 'insert into menu_items_params(item_id, component_id, param_id, value) values ';
            if (isset($itemParams[$this->component_id])) {

                $json = array();
                if (isset(AmcWm::app()->appModule->options['default']['json']['generatedChilds'])) {
                    $json = AmcWm::app()->appModule->options['default']['json']['generatedChilds'];
                }

                foreach ($itemParams[$this->component_id] as $param => $value) {
                    if ($value) {
                        $paramType = self::getParamType($param);
                        if ($paramType === "MENU_CLASS") {
                            $json['id'] = $value;
                            $menuValue = Yii::app()->db->quoteValue(CJSON::encode($json));
                        } else if ($value) {
                            $menuValue = Yii::app()->db->quoteValue($value);
                        }
                        $queries[] = sprintf('(%d, %d, %d, %s)', $this->item_id, $this->component_id, $param, $menuValue);
                    }
                }

                if (count($queries)) {
                    $correct = true;
                    $q = $addQuery . "\n" . implode(",\n", $queries) . ";";
//                    die('<br >' .$q);
                    $success = Yii::app()->db->createCommand($q)->execute();
                    $this->setAttribute('link', '');
                } else if (isset($component['route'])) {
                    $correct = true;
                    $this->setAttribute('link', $component['route']);
                }
            } else {
                if (isset($component['route'])) {
                    $correct = true;
                    $this->setAttribute('link', $component['route']);
                }
            }
        } else {
            $this->setAttribute('link', '');
            $correct = true;
        }
        if ($correct) {
            $this->save();
        }
        return $success;
    }

    /**
     * delete all items params
     * @param int $itemId
     * @return boolean
     */
    public function deleteItemParams($itemId) {
        $query = sprintf('delete from menu_items_params where item_id = %d', $itemId);
        Yii::app()->db->createCommand($query)->execute();
        return true;
    }

}
