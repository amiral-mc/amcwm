<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "votes_questions".
 *
 * The followings are the available columns in table 'votes_questions':
 * @property integer $ques_id
 * @property string $creation_date
 * @property string $publish_date
 * @property integer $published
 * @property string $expire_date
 * @property integer $suspend
 *
 * The followings are the available model relations:
 * @property VotesQuestionsTranslation[] $translationChilds
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VotesQuestions extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VotesQuestions the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'votes_questions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $date = date("Y-m-d H:i:s");
        return array(
            array('publish_date', 'required'),
            array('creation_date', 'default',
                'value' => new CDbExpression("'$date'"),
                'setOnEmpty' => false),
            array('published, suspend', 'numerical', 'integerOnly' => true),
            array('expire_date', 'safe'),
            array('expire_date', 'compare', 'compareAttribute' => 'publish_date', 'operator' => '>', 'allowEmpty' => true),
            array('expire_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>', 'allowEmpty' => true),
            array('publish_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ques_id, creation_date, publish_date, published, expire_date, suspend', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'translationChilds' => array(self::HAS_MANY, 'VotesQuestionsTranslation', 'ques_id', 'index' => 'content_lang'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ques_id' => AmcWm::t("msgsbase.core", 'ID'),
            'creation_date' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'publish_date' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'suspend' => AmcWm::t("msgsbase.core", 'Suspend'),
            'expire_date' => AmcWm::t("msgsbase.core", 'Expire Date'),
        );
    }

    /**
     * Get voters count
     * @param int $pollId
     * @access public
     * @return int
     */
    public function getVotersCount() {
        $count = (int) Yii::app()->db->createCommand("select count(*) from voters v inner join votes_options o on v.option_id = o.option_id where ques_id = " . (int) $this->ques_id)->queryScalar();
        return $count;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('ques_id', $this->ques_id);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('suspend', $this->suspend);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * @todo check sort logic if the section id has been changes
     * This method is invoked after each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        if (!$this->expire_date || $this->expire_date == '0000-00-00 00:00:00') {
            $this->expire_date = NULL;
        }
        return parent::beforeSave();
    }

}