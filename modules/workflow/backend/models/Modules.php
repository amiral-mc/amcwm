<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "modules".
 *
 * The followings are the available columns in table 'modules':
 * @property integer $module_id
 * @property integer $parent_module
 * @property string $module
 * @property integer $virtual
 * @property integer $enabled
 * @property integer $system
 * @property integer $workflow_enabled
 *
 * The followings are the available model relations:
 * @property Attachment[] $attachments
 * @property Controllers[] $controllers
 * @property ForwardModules $forwardModules
 * @property ForwardModules[] $forwardModules1
 * @property Modules $parentModule
 * @property Modules[] $modules
 * @property ModulesComponents[] $modulesComponents
 * @property ModulesLabels[] $modulesLabels
 * @property SystemAttributes[] $systemAttributes
 * @property Workflow[] $workflows
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Modules extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Modules the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'modules';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parent_module, virtual, enabled, system, workflow_enabled', 'numerical', 'integerOnly' => true),
            array('module', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('module_id, parent_module, module, virtual, enabled, system, workflow_enabled', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'attachments' => array(self::HAS_MANY, 'Attachment', 'module_id'),
            'controllers' => array(self::HAS_MANY, 'Controllers', 'module_id'),
            'forwardModules' => array(self::HAS_ONE, 'ForwardModules', 'forward_from'),
            'forwardModules1' => array(self::HAS_MANY, 'ForwardModules', 'forward_to'),
            'parentModule' => array(self::BELONGS_TO, 'Modules', 'parent_module'),
            'modules' => array(self::HAS_MANY, 'Modules', 'parent_module'),
            'modulesComponents' => array(self::HAS_MANY, 'ModulesComponents', 'module_id'),
            'modulesLabels' => array(self::HAS_MANY, 'ModulesLabels', 'module_id'),
            'systemAttributes' => array(self::HAS_MANY, 'SystemAttributes', 'module_id'),
            'workflows' => array(self::HAS_MANY, 'Workflow', 'module_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'module_id' => 'Module',
            'parent_module' => 'Parent Module',
            'module' => 'Module',
            'virtual' => 'Virtual',
            'enabled' => 'Enabled',
            'system' => 'System',
            'workflow_enabled' => 'Workflow Enabled',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('module_id', $this->module_id);
        $criteria->compare('parent_module', $this->parent_module);
        $criteria->compare('module', $this->module, true);
        $criteria->compare('virtual', $this->virtual);
        $criteria->compare('enabled', $this->enabled);
        $criteria->compare('system', $this->system);
        $criteria->compare('workflow_enabled', $this->workflow_enabled);
        
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}