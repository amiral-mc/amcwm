<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "menu_items_params".
 *
 * The followings are the available columns in table 'menu_items_params':
 * @property integer $item_id
 * @property integer $component_id
 * @property integer $param_id
 * @property string $value
 *
 * The followings are the available model relations:
 * @property MenuItems $item
 * @property ModulesComponentsParams $component
 * @property ModulesComponentsParams $param
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 
 */
class MenuItemsParams extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MenuItemsParams the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'menu_items_params';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_id, component_id, param_id, value', 'required'),
            array('item_id, component_id, param_id', 'numerical', 'integerOnly' => true),
            array('value', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('item_id, component_id, param_id, value', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'item' => array(self::BELONGS_TO, 'MenuItems', 'item_id'),
            'component' => array(self::BELONGS_TO, 'ModulesComponentsParams', 'component_id'),
            'param' => array(self::BELONGS_TO, 'ModulesComponentsParams', 'param_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'item_id' => 'Item',
            'component_id' => 'Component',
            'param_id' => 'Param',
            'value' => 'Value',
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

        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('component_id', $this->component_id);
        $criteria->compare('param_id', $this->param_id);
        $criteria->compare('value', $this->value, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
   
}