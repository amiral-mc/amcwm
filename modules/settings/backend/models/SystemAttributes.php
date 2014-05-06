<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "system_attributes".
 *
 * The followings are the available columns in table 'system_attributes':
 * @property integer $attribute_id
 * @property integer $module_id
 * @property integer $attribute_type
 * @property integer $is_system
 * @property integer $is_new_type
 *
 * The followings are the available model relations:
 * @property SystemAttributesTranslation[] $translationChilds
 * @property Modules[] $module
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SystemAttributes extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SystemAttributes the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'system_attributes';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('attribute_type, module_id', 'required'),
            array('attribute_type, is_system, module_id', 'numerical', 'integerOnly' => true),
            array('attribute_type', 'validateAttributeType'),
            array('is_new_type', 'validateAttributeType'),
            array('is_new_type', 'unique', 'caseSensitive' => false, 'skipOnError' => true, 'allowEmpty' => true),
            array('is_new_type', 'validateAttributeName'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('attribute_id, attribute_type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'translationChilds' => array(self::HAS_MANY, 'SystemAttributesTranslation', 'attribute_id', 'index' => 'content_lang'),
            'module' => array(self::BELONGS_TO, 'Modules', 'module_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'attribute_id' => AmcWm::t("msgsbase.core", 'ID'),
            'attribute_type' => AmcWm::t("msgsbase.core", 'Attribute Type'),
            'module_id' => AmcWm::t("msgsbase.core", 'Module'),
            'is_new_type' => AmcWm::t("msgsbase.core", 'New Type'),
        );
    }

    /**
     * @return attribute type
     */
    public function getModuleName() {
        $modulesList = self::getModulesList();
        $module = null;
        if (isset($modulesList[$this->module_id])) {
            $module = $modulesList[$this->module_id];
        }
        return $module;
    }

    /**
     * @return attribute type
     */
    public function getAttributeType() {
        $attributesTyppesList = self::getAttributesTypesList();
        $type = null;
        if (isset($attributesTyppesList[$this->attribute_type])) {
            $type = $attributesTyppesList[$this->attribute_type];
        }
        return $type;
    }

    /**
     * generate attributes types list
     */
    static public function getAttributesTypesList() {
        return AttributesList::getAttributesTypesList();
    }

    /**
     * generate modules types list
     */
    static public function getModulesList() {
        $usedModules = AmcWm::app()->getController()->module->appModule->settings['usedModules'];
        $modulesList = array();
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $messageSystem = "amcwm.system.messages.system";
        foreach ($usedModules as $usedModule) {
            $module = amcwm::app()->acl->getModule($usedModule['name']);
            if (isset($module['messageSystem'])) {
                $messageSystem = $module['messageSystem'];
            }
            if ($module) {
                $modulesList[$module['id']] =  AmcWm::t($messageSystem, $module['label']);
            }
        }
        return $modulesList;
    }

    public function validateAttributeType($attribute, $params) {
        if (!$this->isNewRecord) {
            $attributes = $this->getOnlineAttributes();
            if (($attributes['attribute_type'] != $this->attribute_type) && $this->checkAttributesTables()) {
                $this->addError($attribute, AmcWm::t("msgsbase.core", 'You cannot change attribute type used in system components'));
            }
        }
    }

    public function validateAttributeName($attribute, $params) {
        if ($this->is_new_type) {
            if (!preg_match("/^[a-z]([0-9a-z_])+$/i", $this->is_new_type))
                $this->addError($attribute, AmcWm::t("msgsbase.core", 'Attribute name contains invalid characters, only letters, numbers and _ are allowed.'));
        }
    }

    /**
     * @return boolean
     */
    public function checkAttributesTables() {
        $found = false;
        $usedModules = AmcWm::app()->getController()->module->appModule->settings['usedModules'];
        foreach ($usedModules as $module) {
            foreach ($module['tables'] as $table) {
                $query = sprintf("select attribute_id from %s where attribute_id =%d", Html::escapeString($table['name']), $this->attribute_id);
                $found = $this->dbConnection->createCommand($query)->queryScalar();
                if ($found) {
                    break;
                }
            }
        }
        return $found;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('attribute_id', $this->attribute_id);
        $criteria->compare('attribute_type', $this->attribute_type);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
