<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "events_translation".
 *
 * The followings are the available columns in table 'events_translation':
 * @property string $event_id
 * @property string $content_lang
 * @property string $event_header
 * @property string $event_detail
 * @property string $location
 *
 * The followings are the available model relations:
 * @property Events $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class EventsTranslation extends ChildTranslatedActiveRecord {   

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EventsTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'events_translation';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array('attachment', 'isArray', array("allowEmpty" => true)),
//            array('attachment', 'validateAttachment'),
            array('content_lang, event_header, event_detail, location', 'required'),
            array('event_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('event_header', 'length', 'max' => 500),
            array('location', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('event_id, content_lang, event_header, event_detail, location', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'Events', 'event_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
//            'attachment' => AmcWm::t("msgsbase.core", 'Attachment'),
            'event_id' => AmcWm::t("msgsbase.core", 'ID'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'event_header' => AmcWm::t("msgsbase.core", 'Event Header'),
            'event_detail' => AmcWm::t("msgsbase.core", 'Event Detail'),
            'location' => AmcWm::t("msgsbase.core", 'Location'),
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
        $criteria->compare('event_id', $this->event_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('event_header', $this->event_header, true);
        $criteria->compare('event_detail', $this->event_detail, true);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('p.section_id', $this->parentContent->section_id);
        $criteria->compare('p.country_code', $this->parentContent->country_code);
        $criteria->compare('p.event_date', $this->getParentContent()->event_date, true);
        $criteria->compare('p.published', $this->getParentContent()->published, true);
        $criteria->join .=" inner join events p on t.event_id = p.event_id";
        $sort = new CSort();
        $sort->defaultOrder = 'event_date desc';
        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    public function afterFind() {
        $this->displayTitle = $this->event_header;
        parent::afterFind();
    }

}

