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
class UsedAttributesList extends Dataset {

    /**
     * current module id
     * @var array
     */
    private $_moduleId;

    /**
     * current item primary id value
     * @var integer
     */
    private $_id;

    /**
     * 
     * @var string current table
     */
    private $_table;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param string $table
     * @param integer $id
     * @param integer $attributeType
     * @param integer $systemAttribute -1 get both , otherwise get the attribute where its value equal the given $systemAttribute
     */
    public function __construct($table, $id, $attributeType = 0, $systemAttribute = AttributesList::NORMAL_SYSTEM_ATTRIBUTE) {
        $usedModules = AttributesList::getSettings()->settings['usedModules'];
        $moduleData = null;
        $table = Html::escapeString($table);
        foreach ($usedModules as $usedModule) {
            foreach ($usedModule['tables'] as $usedTable) {
                if ($usedTable['name'] == $table) {
                    $this->_table = $usedTable;
                    $moduleData = amcwm::app()->acl->getModule($usedModule['name']);
                    break;
                }
            }
        }
        if ($moduleData) {
            if ($systemAttribute == AttributesList::NORMAL_SYSTEM_ATTRIBUTE) {
                $this->addWhere("(t.is_system in  (" . AttributesList::SYSTEM_ATTRIBUTE . ", " . AttributesList::NORMAL_ATTRIBUTE . "))");
            } else if ($systemAttribute != AttributesList::ALL_ATTRIBUTE) {
                $this->addWhere("t.is_system = " . (int) $systemAttribute);
            }
            if ($attributeType) {
                $this->addWhere("t.attribute_type = " . (int) $attributeType);
            }
            $this->_id = $id;
            $this->_moduleId = (int) $moduleData['id'];
            $this->recordIdAsKey = true;
        }
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
            $this->addOrder("t.attribute_type asc , a.attribute_sort");
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
        $siteLanguage = Controller::getContentLanguage();
        $orders = $this->generateOrders();

        $wheres = $this->generateWheres();
        $limit = ($this->limit) ? "limit {$this->fromRecord} , {$this->limit}" : "";
        $cols = $this->generateColumns();
        $this->query = sprintf("SELECT 
            t.attribute_id
            ,t.attribute_type
            ,t.is_new_type
            ,t.is_system
            ,tt.label
            ,a.{$this->_table['primaryKey']} as pk 
            ,v.value
            ,vt.value translate_value {$cols}
            FROM  system_attributes t            
            inner join system_attributes_translation tt on t.attribute_id = tt.attribute_id and tt.content_lang = %s            
            inner join {$this->_table['name']} a on t.attribute_id = a.attribute_id
            left join {$this->_table['name']}_value v on a.{$this->_table['primaryKey']} = v.{$this->_table['primaryKey']}
            left join {$this->_table['name']}_value_translation vt on a.{$this->_table['primaryKey']} = vt.{$this->_table['primaryKey']} and vt.content_lang = %s            
            {$this->joins}
            where module_id = %d and {$this->_table['foreignKey']} = %d
            $wheres
            $orders
            {$limit}
            ", Yii::app()->db->quoteValue($siteLanguage), Yii::app()->db->quoteValue($siteLanguage), $this->_moduleId, $this->_id);
        $dataset = Yii::app()->db->createCommand($this->query)->queryAll();
        $settings = DirectoryListData::getSettings()->settings;
//        print_r($dataset);


        $index = 0;
        foreach ($dataset as $row) {
            $attributeType = AttributesList::getAttributeType($row["attribute_type"]);
            $attributeType['systemOnly'] = $attributeType['systemOnly'] || $row['is_system'] > AttributesList::SYSTEM_ATTRIBUTE;
            $attributeType['isSystem'] = $row['is_system'];
            $attributeData = array();
            $attributeData['id'] = $row["pk"];
            if ($attributeType['translate']) {
                $attributeData['value'] = $row["translate_value"];
            } else {
                $attributeData['value'] = $row["value"];
            }

            $attributeName = null;
            foreach ($settings['extraAttributes']['attributesMaps'] as $map) {
                if (isset($map[$attributeType['name']])) {
                    $attributeName = $map[$attributeType['name']];
                    break;
                }
            }
            if (!$attributeName) {
                $attributeName = $attributeType['name'];
            }
            if (($row["is_new_type"])) {
                if (!$attributeType['systemOnly']) {
                    $this->items[$attributeName]['inheritedAttributes'][$row["is_new_type"]] = $row["is_new_type"];
                }
                $attributeName = $row["is_new_type"];
                $label = $row["label"];
            } else {
                $label = null;
            }
            $attributeData['systemAttrbuiteId'] = $row['attribute_id'];
            $this->items[$attributeName]['id'] = $row['attribute_id'];
            $this->items[$attributeName]['name'] = $attributeName;
            $this->items[$attributeName]['translate'] = $attributeType['translate'];
            $this->items[$attributeName]['isExtendable'] = !$attributeType['systemOnly'];
            $this->items[$attributeName]['field'] = $attributeType['name'];
            $this->items[$attributeName]['systemOnly'] = $attributeType['systemOnly'];
            $this->items[$attributeName]['struct'] = $attributeType;
            $this->items[$attributeName]['label'] = $label;
            $this->items[$attributeName]['data'][($attributeType['systemOnly']) ? $attributeName : $index] = $attributeData;
            $index++;
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}