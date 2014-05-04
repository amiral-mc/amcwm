<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "social_networks".
 *
 * The followings are the available columns in table 'social_networks':
 * @property integer $social_id
 * @property string $network_name
 * @property integer has_media 
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 * @property Galleries[] $galleries
 * @property Images[] $images
 * @property Sections[] $sections
 * @property Videos[] $videoses
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SocialNetworks extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return SocialNetworks the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'social_networks';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('social_id, network_name', 'required'),
            array('social_id, has_media', 'numerical', 'integerOnly' => true),
            array('network_name', 'length', 'max' => 45),
            array('class_name', 'length', 'max' => 15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('social_id,class_name, network_name, has_media', 'safe', 'on' => 'search'),
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
            'social_id' => 'Social',
            'network_name' => 'Network Name',
            'has_media' => 'Has Media',
            'class_name' => 'Class Name',
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

        $criteria->compare('social_id', $this->social_id);
        $criteria->compare('network_name', $this->network_name, true);
        $criteria->compare('has_media', $this->has_media, true);
        $criteria->compare('class_name', $this->class_name, true);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

}