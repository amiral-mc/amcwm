<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "images".
 *
 * The followings are the available columns in table 'images':
 * @property string $image_id
 * @property string $creation_date
 * @property string $ext
 * @property string $hits
 * @property string $user_id
 * @property integer $is_background
 * @property string $gallery_id
 * @property integer $published
 * @property string $publish_date
 * @property string $expire_date
 * @property string $image_sort
 * @property integer $in_slider
 * @property string $votes
 * @property double $votes_rate
 * @property integer $show_media
 * @property string $update_date
 * @property string $comments
 *
 * The followings are the available model relations:
 * @property Galleries $gallery
 * @property Users $user
 * @property ImagesComments[] $imagesComments
 * @property ImagesTranslation[] $translationChilds
 * @property Infocus[] $infocuses
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Images extends ParentTranslatedActiveRecord {

    /**
     * Sort field name
     * @var string 
     */
    protected $sortField = "image_sort";
    /**
     * Sort Dependency attributes
     * @var string 
     */
    protected $sortDependencyFields = array('gallery_id', 'is_background');
    public $imageFile = null;

    /**
     * Social ids added to this active record
     * @var array
     * @access public
     */
    public $socialIds = array();
    public $infocusId;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Images the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'images';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $date = date("Y-m-d H:i:s");
        return array(
            array('publish_date', 'required'),
            array('is_background, published, in_slider, show_media', 'numerical', 'integerOnly' => true),
            array('votes_rate', 'numerical'),
            array('ext', 'length', 'max' => 4),
            array('hits, user_id, gallery_id, image_sort, votes, comments', 'length', 'max' => 10),
            array('expire_date, update_date', 'safe'),
            array('publish_date, expire_date, description', 'safe'),
            array('expire_date', 'compare', 'compareAttribute' => 'publish_date', 'operator' => '>', 'allowEmpty' => true),
            array('update_date', 'default',
                'value' => $date,
                'setOnEmpty' => false),
            array('publish_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            array('imageFile', 'required', 'on' => 'insert'),
            array('imageFile', 'validateImageFile', 'on' => 'update'),
            array('imageFile', 'file', 'types' => Yii::app()->getController()->imageInfo['info']['extensions'], 'allowEmpty' => true, 'maxSize' => Yii::app()->getController()->imageInfo['info']['size']),
            array('imageFile', 'ValidateImage', 'checkValues' => Yii::app()->getController()->imageInfo['info'],
                'errorMessage' => Yii::app()->getController()->imageInfo['errorMessage'],
            ),
            array('is_background', 'default',
                'value' => Yii::app()->getController()->isBackground,
                'setOnEmpty' => false),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('image_id, creation_date, ext, hits, user_id, is_background, gallery_id, published, publish_date, expire_date, image_sort, in_slider, votes, votes_rate, show_media, update_date, comments', 'safe', 'on' => 'search'),
        );
    }

    public function validateImageFile($attribute, $params) {
        if (!$this->imageFile instanceof CUploadedFile) {

            $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->getController()->imageInfo['path']) . "/" . $this->image_id . "." . $this->ext;
            $imageFile = str_replace("{gallery_id}", $this->gallery_id, $imageFile);
            $error = !is_file($imageFile);
            if ($error) {
                $this->addError($attribute, Yii::t('yii', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel($attribute))));
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'gallery' => array(self::BELONGS_TO, 'Galleries', 'gallery_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'imagesComments' => array(self::HAS_MANY, 'ImagesComments', 'image_id'),
            'translationChilds' => array(self::HAS_MANY, 'ImagesTranslation', 'image_id', "index" => "content_lang"),
            'infocuses' => array(self::HAS_MANY, 'InfocusHasImages', 'image_id', "index" => "infocus_id"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'image_id' => AmcWm::t("msgsbase.core", 'id'),
            'creation_date' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'ext' => AmcWm::t("msgsbase.core", 'Ext'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'user_id' => AmcWm::t("msgsbase.core", 'User'),
            'is_background' => AmcWm::t("msgsbase.core", 'Is Background'),
            'gallery_id' => AmcWm::t("msgsbase.core", 'Gallery'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'publish_date' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'expire_date' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'image_sort' => AmcWm::t("msgsbase.core", 'Sort'),
            'in_slider' => AmcWm::t("msgsbase.core", 'In Slider'),
            'imageFile' => AmcWm::t("msgsbase.core", 'Image File'),
            'socialIds' => AmcWm::t("msgsbase.core", 'socialIds'),
            'infocusId' => AmcWm::t("msgsbase.core", 'In Focus File'),
            'show_media' => 'Show',
            'votes' => 'Votes',
            'votes_rate' => 'Votes Rate',
            'show_media' => 'Show Media',
            'update_date' => 'Update Date',
            'comments' => 'Comments',
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

        $criteria->compare('image_id', $this->image_id, true);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('ext', $this->ext, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('is_background', $this->is_background);
        $criteria->compare('gallery_id', $this->gallery_id, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('image_sort', $this->image_sort, true);
        $criteria->compare('in_slider', $this->in_slider);
        $criteria->compare('votes', $this->votes, true);
        $criteria->compare('votes_rate', $this->votes_rate);
        $criteria->compare('show_media', $this->show_media);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('comments', $this->comments, true);

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
        if (count($this->infocuses)) {
            $this->infocusId = $this->infocuses[0]->infocus_id;
        }
        parent::afterFind();
    }

    /**
     * @todo check sort logic if the section id has been changes
     * This method is invoked after each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
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
        $conditions = array();
        $condition = null;
        if ($this->gallery_id) {
            $conditions[] = "gallery_id = " . (int) $this->gallery_id;
        }
         else {
            $condition[] = "gallery_id is null";
        }
        $conditions[] = "is_background = " . (int) $this->is_background;
        if (count($conditions)) {
            $condition = implode(" and ", $conditions);
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
        $this->correctSort();
    }

}