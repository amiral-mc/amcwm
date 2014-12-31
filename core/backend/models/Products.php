<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property string $product_id
 * @property string $gallery_id
 * @property integer $section_id
 * @property string $votes
 * @property double $votes_rate
 * @property string $hits
 * @property string $shared
 * @property string $comments
 * @property integer $published
 * @property string $create_date
 * @property string $publish_date
 * @property string $expire_date
 * @property string $update_date
 * @property string $product_sort
 * @property integer $is_system
 * @property string $price
 * @property string $product_code
 *
 * The followings are the available model relations:
 * @property Sections $section
 * @property Galleries $gallery
 * @property ProductsComments[] $productsComments
 * @property ProductsTranslation[] $productsTranslations
 */
class Products extends ParentTranslatedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'products';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $date = date("Y-m-d H:i:s");
        return array(
            array('section_id, publish_date', 'required'),
            array('section_id, published, is_system', 'numerical', 'integerOnly' => true),
            array('votes_rate', 'numerical'),
            array('gallery_id, votes, hits, shared, comments, product_sort', 'length', 'max' => 10),
            array('price', 'length', 'max' => 8),
            array('product_code', 'length', 'max' => 50),
            array('expire_date, update_date', 'safe'),
            array('create_date', 'default',
                'value' => $date,
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('product_id, gallery_id, section_id, votes, votes_rate, hits, shared, comments, published, create_date, publish_date, expire_date, update_date, product_sort, is_system, price, product_code', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'section' => array(self::BELONGS_TO, 'Sections', 'section_id'),
            'gallery' => array(self::BELONGS_TO, 'Galleries', 'gallery_id'),
            'productsComments' => array(self::HAS_MANY, 'ProductsComments', 'product_id'),
            'translationChilds' => array(self::HAS_MANY, 'ProductsTranslation', 'product_id', 'index' => 'content_lang'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'product_id' => AmcWm::t('msgsbase.core', 'Product'),
            'gallery_id' => AmcWm::t('msgsbase.core', 'Gallery'),
            'section_id' => AmcWm::t('msgsbase.core', 'Section'),
            'votes' => AmcWm::t('msgsbase.core', 'Votes'),
            'votes_rate' => AmcWm::t('msgsbase.core', 'Votes Rate'),
            'hits' => AmcWm::t('msgsbase.core', 'Hits'),
            'shared' => AmcWm::t('msgsbase.core', 'Shared'),
            'comments' => AmcWm::t('msgsbase.core', 'Comments'),
            'published' => AmcWm::t('msgsbase.core', 'Published'),
            'create_date' => AmcWm::t('msgsbase.core', 'Create Date'),
            'publish_date' => AmcWm::t('msgsbase.core', 'Publish Date'),
            'expire_date' => AmcWm::t('msgsbase.core', 'Expire Date'),
            'update_date' => AmcWm::t('msgsbase.core', 'Update Date'),
            'product_sort' => AmcWm::t('msgsbase.core', 'Product Sort'),
            'is_system' => AmcWm::t('msgsbase.core', 'Is System'),
            'price' => AmcWm::t('msgsbase.core', 'Price'),
            'product_code' => AmcWm::t('msgsbase.core', 'Product Code'),
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('gallery_id', $this->gallery_id, true);
        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('votes', $this->votes, true);
        $criteria->compare('votes_rate', $this->votes_rate);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('shared', $this->shared, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('product_sort', $this->product_sort, true);
        $criteria->compare('is_system', $this->is_system);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('product_code', $this->product_code, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Products the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $current = $this->getCurrent();
        if ($current instanceof ChildTranslatedActiveRecord) {
            $this->displayTitle = $current->product_name;
        }
        parent::afterFind();
    }

    /**
     * This method is invoked before each record has been saved
     * @access public
     * @return boolean
     */
    public function beforeSave() {
        if (!$this->expire_date || $this->expire_date == '0000-00-00 00:00:00') {
            $this->expire_date = NULL;
        }
        if (!$this->publish_date || $this->publish_date == '0000-00-00 00:00:00') {
            $this->publish_date = NULL;
        }
        return parent::beforeSave();
    }

    /**
     * Sort the given model acording to $direction order
     * @param string $direction
     * @param string $language content language
     * @param string $condition condition to be added to update query
     * @access protected
     * @return boolean
     */
    public function sort($direction = "up", $condition = null) {
        if ($this->section_id) {
            $condition = "section_id = " . (int) $this->section_id;
        } else {
            $condition = "section_id is null";
        }
        parent::sort($direction, $condition);
    }

    /**
     * This method is invoked after deleting a active record translion child.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return void
     */
    protected function afterDeleteChild($childAttributes) {
        return $this->correctSort();
    }

}
