<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "workflow_tasks".
 *
 * The followings are the available columns in table 'workflow_tasks':
 * @property integer $task_id
 * @property integer $step_id
 * @property integer $return_from
 * @property string $item_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Users[] $users
 * @property WorkflowComments[] $workflowComments
 * @property WorkflowComments[] $workflowComments1
 * @property WorkflowTasks $returnFrom
 * @property WorkflowTasks[] $workflowTasks
 * @property WorkflowSteps $step
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WorkflowTasks extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WorkflowTasks the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'workflow_tasks';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('step_id, item_id', 'required'),
            array('step_id, return_from, status', 'numerical', 'integerOnly' => true),
            array('item_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('task_id, step_id, return_from, item_id, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'users' => array(self::MANY_MANY, 'Users', 'users_workflow_log(tasks_id, user_id)'),
            'workflowComments' => array(self::HAS_MANY, 'WorkflowComments', 'from_task'),
            'workflowComments1' => array(self::HAS_MANY, 'WorkflowComments', 'to_task'),
            'returnFrom' => array(self::BELONGS_TO, 'WorkflowTasks', 'return_from'),
            'workflowTasks' => array(self::HAS_MANY, 'WorkflowTasks', 'return_from'),
            'step' => array(self::BELONGS_TO, 'WorkflowSteps', 'step_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'task_id' => AmcWm::t("msgsbase.core", 'Task'),
            'step_id' => AmcWm::t("msgsbase.core", 'Step'),
            'return_from' => AmcWm::t("msgsbase.core", 'Return From'),
            'item_id' => AmcWm::t("msgsbase.core", 'Item'),
            'status' => AmcWm::t("msgsbase.core", 'Status'),
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

        $criteria->compare('task_id', $this->task_id);
        $criteria->compare('step_id', $this->step_id);
        $criteria->compare('return_from', $this->return_from);
        $criteria->compare('item_id', $this->item_id, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}