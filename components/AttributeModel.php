<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ExtendableField extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttributeModel extends CModel {

    /**
     * Attribute ID
     * @var integer 
     */
    public $id;

    /**
     * Current owner ID
     * @var integer 
     */
    public $ownerId;

    /**
     * Attribute value
     * @var mixed 
     */
    public $value;

    /**
     * Attribute label
     * @var string 
     */
    public $label;

    /**
     * Attribute name
     * @var string 
     */
    public $name;

    /**
     * Attribute sort number
     * @var integer 
     */
    public $sort;

    /**
     * Attribute system id
     * @var integer 
     */
    public $systemAttrbuiteId;

    /**
     * Custom rules array 
     * @var array
     */
    protected $customRules = array();

    /**
     * Setting structure array
     * @var array 
     */
    protected $struct = array();

    /**
     *
     * @var boolean if the current model is new record or not
     */
    public $isNew = true;
    
    /**
     *
     * @var array data arry list , used in upload files system
     */
    public $data = array();

    /**
     * Constructor
     * @param array $struct
     * @param boolean $isRequired
     */
    public function __construct($struct) {        
        if ($struct['required']) {
            $this->customRules = array(array('value', 'required'));
        }
        $this->struct = $struct;
        if (isset($struct['length'])) {
            array_push($this->customRules, array('value', 'length', 'max' => $struct['length']));
        }
        if (isset($struct['rules'])) {
            foreach ($struct['rules'] as $rule) {
                array_push($this->customRules, $rule);
            }
        }
    }

    /**
     * Get model request params from POST array
     * @param string $modelName
     * @return array
     */
    public static function getParam($modelName) {
        $params = AmcWm::app()->request->getParam(__CLASS__);
        if (isset($params[$modelName])) {
            return $params[$modelName];
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return $this->customRules;
    }

    public function attributeNames() {
        
    }

    /**
     * Save attribute model
     * @return boolean
     */
    public function save() {        
        if (isset($this->struct['table'])) {
            $siteLanguage = Controller::getCurrentLanguage();
            $foreignKey = Html::escapeString($this->struct['table']['foreignKey']);
            $primaryKey = Html::escapeString($this->struct['table']['primaryKey']);
            $insertQueryTemplate = "insert into {$this->struct['table']['name']} ({$foreignKey}, attribute_id, attribute_sort) values(%d, %d, %d)";
            $insertQueryDataTemplate = "insert into {$this->struct['table']['name']}_value ({$primaryKey}, value) values(%d, %s)";
            $insertQueryLangDataTemplate = "insert into {$this->struct['table']['name']}_value_translation ({$primaryKey}, value, content_lang) values(%d, %s , %s)";
            $updateQueryTemplate = "update {$this->struct['table']['name']} set attribute_sort = %d %s where {$primaryKey} = %d";
            $updateQueryDataTemplate = "update {$this->struct['table']['name']}_value set value = %s where {$primaryKey} = %d";
            $updateQueryLangDataTemplate = "update {$this->struct['table']['name']}_value_translation set value = %s where {$primaryKey} = %d and content_lang =%s";
            $isTranslated = false;
            if ($this->struct['model'] instanceof ChildTranslatedActiveRecord) {
                $isTranslated = Controller::getCurrentLanguage() != $this->owner->content_lang;
                $siteLanguage = $this->owner->content_lang;
            }
            if ($this->isNew) {
                if (!$isTranslated && !(int)$this->id) {                    
                    $insertQuery = sprintf($insertQueryTemplate, $this->ownerId, $this->systemAttrbuiteId, $this->sort);
                    AmcWm::app()->db->createCommand($insertQuery)->execute();
                    $insertedId = AmcWm::app()->db->lastInsertID;
                } else {
                    $insertedId = $this->id;
                }
                if ($this->struct['translate']) {
                    $insertDataQuery = sprintf($insertQueryLangDataTemplate, $insertedId, AmcWm::app()->db->quoteValue($this->value), AmcWm::app()->db->quoteValue($siteLanguage));
                } else {
                    $insertDataQuery = sprintf($insertQueryDataTemplate, $insertedId, AmcWm::app()->db->quoteValue($this->value));
                }
                AmcWm::app()->db->createCommand($insertDataQuery)->execute();
            } else {
                if ($this->systemAttrbuiteId) {
                    $updateQuery = sprintf($updateQueryTemplate, $this->sort, ", attribute_id=" . (int) $this->systemAttrbuiteId, $this->id);
                } else {
                    $updateQuery = sprintf($updateQueryTemplate, $this->sort, "", $this->id);
                }
                AmcWm::app()->db->createCommand($updateQuery)->execute();
                if ($this->struct['translate']) {
                    $updateDataQuery = sprintf($updateQueryLangDataTemplate, AmcWm::app()->db->quoteValue($this->value), $this->id, AmcWm::app()->db->quoteValue($siteLanguage));
                } else {
                    $updateDataQuery = sprintf($updateQueryDataTemplate, AmcWm::app()->db->quoteValue($this->value), $this->id);
                }
                AmcWm::app()->db->createCommand($updateDataQuery)->execute();
            }
        }
    }

    /**
     * Returns the attribute labels.
     * Attribute labels are mainly used in error messages of validation.
     * By default an attribute label is generated using {@link generateAttributeLabel}.
     * This method allows you to explicitly specify attribute labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to
     * merge the parent labels with child labels using functions like array_merge().
     *
     * @return array attribute labels (name=>label)
     * @see generateAttributeLabel
     */
    public function attributeLabels() {
        return array('value' => $this->label);
    }

}

