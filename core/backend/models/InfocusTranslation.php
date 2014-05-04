<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "infocus_translation".
 *
 * The followings are the available columns in table 'infocus_translation':
 * @property string $infocus_id
 * @property string $content_lang
 * @property string $header
 * @property string $brief
 * @property string $tags
 *
 * The followings are the available model relations:
 * @property Infocus $infocus
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InfocusTranslation extends ChildTranslatedActiveRecord {

    /**
     *
     * @var integer published  , this attribute append to query in search 
     */
//    public $published;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InfocusTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'infocus_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, header, brief', 'required'),
            array('content_lang', 'length', 'max' => 2),
            array('header', 'length', 'max' => 500),
            array('tags', 'length', 'max' => 1024),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('infocus_id, content_lang, header, brief, tags', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Infocus', 'infocus_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'infocus_id' => AmcWm::t("msgsbase.core", 'ID'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'header' => AmcWm::t("msgsbase.core", 'Header'),
            'brief' => AmcWm::t("msgsbase.core", 'Brief'),
            'tags' => AmcWm::t("msgsbase.core", 'Tags'),
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

        $criteria->compare('infocus_id', $this->infocus_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('header', $this->header, true);
        $criteria->compare('brief', $this->brief, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('i.published', $this->parentContent->published);
        $criteria->compare('i.section_id', $this->parentContent->section_id);
        $criteria->compare('i.create_date', $this->parentContent->create_date);
        
        $criteria->join .=" inner join infocus i on i.infocus_id = t.infocus_id ";
        
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        $order = "{$sorting[$this->parentContent->tableName()]['sortField']} {$sorting[$this->parentContent->tableName()]['order']}";
        $sort = new CSort();        
        $sort->defaultOrder ="{$order}";        
        
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

}