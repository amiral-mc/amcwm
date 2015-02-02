
<?php

/**
 * This is the model class for table "products_translation".
 *
 * The followings are the available columns in table 'products_translation':
 * @property string $product_id
 * @property string $product_name
 * @property string $content_lang
 * @property string $product_brief
 * @property string $product_description
 * @property string $product_specifications
 * @property string $tags
 *
 * The followings are the available model relations:
 * @property Products $product
 */
class ProductsTranslation extends ChildTranslatedActiveRecord {

    /**
     * @var type string Section Name
     */
    public $section_name;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'products_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('product_name, content_lang, product_description', 'required'),
            array('product_id', 'length', 'max' => 10),
            array('product_name', 'length', 'max' => 100),
            array('content_lang', 'length', 'max' => 2),
            array('product_description, tags', 'length', 'max' => 1024),
            array('product_brief, product_specifications', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('product_id, product_name, content_lang, product_brief, product_description, product_specifications, tags', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Products', 'product_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'product_id' => AmcWm::t('msgsbase.core', 'Product'),
            'product_name' => AmcWm::t('msgsbase.core', 'Product Name'),
            'content_lang' => AmcWm::t('msgsbase.core', 'Content Lang'),
            'product_brief' => AmcWm::t('msgsbase.core', 'Product Brief'),
            'product_description' => AmcWm::t('msgsbase.core', 'Product Description'),
            'product_specifications' => AmcWm::t('msgsbase.core', 'Product Specification'),
            'tags' => AmcWm::t('msgsbase.core', 'Tags'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('product_name', $this->product_name, true);
        $criteria->compare('t.content_lang', $this->content_lang, true);
        $criteria->compare('product_brief', $this->product_brief, true);
        $criteria->compare('product_description', $this->product_description, true);
        $criteria->compare('product_specifications', $this->product_specifications, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('p.votes', $this->getParentContent()->votes, true);
        $criteria->compare('p.votes_rate', $this->getParentContent()->votes_rate);
        $criteria->compare('p.hits', $this->getParentContent()->hits, true);
        $criteria->compare('p.published', $this->getParentContent()->published);
        $criteria->compare('p.create_date', $this->getParentContent()->create_date, true);
        $criteria->compare('p.section_id', $this->getParentContent()->section_id);
        $criteria->compare('p.publish_date', $this->getParentContent()->publish_date, true);
        $criteria->compare('p.expire_date', $this->getParentContent()->expire_date, true);
        $criteria->select = "t.product_id, t.product_name, t.content_lang, p.published, p.comments, p.publish_date, p.create_date, st.section_name";
        $criteria->join .=" JOIN products p on t.product_id = p.product_id";
        $criteria->join .=" LEFT JOIN sections_translation st on p.section_id = st.section_id and st.content_lang = " . Yii::app()->db->quoteValue($this->content_lang);
        $sort = new CSort();
        $sort->attributes = array(
            'article_header' => array(
                'asc' => 'article_header',
                'desc' => 'article_header desc',
            ),
            'article_id' => array(
                'asc' => 'article_id',
                'desc' => 'article_id desc',
            ),
            'comments' => array(
                'asc' => 'comments',
                'desc' => 'comments desc',
            ),
            'section_id' => array(
                'asc' => 'st.section_name',
                'desc' => 'st.section_name desc',
            ),
            'create_date' => array(
                'asc' => 'create_date',
                'desc' => 'create_date desc',
            ),
            'content_lang' => array(
                'asc' => 't.content_lang',
                'desc' => 't.content_lang desc',
            ),
            'published' => array(
                'asc' => 'published',
                'desc' => 'published desc',
            ),
        );
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProductsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->displayTitle = $this->product_name;
        parent::afterFind();
    }

}
