<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "issues".
 *
 * The followings are the available columns in table 'issues':
 * @property integer $issue_id
 * @property string $issue_date
 * @property integer $published
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Issues extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Issues the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'issues';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('issue_date', 'required'),
            array('published', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('issue_id, issue_date, published', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'issue_id' => AmcWm::t("msgsbase.core", 'Issue Number'),
            'issue_date' => AmcWm::t("msgsbase.core", 'Issue Date'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $sort = new CSort();
        $sort->defaultOrder = 'issue_date desc';
        $criteria = new CDbCriteria;

        $criteria->compare('issue_id', $this->issue_id);
        $criteria->compare('issue_date', $this->issue_date, true);
        $criteria->compare('published', $this->published);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    public function addNewIssue() {
        $issueData = Issue::getInstance()->getIssue();
        $attributes['issue_date'] = $this->getNewIssueDate();
        $attributes['publish'] = 0;
        $ok = false;
        if (!$issueData['lastNotActive']['issue_id']) {
            $this->attributes = $attributes;
            $ok = $this->save();
        }
        return $ok;
    }

    public function getNewIssueDate() {
        $issueData = Issue::getInstance()->getIssue();
        return date("Y-m-d", strtotime($issueData['lastActive']['issue_date'] . " 1 day"));
    }

    public function publish($published = 1) {
        $issueData = Issue::getInstance()->getIssue();
        $currentTime = strtotime(date("Y-m-d 23:59:59"));
        $issueTime = strtotime(date($this->issue_date));
        $ok = $issueData['lastNotActive']['issue_id'] == $this->issue_id && !$this->published && $issueTime <= $currentTime;
        if ($ok) {
            parent::publish($published);
        }
        return $ok;
    }

    public function checkPublish() {
        $issueData = Issue::getInstance()->getIssue();
        $ok = $issueData['lastNotActive']['issue_id'] == $this->issue_id && !$this->published;
        return $ok;
    }

}