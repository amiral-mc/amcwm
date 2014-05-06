<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "votes_questions_translation".
 *
 * The followings are the available columns in table 'votes_questions_translation':
 * @property integer $ques_id
 * @property string $content_lang
 * @property string $ques
 *
 * The followings are the available model relations:
 * @property VotesOptions[] $votesOptions
 * @property VotesQuestions $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VotesQuestionsTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VotesQuestionsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'votes_questions_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ques', 'required'),
            array('ques_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('ques', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ques_id, content_lang, ques', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'votesOptions' => array(self::HAS_MANY, 'VotesOptions', 'ques_id, content_lang'),
            'parentContent' => array(self::BELONGS_TO, 'VotesQuestions', 'ques_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ques_id' => AmcWm::t("msgsbase.core", 'ID'),
            'ques' => AmcWm::t("msgsbase.core", 'Question'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
        );
    }

    /**
     * Get poll results for the current model
     * @param int $pollId
     * @access public
     * @return array
     */
    public function getResults() {
        $poll = array("total" => 0, "votes" => array());
        foreach ($this->votesOptions as $option) {
            $optionCount = (int) Yii::app()->db->createCommand("select count(*) from voters where option_id = " . (int) $option->option_id)->queryScalar();
            if ($optionCount) {
                $poll['total'] += $optionCount;
                $poll['votes'][$option->option_id]['votes'] = $optionCount;
            } else {
                $poll['votes'][$option->option_id]['votes'] = 0;
            }
            $poll['votes'][$option->option_id]['option'] = $option->value;
        }
        return $poll;
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
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('ques', $this->ques, true);
        $criteria->compare('p.creation_date', $this->parentContent->creation_date, true);
        $criteria->compare('p.publish_date', $this->parentContent->publish_date, true);
        $criteria->compare('p.published', $this->parentContent->published);
        $criteria->compare('p.expire_date', $this->parentContent->expire_date, true);
        $criteria->compare('p.suspend', $this->parentContent->suspend);
        $criteria->join .=" inner join votes_questions p on t.ques_id = p.ques_id ";
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->displayTitle = $this->ques;
        parent::afterFind();
    }

}