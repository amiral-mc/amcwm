<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "voters".
 *
 * The followings are the available columns in table 'voters':
 * @property string $answer_id
 * @property integer $option_id
 * @property string $content_lang
 * @property string $voted_on
 * @property string $ip
 * @property string $user_id
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property VotesOptions $option
 * @property VotesOptions $contentLang
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Voters extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Voters the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'voters';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('option_id, content_lang, voted_on', 'required'),
            array('option_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('ip', 'length', 'max' => 16),
            array('user_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('answer_id, option_id, content_lang, voted_on, ip, user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'option' => array(self::BELONGS_TO, 'VotesOptions', 'option_id'),
            'contentLang' => array(self::BELONGS_TO, 'VotesOptions', 'content_lang'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'answer_id' => 'Answer',
            'option_id' => 'Option',
            'content_lang' => 'Content Lang',
            'voted_on' => 'Voted On',
            'ip' => 'Ip',
            'user_id' => 'User',
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

        $criteria->compare('answer_id', $this->answer_id, true);
        $criteria->compare('option_id', $this->option_id);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('voted_on', $this->voted_on, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('user_id', $this->user_id, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}