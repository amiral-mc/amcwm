<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "tenders_translation".
 *
 * The followings are the available columns in table 'tenders_translation':
 * @property string $tender_id
 * @property string $content_lang
 * @property string $title
 * @property string $description
 * @property string $conditions
 * @property string $notes
 * @property string $technical_results
 * @property string $financial_results
 *
 * The followings are the available model relations:
 * @property Tenders $tender
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TendersTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenders_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, title, description', 'required'),
            array('tender_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('title', 'length', 'max' => 255),
            array('conditions, notes, technical_results, financial_results', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('tender_id, content_lang, title, description, conditions, notes, technical_results, financial_results', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Tenders', 'tender_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'tender_id' => AmcWm::t("msgsbase.core", 'Tender'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'title' => AmcWm::t("msgsbase.core", 'Title'),
            'description' => AmcWm::t("msgsbase.core", 'Description'),
            'conditions' => AmcWm::t("msgsbase.core", 'Conditions'),
            'notes' => AmcWm::t("msgsbase.core", 'Notes'),
            'technical_results' => AmcWm::t("msgsbase.core", 'Technical Results Data'),
            'financial_results' => AmcWm::t("msgsbase.core", 'Financial Results Data'),
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
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('conditions', $this->conditions, true);
        $criteria->compare('notes', $this->notes, true);
        $criteria->compare('technical_results', $this->technical_results, true);
        $criteria->compare('financial_results', $this->financial_results, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->displayTitle = $this->title;
        parent::afterFind();
    }

}
