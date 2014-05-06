<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AttributesBehaviors adding behaviors to any model to allow extendable attributes
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ExtendableAttributesBehaviors extends CBehavior {

    /**
     * Current owner ID
     * @var integer 
     */
    private $_ownerId;

    /**
     * 
     * @var array extend attributes
     */
    private $_extendAttributes = array();

    /**
     *
     * @var array current extend attributes 
     */
    private $_oldExtendAttributes = array();

    /**
     *
     * @var array current table settings
     */
    private $_table = array();

    /**
     * Attaches the behavior object to the component.
     * The default implementation will set the {@link owner} property
     * and attach event handlers as declared in {@link events}.
     * This method will also set {@link enabled} to true.
     * Make sure you've declared handler as public and call the parent implementation if you override this method.
     * @param CComponent $owner the component that this behavior is to be attached to.
     */
    public function attach($owner) {
        $ownerParent = null;
        if ($owner instanceof ChildTranslatedActiveRecord) {
            $primaryKeys = array_keys($owner->primaryKey);
            $this->_ownerId = $owner->primaryKey[$primaryKeys[0]];
            $ownerParent = $owner->getParentContent();
        } else if ($owner instanceof ActiveRecord) {
            $this->_ownerId = $owner->primaryKey;
        }
        $settings = $owner->getModuleSettings()->settings;
        if (isset($settings['extraAttributes']['table']) && isset($settings['extraAttributes']['enable']) && $settings['extraAttributes']['enable']) {
            parent::attach($owner);
            $settings = $this->owner->getModuleSettings()->settings;
            $usedModules = AttributesList::getSettings()->settings['usedModules'];
            foreach ($usedModules as $usedModule) {
                foreach ($usedModule['tables'] as $usedTable) {
                    if ($usedTable['name'] == $settings['extraAttributes']['table']) {
                        $this->_table = $usedTable;
                        break;
                    }
                }
            }
            $list = new ModelsAttributesList($owner, $this->_ownerId, 0, AttributesList::ALL_ATTRIBUTE);
            $list->generate();
            $itemAttributes = $list->getItems();
            $list = new AttributesList($settings['name'], AttributesList::ALL_ATTRIBUTE);
            $list->generate();
            $attributesConfig = $list->getItems();
//            print_r($attributesConfig);
//            die();
//            print_r($itemAttributes);
//            die();
            foreach ($attributesConfig as $attibuteConfigName => $attibute) {
                $attributeName = $attibuteConfigName;
                if (isset($settings['extraAttributes']['attributesMaps'][$owner->tableName()][$attibuteConfigName])) {
                    $attributeName = $settings['extraAttributes']['attributesMaps'][$owner->tableName()][$attibuteConfigName];
                }
                if (isset($itemAttributes[$attributeName])) {
                    $this->_extendAttributes['attributes'][$attributeName] = $attibute;
                    if (isset($itemAttributes[$attributeName]['data'])) {
                        $this->_extendAttributes['attributes'][$attributeName]['data'] = $itemAttributes[$attributeName]['data'];
                    } else {
                        $this->_extendAttributes['attributes'][$attributeName]['data'] = array();
                    }
                } else {
                    $this->_extendAttributes['attributes'][$attributeName] = $attibute;
                    $this->_extendAttributes['attributes'][$attributeName]['data'] = array();
                }
                if ($owner->hasAttribute($attributeName)) {
                    $this->_extendAttributes['attributes'][$attributeName]['label'] = $owner->getAttributeLabel($attributeName);
                }
                if (isset($attibute['id'])) {
                    $this->_extendAttributes['listData'][$attibute['field']][$attibute['id']] = $this->_extendAttributes['attributes'][$attributeName]['label'];
                    $this->_extendAttributes['listNames'][$attibute['field']][$attibute['id']] = $attributeName;
                }
                if ($this->_extendAttributes['attributes'][$attributeName]['isExtendable']) {
                    $this->_extendAttributes['attributes'][$attributeName]['struct']['required'] = true;
                } else if (isset($settings['extraAttributes']['required'][$attributeName])) {
                    $this->_extendAttributes['attributes'][$attributeName]['struct']['required'] = true;
                } else {
                    $this->_extendAttributes['attributes'][$attributeName]['struct']['required'] = false;
                }
            }
            $this->_oldExtendAttributes = $this->_extendAttributes;
            $owner->onValidateOthers = array($this, "validateExtendable");
            $owner->onAfterSave = array($this, "saveExtendable");
            $owner->onAfterDelete = array($this, "deleteExtendable");
            if ($ownerParent) {
                $ownerParent->attachBehavior("extendableBehaviors", new ExtendableAttributesBehaviors());
            }
        }
    }

    /**
     * Get AttributeModel list
     * @param boolean $isExtendable
     * @return array
     */
    public function getExtendedModels($isExtendable = false) {
        $models = array();
        foreach ($this->_extendAttributes['attributes'] as $attribute) {
            if (isset($attribute['data']) && $attribute['isExtendable'] == $isExtendable) {
                $data = $attribute['data'];
                foreach ($data as $model) {
                    $models[] = $model;
                }
            }
        }
        return $models;
    }

    /**
     * Get extend attributes
     * @return array
     */
    public function getExtendedAttributes() {
        if (isset($this->_extendAttributes['attributes'])) {
            return $this->_extendAttributes['attributes'];
        }
    }

    /**
     * Get extend label for the given $attribute
     * @return array
     */
    public function getExtendedLabel($attribute) {
        if (isset($this->_extendAttributes['attributes'][$attribute]['label'])) {
            return $this->_extendAttributes['attributes'][$attribute]['label'];
        }
    }

    /**
     * Get extend model for the given $attribute
     * @return array
     */
    public function getExtendedModel($attribute) {
        $model = null;
        if ($this->owner->hasAttribute($attribute)) {
            $model = $this->owner;
        } else {
            $attributeData = $this->getExtendedAttribute($attribute);
            if ($attributeData) {
                if (!isset($attributeData['data'][$attribute])) {
                    $attributeData['struct']['table'] = $this->_table;
                    $attributeData['struct']['model'] = $this->owner;
                    $model = new AttributeModel($attributeData['struct']);
                } else {
                    $model = $attributeData['data'][$attribute];
                }
                $model->ownerId = $this->_ownerId;
            }
        }
        return $model;
    }

    /**
     * get extend attribute values needed for CDetailView for the given $attribute
     * @param string $attribute
     * @return array
     */
    public function getExtendedAttributeViewValues($attribute) {
        $values = array();
        $viewAttributes = array();
        if ($this->owner->hasAttribute($attribute)) {
            $viewAttribute = array(
                'name' => $attribute,
                'label' => $this->owner->getAttributeLabel($attribute),
                'type' => 'html',
                'value' => null,
            );
            $values[] = $this->owner->$attribute;
            if (isset($this->_extendAttributes['attributes'][$attribute]['data'])) {
                foreach ($this->_extendAttributes['attributes'][$attribute]['data'] as $attributeData) {
                    $values[] = $attributeData->value;
                }
                $viewAttribute['value'] = implode("<br />", $values);
            }
            $viewAttributes[] = $viewAttribute;
            if (isset($this->_extendAttributes['attributes'][$attribute]['inheritedAttributes'])) {
                foreach ($this->_extendAttributes['attributes'][$attribute]['inheritedAttributes'] as $inheritedAttribute => $inheritedAttributeArray) {
                    $values = array();
                    if (isset($this->_extendAttributes['attributes'][$inheritedAttribute]['data'])) {
                        $viewAttribute = array(
                            'name' => $inheritedAttribute,
                            'label' => $this->_extendAttributes['attributes'][$inheritedAttribute]['label'],
                            'type' => 'html',
                            'value' => null,
                        );
                        foreach ($this->_extendAttributes['attributes'][$inheritedAttribute]['data'] as $attributeData) {
                            $values[] = $attributeData->value;
                        }
                        $viewAttribute['value'] = implode("<br />", $values);
                        $viewAttributes[] = $viewAttribute;
                    }
                }
            }
        } else {
            if (isset($this->_extendAttributes['attributes'][$attribute])) {
                $viewAttribute = array(
                    'name' => $attribute,
                    'label' => $this->_extendAttributes['attributes'][$attribute]['label'],
                    'type' => 'html',
                    'value' => null,
                );
                if (isset($this->_extendAttributes['attributes'][$attribute]['data'][$attribute])) {
                    $viewAttribute['value'] = $this->_extendAttributes['attributes'][$attribute]['data'][$attribute]->value;
                }
            }
            $viewAttributes[] = $viewAttribute;
        }

        return $viewAttributes;
    }

    /**
     * set extend attribute data for the given $attribute
     * @return array
     */
    public function setExtendedAttributeData($attribute, $data) {
        if (isset($this->_extendAttributes['attributes'][$attribute]['data'])) {
            if (!isset($data['id'])) {
                $data['id'] = "new_" . (count($this->_extendAttributes['attributes'][$attribute]['data']) + 1);
            }
            if (isset($this->_extendAttributes['attributes'][$attribute]['data'][$data['id']])) {
                $model = $this->_extendAttributes['attributes'][$attribute]['data'][$data['id']];
            } else {
                $this->_extendAttributes['attributes'][$attribute]['struct']['table'] = $this->_table;
                $this->_extendAttributes['attributes'][$attribute]['struct']['model'] = $this->owner;
                $model = new AttributeModel($this->_extendAttributes['attributes'][$attribute]['struct']);
            }
            $model->ownerId = $this->_ownerId;
            $model->id = $data['id'];

            if (isset($data['systemAttrbuiteId'])) {
                $model->systemAttrbuiteId = $data['systemAttrbuiteId'];
            } else {
                $model->systemAttrbuiteId = $this->_extendAttributes['attributes'][$attribute]['id'];
            }
            if (isset($data['value'])) {
                $model->value = $data['value'];
            }
            if (isset($data['sort'])) {
                $model->sort = $data['sort'];
            }
            if ($this->_extendAttributes['attributes'][$attribute]['isExtendable']) {
                $this->_extendAttributes['attributes'][$attribute]['data'][$data['id']] = $model;
            } else {
                if (!isset($this->_extendAttributes['attributes'][$attribute]['data'][$attribute])) {
                    $this->_extendAttributes['attributes'][$attribute]['data'][$attribute] = $model;
                }
                if ($this->_extendAttributes['attributes'][$attribute]['struct']['dataType'] == "files") {
                    $this->_extendAttributes['attributes'][$attribute]['data'][$attribute]->data[$data['id']] = $model;
                }
            }
        }
    }

    /**
     * Get extend attribute for the given $attribute
     * @return array
     */
    public function getExtendedAttribute($attribute) {
        if (isset($this->_extendAttributes['attributes'][$attribute])) {
            if (isset($this->_extendAttributes['attributes'][$attribute]['inheritedAttributes'])) {
                foreach ($this->_extendAttributes['attributes'][$attribute]['inheritedAttributes'] as $inheritedAttribute => $inheritedAttributeData) {
                    $this->_extendAttributes['attributes'][$attribute]['inheritedAttributes'][$inheritedAttribute] = $this->getExtendedAttribute($inheritedAttribute);
                }
            }
            $this->_extendAttributes['attributes'][$attribute]['listData'] = $this->_oldExtendAttributes['listData'][$this->_oldExtendAttributes['attributes'][$attribute]['field']];
            $this->_extendAttributes['attributes'][$attribute]['listNames'] = $this->_oldExtendAttributes['listNames'][$this->_oldExtendAttributes['attributes'][$attribute]['field']];
            return $this->_extendAttributes['attributes'][$attribute];
        }
    }

    /**
     * Save extendable
     * @todo instead of sql statement use AttributeModel.save() 
     * @param CModelEvent $event
     */
    public function deleteExtendable($event) {
        
    }

    /**
     * Save extendable
     * @todo instead of sql statement use AttributeModel.save() 
     * @param CModelEvent $event
     */
    public function saveExtendable($event) {
        $ownerParam = AttributeModel::getParam($this->owner->getClassName());
        $siteLanguage = Controller::getCurrentLanguage();
        if ($ownerParam) {
            $isTranslated = false;
            if ($this->owner instanceof ChildTranslatedActiveRecord) {
                $isTranslated = Controller::getCurrentLanguage() != $this->owner->content_lang;
                $siteLanguage = $this->owner->content_lang;
                $primaryKeys = array_keys($this->owner->primaryKey);
                $this->_ownerId = $this->owner->primaryKey[$primaryKeys[0]];
            } else if ($this->owner instanceof ActiveRecord) {
                $this->_ownerId = $this->owner->primaryKey;
            }
            if ($this->_table) {
                $foreignKey = Html::escapeString($this->_table['foreignKey']);
                $primaryKey = Html::escapeString($this->_table['primaryKey']);
                $insertQueryTemplate = "insert into {$this->_table['name']} ({$foreignKey}, attribute_id, attribute_sort) values(%d, %d, %d)";
                $insertQueryDataTemplate = "insert into {$this->_table['name']}_value ({$primaryKey}, value) values(%d, %s)";
                $insertQueryLangDataTemplate = "insert into {$this->_table['name']}_value_translation ({$primaryKey}, value, content_lang) values(%d, %s , %s)";
                $updateQueryTemplate = "update {$this->_table['name']} set attribute_sort = %d %s where {$primaryKey} = %d";
                $updateQueryDataTemplate = "update {$this->_table['name']}_value set value = %s where {$primaryKey} = %d";
                $updateQueryLangDataTemplate = "update {$this->_table['name']}_value_translation set value = %s where {$primaryKey} = %d and content_lang =%s";
                $deleteQueryTemplate = "delete from {$this->_table['name']} where {$primaryKey} in(%s)";
                $deleteQueryDataTemplate = "delete from {$this->_table['name']}_value%s where {$primaryKey} in(%s)";
                foreach ($this->_extendAttributes['attributes'] as $attributeName => $attributeMetaData) {
                    $deleted = array();
                    if (isset($ownerParam[$attributeName])) {
                        $attributeData = $ownerParam[$attributeName];
                        $sort = 1;
                        if (isset($this->_extendAttributes['attributes'][$attributeName])) {
                            foreach ($attributeData as $attribute) {
                                if (!$this->_extendAttributes['attributes'][$attributeName]['isExtendable']) {
                                    $attribute['id'] = $attributeName;
                                }
                                if (isset($this->_extendAttributes['attributes'][$attributeName]['data'][$attribute['id']])) {
                                    $attributeModel = $this->_extendAttributes['attributes'][$attributeName]['data'][$attribute['id']];
                                    $id = (int)$attributeModel->id;
                                    if (!$attributeModel->isNew) {
                                        if (isset($attribute['systemAttrbuiteId'])) {
                                            $updateQuery = sprintf($updateQueryTemplate, $sort, ", attribute_id=" . (int) $attribute['systemAttrbuiteId'], $id);
                                        } else {
                                            $updateQuery = sprintf($updateQueryTemplate, $sort, "", $id);
                                        }
                                        AmcWm::app()->db->createCommand($updateQuery)->execute();
                                        if ($this->_extendAttributes['attributes'][$attributeName]['translate']) {
                                            $updateDataQuery = sprintf($updateQueryLangDataTemplate, AmcWm::app()->db->quoteValue($attribute['value']), $id, AmcWm::app()->db->quoteValue($siteLanguage));
                                        } else {
                                            $updateDataQuery = sprintf($updateQueryDataTemplate, AmcWm::app()->db->quoteValue($attribute['value']), $id);
                                        }
                                        AmcWm::app()->db->createCommand($updateDataQuery)->execute();
//                                        echo $updateDataQuery . "\n";
//                                        echo $updateQuery . "\n";
                                    } else {
                                        if (!$isTranslated && !$id) {
                                            if (!isset($attribute['systemAttrbuiteId'])) {
                                                $attribute['systemAttrbuiteId'] = $this->_extendAttributes['attributes'][$attributeName]['id'];
                                            }
                                            $insertQuery = sprintf($insertQueryTemplate, $this->_ownerId, $attribute['systemAttrbuiteId'], $sort);
                                            AmcWm::app()->db->createCommand($insertQuery)->execute();
                                            $insertedId = AmcWm::app()->db->lastInsertID;
                                        } else {
                                            $insertedId = $id;
                                        }
                                        if ($this->_extendAttributes['attributes'][$attributeName]['translate']) {
                                            $insertDataQuery = sprintf($insertQueryLangDataTemplate, $insertedId, AmcWm::app()->db->quoteValue($attribute['value']), AmcWm::app()->db->quoteValue($siteLanguage));
                                        } else {
                                            $insertDataQuery = sprintf($insertQueryDataTemplate, $insertedId, AmcWm::app()->db->quoteValue($attribute['value']));
                                        }
//                                        echo "{$id}{$insertQuery}\n";
//                                        echo "{$id}{$insertDataQuery}\n";
                                        AmcWm::app()->db->createCommand($insertDataQuery)->execute();
                                    }
                                }
                                $sort++;
                            }
                        }
                    }
                    if (isset($this->_oldExtendAttributes['attributes'][$attributeName]['data']) && $this->_oldExtendAttributes['attributes'][$attributeName]['isExtendable']) {
                        foreach ($this->_oldExtendAttributes['attributes'][$attributeName]['data'] as $oldAttribute) {
                            if (!isset($this->_extendAttributes['attributes'][$attributeName]['data'][$oldAttribute->id])) {
                                $deleted[] = (int) $oldAttribute->id;
                            }
                        }
                        if (count($deleted)) {
                            $deleteQuery = sprintf($deleteQueryTemplate, implode(",", $deleted));
                            AmcWm::app()->db->createCommand($deleteQuery)->execute();
                            $deleteDataQuery = sprintf($deleteQueryDataTemplate, ($this->_extendAttributes['attributes'][$attributeName]['translate']) ? "_translation" : "", implode(",", $deleted));
                            AmcWm::app()->db->createCommand($deleteDataQuery)->execute();
//                            echo $deleteQuery;
//                            echo "\n";
//                            echo $deleteDataQuery;
//                            echo "\n";
                        }
                    }
                }
            }
        }
    }

    /**
     * Validate extendable event
     * @param CModelEvent $event
     */
    public function validateExtendable($event) {
        $validate = true;
        $ownerParam = AttributeModel::getParam($this->owner->getClassName());
        if ($ownerParam) {
            foreach ($this->_extendAttributes['attributes'] as $attributeName => $attributeMetaData) {
                if (isset($ownerParam[$attributeName])) {
                    $attributeData = $ownerParam[$attributeName];
                    $extendAttributesData = array();
                    $validateAttribute = true;
                    foreach ($attributeData as $attribute) {
                        if (!$this->_extendAttributes['attributes'][$attributeName]['isExtendable']) {
                            $attribute['id'] = $attributeName;
                        }
                        if (array_key_exists('value', $attribute)) {
                            if (isset($this->_extendAttributes['attributes'][$attributeName]['data'][$attribute['id']])) {
                                $attributeModel = $this->_extendAttributes['attributes'][$attributeName]['data'][$attribute['id']];
                                $attributeModel->ownerId = $this->_ownerId;
                            } else {
                                $this->_extendAttributes['attributes'][$attributeName]['struct']['table'] = $this->_table;
                                $this->_extendAttributes['attributes'][$attributeName]['struct']['model'] = $this->owner;
                                $attributeModel = new AttributeModel($this->_extendAttributes['attributes'][$attributeName]['struct']);
                                $attributeModel->ownerId = $this->_ownerId;
                                $attributeModel->id = $attribute['id'];
                                $attributeModel->name = $attributeName;
                                $attributeModel->label = $this->_extendAttributes['attributes'][$attributeName]['label'];
                            }
                            $attributeModel->attributes = $attribute;
                            if (!$attributeModel->label) {
                                $attributeModel->label = $this->owner->getAttributeLabel($attributeName);
                            }
                            $validateAttribute &=$attributeModel->validate();
                            $extendAttributesData[$attribute['id']] = $attributeModel;
                        }
                    }
                    $validate &= $validateAttribute;
                    if (!$validateAttribute) {
                        $errors = $attributeModel->getErrors();
                        if ($this->_extendAttributes['attributes'][$attributeName]['isExtendable']) {
                            foreach ($errors['value'] as $error) {
                                $this->owner->addError($attributeName, $error);
                            }
                        }
                    }
                    $this->_extendAttributes['attributes'][$attributeName]['data'] = $extendAttributesData;
                } else {
                    if ($this->_extendAttributes['attributes'][$attributeName]['isExtendable']) {
                        $this->_extendAttributes['attributes'][$attributeName]['data'] = array();
                    }
                }
            }
        }
        $event->isValid = $validate;
    }

}
