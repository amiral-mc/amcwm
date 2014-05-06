<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "comments_owners".
 * 
 * The followings are the available columns in table 'comments_owners':
 * @property string $name
 * @property string $email
 * @property string $comment_id
 *
 * The followings are the available model relations:
 * @property Comments $comment
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class CommentsOwners extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return CommentsOwners the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'comments_owners';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, email, comment_id', 'required'),
            array('name', 'length', 'max' => 40),
            array('email', 'length', 'max' => 100),
            array('comment_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, email, comment_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'comment' => array(self::BELONGS_TO, 'Comments', 'comment_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'name' => 'Name',
            'email' => 'Email',
            'comment_id' => 'Comment',
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

        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('comment_id', $this->comment_id);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

}
