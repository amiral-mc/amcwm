<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ParentTranslatedActiveRecord class, Used for generate active record for parent contents table records liks article
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ParentTranslatedActiveRecord extends ActiveRecord {

    /**
     * Sort field name
     * @var string 
     */
    protected $sortField;

    /**
     * order type asc or desc read this from parameters
     * @var string 
     */
    protected $orderType = "asc";

    /**
     * Current module table that has one to one relation ship with the current table, example news and article, default equal the same table name
     * @var string 
     */
    protected $moduleTable = null;

    /**
     * Sort Dependency attributes
     * @var string 
     */
    protected $sortDependencyFields = array();

    /**
     * Translation relation name in relation array
     * Default equal to translationChilds
     * @var string 
     */
    protected $translationRelationName = "translationChilds";

    /**
     * This method is invoked after a model instance is created by new operator.
     * The default implementation raises the {@link onAfterConstruct} event.
     * You may override this method to do postprocessing after model creation.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    public function afterConstruct() {
        $sorting = array();
        if (AmcWm::app()->appModule) {
            $this->moduleTable = AmcWm::app()->appModule->getTable();
            $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
            if (!$this->moduleTable) {
                $this->moduleTable = $this->tableName();
            }
            if (isset($sorting[$this->moduleTable])) {
                $this->orderType = $sorting[$this->moduleTable]['order'];
            }
        }
        //die($this->moduleTable);
        parent::afterConstruct();
    }

    /**
     * Get current module table that has one to one relation ship with the current table, 
     * example news and article, default equal the same table name
     * @access public
     * @return string     
     */
    public function getModuleTable() {
        return $this->moduleTable;
    }

    /**
     * Gets translation relation name in relation array, default equal to translationChilds
     * @access  public
     * @return string
     */
    public function getTranslationRelationName() {
        return $this->translationRelationName;
    }

    /**
     * Chiek if given $language exist in the translation childs
     * @param string $language
     * @access public
     * @return boolean
     */
    public function isLanguageInChilds($language) {
        $translationRelationName = $this->translationRelationName;
        return isset($this->{$translationRelationName}[$language]);
    }

    /**
     * Gets the translated active record for the given $language
     * @param string $language
     * @access public
     * @return ActiveRecord
     */
    public function getTranslated($language) {
        $translationRelationName = $this->translationRelationName;
        $current = null;
        if ($this->isLanguageInChilds($language)) {
            $current = $this->{$translationRelationName}[$language];
        }
        return $current;
    }

    /**
     * Gets the translated active record for the current language
     * @access public
     * @see ParentTranslatedActiveRecord.getTranslated
     * @return ActiveRecord
     */
    public function getCurrent() {
        return $this->getTranslated(Controller::getContentLanguage());
    }

    /**
     * Deletes the row corresponding to this active record and related active records translation.
     * @access public
     * @return boolean whether the deletion is successful.
     * @throws CException if the record is new
     * 
     */
    public function delete() {
        $deleted = false;
        if (!$this->getIsNewRecord()) {
            if ($this->beforeDelete()) {
                $translationRelationName = $this->translationRelationName;
                foreach ($this->$translationRelationName as $child) {
                    $attributes = $child->attributes;
                    $deleted = $child->delete();
                    if ($deleted) {
                        $this->afterDeleteChild($attributes);
                    }
                }
                $deleted = parent::delete();
            }            
        }
        return $deleted;
    }

    /**
     * Sets translation childs attributes to be null.
     * @param array $names list of attributes to be set null. If this parameter is not given,
     * all attributes as specified by {@link attributeNames} will have their values unset.
     * @since 1.1.3
     * @access public
     * @return void
     */
    public function unsetTranslationsAttributes($names = null) {
        $translationRelationName = $this->translationRelationName;
        foreach ($this->$translationRelationName as $child) {
            if ($child !== null) {
                $child->unsetAttributes($names = null);
            }
        }
    }

    /**
     * Add translation child to parent model
     * @param mixed $record the related record
     * @param string $language the language value in the related object collection.
     * @access public
     */
    public function addTranslationChild($record, $language) {
        $translationRelationName = $this->translationRelationName;
        if (!$this->isLanguageInChilds($language)) {
            $record->content_lang = $language;
            $this->addRelatedRecord($translationRelationName, $record, $language);
            $parentRelationName = $record->getParentRelationName();
            $record->$parentRelationName = $this;
        }
    }

    /**
     * Correct sort after delete or move reccord
     * @access protected
     * @return boolean
     */
    protected function correctSort() {
        $sortField = $this->sortField;
        if (isset($this->$sortField)) {
//            $operator = ">";
//            if ($this->orderType == "desc") {
//                $operator = "<";
//            }
//            if (isset(Yii::app()->params['tables'][$this->moduleTable]['sort'])) {
//                $this->orderType = Yii::app()->params['tables'][$this->moduleTable]['sort'];
//            }
            $query = sprintf("UPDATE {$this->tableName()} p
                SET {$sortField} = {$sortField}-1 
                WHERE {$sortField} > %d"
                    , $this->{$sortField}
            );
            Yii::app()->db->createCommand($query)->execute();
        }
        return true;
    }

    /**
     * This method is invoked after each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        $sortField = $this->sortField;
        if ($this->hasAttribute($sortField)) {
            if ($this->isNewRecord) {
                $this->setMaxSort();
            } else {
                $onlineAttributes = $this->getOnlineAttributes();
                if ($this->sortDependencyFields) {
                    $changeSort = false;
                    foreach ($this->sortDependencyFields as $dependencyField) {
                        if (isset($this->attributes[$dependencyField]) && $onlineAttributes[$dependencyField] != $this->attributes[$dependencyField]) {
                            $changeSort = true;
                            break;
                        }
                    }
                    if ($changeSort) {
                        $this->correctSort();
                        $this->setMaxSort();
                    }
                }
            }
        }
        return parent::beforeSave();
    }

    /**
     * Get max sorting for the current active record
     * @access public
     * @return integer
     */
    public function getMaxSort() {
        $sortField = $this->sortField;
        $maxSort = null;
        if ($this->hasAttribute($sortField)) {
            $query = "SELECT coalesce(max($this->sortField),0) as max_sort FROM {$this->tableName()}";
            $maxSort = Yii::app()->db->createCommand($query)->queryScalar();
        }
        return $maxSort;
    }

    /**
      /**
     * Set sorting equal to the max sort number
     * @access public
     * @return void
     */
    public function setMaxSort() {
        $sortField = $this->sortField;
        if ($this->hasAttribute($sortField)) {
            $maxSort = $this->getMaxSort() + 1;
            $this->setAttribute($this->sortField, $maxSort);
        }
    }

    /**
     * Gets sort field name
     * @access public
     * @return astring
     */
    public function getSortField() {
        return $this->sortField;
    }

    /**
     * Sort the given model acording to $direction order
     * @todo generate condition from dependancy
     * @param string $direction
     * @param string $condition condition to be added to update query
     * @access protected
     * @return boolean
     */
    public function sort($direction = "up", $condition = null) {
        $transaction = Yii::app()->db->beginTransaction();
        $log = "Old values: <pre>" . print_r($this->attributes, true) . "</pre><hr />";
        try {
            $sortField = $this->sortField;
            if (isset($this->$sortField)) {
                $pk = $this->tableSchema->primaryKey;
                $conditionWhere = null;
                if ($condition) {
                    $conditionWhere = "where {$condition}";
                    $condition = "and {$condition}";
                }
                if ($direction == 'up') {
                    $operator = "<";
                    $orderBy = "desc";
                    if ($this->orderType == "desc") {
                        $operator = ">";
                        $orderBy = "asc";
                    }
                    $query = sprintf("SELECT p.{$sortField} sort_number, p.{$pk} pk_id  
                        FROM {$this->tableName()} p
                        WHERE $sortField {$operator} %d 
                        $condition
                        order by {$sortField} $orderBy    
                        limit 1", $this->$sortField);
                    $otherSort = Yii::app()->db->createCommand($query)->queryRow();
                    $log .="UP: 1 <pre>" . print_r($otherSort, true) . "</pre> | {$query}<br />";
                    if (!$otherSort) {
                        $query = sprintf("SELECT p.{$sortField} sort_number, p.{$pk} pk_id
                            FROM {$this->tableName()} p
                            $conditionWhere
                            order by $sortField $orderBy
                            limit 1", $this->$sortField);
                        $otherSort = Yii::app()->db->createCommand($query)->queryRow();
                        $log .="UP 2: <pre>" . print_r($otherSort, true) . "</pre> | {$query}";
                    }
                    $log .="UP: end<hr />";
                } else if ($direction == 'down') {
                    $operator = ">";
                    $orderBy = "asc";
                    if ($this->orderType == "desc") {
                        $operator = "<";
                        $orderBy = "desc";
                    }
                    $query = sprintf("SELECT p.{$sortField} sort_number, p.{$pk} pk_id
                    FROM {$this->tableName()} p
                    WHERE $sortField {$operator} %d                     
                    $condition
                    order by $sortField $orderBy
                    limit 1 ", $this->$sortField);
                    $otherSort = Yii::app()->db->createCommand($query)->queryRow();
                    $log .="DOWN: 1 <pre>" . print_r($otherSort, true) . "</pre> | {$query}<br />";
                    if (!$otherSort) {
                        $query = sprintf("SELECT p.{$sortField} sort_number, p.{$pk} pk_id
                    FROM {$this->tableName()} p
                    $conditionWhere
                    order by $sortField $orderBy
                    limit 1", $this->$sortField);
                        $otherSort = Yii::app()->db->createCommand($query)->queryRow();
                        $log .="DOWN: 2 <pre>" . print_r($otherSort, true) . "</pre> | {$query}<br />";
                    }
                    $log .="DOWN: end<hr />";
                }
                $otherId = $otherSort['pk_id'];
                $newSortOrder = $otherSort['sort_number'];
                $otherSort = $this->$sortField;
                $currentSort = $this->$sortField;
                $this->setAttribute($sortField, $newSortOrder);
                $log .= "New values: <pre>" . print_r($this->attributes, true) . "</pre><hr />";
                if ($this->save()) {
                    $query = sprintf("UPDATE {$this->tableName()} 
                   SET $sortField = %d 
                    WHERE $pk = %d 
                    $condition
                    ", $currentSort, $otherId);
                    Yii::app()->db->createCommand($query)->execute();
                    $log .="AfterSave: {$query}<hr />";
                }
                $transaction->commit();
            }
        } catch (CDbException $e) {
            $transaction->rollback();
        }
        //die($log);
        //$transaction->rollback();
        return true;
    }

    /**
     * This method is invoked after deleting a active record translion child.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return void
     */
    protected function afterDeleteChild($childAttributes) {
        
    }

    /**
     * order type asc or desc read this from settongs
     * @access public
     * @return string 
     */
    public function getOrderType() {
        return $this->orderType;
    }

}
