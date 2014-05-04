<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property string $comment_id
 * @property string $comment_review
 * @property string $comment_header
 * @property string $comment
 * @property integer $published
 * @property string $comment_date
 * @property string $ip
 * @property integer $hide
 * @property string $user_id
 * @property string $bad_imp
 * @property string $good_imp
 * @property integer $force_display
 *
 * The followings are the available model relations:
 * @property ArticlesComments $articlesComments
 * @property Comments $commentReview
 * @property Comments[] $comments
 * @property Users $user
 * @property CommentsOwners $commentsOwners
 * @property ImagesComments $imagesComments
 * @property VideosComments $videosComments
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Comments extends ActiveRecord {
  
    public $verifyCode;
    /**
     * Returns the static model of the specified AR class.
     * @return Comments the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('comment_header, comment', 'required'),
            array('published, hide, force_display, good_imp', 'numerical', 'integerOnly' => true),
            array('comment_review, user_id, bad_imp, good_imp', 'length', 'max' => 10),
            array('comment_header', 'length', 'max' => 50),
            array('ip', 'length', 'max' => 15),
            array('comment_date', 'safe'),
            //array('comment_header, comment', 'safe'),
            array('comment_date', 'default', 'value' => new CDbExpression('NOW()'), 'setOnEmpty' => false, 'on' => 'insert'),
            array('ip', 'default', 'value' => Yii::app()->request->userHostAddress, 'setOnEmpty' => false, 'on' => 'insert'),
            array('published', 'default', 'value' => 1, 'setOnEmpty' => false, 'on' => 'insert'),
            array('bad_imp', 'default', 'value' => 0, 'setOnEmpty' => 0, 'on' => 'insert'),
            array('good_imp', 'default', 'value' => 0, 'setOnEmpty' => 0, 'on' => 'insert'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('comment_id, comment_review, comment_header, comment, published, comment_date, ip, hide, user_id, bad_imp, good_imp, force_display', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'articlesComments' => array(self::HAS_ONE, 'ArticlesComments', 'article_comment_id'),
            'commentReview' => array(self::BELONGS_TO, 'Comments', 'comment_review'),
            'comments' => array(self::HAS_MANY, 'Comments', 'comment_review'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'commentsOwners' => array(self::HAS_ONE, 'CommentsOwners', 'comment_id'),
            'imagesComments' => array(self::HAS_ONE, 'ImagesComments', 'image_comment_id'),
            'videosComments' => array(self::HAS_ONE, 'VideosComments', 'video_comment_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'comment_id' => AmcWm::t("comments", 'Comment'),
            'comment_review' => AmcWm::t("comments", 'Comment Review'),
            'comment_header' => AmcWm::t("comments", 'Comment Title'),
            'comment' => AmcWm::t("comments", 'Comment Details'),
            'published' => AmcWm::t("comments", 'Published'),
            'comment_date' => AmcWm::t("comments", 'Comment Date'),
            'ip' => AmcWm::t("comments", 'Ip'),
            'hide' => AmcWm::t("comments", 'Hide'),
            'user_id' => AmcWm::t("comments", 'User'),
            'bad_imp' => AmcWm::t("comments", 'Bad Imp'),
            'good_imp' => AmcWm::t("comments", 'Good Imp'),
            'force_display' => AmcWm::t("comments", 'Force Display'),
            'verifyCode' => AmcWm::t("amcFront",'Verification Code'),
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

        $criteria->compare('comment_id', $this->comment_id, true);
        $criteria->compare('comment_review', $this->comment_review, true);
        $criteria->compare('comment_header', $this->comment_header, true);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('comment_date', $this->comment_date, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('hide', $this->hide);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('bad_imp', $this->bad_imp, true);
        $criteria->compare('good_imp', $this->good_imp, true);
        $criteria->compare('force_display', $this->force_display);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}