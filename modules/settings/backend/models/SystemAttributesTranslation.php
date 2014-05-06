<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "system_attributes_translation".
 *
 * The followings are the available columns in table 'system_attributes_translation':
 * @property integer $attribute_id
 * @property string $content_lang
 * @property string $label
 *
 * The followings are the available model relations:
 * @property SystemAttributes $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SystemAttributesTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SystemAttributesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'system_attributes_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('label', 'required'),
            array('attribute_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('label', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('attribute_id, content_lang, label', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'SystemAttributes', 'attribute_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'attribute_id' => AmcWm::t("msgsbase.core", 'ID'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'label' =>  AmcWm::t("msgsbase.core", 'Label'),
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

        $criteria->compare('attribute_id', $this->attribute_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('label', $this->label, true);
        $criteria->compare('p.attribute_type', $this->getParentContent()->attribute_type);
        $criteria->join .=" inner join system_attributes p on t.attribute_id = p.attribute_id";
        $criteria->addCondition("(p.is_system in  (" . AttributesList::SYSTEM_ATTRIBUTE . ", " . AttributesList::NORMAL_ATTRIBUTE . "))");
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}