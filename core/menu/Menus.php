<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Menus class, generate menu levels for the give menu id
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Menus extends Dataset {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Is main menu or not
     * @var boolean 
     */
    private $_isMain;

    /**
     * Menu id to generate menu according to it.
     * @var integer
     */
    private $_id;

    /**
     * Menu mame
     * @var integer
     */
    private $_name;

    /**
     * Menu levels depth
     * @var integer 
     */
    private $_levels;

    /**
     * The Menus registry instances array.
     * @var array
     * @static
     */
    private static $_instances = array();

    /**
     * menu icons path
     * @var integer 
     */
    private $_iconsPath;

    /**
     * menu page path
     * @var integer 
     */
    private $_pageImagePath;

    /**
     * Constructor, you should not call the constructor directly, but instead call the static registry factory method Menus.getMenu().<br />
     * @param integer|string $id menu id or name to generate menu according to it.
     * @param boolean $isMain is main menu or not
     * @access private
     */
    private function __construct($id, $isMain = false) {
        if (is_string($id)) {
            $query = sprintf("select menu_id, menu_name, levels from menus where menu_name = %s", AmcWm::app()->db->quoteValue($id));
        } else {
            $query = sprintf("select menu_id, menu_name, levels from menus where menu_id = %d", $id);
        }
        $this->_isMain = $isMain;
        $menuData = Yii::app()->db->createCommand($query)->queryRow();
        if ($menuData) {
            $this->_id = $menuData['menu_id'];
            $this->_name = $menuData['menu_name'];
            $this->_levels = $menuData['levels'];
            $this->_levels = Yii::app()->db->createCommand($query)->queryScalar();
            $this->_iconsPath = (isset(self::getSettings()->mediaSettings['path'])) ? AmcWm::app()->baseUrl . "/" . self::getSettings()->mediaSettings['path'] : "";
            $this->_pageImagePath = (isset(self::getSettings()->mediaSettings['pageImage']['path'])) ? AmcWm::app()->baseUrl . "/" . self::getSettings()->mediaSettings['pageImage']['path'] : "";
            $this->generate();
        }
    }

    /**
     * Set menus
     * @param array $menus
     * @static
     * @access public
     * @return void
     */
    public static function setMenus($menus = array()) {
        foreach ($menus as $menu) {
            self::setMenu($menu['id'], $menu['is_main']);
        }
    }

    /**
     * Registry and factory Menus method.
     * If <var>$id</var> is not registered in <var>Menus.$_instances</var>, we create new Menus object then registered it.
     * @param integer|string $id menu id or name to generate menu according to it.
     * @param boolean $isMain is main menu or not
     * @static
     * @access public
     * @return Menus the Singleton instance of the given menu id
     */
    public static function setMenu($id, $isMain = false) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $cache = Yii::app()->getComponent('cache');
        if ($cache !== NULL) {
            self::$_instances = unserialize($cache->get("menus"));
            if (self::$_instances == null) {
                self::$_instances = array();
            }
        }
        if (!array_key_exists($siteLanguage, self::$_instances)) {
            self::$_instances[$siteLanguage] = array();
        }
        if (!array_key_exists($id, self::$_instances[$siteLanguage])) {
            self::$_instances[$siteLanguage][$id] = new self($id, $isMain);
            if ($cache !== NULL) {
                $cache->set('menus', serialize(self::$_instances), Yii::app()->params["cacheDuration"]["static"]);
            }
        }
    }

    /**
     * Get the given menu $id instance
     * @param integer|string $id menu id or name to generate menu according to it.
     * @static
     * @access public
     * @return Menus the Singleton instance of the given menu id
     */
    public static function &getMenu($id) {
        $menuId = $id;
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if (is_string($id) && isset(self::$_instances[$siteLanguage])) {
            foreach (self::$_instances[$siteLanguage] as $menu) {
                if ($menu->getName() == $id) {
                    $menuId = $id;
                    break;
                }
            }
        }
        if (!isset(self::$_instances[$siteLanguage][$menuId])) {
            self::setMenu($menuId);
        }
        return self::$_instances[$siteLanguage][$menuId];
    }

    /**
     * Get menus used in the system
     * @static
     * @return array
     * @access public
     */
    final static public function getMenus() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if (isset(self::$_instances[$siteLanguage])) {
            return self::$_instances[$siteLanguage];
        } else {
            return array();
        }
    }

    /**
     * Get the menu name    
     * @return string
     * @access public
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Return an array with the names of all variables of that menu instance that should be serialized
     * @return array
     * @access public
     */
    public function __sleep() {
        return array("_name", "_id", "_levels", "items", "_isMain", "_iconsPath");
    }

    /**
     * Check if this menu instance main menu or not
     * @return boolean
     * @access public
     */
    public function isMainMenu() {
        return $this->_isMain;
    }

    /**
     * Reconstruct any resources that the menu instance may have after unserialize it.
     * @access public
     * @return void
     */
    public function __wakeup() {
        
    }

    /**
     * Get menu item id from the given $id
     * @param string $id
     * @access public
     * @return array
     * @static
     */
    public static function getMenuItemIds($id) {
        $menu = array('id' => (int) $id, 'item' => 0);
        $menuIds = explode("-", $id, 2);
        if (count($menuIds) == 2) {
            $menu['id'] = (int) $menuIds[0];
            $menu['item'] = (int) $menuIds[1];
        }
        return $menu;
    }

    /**
     * 
     * Get menu item sisters for the given $id
     * @see Menus.walkTo
     * @param string $id menu id
     * @param boolean $mergeSubs
     * @access public
     * @return array
     */
    public static function getMenuItemSisters($id, $mergeSubs = false) {
        $menuIds = self::getMenuItemIds($id);
        $menu = self::getMenu($menuIds['id']);
        $list = $menu->walkTo($menuIds['item']);
        $finalList = $list;
        $finalList['items'] = array();
        if ($mergeSubs) {
            $menu->_mergeSubSistersItems($list['items'], $finalList['items'], 1);
        } else {
            foreach ($list['items'] as $itemId => $item) {
                $finalList['items'][$itemId] = $item;
                if (isset($item['url'][0])) {
                    $url = $item['url'];
                    if(is_array($url)){
                        $finalList['items'][$itemId]['isActive'] = Data::getInstance()->isCurrentRoute(array_shift($url), $url);
                    }
                }
            }
        }
        return $finalList;
    }

    /**
     * merge sub sisters list
     * @param array $list
     * @param array $finalList
     * @param integer $level
     */
    private function _mergeSubSistersItems($list, &$finalList, $level) {
        if ($level <= Menus::getSettings()->options['default']['integer']['maxSisterLevels']) {
            $this->_generateMeregedSistersItems($list, $finalList, $level);
        }
    }

    /**
     * generated merged sisters list
     * @param array $list
     * @param array $finalList
     * @param integer $level
     */
    private function _generateMeregedSistersItems($list, &$finalList, $level) {
        $level++;
        foreach ($list as $itemId => $item) {
            if (isset($item['items'])) {
                $this->_mergeSubSistersItems($item['items'], $finalList, $level);
            } else {
                $finalList[$itemId] = $item;
                if (isset($item['url'][0])) {
                    $url = $item['url'];
                    if(is_array($url)){
                        $finalList[$itemId]['isActive'] = Data::getInstance()->isCurrentRoute(array_shift($url), $url);    
                    }
                    
                }
            }
        }
    }

    /**
     * 
     * Get menu item for the given $id
     * @see Menus.walkTo
     * @param string $id menu id
     * @access public
     * @return array
     */
    public static function getMenuItem($id) {
        $menuIds = self::getMenuItemIds($id);
        $list = self::getMenuItemSisters($id);
        $menuData = array();
        if (isset($list['items'][$menuIds['item']]) && count($list['items'][$menuIds['item']])) {
            $menuData = $list['items'][$menuIds['item']];
        } else {
            $menuData = $list;
        }
        return $menuData;
    }

    /**
     * walk throuh childs 

     * @param array $childsOwner
     * @param integer $stopKey $key to stop when found it
     * @param array $list
     * @access private
     * @return boolean
     */
    private function _walkThroughChilds($childsOwner, $stopKey, &$list) {
        $stop = false;
        $count = count($childsOwner['items']);
        reset($childsOwner['items']);
        $i = 0;
        while (($i < $count && (list($id, $item) = each($childsOwner['items'])) && !$stop)) {
            if ($id == $stopKey) {
                $list = $childsOwner;
                $stop = true;
            } else if (isset($item['items'])) {
                $stop = $this->_walkThroughChilds($item, $stopKey, $list);
            }
            $i++;
        }
        return $stop;
    }

    /**
     * 
     * Walk inside menu array and stop when found the $stopKey     
     * @param integer $stopKey $key to stop when found it
     * @access public
     * @return array return parent item if found it
     */
    public function walkTo($stopKey) {
        $menuItems = $this->getMenuItems();
        $list = array("label" => null, "url" => array(), "items" => array());
        if (isset($menuItems[$stopKey])) {
            $list = $menuItems[$stopKey];
        } else {
            $count = count($menuItems);
            reset($menuItems);
            $i = 0;
            $stop = false;
            while (($i < $count && (list($id, $menuItem) = each($menuItems)) && !$stop)) {
                if ($id == $stopKey) {
                    $list = $menuItem;
                    $stop = true;
                    //die($id);                
                } else if (isset($menuItem['items'])) {
                    $stop = $this->_walkThroughChilds($menuItem, $stopKey, $list);
                }
            }
        }
        if (!array_key_exists('items', $list)) {
            $list['items'] = array();
        }
        return $list;
    }

    /**
     * Walk inside menu items array and get the parent path of $stopKey item
     * @see Menus._getPathTree
     * @access public
     * @param integer $stopKey $key to stop when found it
     * @return array
     */
    public function getPath($stopKey) {
        $tree = array();
        $stop = false;
        $menuItems = $this->getMenuItems();
        $count = count($menuItems);
        reset($menuItems);
        $i = 0;
        while (($i < $count && (list($id, $item) = each($menuItems)) && !$stop)) {
            $tree[0] = array('label' => $item['label'], 'url' => $item['url'], 'stoped' => $stop);
            if ($id == $stopKey) {
                $stop = true;
                $tree[0] = array('label' => $item['label'], 'url' => $item['url'], 'stoped' => $stop);
                //die($id);                
            } else if (isset($item['items'])) {
                $stop = $this->_getPathTree($stopKey, $item['items'], $tree, 0);
            }
        }
        $correctTree = array();
        foreach ($tree as $item) {
            $correctTree[] = $item;
            if ($item['stoped']) {
                break;
            }
        }
        //print_r($correctTree);
//        print_r($tree);
//        die();
        return $correctTree;
    }

    /**
     * 
     * Walk inside menu items array and get the parent path of $stopKey item
     * @param integer $stopKey $key to stop when found it
     * @param array $menuItems array list to search in
     * @param array $tree $stopKey parent path tree array
     * @param integer $level path level depth
     * @access private
     * @return boolean
     */
    private function _getPathTree($stopKey, $menuItems, &$tree, $level = 0) {
        $level++;
        $stop = false;
        $count = count($menuItems);
        $i = 0;
        reset($menuItems);
        while (($i < $count && (list($id, $item) = each($menuItems)) && !$stop)) {
            //echo "{$id} - {$level} {$item['label']}\n";
            $tree[$level] = array('label' => $item['label'], 'url' => $item['url'], 'stoped' => $stop);
            if ($id == $stopKey) {
                $stop = true;
                $tree[$level] = array('label' => $item['label'], 'url' => $item['url'], 'stoped' => $stop);
            } else if (isset($item['items'])) {
                $stop = $this->_getPathTree($stopKey, $item['items'], $tree, $level);
            }
            $i++;
        }
        return $stop;
    }

    /**
     * Get menu item id from the given route
     * @static
     * @param string $route
     * @param boolean $fromMain if the given value equal true then we get the values from main menu
     * @access public
     * @return array     
     */
    static public function getMenuIdFromRoute($route, $fromMain = true) {
        unset($route['lang']);
        unset($route['title']);
        unset($route['r']);
        foreach ($route as $routeKey => $routeValue) {
            if ($routeValue == null && $routeKey != "id") {
                unset($route[$routeKey]);
            }
        }
        $route[0] = trim($route[0], "/");
        $menus = self::getMenus();
        $itemsIds = array();
        $menuId['id'] = 0;
        $menuId['item'] = 0;
        foreach ($menus as $menu) {
            $menuRoute = $menu->getMenuRoutes();
            if (isset($menuRoute[$route[0]]['url'])) {
                foreach ($menuRoute[$route[0]]['url'] as $urlKey => $url) {
                    if (count($route) > count($url)) {
                        $compare = array_diff_assoc($route, $url);
                    } else {
                        $compare = array_diff_assoc($url, $route);
                    }
                    if (!$compare) {
                        $itemsIds[$menu->getId()] = $menuRoute[$route[0]]['itemIds'][$urlKey];
                        break;
                    }
                }
            }
        }
//        die();
        if (count($itemsIds)) {
            foreach ($itemsIds as $id => $itemId) {
                $menuId['id'] = $id;
                $menuId['item'] = $itemId;
                $menu = self::getMenu($id);
                if ($fromMain && self::getMenu($id)->isMainMenu()) {
                    break;
                }
            }
        }
        return $menuId;
    }

    /**
     * 
     * Get menu path for the given $id
     * @param string $id menu annd menu item id to get code parameter 
     * @param array $parentRoute 
     * @param array $currentRoute
     * @access public
     * @return array
     */
    public static function getMenuItemPath($id = null, $parentRoute = array(), $currentRoute = array()) {
        $menuTree = array();
        if ($id) {
            $menuId = self::getMenuItemIds($id);
        } else {
            if (isset($parentRoute[0])) {
                $menuId = self::getMenuIdFromRoute($parentRoute);
            } else {
                $menuId = self::getMenuIdFromRoute($currentRoute);
            }
        }
        if ($menuId['item']) {
            $menu = self::getMenu($menuId['id']);
            $menuTree = $menu->getPath($menuId['item']);
        }
        return $menuTree;
    }

    /**
     * 
     * Get menu code params for the given $id
     * @param string $id menu annd menu item id to get code parameter 
     * @access public
     * @return array
     */
    public static function getMenuCodeParams($id) {
        $params = array();
        if ($id) {
            $params = self::getMenuItemParams($id, "CODE");
        } else {
            $controller = Yii::app()->getController();
            $actionParams = $controller->getActionParams();
            $route[0] = $controller->getRoute();
            foreach ($actionParams as $paramKey => $paramValue) {
                if ($paramValue && $paramKey != "lang") {
                    $route[$paramKey] = $paramValue;
                }
            }
            $menus = self::getMenus();
            $menusParams = array();
            foreach ($menus as $menu) {
                $menuRoute = $menu->getMenuRoutes();
                if (isset($menuRoute[$route[0]]['url'])) {
                    foreach ($menuRoute[$route[0]]['url'] as $urlKey => $url) {
                        $compare = array_diff_assoc($route, $url);
                        if (!$compare && isset($menuRoute[$route[0]]['code'][$urlKey])) {
                            $menusParams[$menu->getId()] = $menuRoute[$route[0]]['code'][$urlKey];
                            break;
                        }
                    }
                }
            }
            foreach ($menusParams as $menuId => $menuParam) {
                $params = $menuParam;
                if (self::getMenu($menuId)->isMainMenu()) {
                    break;
                }
            }
        }
        return $params;
    }

    /**
     * Get the menu id     
     * @return integer
     * @access public
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Get menu items params for the given $id
     * @param string $id menu annd menu itenm id to get parameter 
     * @param string $filter  to get values according to it , filter options : ROUTE, CODE , MENU_CLASS
     * @access public
     * @return array
     */
    public static function getMenuItemParams($id, $filter = null) {
        $menuIds = self::getMenuItemIds($id);
        $params = array();
        if ($menuIds['item']) {
            $menu = self::getMenu($menuIds['id']);
            $params = $menu->getItemParams($menuIds['item'], $filter);
        }
        return $params;
    }

    /**
     *
     * Generate menu list
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
            $this->addOrder("t.sort_item");
        }
        $this->setItems();
    }

    /**
     * Set the menu array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->items['list'] = array();
        $this->items['params'] = array();
        $this->items['routes'] = array();
        $this->items['parents'] = array();
        //left join modules_components c on mc.component_id = c.component_id 

        $this->query = sprintf("
            select t.item_id, t.parent_item, t.link, t.icon, t.page_img, l.label $cols
            from menu_items t
            left join menu_item_translation l on t.item_id = l.item_id                        
            where menu_id = %d 
            and content_lang = %s
            and t.parent_item is null
            and t.published = 1
            $wheres
            $orders"
                , $this->_id
                , Yii::app()->db->quoteValue($siteLanguage)
        );
        $menuItemsDataset = Yii::app()->db->createCommand($this->query)->queryAll();
        foreach ($menuItemsDataset as $menuRow) {
            $this->items['list'][$menuRow['item_id']]['label'] = $menuRow['label'];
            $this->items['params'][$menuRow['item_id']] = $this->_generateParams($menuRow['item_id']);
            $this->items['list'][$menuRow['item_id']]['icon'] = ($menuRow['icon']) ? "{$this->_iconsPath}/{$menuRow['item_id']}.{$menuRow['icon']}" : null;
            $this->items['list'][$menuRow['item_id']]['pageImg'] = ($menuRow['page_img']) ? "{$this->_pageImagePath}/{$menuRow['item_id']}.{$menuRow['page_img']}" : null;
            $this->items['list'][$menuRow['item_id']]['url'] = $menuRow['link'];
            $this->items['list'][$menuRow['item_id']]['url'] = $this->_generateUrl($menuRow['item_id'], $this->items['list'][$menuRow['item_id']], $this->items['params'][$menuRow['item_id']]);
            $this->_setChilds($this->items['list'][$menuRow['item_id']], $menuRow['item_id']);
        }
    }

    /**
     * set menu params
     * @param integer $itemId current menu item id     
     * @access private
     * return string|array
     */
    private function _generateParams($itemId) {
        $itemId = (int) $itemId;
        $query = " select * from menus_params p
            inner join menu_items_params mp on p.param_id = mp.param_id
            inner join modules_components c on mp.component_id = c.component_id
            where mp.item_id = {$itemId}
            ";
        $params = Yii::app()->db->createCommand($query)->queryAll();
        return $params;
    }

    /**
     * Gets menu list data
     * @access public
     * @return array
     */
    public function getMenuItems() {
        $items = array();
        if (isset($this->items['list'])) {
            $items = $this->items['list'];
        }
        return $items;
    }

    /**
     * Gets menu routes list
     * @access public
     * @return array
     */
    public function getMenuRoutes() {
        $items = array();
        if (isset($this->items['routes'])) {
            $items = $this->items['routes'];
        }
        return $items;
    }

    /**
     * Gets menu params list
     * @access public
     * @return array
     */
    public function getMenuParams() {
        $items = array();
        if (isset($this->items['params'])) {
            $items = $this->items['params'];
        }
        return $items;
    }

    /**
     * Gets menu item param for the given $itemId
     * @param integer $itemId
     * @param string $filter  to get values according to it , filter options : ROUTE, CODE , MENU_CLASS
     * @access public
     * @return array
     */
    public function getItemParams($itemId, $filter = null) {
        $params = array();
        if (isset($this->items['params'][$itemId])) {
            if ($filter) {
                foreach ($this->items['params'][$itemId] as $param) {
                    if ($param['param_type'] == $filter) {
                        $params[] = $param;
                    }
                }
            } else {
                $params = $this->items['params'][$itemId];
            }
        }
        return $params;
    }

    /**
     * set menu childs for the given $itemId
     * @param array $menuItem menu item to append child data set to it
     * @param integer $itemId Menu item id to get child related to it
     * @access private     
     * return void
     */
    private function _setChilds(&$menuItem, $itemId) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("
            select t.item_id, t.parent_item, t.link, t.icon, t.page_img, l.label $cols
            from menu_items t
            inner join menu_item_translation l on t.item_id = l.item_id                        
            where menu_id = %d 
            and content_lang = %s
            and t.parent_item = %d
            and t.published = 1
            $wheres
            $orders"
                , $this->_id
                , Yii::app()->db->quoteValue($siteLanguage)
                , $itemId
        );
        $menuItemsDataset = Yii::app()->db->createCommand($this->query)->queryAll();
        foreach ($menuItemsDataset as $menuRow) {
            $menuItem['items'][$menuRow['item_id']]['label'] = $menuRow['label'];
            $this->items['params'][$menuRow['item_id']] = $this->_generateParams($menuRow['item_id']);
            $this->items['parents'][$menuRow['item_id']][$itemId] = $menuItem;
            $menuItem['items'][$menuRow['item_id']]['icon'] = ($menuRow['icon']) ? "{$this->_iconsPath}/{$menuRow['item_id']}.{$menuRow['icon']}" : null;
            $menuItem['items'][$menuRow['item_id']]['pageImg'] = ($menuRow['page_img']) ? "{$this->_pageImagePath}/{$menuRow['item_id']}.{$menuRow['page_img']}" : null;
            $menuItem['items'][$menuRow['item_id']]['url'] = $menuRow['link'];
            $menuItem['items'][$menuRow['item_id']]['url'] = $this->_generateUrl($menuRow['item_id'], $menuItem['items'][$menuRow['item_id']], $this->items['params'][$menuRow['item_id']]);
            $this->_setChilds($menuItem['items'][$menuRow['item_id']], $menuRow['item_id']);
        }
    }

    /**
     * set menu item url
     * @param array $menuItem the current menu item
     * @param array $param module menu item parameters
     * @access private
     * return string|array
     */
    private function _setModuleChilds(&$menuItem, $param) {
        if ($param['route'] && $param['param']) {
            $moduleParam = CJSON::decode($param['value']);
            if (is_array($moduleParam)) {
                $classChilds = ucfirst($param['param'] . "Childs");
                //$adminClassChilds = "Admin" . ucfirst($param['param'] . "Childs");
                $moduleParam['menu'] = "{$this->_id}-{$param['item_id']}";
                $module = new $classChilds($param['module_id'], $moduleParam);
                $menuItem['items'] = $module->getItems();
            }
        }
    }

    /**
     * set menu item url
     * @param integer $itemId current menu item id          
     * @param array $menuItem the current menu item
     * @param array $params current menu item parameters
     * @access private
     * return string|array
     */
    private function _generateUrl($itemId, &$menuItem, $params) {
        $itemUrl = trim($menuItem['url'], "/");
        $url = array();
        $codeRouteId = 0;
        $routeCode = array();
        if (!$itemUrl) {
            if (count($params)) {
                $routeParam = array();
                foreach ($params as $param) {
                    switch ($param['param_type']) {
                        case "ROUTE":
                            $codeRouteId .= $param['param'] . $param['value'];
                            $routeParam[0] = $param['route'];
                            $routeParam[$param['param']] = $param['value'];
                            if (!isset($url[0])) {
                                $url[0] = "/" . $param['route'];
                            }
                            $url[$param['param']] = $param['value'];
                            break;
                        case "MENU_CLASS":
                            $this->_setModuleChilds($menuItem, $param);
                            break;
                        case "CODE":
                            $routeCode[$param['param'] . $param['value']] = $param;
                            break;
                    }
                }

                $this->items['routes'][$param['route']]['url'][$codeRouteId] = $routeParam;
                $this->items['routes'][$param['route']]['code'][$codeRouteId] = $routeCode;
                $this->items['routes'][$param['route']]['itemIds'][$codeRouteId] = $itemId;
            }
        } else {
            $linkData = parse_url($itemUrl);
            if (!isset($linkData['scheme'])) {
                $urlParams = array();
                $url = explode("&", $itemUrl, 2);
                if (isset($url[1])) {
                    parse_str($url[1], $urlParams);
                }
                $urlRoute = "/" . $url[0];
                $routeParam[0] = $url[0];
                $url = array();
                $url[0] = $urlRoute;

                $url = array_merge($url, $urlParams);
                $routeParam = array_merge($routeParam, $urlParams);
                $codeRouteId .= $itemId;

                $this->items['routes'][$itemUrl]['url'][$codeRouteId] = $routeParam;
                $this->items['routes'][$itemUrl]['code'][$codeRouteId] = $routeCode;
                $this->items['routes'][$itemUrl]['itemIds'][$codeRouteId] = $itemId;
            } else {
                $url = $itemUrl;
            }
        }

        if (is_array($url) && count($url)) {
            $url['menu'] = "{$this->_id}-$itemId";
        }
        return $url;
    }

    /**
     * Generate one levele mene
     * @todo  remove this method, then replace it with extension
     * @param string $separator menu item seperator
     * @param array $appended 
     * @param boolean $useIcon      
     * @param boolean $return should echo or just return the output
     * @access public
     * @deprecated since version 1.1
     * @return void
     */
    public function generateMenu($separator = "&nbsp;", $useIcon = true, $appended = array(), $return = false) {
        $menuItems = $this->getMenuItems();
        if (count($appended)) {
            $menuItems = array_merge($menuItems, $appended);
        }
        $menuItemsCount = count($menuItems);
        $i = 0;
        $output = "";
        foreach ($menuItems as $menuItem) {
            $i++;
            $url = "#";
            $htmlOptions = array();
            if (is_array($menuItem['url']) && count($menuItem['url'])) {
                $url = $menuItem['url'];
            } else if ($menuItem['url']) {
                $url = $menuItem['url'];
                $linkData = parse_url($url);
                if (isset($linkData['scheme'])) {
                    $htmlOptions['target'] = "_blank";
                }
            }
            if ($i == $menuItemsCount) {
                $separator = null;
            }
            $icon = ($useIcon && isset($menuItem['icon'])) ? '<img src="' . $menuItem['icon'] . '" border="0" alt="" />' : '';
            $output .= Html::link("{$icon}{$menuItem['label']}", $url, $htmlOptions) . $separator;
        }

        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }

    /**
     * Get menus setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("menus", false);
        }
        return self::$_settings;
    }

}
