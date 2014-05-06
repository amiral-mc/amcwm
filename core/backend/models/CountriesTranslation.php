<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "countries_translation".
 *
 * The followings are the available columns in table 'countries_translation':
 * @property string $code
 * @property string $content_lang
 * @property string $country
 *
 * The followings are the available model relations:
 * @property Countries $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class CountriesTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CountriesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'countries_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code, content_lang', 'required'),
            array('code, content_lang', 'length', 'max' => 2),
            array('country', 'length', 'max' => 200),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('code, content_lang, country', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Countries', 'code'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'code' => 'Code',
            'content_lang' => 'Content Lang',
            'country' => 'Country',
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

        $criteria->compare('code', $this->code, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('country', $this->country, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}