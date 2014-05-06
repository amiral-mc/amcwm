<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ParamsTaskManager class, run controller task
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ParamsTaskManager {

    /**
     *  Equal true if the sister class generate sisters data 
     * @var mixed     
     */
    private $_success = false;

    /**
     * Class used for controlling the task
     * @var ParamsTask
     */
    private $_class = null;

    /**
     * Constructor, this SistersRelatedManager
     * @param string $type
     * @param integer $limit, The numbers of record to fetch
     * @access private
     * @throws Error Error if you call the constructor directly
     */
    public function __construct($searchFor = null, $params = array(), $rowSelected = null, $limit = 10) {
        if (count($params)) {
            $className = self::createClassFromRoute($params);
            $this->_pageSize = $limit;
            if (file_exists(Yii::getPathOfAlias("amcwm.components.menu.params.{$className}") . ".php")) {
                $this->_class = new $className($searchFor, $params, $limit);
                $this->_class->setSelectedRow($rowSelected);
                $this->_class->generate(Yii::app()->request->getParam("page", 1));
                $this->_success = $this->_class->hasItems();
            }
        }
    }

    /**
     * get the numbers of items to be fetch
     * @access public 
     * @return integer
     */
    public function getPageSize() {
        return $this->_pageSize;
    }

    /**
     * @return PagingDatasetProvider
     */
    public function getDataProvider() {
        return $this->_class->getDataProvider();
    }

    /**
     * function to retrun any extra columns for each task if any
     * @param array $cols
     * @return array
     */
    public function setGridColumns($cols = array()) {
        $this->_class->setGridColumns($cols);
    }
    
    /**
     * function to retrun any extra columns for each task if any
     * @param array $cols
     * @return array
     */
    public function getGridColumns() {
        return $this->_class->getGridColumns();
    }

    /**
     * Return true if the sister class generate sisters data 
     * @access public
     * @return boolean
     */
    public function hasItems() {
        return $this->_success;
    }

    /**
     * Get the item title
     * @access public
     * @return string
     */
    static public function getTitle($params, $defaultValue) {
        $className = self::createClassFromRoute($params);
        if ($className) {
            $class = new $className($defaultValue, $params);
            return $class->getTitle();
        }
    }

    static public function validateParams($params, $paramData) {
        $valid = true;
        foreach ($params as $param) {
            $className = self::createClassFromRoute($param);
            if ($className) {
                $class = new $className('', $params);
                $valid &= $class->validate($paramData);
            }
        }
        return $valid;
    }

    /**
     * Create class name from the given $routeUrl
     * @param array $routeUrl
     * @param string $postFix
     * @param string $classDirectory
     * @return string
     * @access public
     */
    static public function createClassFromRoute($paramsArray, $postFix = "ParamTask", $classDirectory = "amcwm.components.menu.params") {
        $moduleName = $paramsArray['module'];
        $componentRoute = $paramsArray['route'];
        $className = null;

        $route = str_replace(array("/default",), "", trim($componentRoute, "/"));
        $id = null;
        while (($pos = strpos($route, '/')) !== false) {
            $id = ucfirst(substr($route, 0, $pos));
            $route = (string) substr($route, $pos + 1);
        }
        $className = ucfirst($moduleName) . ucfirst($route) . ucfirst($postFix);
        if (!file_exists(Yii::getPathOfAlias("{$classDirectory}.{$className}") . ".php")) {
            $className = null;
        }

        return $className;
    }

    static function getParamData($param_id, $component_id) {
        $query = sprintf(
                "select mp.*, mcpt.*, c.route, m.module, m.module_id
                from menus_params mp
                inner join moduls_components_params_translation mcpt on mcpt.param_id = mp.param_id
                inner join modules_components c on c.component_id = mcpt.component_id
                inner join modules m on m.module_id = c.module_id
                where mcpt.component_id = %d
                and mp.param_id = %d
                and mcpt.content_lang = %s
                ", $component_id, $param_id, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        return Yii::app()->db->createCommand($query)->queryAll();
    }

}