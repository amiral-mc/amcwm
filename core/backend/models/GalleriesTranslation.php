<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "galleries_translation".
 *
 * The followings are the available columns in table 'galleries_translation':
 * @property string $gallery_id
 * @property string $content_lang
 * @property string $gallery_header
 * @property string $tags
 *
 * The followings are the available model relations:
 * @property Galleries $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GalleriesTranslation extends ChildTranslatedActiveRecord {

    /**
     *
     * @var string section name  , this attribute append to query in search 
     */
    public $section_name;

    /**
     *
     * @var integer published  , this attribute append to query in search 
     */
    public $published;

    /**
     *
     * @var integer section_id  , this attribute append to query in search 
     */
    public $section_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GalleriesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'galleries_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, gallery_header', 'required'),
            array('gallery_id', 'length', 'max' => 10),
            array('published, section_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('gallery_header', 'length', 'max' => 500),
            array('tags', 'length', 'max' => 1024),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('gallery_id, content_lang, gallery_header, tags', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Galleries', 'gallery_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'gallery_id' => AmcWm::t("msgsbase.core", 'id'),
            'gallery_header' => AmcWm::t("msgsbase.core", 'Gallery Header'),
            'tags' => AmcWm::t("msgsbase.core", 'Tags'),
            'content_lang' => AmcWm::t("amcBack", 'Content Language'),
            'section_name' => AmcWm::t("msgsbase.core", 'Section'),
            'section_id' => AmcWm::t("msgsbase.core", 'Section'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
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
        $criteria->select = "t.gallery_id , t.content_lang, t.gallery_header , t.tags , p.published , p.section_id ,  st.section_name";
        $criteria->compare('gallery_id', $this->gallery_id, true);
        $criteria->compare('gallery_header', $this->gallery_header, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('p.section_id', $this->section_id, true);
        $criteria->compare('p.published', $this->published);
        $criteria->compare('t.content_lang', $this->content_lang, true);
        $criteria->join .=" inner join galleries p on t.gallery_id = p.gallery_id ";
        $criteria->join .=" left join sections_translation st on p.section_id = st.section_id and st.content_lang = t.content_lang";
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        $order = "{$sorting[$this->parentContent->tableName()]['sortField']} {$sorting[$this->parentContent->tableName()]['order']}";
        $sort = new CSort();        
        $sort->defaultOrder ="p.section_id asc, {$order}";
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->displayTitle = $this->gallery_header;
        parent::afterFind();
    }

}