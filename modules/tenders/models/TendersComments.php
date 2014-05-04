<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "tenders_comments".
 *
 * The followings are the available columns in table 'tenders_comments':
 * @property string $tender_id
 * @property string $comment_id
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersComments extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TendersComments the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenders_comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tender_id, comment_id', 'required'),
            array('tender_id, comment_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('tender_id, comment_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'comments' => array(self::BELONGS_TO, 'Comments', 'comment_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'tender_id' => AmcWm::t("msgsbase.core", 'Tender'),
            'comment_id' => AmcWm::t("msgsbase.core", 'Comment'),
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

        $criteria->compare('tender_id', $this->tender_id, true);
        $criteria->compare('comment_id', $this->comment_id, true);
        $criteria->join .= "inner join comments c on c.comment_id = t.comment_id";
        $sort = new CSort();
        $sort->defaultOrder = "comment_id desc";
        $sort->attributes = array(
            'comment_id' => array(
                'asc' => 'comment_id',
                'desc' => 'comment_id desc',
            ),
            'comment_header' => array(
                'asc' => 'comment_header',
                'desc' => 'comment_header desc',
            ),
            'ip' => array(
                'asc' => 'ip',
                'desc' => 'ip desc',
            ),
            'user_id' => array(
                'asc' => 'user_id',
                'desc' => 'user_id desc',
            ),
            'comment_date' => array(
                'asc' => 'comment_date',
                'desc' => 'comment_date desc',
            ),
        );

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

}