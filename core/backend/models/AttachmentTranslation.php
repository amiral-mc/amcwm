<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "attachment_translation".
 *
 * The followings are the available columns in table 'attachment_translation':
 * @property string $attach_id
 * @property string $content_lang
 * @property string $title
 * @property string $description
 * 
 * The followings are the available model relations:
 * @property Attachment $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttachmentTranslation extends ChildTranslatedActiveRecord {

    /**
     * sort attribute in attachment table
     * @var integer 
     */
    public $attach_sort;
    /**
     * Content type attribute in attachment table
     * @var string 
     */
    public $content_type;

    /**
     * URL attribute in attachment table
     * @var string 
     */
    public $attach_url;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AttachmentTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'attachment_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, title', 'required'),
            array('attach_id', 'length', 'max' => 10),
            array('attach_sort', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('title', 'length', 'max' => 100),
            array('description', 'safe'),
            array('content_type', 'length', 'max' => 14),
            array('attach_url', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('attach_id, content_lang, title, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Attachment', 'attach_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'attach_id' => 'Attach',
            'content_lang' => 'Content Lang',
            'title' => AmcWm::t("amcCore", 'Attachment Title'),
            'description' => 'Description',
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
        $criteria->compare('attach_id', $this->attach_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('p.attach_url', $this->getParentContent()->attach_url, true);
        $criteria->join .=" inner join attachment p on t.attach_id = p.attach_id";
        $sort = new CSort();
        $sort->defaultOrder = "p.attach_sort asc";
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                    'pagination' => false,
                ));
    }

}