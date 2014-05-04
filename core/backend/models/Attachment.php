<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "attachment".
 *
 * The followings are the available columns in table 'attachment':
 * @property string $attach_id
 * @property string $ref_id
 * @property integer $module_id
 * @property integer $table_id
 * @property string $content_type
 * @property string $attach_url
 * @property string $create_date
 * @property string $attach_sort
 *
 * The followings are the available model relations:
 * @property Modules $module
 * @property AttachmentTranslation[] $translationChilds
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Attachment extends ParentTranslatedActiveRecord {    

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Attachment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'attachment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $date = date("Y-m-d H:i:s");
        return array(
            
            array('ref_id, module_id, table_id', 'required'),
            array('module_id, table_id, attach_sort', 'numerical', 'integerOnly' => true),
            array('ref_id', 'length', 'max' => 10),
            array('content_type', 'length', 'max' => 14),
            array('attach_url', 'length', 'max' => 100),
              array('create_date', 'default',
                'value' => $date,
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('attach_id, ref_id, module_id, table_id, content_type, attach_url, create_date, attach_sort', 'safe', 'on' => 'search'),
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
            'translationChilds' => array(self::HAS_MANY, 'AttachmentTranslation', 'attach_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'attach_id' => 'Attach',
            'ref_id' => 'Ref',
            'module_id' => 'Module',
            'table_id' => 'Table',
            'content_type' => 'Content Type',
            'attach_url' => 'Attach Url',
            'create_date' => 'Create Data',
            'attach_sort' => 'Attach Sort',
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

        $criteria->compare('attach_id', $this->attach_id, true);
        $criteria->compare('ref_id', $this->ref_id, true);
        $criteria->compare('module_id', $this->module_id);
        $criteria->compare('table_id', $this->table_id);
        $criteria->compare('content_type', $this->content_type, true);
        $criteria->compare('attach_url', $this->attach_url, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('attach_sort', $this->attach_sort, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Sort the given model acording to $direction order
     * @param string $direction
     * @param string $language content language
     * @param string $condition condition to be added to update query
     * @access protected
     * @return boolean
     */
    public function sort($direction = "up", $condition = null) {
        $condition = "module_id = " . (int) $this->module_id;
        $condition .= " and table_id = " . (int) $this->table_id;
        $condition .= " and ref_id = " . (int) $this->ref_id;
        parent::sort($direction, $condition);
    }

    /**
     * This method is invoked after deleting a active record translion child.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return void
     */
    protected function afterDeleteChild($childAttributes) {
        return $this->correctSort();
    }

}