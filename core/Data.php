<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Data class, generate common data and put it in the cache
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Data {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * The Singleton Data instance.
     * @var Data
     * @static
     * @access private
     */
    private static $_instance = null;

    /**
     * Sections tree array used in the system
     * @var array 
     */
    private $_sections = array();

    /**
     * Sections ids array used in the system
     * @var array 
     */
    private $_sectionsIds = array();

    /**
     * Constructor, this Data implementation is a Singleton.
     * You should not call the constructor directly, but instead call the static Singleton factory method Data.getInstance().<br />

     * @access private
     * @throws Error Error if you call the constructor directly
     */
    private function __construct() {
        $sectionsIds = array();
        foreach (Yii::app()->params['languages'] as $language => $languageName) {
            $this->_sections[$language] = array();

            $this->_setSections($language, $this->_sections[$language], $sectionsIds);
        }

        foreach ($sectionsIds as $sectionId => $subSections) {
            $this->_sectionsIds[$sectionId] = $subSections;
            foreach ($subSections as $subSectionId) {
                if (isset($sectionsIds[$subSectionId])) {
                    $this->_sectionsIds[$sectionId] = $this->_sectionsIds[$sectionId] + $sectionsIds[$subSectionId];
                }
            }
        }
    }

    /**
     * Return an array with the names of all variables of that Data instance that should be serialized
     * @return array
     * @access public
     */
    public function __sleep() {
        return array("_sections", "_sectionsIds");
    }

    /**
     * Reconstruct any resources that the Data instance may have after unserialize it.
     * @access public
     * @return void
     */
    public function __wakeup() {
        
    }

    /**
     * Get sections tree
     * @todo add code to get sub sections tree of the given $sectionId
     * @param integer $sectionId the parent section id to get sub sections contents from, if equal null then we gets contents from top parent sections
     * @param string $siteLanguage
     * @access public
     * @return array 
     */
    public function getSectionsTree($sectionId = null, $siteLanguage = null) {
        if (!$siteLanguage) {
            $siteLanguage = Yii::app()->user->getCurrentLanguage();
        }
        $sections = array();
        if ($sectionId) {
            
        } else {
            if (isset($this->_sections[$siteLanguage])) {
                $sections = $this->_sections[$siteLanguage];
            }
        }
        return $sections;
    }

    /**
     * 
     * Get sub-sections ids for a given section
     * @todo need to enhance this method
     * @access public
     * @param string $id
     * @return array
     */
    public function getSectionIds($id) {
        if (isset($this->_sectionsIds[$id])) {
            return $this->_sectionsIds[$id];
        }
    }

    /**
     * 
     * Get sub-sections ids as array list for the given section $id
     * @todo need to enhance this method
     * @access public
     * @param string $id
     * @return array
     */
    public function getSectionSubIds($id, $sections = array(), &$subs = array()) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if (!$sections && isset($this->_sections[$siteLanguage][$id]['childs']) && $this->_sections[$siteLanguage][$id]['childs']) {
            $sections = $this->_sections[$siteLanguage][$id]['childs'];
        }
        if ($sections) {
            foreach ($sections as $section) {
                $subs[] = $section['data']['section_id'];
                if ($section['childs']) {
                    $this->getSectionSubIds($section['data']['section_id'], $section['childs'], $subs);
                }
            }
        }
        return $subs;
    }

    /**
     * Get sub-sections list for the given $id
     * @todo fix it to get sub sections levels greater than 2
     * @access public
     * @param integer $id
     * @return array 
     */
    public function getSubSections($id) {
        $list = array();
        $section = $this->getSection($id);
        if (isset($section['childs'])) {
            $list = $section['childs'];
        }
        return $list;
    }

    /**
     * Get sections record for the given $id
     * @access public
     * @param integer $id
     * @return array 
     */
    public function getSection($id) {
        $section = array();
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if (isset($this->_sections[$siteLanguage][$id])) {
            $section = $this->_sections[$siteLanguage][$id];
        }
        return $section;
    }

    /**
     * Factory Singleton Data method.
     * @static
     * @access public
     * @return Data the Singleton instance of the Data
     */
    static public function &getInstance() {

        $dataFile = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . "data.bin";
        if(is_readable($dataFile)){            
            self::$_instance = unserialize(file_get_contents($dataFile));
        }
        if (self::$_instance == null) {            
            self::$_instance = new self();
            file_put_contents($dataFile, serialize(self::$_instance));                    
        }            
        return self::$_instance;
    }    

    /**
     * Sets the sections tree
     * @param int $parent
     * @param int $index 
     * @access private
     * @return void
     */
    private function _setSections($siteLanguage, &$sectuonsTree = array(), &$sectionIds = array(), $parent = null) {
        if ($parent) {
            $parentWhere = " and s.parent_section = {$parent}";
        } else {
            $parentWhere = " and s.parent_section is null ";
        }
        $sectionsQuery = sprintf(
                "select 
                    s.section_id,
                    s.parent_section,
                    s.settings,
                    t.section_name                                
                from sections s force index (idx_section_sort)
                inner join sections_translation t on s.section_id = t.section_id
                where s.published = %d
                and t.content_lang = %s $parentWhere                
                order by " . SectionsData::getDefaultSortOrder(), ActiveRecord::PUBLISHED, Yii::app()->db->quoteValue($siteLanguage)
        );
        $sections = Yii::app()->db->createCommand($sectionsQuery)->queryAll();
        if (count($sections)) {
            foreach ($sections As $section) {
                $sectuonsTree[$section['section_id']]['data'] = $section;
                $sectuonsTree[$section['section_id']]['childs'] = array();
                if ($parent) {
                    $sectionIds[$parent][$section['section_id']] = $section['section_id'];
                }
                $this->_setSections($siteLanguage, $sectuonsTree[$section['section_id']]['childs'], $sectionIds, $section['section_id']);
            }
        }
    }

    /**
     * Generate  website home page keywords
     * @access public
     * @return array 
     */
    public function generatHomeKeywords() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        str_replace("\n\r", "\n", Yii::app()->params['custom']['front']['site']['keywords']);
        $keywords = explode("\n", Yii::app()->params['custom']['front']['site']['keywords']);
        if (isset($this->_sections[$siteLanguage])) {
            foreach ($this->_sections[$siteLanguage] as $section) {
                $keywords[] = $section['data']['section_name'];
            }
        }
        return implode(", ", $keywords);
    }

    /**
     *
     * Get sections top articles list for each section in the system
     * @param string $table, table name to get data using it
     * @param string $module, module name that handle the output
     * @param integer $sectionsLimit, Number of sections to be displayed
     * @param integer $articlesLimits, Number of articles to be displayed in each section
     * @param integer $parentSectionId to get sub sections contents from, if equal null or 0  then we gets contents from top parent sections
     * @return array
     */
    public function sectionsTopArticles($table = "articles", $module = "articles", $sectionsLimit = 10, $articlesLimit = 4, $parentSectionId = null, $mediapath = null) {
        $data = new ArticlesListData(array($table));
        $data->addColumn('tags');
        if (isset($mediapath)) {
            $path = Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths[$mediapath]['path'] . "/";
            $data->setMediaPath($path);
        }
        $articlesTables = ArticlesListData::getArticlesTables();
        if ($table == "articles") {
            //$data->addJoin("inner join {$this->_table} on t.article_id = {$this->_table}.article_id");
            foreach ($articlesTables as $articleTable) {
                $data->addJoin("left join {$articleTable} on t.article_id = {$articleTable}.article_id");
                $data->addWhere("{$articleTable}.article_id is null");
            }
        }
        $data->setModuleName($module);
        $sections = new SectionsListData($data, $sectionsLimit, $articlesLimit);
        $sections->setParentSectionId($parentSectionId);
        return $sections->generate();
    }

    /**
     *
     * Get sections top articles list for each section in the system
     * @param string $table, table name to get data using it
     * @param string $module, module name that handle the output
     * @param integer $sectionsLimit, Number of sections to be displayed
     * @param integer $articlesLimits, Number of articles to be displayed in each section
     * @param integer $parentSectionId to get sub sections contents from, if equal null or 0  then we gets contents from top parent sections
     * @return array
     */
    public function homeSectionsList($table = "articles", $module = "articles", $sectionsLimit = 10, $articlesLimit = 4, $parentSectionId = null) {
        $data = new ArticlesListData(array($table));
        $data->addColumn('tags');
        $data->addOrder("create_date desc");
        $mediaPath = Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths['sections']['path'] . "/";
        $data->setMediaPath($mediaPath);
        $articlesTables = ArticlesListData::getArticlesTables();
        if ($table == "articles") {
            //$data->addJoin("inner join {$this->_table} on t.article_id = {$this->_table}.article_id");
            foreach ($articlesTables as $articleTable) {
                $data->addJoin("left join {$articleTable} on t.article_id = {$articleTable}.article_id");
                $data->addWhere("{$articleTable}.article_id is null");
            }
        }
        $data->setModuleName($module);
        $sections = new SectionsListData($data, $sectionsLimit, $articlesLimit);
        $sections->setParentSectionId($parentSectionId);
        return $sections->generate();
    }

    /**
     * Get section path for the given section $id 
     * @param int $id
     * @param string $language,          
     * @param array $sections
     * @param boolean $publishedOnly
     * @access public
     * @return array     
     */
    public function getSectionPath($id, $language = null, &$sections = array(), $publishedOnly = false) {
        if (!$language) {
            $language = Controller::getCurrentLanguage();
        }
        $query = sprintf(
                "select 
                    s.section_id,
                    s.parent_section,
                    t.section_name
                from sections s force index (idx_section_sort)
                inner join sections_translation t on s.section_id = t.section_id
                where s.section_id = %d 
                and t.content_lang = %s 
                limit 0, 1", $id, Yii::app()->db->quoteValue($language));
        $section = Yii::app()->db->createCommand($query)->queryRow();
        if (is_array($section) && $section['parent_section'] != $section['section_id']) {
            $sections[] = $section;
            $this->getSectionPath($section['parent_section'], $language, $sections);
        }
        return array_reverse($sections);
    }

    /**
     * get breadcrumbs array list
     * @param array $parentRoute     
     * @param boolean $removeLastItem
     * @param array $appendBefore append path to breadcrumb
     * @param int $id
     */
    public function getBeadcrumbs($parentRoute, $removeLastItem = false, $appendBefore = array()) {
        $menuId = Yii::app()->request->getParam("menu");
        $controller = AmcWm::app()->getController();
        $currentRoute = $controller->getActionParams();
        $currentRoute[0] = $controller->getRoute();
        $tree = Menus::getMenuItemPath($menuId, $parentRoute, $currentRoute);
        $count = count($tree);
        $breadcrumbs = array();
        if ($count) {
            $breadcrumbs = $this->_generateBeadcrumbs($tree, $removeLastItem, $appendBefore);
        } else {
            $className = self::createClassFromRoute($parentRoute, "Breadcrumbs", "amcwm.components.breadcrumb");
            if ($className) {
                $breadcrumbsDataset = new $className($parentRoute);
                $tree = $breadcrumbsDataset->getPath();
                $breadcrumbs = $this->_generateBeadcrumbs($tree, $removeLastItem, $appendBefore);
            }
        }
        return $breadcrumbs;
    }

    /**
     * Generate breadcrumbs data
     * @param array $tree
     * @param boolean $removeLastItem
     * @param array $appendBefore append path to breadcrumb
     * @access private
     * @return array
     */
    private function _generateBeadcrumbs($tree, $removeLastItem, $appendBefore) {
        $count = count($tree);
        $breadcrumbs = $appendBefore;
        $module = Yii::app()->request->getParam('module');
        if ($count) {
            if ($removeLastItem) {
                $count--;
            }
            for ($i = 0; $i < $count; $i++) {
                if (isset($tree[$i]['url']) && $tree[$i]['url']) {
                    if ($module && !isset($parentRoute['module'])) {
                        $tree[$i]['module'] = $module;
                    }
                    $breadcrumbs[$tree[$i]['label']] = $tree[$i]['url'];
                } else {
                    $breadcrumbs[] = $tree[$i]['label'];
                }
            }
        }
        return $breadcrumbs;
    }

    /**
     * Create class name from the given $routeUrl
     * @param array $routeUrl
     * @param string $postFix
     * @param string $classDirectory
     * @return string
     * @access public
     */
    static public function createClassFromRoute($routeUrl, $postFix, $classDirectory = "amcwm.components") {
        $className = null;
        if (is_array($routeUrl) && count($routeUrl)) {
            $route = str_replace(array("/default", "/index"), "", trim($routeUrl[0], "/"));
            $id = null;
            while (($pos = strpos($route, '/')) !== false) {
                $id = ucfirst(substr($route, 0, $pos));
                $route = (string) substr($route, $pos + 1);
            }
            $className = $id . ucfirst($route) . ucfirst($postFix);
            if (!file_exists(Yii::getPathOfAlias("{$classDirectory}.{$className}") . ".php")) {
                $className = null;
            }
        }
        return $className;
    }

    /**
     * Get current forward module param
     * @static
     * @return string
     * @access public
     */
    public static function getForwardModParam() {
        return Yii::app()->request->getParam("module");
    }

    /**
     * isCurrentRoute method to check if 
     * the current url is equal to the givin route 
     * and its givin params.
     * $param is the specific parameter to post
     * @param string $route
     * @param array $params
     * @param string $param
     * @return boolean
     */
    public function isCurrentRoute($route, $params = array(), $param = 'id') {
        $isActive = false;
        $controller = AmcWm::app()->getController();
        $currentRoute = $controller->getActionParams();
        $currentRoute[0] = $controller->getRoute();
        if (trim($currentRoute[0], '/') == trim($route, '/') && isset($params[$param]) && isset($currentRoute[$param]) && ($params[$param] == $currentRoute[$param]))
            $isActive = true;

        return $isActive;
    }

    /**
     * Get menus setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings($module) {
        if (self::$_settings == null) {
            self::$_settings = new Settings($module, false);
        }
        return self::$_settings;
    }

    /**
     * get Page Image, used in the widget top image
     * @param string $module, the module name 
     * @param string $sectionImg, the section default image
     * @param string $defaultImg, the default image assined to the page
     * @return string
     */
    public function getPageImage($module = null, $currentImage = null, $sectionImg = null, $defaultImg = null, $pageRoute = null) {
        /**
         * 1- check for the menu image.
         * 2- check for the article image.
         * 3- check for the section image.
         * 4- check for the default image.
         */
//        if (file_exists(AmcWm::app()->basePath . "/../images/front/{$module}Image.png")) {
//            $defaultImg = AmcWm::app()->baseUrl . "/images/front/{$module}Image.png";
//        }
        if ($module) {
            $settings = Settings::getModuleSettings($module);
            if ($settings && isset($settings['options']['default']['widgetImage']) && $settings['options']['default']['widgetImage']) {
                $settings['options']['default']['widgetImage'] = trim($settings['options']['default']['widgetImage'], "/");
                $defaultImg = AmcWm::app()->baseUrl . "/{$settings['options']['default']['widgetImage']}";
            }
        }
        $menuId = Yii::app()->request->getParam("menu");
        $menu = null;
        if ($menuId) {
            $menu = Menus::getMenuItem($menuId);
        } else {
            $controller = AmcWm::app()->getController();
            if ($pageRoute) {
                $currentRoute[0] = $pageRoute;
            } else {
                $currentRoute[0] = $controller->getRoute();
            }

            if (isset($currentRoute)) {
                $menuId = Menus::getMenuIdFromRoute($currentRoute);
                if (isset($menuId['id']) && $menuId['id'] != 0) {
                    $menu = Menus::getMenuItem("{$menuId['id']}-{$menuId['item']}");
                }
            }
        }

        if (isset($menu['pageImg'])) {
            $defaultImg = $menu['pageImg'];
        } else {
            if (is_array($currentImage)) {
                if (isset($currentImage[1])) {
                    $defaultImg = $currentImage[1];
                } else if (isset($currentImage[0])) {
                    $defaultImg = $currentImage[0];
                } elseif ($sectionImg) {
                    $defaultImg = $sectionImg;
                }
            } else {
                if ($currentImage) {
                    $defaultImg = $currentImage;
                } elseif ($sectionImg) {
                    $defaultImg = $sectionImg;
                }
            }
        }
        return $defaultImg;
    }

    /**
     * Get content owner from breadcrumbs
     * @param array $breadcrumbs
     * @return string
     */
    public function getBreadcrumbsContentParentLabel($breadcrumbs) {
        $count = count($breadcrumbs);
        if ($count >= 2) {
            $index = 0;
            foreach ($breadcrumbs as $key => $value) {
                if ($index == $count - 2) {
                    $label = (is_array($value)) ? $key : $value;
                    break;
                }
                $index ++;
            }
            return $label;
        }
    }

    /**
     * Function to correct thousands & floating separators according to parameters.
     * @param type $number  Number to perform the replacement
     * @param type $thousandSeparator  Thousand separator character
     * @param type $floatingSeparator  Floating separator character
     * @return type integer
     */
    public function correctNumber($number, $thousandSeparator, $floatingSeparator = null) {
        return str_replace(array($thousandSeparator, $floatingSeparator), array("", "."), $number);
    }

    /**
     * Get Action ID
     * @param type $module  Module Name
     * @param type $controller  Controller Name
     * @param type $action  Action Name
     * @param type $isBackend  Is Backend portal defaults to true
     * @return type
     */
    public function getActionId($module, $controller, $action = 'create', $isBackend = true) {
        $parentId = $isBackend ? "=1" : "is null";
        $query = 'SELECT action_id'
                . ' FROM actions a'
                . ' INNER JOIN controllers c on a.controller_id = c.controller_id'
                . ' INNER JOIN modules m on c.module_id = m.module_id'
                . ' WHERE module = "' . $module . '" '
                . ' AND controller = "' . $controller . '" '
                . ' AND action = "' . $action . '"'
                . ' AND parent_module ' . $parentId;
        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    /**
     * Update article / video / breaking news rating
     * @param integer $rating
     * @param string $table
     * @param string $tableKey
     * @param int $recordId
     * @access public
     * @return void
     */
    public function setRatingForTable($score, $table, $tableKey, $recordId) {
        $cookieName = "{$table}_{$recordId}_rate";
        if (!isset(Yii::app()->request->cookies[$cookieName])) {
            $cookie = new CHttpCookie($cookieName, $recordId);
            $cookie->expire = time() + 3600;
            $cookie->httpOnly = true;
            Yii::app()->request->cookies[$cookieName] = $cookie;
            if ($score > 5) {
                $score = 5;
            }
            $query = sprintf("update $table set votes_rate = (votes_rate * votes + %f) / (votes + 1), votes = votes + 1 where {$tableKey} = %d", $score, $recordId);
            Yii::app()->db->createCommand($query)->execute();
        }
    }

    /**
     * Get rating
     * @param float $rating
     * @access public
     * @return array
     */
    public function getRatingStarts($rating) {
        $myRating = floor($rating);
        $fractions = $rating - $myRating;
        $half = false;
        if ($fractions >= 0.25 && $fractions < 0.75) {
            $half = true;
        } elseif ($fractions >= 0.75) {
            $myRating = ceil($rating);
            $half = false;
        }
        $starts['rating'] = $myRating;
        $starts['half'] = $half;
        return $starts;
    }

    /**
     * 
     * Verify a password against a hash.
     * @param string $password The password to verify. If password is empty or not a string, method will return false.
     * @param string $hash The hash to verify the password against.
     * @param string $username the current username
     * @return bool True if the password matches the hash.
     */
    public function verifyPassword($password, $hash, $username = null){

        if(strlen($hash) == 32){
            if(CPasswordHelper::same(md5($password), $hash)){
                if($username){
                    AmcWm::app()->db->createCommand()->update("users", array("passwd" => CPasswordHelper::hashPassword($password)), "username=:username", array(":username"=>$username));
}
                return true;
            }            
        }
        else{
            return CPasswordHelper::verifyPassword($password, $hash);                
        }
        return false;
        
    }
            
            
}
