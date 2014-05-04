<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "tenders_department".
 *
 * The followings are the available columns in table 'tenders_department':
 * @property integer $department_id
 * @property integer $parent_department
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property Tenders[] $tenders
 * @property TendersDepartment $parentDepartment
 * @property TendersDepartment[] $tendersDepartments
 * @property TendersDepartmentTranslation[] $tendersDepartmentTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersDepartment extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TendersDepartment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenders_department';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parent_department, published', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('department_id, parent_department, published', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'tenders' => array(self::HAS_MANY, 'Tenders', 'department_id'),
            'parentDepartment' => array(self::BELONGS_TO, 'TendersDepartment', 'parent_department'),
            'tendersDepartments' => array(self::HAS_MANY, 'TendersDepartment', 'parent_department'),
            'translationChilds' => array(self::HAS_MANY, 'TendersDepartmentTranslation', 'department_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'department_id' => AmcWm::t("msgsbase.core", 'Department'),
            'parent_department' => AmcWm::t("msgsbase.core", 'Parent Department'),
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

        $criteria = new CDbCriteria;

        $criteria->compare('department_id', $this->department_id);
        $criteria->compare('parent_department', $this->parent_department);
        $criteria->compare('published', $this->published);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    static public function getDepartmentsList($myId = null){
        $where = '';
        if($myId){
            $where = ' and department_id <> ' . $myId;
        }
        return CHtml::listData(Yii::app()->db->createCommand(sprintf("select department_id, department_name from tenders_department_translation where content_lang=%s {$where} order by department_name ", Yii::app()->db->quoteValue(Controller::getContentLanguage())))->queryAll(), 'department_id', "department_name");
    }
    
    public function getDepartment($depId){
        $list = $this->getDepartmentsList();
	if(isset($list[$depId])){
        	return $list[$depId];
	}
    }
}
