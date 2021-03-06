<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "articles_titles".
 *
 * The followings are the available columns in table 'articles_titles':
 * @property string $title_id
 * @property string $article_id
 * @property string $content_lang
 * @property string $title
 *
 * The followings are the available model relations:
 * @property ArticlesTranslation $articleDetail
 * @property ArticlesTranslation $contentLang
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesTitles extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ArticlesTitles the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'articles_titles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article_id, content_lang, title', 'required'),
            array('article_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('title', 'length', 'max' => 500),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('title_id, article_id, content_lang, title', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'articleDetail' => array(self::BELONGS_TO, 'ArticlesTranslation', 'article_id, content_lang'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'title_id' => 'Title',
            'article_id' => AmcWm::t("msgsbase.core", 'Article ID'),
            'content_lang' =>AmcWm::t("msgsbase.core", 'Content Lang'),
            'title' => AmcWm::t("msgsbase.core", 'Title'),
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

        $criteria->compare('title_id', $this->title_id, true);
        $criteria->compare('article_id', $this->article_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('title', $this->title, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
}