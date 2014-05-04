<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * AttributesList, get attributes used in any extra attribute table 
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttributesList extends Dataset {

    const NORMAL_ATTRIBUTE = 0;
    const SYSTEM_ATTRIBUTE = 1;
    const ALL_ATTRIBUTE = -1;
    const NORMAL_SYSTEM_ATTRIBUTE = -2;
    const CUSTOM_SYSTEM_ATTRIBUTE = 2;

    /**
     * current module id
     * @var array
     */
    private $_moduleId;

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param string $module
     * @param integer $systemAttribute -1 get both , otherwise get the attribute where its value equal the given $systemAttribute
     */
    public function __construct($module, $systemAttribute = self::NORMAL_SYSTEM_ATTRIBUTE) {
        $usedModules = self::getSettings()->settings['usedModules'];
        $moduleData = null;
        foreach ($usedModules as $usedModule) {
            if ($usedModule['name'] == $module) {
                $moduleData = amcwm::app()->acl->getModule($module);
                break;
            }
        }
        if ($moduleData) {
            if ($systemAttribute == AttributesList::NORMAL_SYSTEM_ATTRIBUTE) {
                $this->addWhere("(t.is_system in  (" . AttributesList::SYSTEM_ATTRIBUTE . ", " . AttributesList::NORMAL_ATTRIBUTE . "))");
            } else if ($systemAttribute != AttributesList::ALL_ATTRIBUTE) {
                $this->addWhere("t.is_system = " . (int) $systemAttribute);
            }
            $this->_moduleId = (int) $moduleData['id'];
            $this->recordIdAsKey = true;
        }
    }

    /**
     * generate attributes types list
     * @param boolean $exculedSystemAttributes
     * @return array
     */
    static public function getAttributesTypesList($exculedSystemAttributes = true) {
        $attributesTypes = self::getSettings()->settings['attributesTypes'];
        static $attributesTyppesList = array();
        if (!$attributesTyppesList) {
            foreach ($attributesTypes as $attributeId => $attribute) {
                if (!$attribute['systemOnly'] || !$exculedSystemAttributes) {
                    $attributesTyppesList[$attributeId] = $attribute['name'];
                }
            }
        }
        return $attributesTyppesList;
    }

    /**
     * generate attribute type data
     * @return array
     */
    static public function getAttributeType($id) {
        $attributesTypes = self::getSettings()->settings['attributesTypes'];
        if (isset($attributesTypes[$id])) {
            return $attributesTypes[$id];
        }
    }

    /**
     * Get Setting setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("settings", false);
        }
        return self::$_settings;
    }

    /**
     *
     * Generate lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
            $this->addOrder("t.attribute_type asc");
        }
        parent::generate();
    }

    /**
     * Set the attachment items
     * @todo explain the query
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $limit = ($this->limit) ? "limit {$this->fromRecord} , {$this->limit}" : "";
        $this->query = sprintf("SELECT 
            t.attribute_id
            ,t.attribute_type
            ,t.is_new_type
            ,t.is_system
            ,tt.label
            FROM  system_attributes t            
            inner join system_attributes_translation tt on t.attribute_id = tt.attribute_id and tt.content_lang = %s            
            {$this->joins}
            where module_id = %d
            $wheres
            $orders
            {$limit}
            ", Yii::app()->db->quoteValue($siteLanguage), $this->_moduleId);
        $dataset = Yii::app()->db->createCommand($this->query)->queryAll();
        $index = -1;
        foreach ($dataset As $row) {
            $index = $row['attribute_id'];            
            $attributeType = self::getAttributeType($row["attribute_type"]);
            $attributeType['systemOnly'] = $attributeType['systemOnly'] || $row['is_system'] > AttributesList::SYSTEM_ATTRIBUTE;
            if (($row["is_new_type"])) {
                $attributeName = $row["is_new_type"];
                $label = $row["label"];
                if (!$attributeType['systemOnly']) {
                    $this->items[$attributeType['name']]['inheritedAttributes'][$attributeName] = array();                    
                }
                $this->items[$attributeType['name']]['field'] = $attributeType['name'];
                $this->items[$attributeType['name']]['isExtendable'] = !$attributeType['systemOnly'];
                $this->items[$attributeType['name']]['systemOnly'] = $attributeType['systemOnly'];
                $this->items[$attributeType['name']]['struct'] = $attributeType;
                $this->items[$attributeType['name']]['translate'] = $attributeType['translate'];
            } else {
                    $attributeName = $attributeType['name'];
                    $label = $row["label"];
            }
            $this->items[$attributeName]['id'] =  $row['attribute_id'];
            $this->items[$attributeName]['name'] = $attributeName;            
            $this->items[$attributeName]['translate'] = $attributeType['translate'];
            $this->items[$attributeName]['isExtendable'] = !$attributeType['systemOnly'];
            $this->items[$attributeName]['field'] = $attributeType['name'];
            $this->items[$attributeName]['systemOnly'] = $attributeType['systemOnly'];
            $this->items[$attributeName]['struct'] = $attributeType;
            $this->items[$attributeName]['label'] = $label;   
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}