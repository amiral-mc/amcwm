<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "docs_translation".
 *
 * The followings are the available columns in table 'docs_translation':
 * @property string $doc_id
 * @property string $content_lang
 * @property string $title
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Docs $doc
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DocsTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DocsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'docs_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, title', 'required'),
            array('doc_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('description', 'safe'),
            array('title', 'length', 'max' => 150),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('doc_id, content_lang, title, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Docs', 'doc_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'doc_id' => AmcWm::t("msgsbase.core", 'Doc'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'title' => AmcWm::t("msgsbase.core", 'Title'),
            'description' => AmcWm::t("msgsbase.core", 'Description'),
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
        $sort = new CSort();
        $criteria->compare('doc_id', $this->doc_id, true);
        $criteria->compare('t.content_lang', $this->content_lang, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->join .=" inner join docs p on t.doc_id = p.doc_id";
        $criteria->join .=" left join docs_categories_translation ct on p.category_id = ct.category_id and ct.content_lang = " . Yii::app()->db->quoteValue($this->content_lang);
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        $order = "{$sorting['docs']['sortField']} {$sorting['docs']['order']}";
        $sort->defaultOrder = "p.category_id , {$order}";
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

}