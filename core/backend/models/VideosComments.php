<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "videos_comments".
 *
 * The followings are the available columns in table 'videos_comments':
 * @property string $video_id
 * @property string $video_comment_id
 * 
 * The followings are the available model relations:
 * @property Comments $comments
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VideosComments extends ActiveRecord {

    public $username;

    /**
     * Returns the static model of the specified AR class.
     * @return VideosComments the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'videos_comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('video_id, video_comment_id', 'required'),
            array('video_id, video_comment_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('video_id, video_comment_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'comments' => array(self::BELONGS_TO, 'Comments', 'video_comment_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'video_id' => AmcWm::t("msgsbase.core", 'Video'),
            'video_comment_id' => Yii::t('comments', 'Comment'),
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
        $criteria->compare('video_comment_id', $this->video_comment_id, true);
        $criteria->compare('c.comment_header', $this->comments->comment_header, true);
        $criteria->compare('c.published', $this->comments->published);
        $criteria->join .= "inner join comments c on c.comment_id = t.video_comment_id";
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
     * get comment model
     * @access public
     * @return CActiveRecord 
     */
    public function getComment() {
        return Comments::model()->findByPk($this->comment_id);
    }

    public function primaryKey() {
        return $this->video_id;
    }

}
