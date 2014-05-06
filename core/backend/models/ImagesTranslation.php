<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "images_translation".
 *
 * The followings are the available columns in table 'images_translation':
 * @property string $image_id
 * @property string $content_lang
 * @property string $image_header
 * @property string $tags
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Images $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ImagesTranslation extends ChildTranslatedActiveRecord {

    /**
     *
     * @var string section name  , this attribute append to query in search 
     */
    public $section_name;

    /**
     *
     * @var string section name  , this attribute append to query in search 
     */
    public $username;

    /**
     *
     * @var integer published  , this attribute append to query in search 
     */
    public $published;
    /**
     *
     * @var integer is_background  , this attribute append to query in search 
     */
    public $is_background;
    
    /**
     *
     * @var string ext  , this attribute append to query in search 
     */
    public $ext;
    

    /**
     *
     * @var integer hits  , this attribute append to query in search 
     */
    public $hits;

    /**
     *
     * @var integer in_slider  , this attribute append to query in search 
     */
    public $in_slider;

    /**
     *
     * @var integer gallery_id  , this attribute append to query in search 
     */
    public $gallery_id;

    /**
     *
     * @var integer creation_date  , this attribute append to query in search 
     */
    public $creation_date;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ImagesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'images_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, image_header', 'required'),
            array('image_id', 'length', 'max' => 10),
            array('published, gallery_id, hits, is_background', 'numerical', 'integerOnly' => true),
            array('creation_date, username, in_slider, ext', 'safe'),
            array('content_lang', 'length', 'max' => 2),
            array('image_header', 'length', 'max' => 255),
            array('tags', 'length', 'max' => 1024),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('image_id, content_lang, image_header, tags, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Images', 'image_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'image_id' => AmcWm::t("msgsbase.core", 'id'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'image_header' => AmcWm::t("msgsbase.core", 'Image Header'),
            'tags' => AmcWm::t("msgsbase.core", 'Tags'),
            'description' => AmcWm::t("msgsbase.core", 'Image Description'),
            'creation_date' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'username' => AmcWm::t("msgsbase.core", 'User'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'in_slider' => AmcWm::t("msgsbase.core", 'In Slider'),
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
        $criteria->select = "t.image_id , t.content_lang, t.image_header , t.tags , t.description , p.published , p.creation_date, p.in_slider, p.hits , p.gallery_id, p.ext ,  p.is_background, u.username";
        $criteria->compare('image_id', $this->image_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('image_header', $this->image_header, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('creation_date', $this->creation_date);
        $criteria->compare('gallery_id', $this->gallery_id);
        $criteria->compare('p.published', $this->published);
        $criteria->join .=" inner join images p on t.image_id = p.image_id ";
        $criteria->join .=" left join users u on p.user_id = u.user_id ";
        $criteria->addCondition("p.is_background = " . (int)Yii::app()->getController()->isBackground);
        
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        $order = "{$sorting[$this->parentContent->tableName()]['sortField']} {$sorting[$this->parentContent->tableName()]['order']}";
        $sort = new CSort();        
        $sort->defaultOrder ="p.gallery_id asc, {$order}";        
        $sort->attributes = array(
            'image_header' => array(
                'asc' => 'image_header',
                'desc' => 'image_header desc',
            ),
            'image_id' => array(
                'asc' => 'image_id',
                'desc' => 'image_id desc',
            ),
            'username' => array(
                'asc' => 'username',
                'desc' => 'username desc',
            ),
            'hits' => array(
                'asc' => 'hits',
                'desc' => 'hits desc',
            ),
            'in_slider' => array(
                'asc' => 'in_slider',
                'desc' => 'in_slider desc',
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
        $this->displayTitle = $this->image_header;
        $this->gallery_id = $this->parentContent->gallery_id;
        parent::afterFind();
    }

}