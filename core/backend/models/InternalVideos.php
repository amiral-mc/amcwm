<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "internal_videos".
 *
 * The followings are the available columns in table 'internal_videos':
 * @property string $video_id
 * @property string $video_ext
 * @property string $img_ext
 *
 * The followings are the available model relations:
 * @property Videos $parentVideo
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InternalVideos extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return InternalVideos the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'internal_videos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('video_id, video_ext', 'required'),
            array('video_id', 'length', 'max' => 10),
            array('video_ext, img_ext', 'length', 'max' => 4),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('video_id, video_ext, img_ext', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentVideo' => array(self::BELONGS_TO, 'Videos', 'video_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'video_id' => 'Video',
            'video_ext' => AmcWm::t("msgsbase.core", 'Video Ext'),
            'img_ext' => AmcWm::t("msgsbase.core", 'Img Ext'),
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

        $criteria->compare('video_id', $this->video_id, true);
        $criteria->compare('video_ext', $this->video_ext, true);
        $criteria->compare('img_ext', $this->img_ext, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}