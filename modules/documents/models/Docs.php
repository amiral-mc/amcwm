<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "docs".
 *
 * The followings are the available columns in table 'docs':
 * @property string $doc_id
 * @property integer $category_id
 * @property string $end_date
 * @property string $start_date
 * @property integer $published
 * @property string $hits
 * @property integer $votes
 * @property string $file_ext
 * @property double $votes_rate
 * @property string $file_lang
 * @property string $create_date
 *
 * The followings are the available model relations:
 * @property DocsCategories $category
 * @property DocsTranslation[] $docsTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Docs extends ParentTranslatedActiveRecord {

    public $docFile;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Docs the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'docs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $date = date("Y-m-d H:i:s");
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('start_date, file_lang', 'required'),
            array('category_id, published, votes', 'numerical', 'integerOnly' => true),
            array('votes_rate', 'numerical'),
            array('hits', 'length', 'max' => 10),
            array('start_date, end_date, create_date', 'safe'),
            array('end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>', 'allowEmpty' => true),
            array('start_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            array('file_lang', 'length', 'max' => 2),
            array('file_ext', 'length', 'max' => 4),
            array('docFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxFileSize']),
            array('create_date', 'default',
                'value' => $date,
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('doc_id, category_id, end_date, start_date, published, hits, votes, file_lang, file_ext, votes_rate', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category' => array(self::BELONGS_TO, 'DocsCategories', 'category_id'),
            'translationChilds' => array(self::HAS_MANY, 'DocsTranslation', 'doc_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'doc_id' => AmcWm::t("msgsbase.core", 'Doc'),
            'category_id' => AmcWm::t("msgsbase.core", 'Category'),
            'end_date' => AmcWm::t("msgsbase.core", 'End Date'),
            'start_date' => AmcWm::t("msgsbase.core", 'Start Date'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'votes' => AmcWm::t("msgsbase.core", 'Votes'),
            'file_ext' => AmcWm::t("msgsbase.core", 'File Ext'),
            'docFile' => AmcWm::t("msgsbase.core", 'Doc File'),
            'votes_rate' => AmcWm::t("msgsbase.core", 'Votes Rate'),
            'file_lang' => AmcWm::t("msgsbase.core", 'File Language'),
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

        $criteria->compare('doc_id', $this->doc_id, true);
        $criteria->compare('category_id', $this->category_id, true);
        $criteria->compare('end_date', $this->end_date, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('published', $this->published, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('votes', $this->votes);
        $criteria->compare('file_ext', $this->file_ext, true);
        $criteria->compare('votes_rate', $this->votes_rate);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}