<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ModelsAttributesList, get attributes used in any extra attribute table 
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ModelsAttributesList extends Dataset {

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
     * current model
     * @var ActiveRecord
     */
    private $_model;

    /**
     * 
     * @var string current table
     */
    private $_table;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param ActiveRecord $model
     * @param string $table
     * @param integer $id
     * @param integer $attributeType
     * @param integer $systemAttribute -1 get both , otherwise get the attribute where its value equal the given $systemAttribute
     */
    public function __construct($model, $table, $id, $attributeType = 0, $systemAttribute = AttributesList::NORMAL_SYSTEM_ATTRIBUTE) {
        $usedModules = AttributesList::getSettings()->settings['usedModules'];
        $moduleData = null;
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
            $this->_model = $model;
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
        $cols = $this->generateColumns();
        $limit = ($this->limit) ? "limit {$this->fromRecord} , {$this->limit}" : "";
        $countData = array('content_lang' => $siteLanguage, 'count' => 0);
        if ($this->_model instanceof ChildTranslatedActiveRecord) {
            $countQuery = sprintf("SELECT content_lang , count(v.{$this->_table['primaryKey']}) `count`
            FROM  system_attributes t            
            inner join {$this->_table['name']} a on t.attribute_id = a.attribute_id
            inner join {$this->_table['name']}_value_translation v on a.{$this->_table['primaryKey']} = v.{$this->_table['primaryKey']}
            {$this->joins}
            where module_id = %d and {$this->_table['foreignKey']} = %d
            $wheres
            $orders
            {$limit}
            ", $this->_moduleId, $this->_id);
            $countData = AmcWm::app()->db->createCommand($countQuery)->queryRow();
            if (!$countData) {
                $countData = array('content_lang' => $siteLanguage, 'count' => 0);
            }
            $this->addJoin(sprintf(
                            "inner join {$this->_table['name']}_value_translation v on a.{$this->_table['primaryKey']} = v.{$this->_table['primaryKey']} and v.content_lang = %s"
                            , Yii::app()->db->quoteValue($countData['content_lang']))
            );
        } else {
            $this->addJoin("inner join {$this->_table['name']}_value v on a.{$this->_table['primaryKey']} = v.{$this->_table['primaryKey']}");
        }

        $this->query = sprintf("SELECT 
            t.attribute_id
            ,t.attribute_type
            ,t.is_new_type
            ,t.is_system
            ,tt.label
            ,a.attribute_sort
            ,a.{$this->_table['primaryKey']} as pk 
            ,v.value {$cols}
            FROM  system_attributes t            
            inner join system_attributes_translation tt on t.attribute_id = tt.attribute_id and tt.content_lang = %s            
            inner join {$this->_table['name']} a on t.attribute_id = a.attribute_id
            {$this->joins}
            where module_id = %d and {$this->_table['foreignKey']} = %d
            $wheres
            $orders
            {$limit}
            ", Yii::app()->db->quoteValue($siteLanguage), $this->_moduleId, $this->_id);
        $dataset = Yii::app()->db->createCommand($this->query)->queryAll();
        $settings = $this->_model->getModuleSettings()->settings;
        $required = $settings['extraAttributes']['required'];
        if (!$countData['count']) {
            $countData['count'] = count($dataset);
        }
        foreach ($dataset as $row) {
            $index = $row['pk'];
            $attributeType = AttributesList::getAttributeType($row["attribute_type"]);
            $attributeType['systemOnly'] = $attributeType['systemOnly'] || $row['is_system'] > AttributesList::SYSTEM_ATTRIBUTE;
            $attributeType['isSystem'] = $row['is_system'];
            if (($row["is_new_type"])) {
                $attributeName = $row["is_new_type"];
                $label = $row["label"];
                if (!$attributeType['systemOnly']) {
                    $this->items[$attributeType['name']]['inheritedAttributes'][$attributeName] = array();
                }
            } else {
                if (isset($settings['extraAttributes']['attributesMaps'][$this->_model->tableName()][$attributeType['name']])) {
                    $attributeName = $settings['extraAttributes']['attributesMaps'][$this->_model->tableName()][$attributeType['name']];
                } else {
                    $attributeName = $attributeType['name'];
                }
                $label = $this->_model->getAttributeLabel($attributeName);
            }
            if ($attributeType['systemOnly']) {
                $index = $attributeName;
                //$attributeType['required'] = true;
            } 
            if (isset($required[$this->_model->tableName()][$attributeName])) {
                $attributeType['required'] = true;
            }             
            else {
                $attributeType['required'] = false;
            }
            $attributeType['table'] = $this->_table;
            $attributeModel = new AttributeModel($attributeType);
            $attributeModel->id = $row["pk"];
            $attributeModel->ownerId = $this->_id;
            $attributeModel->isNew = false;
            if (isset($this->_model->content_lang) && $countData['content_lang'] != $this->_model->content_lang) {
                $queryTrans = sprintf("select value from {$this->_table['name']}_value_translation where {$this->_table['primaryKey']} = {$row["pk"]} 
                    and content_lang = %s", AmcWm::app()->db->quoteValue($this->_model->content_lang));
                $attributeModel->value = AmcWm::app()->db->createCommand($queryTrans)->queryScalar();
                $attributeModel->isNew = ($attributeModel->value) ? false : true;
            } else {
                $attributeModel->value = $row["value"];
            }
            $attributeModel->sort = $row["attribute_sort"];
            $attributeModel->label = $label;
            $attributeModel->name = $attributeName;
            $attributeModel->systemAttrbuiteId = $row['attribute_id'];
            $attributeType['model'] = $this->_model;
            $this->items[$attributeName]['id'] = $row['attribute_id'];
            $this->items[$attributeName]['name'] = $attributeName;
            $this->items[$attributeName]['translate'] = $attributeType['translate'];
            $this->items[$attributeName]['isExtendable'] = !$attributeType['systemOnly'];
            $this->items[$attributeName]['field'] = $attributeType['name'];
            $this->items[$attributeName]['systemOnly'] = $attributeType['systemOnly'];
            $this->items[$attributeName]['struct'] = $attributeType;
            $this->items[$attributeName]['label'] = $label;
            if ($attributeType['dataType'] == "files") {
                if (!isset($this->items[$attributeName]['data'][$index])) {
                    $attributeFilesModel = $attributeModel;                    
                }
                $attributeFilesModel->data[ $row["pk"]] = $attributeModel;
                $this->items[$attributeName]['data'][$index] = $attributeFilesModel;
            }
            else{
                $this->items[$attributeName]['data'][$index] = $attributeModel;
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}