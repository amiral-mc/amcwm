<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "workflow_steps".
 *
 * The followings are the available columns in table 'workflow_steps':
 * @property integer $step_id
 * @property integer $flow_id
 * @property string $step_title
 * @property integer $enabled
 * @property integer $system
 *
 * The followings are the available model relations:
 * @property Actions[] $actions
 * @property Roles[] $roles
 * @property WorkflowSteps $parentStep
 * @property WorkflowSteps[] $workflowSteps
 * @property Workflow $flow
 * @property WorkflowTasks[] $workflowTasks
 * @property Users[] $users
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WorkflowSteps extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WorkflowSteps the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'workflow_steps';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('step_title', 'required'),
            array('flow_id, step_sort, enabled, system', 'numerical', 'integerOnly' => true),
            array('step_title', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('step_id, flow_id, step_sort, step_title, enabled, system', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'actions' => array(self::MANY_MANY, 'Actions', 'workflow_actions(step_id, action_id)'),
            'roles' => array(self::MANY_MANY, 'Roles', 'workflow_roles(step_id, role_id)'),
            'parentStep' => array(self::BELONGS_TO, 'WorkflowSteps', 'step_sort'),
            'workflowSteps' => array(self::HAS_MANY, 'WorkflowSteps', 'step_sort'),
            'flow' => array(self::BELONGS_TO, 'Workflow', 'flow_id'),
            'workflowTasks' => array(self::HAS_MANY, 'WorkflowTasks', 'step_id'),
            'users' => array(self::MANY_MANY, 'Users', 'workflow_users(step_id, user_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'step_id' => 'Step',
            'flow_id' => 'Flow',
            'step_sort' => 'Parent Step',
            'step_title' => 'Step Title',
            'enabled' => 'Enabled',
            'system' => 'System',
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

        $criteria->compare('step_id', $this->step_id);
        $criteria->compare('flow_id', $this->flow_id);
        $criteria->compare('step_sort', $this->step_sort);
        $criteria->compare('step_title', $this->step_title, true);
        $criteria->compare('enabled', $this->enabled);
        $criteria->compare('system', $this->system);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function deleteActions() {
        $query = sprintf("delete from workflow_actions where step_id=%d", $this->step_id);
        Yii::app()->db->createCommand($query)->execute();
    }

    public function deleteOldAssigns($stepId){
        $delQuery1 = sprintf("delete from workflow_roles where step_id=%d", $stepId);
        Yii::app()->db->createCommand($delQuery1)->execute();
        
        $delQuery2 = sprintf("delete from workflow_users where step_id=%d", $stepId);
        Yii::app()->db->createCommand($delQuery2)->execute();
    }

    public function saveRole($roleId, $stepId) {
        $query = sprintf("insert into workflow_roles (step_id, role_id) values (%d, %d)", $stepId, $roleId);
        Yii::app()->db->createCommand($query)->execute();
    }

    public function saveUsers($users, $stepId) {
        foreach ($users as $user) {
            $query = sprintf("insert into workflow_users (step_id, user_id) values (%d, %d)", $stepId, $user);
            Yii::app()->db->createCommand($query)->execute();
        }
    }

}