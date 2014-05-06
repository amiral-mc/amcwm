<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "workflow_comments".
 *
 * The followings are the available columns in table 'workflow_comments':
 * @property string $comment_id
 * @property integer $to_task
 * @property integer $from_task
 * @property string $comment_review
 * @property string $comment_header
 * @property string $comment
 * @property string $comment_date
 * @property string $ip
 * 
 * The followings are the available model relations:
 * @property WorkflowComments $commentReview
 * @property WorkflowComments[] $workflowComments
 * @property WorkflowTasks $fromTask
 * @property WorkflowTasks $toTask
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WorkflowComments extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WorkflowComments the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'workflow_comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('to_task, from_task, comment_header, comment, ip', 'required'),
            array('to_task, from_task', 'numerical', 'integerOnly' => true),
            array('comment_review', 'length', 'max' => 10),
            array('comment_header', 'length', 'max' => 150),
            array('ip', 'length', 'max' => 15),
            array('comment_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('comment_id, to_task, from_task, comment_review, comment_header, comment, comment_date, ip', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'commentReview' => array(self::BELONGS_TO, 'WorkflowComments', 'comment_review'),
            'workflowComments' => array(self::HAS_MANY, 'WorkflowComments', 'comment_review'),
            'fromTask' => array(self::BELONGS_TO, 'WorkflowTasks', 'from_task'),
            'toTask' => array(self::BELONGS_TO, 'WorkflowTasks', 'to_task'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'comment_id' => AmcWm::t("msgsbase.core", 'Comment'),
            'to_task' => AmcWm::t("msgsbase.core", 'To Task'),
            'from_task' => AmcWm::t("msgsbase.core", 'From Task'),
            'comment_review' => AmcWm::t("msgsbase.core", 'Comment Review'),
            'comment_header' => AmcWm::t("msgsbase.core", 'Comment Header'),
            'comment' => AmcWm::t("msgsbase.core", 'Comment'),
            'comment_date' => AmcWm::t("msgsbase.core", 'Comment Date'),
            'ip' => AmcWm::t("msgsbase.core", 'Ip'),
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

        $criteria->compare('comment_id', $this->comment_id, true);
        $criteria->compare('to_task', $this->to_task);
        $criteria->compare('from_task', $this->from_task);
        $criteria->compare('comment_review', $this->comment_review, true);
        $criteria->compare('comment_header', $this->comment_header, true);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('comment_date', $this->comment_date, true);
        $criteria->compare('ip', $this->ip, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}