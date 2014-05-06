<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "workflow".
 *
 * The followings are the available columns in table 'workflow':
 * @property integer $flow_id
 * @property string $flow_title
 * @property integer $enabled
 * @property integer $system
 * @property integer $module_id
 *
 * The followings are the available model relations:
 * @property Modules $module
 * @property WorkflowSteps[] $workflowSteps
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Workflow extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Workflow the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'workflow';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('module_id, flow_title', 'required'),
            array('enabled, system, module_id', 'numerical', 'integerOnly' => true),
            array('flow_title', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('flow_id, flow_title, enabled, system, module_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'module' => array(self::BELONGS_TO, 'Modules', 'module_id'),
            'workflowSteps' => array(self::HAS_MANY, 'WorkflowSteps', 'flow_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'flow_id' => AmcWm::t("msgsbase.core", 'ID'),
            'flow_title' => AmcWm::t("msgsbase.core", 'Flow Title'),
            'enabled' => AmcWm::t("msgsbase.core", 'Enabled'),
            'system' => AmcWm::t("msgsbase.core", 'System'),
            'module_id' => AmcWm::t("msgsbase.core", 'Module'),
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

        $criteria->compare('flow_id', $this->flow_id);
        $criteria->compare('flow_title', $this->flow_title, true);
        $criteria->compare('enabled', $this->enabled);
        $criteria->compare('system', $this->system);
        $criteria->compare('module_id', $this->module_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getStepsCount() {
        return 0;
    }

    /**
     * Get flow steps for the current model
     * @param int $pollId
     * @access public
     * @return array
     */
    public function getSteps() {
        $flowSteps = array();
        foreach ($this->workflowSteps as $step) {
            $flowSteps[$step->step_id] = $step->step_title;
        }
        return $flowSteps;
    }

    /**
     * Get flow steps for the current model
     * @todo  Get action and controllers from modules attrbuite in Acl class
     * @param int $pollId
     * @access public
     * @return array
     */
    public function getControllers($stepId) {
        $language = Controller::getCurrentLanguage();
//        $query = sprintf("select 
//                 c.controller, 
//                 c.controller_id,                 
//                 cl.controller_name
//                from controllers c 
//                inner join controllers_labels cl on cl.controller_id = c.controller_id
//                where c.module_id = %d
//                and cl.content_lang = %s
//                order by c.controller_id
//         ", $this->module_id
//                , Yii::app()->db->quoteValue($language)
//        );
//        $controllers = Yii::app()->db->createCommand($query)->queryAll();
//        foreach ($controllers as $k => $controller) {
//            $controllers[$k]['actions'] = $this->getActions($controller['controller_id'], $stepId);
//        }
        return array();
    }

    /**
     * Get flow steps for the current model
     * @param int $pollId
     * @todo  Get action and controllers from modules attrbuite in Acl class
     * @access public
     * @return array
     */
    public function getActions($cId, $stepId) {
//        $language = Controller::getCurrentLanguage();
//        $query = sprintf("select 
//                 a.action, 
//                 a.permissions, 
//                 a.action_id,
//                 al.action_name
//                from actions a 
//                inner join actions_labels al on al.action_id = a.action_id
//                where a.controller_id = %d
//                and al.content_lang = %s
//                order by a.action_id
//         ", $cId
//                , Yii::app()->db->quoteValue($language)
//        );
//        $actions = Yii::app()->db->createCommand($query)->queryAll();
//        foreach ($actions as $k => $action) {
//            $actions[$k]['selected'] = Yii::app()->db->createCommand(sprintf("select count(*) from workflow_actions where action_id= %d and step_id=%d", $action['action_id'], $stepId))->queryScalar();
//        }
        return array();
    }

}