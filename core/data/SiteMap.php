<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SiteMap class, generate all menu levels
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SiteMap extends Dataset {

    /**
     * The Menus registry instances array.
     * @var array
     * @static
     */
    private static $_instances = array();

    /**
     * Constructor, you should not call the constructor directly, but instead call the static registry factory method Menus.getInstance().<br />
     * @access private
     */
    private function __construct() {
        $this->generate();
    }

    /**
     * Registry and factory Menus method.
     * If <var>$id</var> is not registered in <var>Menus.$_instances</var>, we create new Menus object then registered it.
     * @static
     * @access public
     * @return Menus the Singleton instance of the given menu id
     */
    public static function &getInstance() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $cache = Yii::app()->getComponent('cache');
        if ($cache !== NULL) {
            self::$_instances = unserialize($cache->get("sitemap"));
            if (self::$_instances == null) {
                self::$_instances = array();
            }
        }
        if (!array_key_exists($siteLanguage, self::$_instances)) {
            self::$_instances[$siteLanguage] = new self();
            if ($cache !== NULL) {
                $cache->set('sitemap', serialize(self::$_instances), Yii::app()->params["cacheDuration"]["static"]);
            }
        }
        return self::$_instances[$siteLanguage];
    }

    /**
     * Set the menu array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $menus = Menus::getMenus();
        foreach ($menus as $menu) {
            $this->_setSiteMapItems($menu);
        }
    }

    /**
     * 
     * Append the menu items to site map array list     
     * @todo change url checked to check all levels and compare labels in final level path 
     * Finally remove url if the comparing is true
     * @param Menus $menu the menu childs items
     * @param array $sitemap the final sitemap array
     */
    private function _setSiteMapItems($menu) {
        $menuItems = $menu->getMenuItems();
        foreach ($menuItems as $menuId => $menuItem) {
            $remove = false;
            //$menuItemParams = $menu->getItemParams($menuId);
            //print_r($menuItemParams);

            if (is_array($menuItem['url']) && count($menuItem['url'])) {
                unset($menuItem['url']['menu']);
                $url = trim(strtolower(implode('/', $menuItem['url'])), "/");
                $itemIndex = $url;
                $remove = (count($menuItem['url'] == 1) && trim($url, "/") == "site/index");
            } else {
                $itemIndex = $menuId;
            }
            if (!$remove) {
                $this->items[$itemIndex] = $menuItem;
                if (isset($this->items[$itemIndex]['items'])) {
                    $this->_walkThroughChilds($this->items[$itemIndex]['items'], 0);
                }
            }
        }
    }

    /**
     * walk throuh sitemap childs 
     * @param array $childs
     * @param integer $level
     * @access private
     * @return void
     */
    private function _walkThroughChilds(&$childs, $level) {
        $level ++;
        foreach ($childs as &$item) {
            if (isset($item['items'])) {
                $this->_walkThroughChilds($item['items'], $level);
            } else {
                $this->_setChilds($item, $level);
            }
        }
    }

    /**
     * Set childs for none-childs menu items
     * @param array $child
     * @param integer $level
     * @access private
     * @return void
     */
    private function _setChilds(&$child, $level) {
        if (count($child['url']) && $level < Menus::getSettings()->options['default']['integer']['maxLevels']) {                    
            $taskObj = new ControllerTaskManager($child['url'], $child['url']['menu'], array("limit"=>100));            
            if ($taskObj->isSuccess()) {                
                $taskObj->run(false);
                $child['items'] = $taskObj->getSiteMapData();
            }
        }
    }

}