
<?php

/**
 * This is the model class for table "products_comments".
 *
 * The followings are the available columns in table 'products_comments':
 * @property string $product_comment_id
 * @property string $product_id
 *
 * The followings are the available model relations:
 * @property Comments $productComment
 * @property Products $product
 */
class ProductsComments extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'products_comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('product_comment_id, product_id', 'required'),
            array('product_comment_id, product_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('product_comment_id, product_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'comments' => array(self::BELONGS_TO, 'Comments', 'product_comment_id'),
            'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'product_comment_id' => 'Product Comment',
            'product_id' => 'Product',
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
        $criteria->compare('product_comment_id', $this->product_comment_id, true);
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('c.comment_header', $this->comments->comment_header, true);
        $criteria->compare('c.published', $this->comments->published);
        $criteria->join .= "JOIN comments c on c.comment_id = t.product_comment_id";
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
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => $sort,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProductsComments the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
