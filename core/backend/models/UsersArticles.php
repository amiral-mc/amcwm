<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "users_articles".
 *
 * The followings are the available columns in table 'users_articles':
 * @property string $article_id
 * @property string $user_id
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Articles $article
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class UsersArticles extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return UsersArticles the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users_articles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article_id, user_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('article_id, user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'article' => array(self::BELONGS_TO, 'Articles', 'article_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' => 'Article',
            'user_id' => 'User',
        );
    }

      /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     * @todo add logic for sorting comments
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('article_id', $this->article_id, true);
        $criteria->compare('article.article_header', $this->article->article_header, true);
        $criteria->compare('article.article_detail', $this->article->article_detail, true);
        $criteria->compare('article.content_lang', $this->article->content_lang, true);
        $criteria->compare('article.published', $this->article->published, true);
        $criteria->compare('article.section_id', $this->article->section_id, true);
        $criteria->compare('article.votes', $this->article->votes, true);
        $criteria->compare('article.votes_rate', $this->article->votes_rate);
        $criteria->compare('article.tags', $this->article->tags, true);
        $criteria->compare('article.hits', $this->article->hits, true);
        $criteria->compare('article.archive', $this->article->archive);
        $criteria->compare('article.create_date', $this->article->create_date, true);
        $criteria->compare('article.writer_id', $this->article->writer_id, true);
        $criteria->compare('article.publish_date', $this->article->publish_date, true);
        $criteria->compare('article.expire_date', $this->article->expire_date, true);    
//        $criteria->with = array(
//            'article' => array(
//                'together' => true,
//                'on' => 'article.article_id = t.article_id',
//                'joinType' => 'INNER JOIN',
//            ),
//        );
        $criteria->join = ' inner join articles article on article.article_id = t.article_id ';
        $criteria->join .= ' inner join users user on user.user_id = t.user_id ';
        $criteria->join .= ' left join sections on sections.section_id = article.section_id ';
        $sort = new CSort();
        $sort->defaultOrder = 'article.create_date desc';
        $sort->attributes = array(
            'article_header' => array(
                'asc' => 'article.article_header',
                'desc' => 'article.article_header desc',
            ),
            'username' => array(
                'asc' => 'user.username',
                'desc' => 'user.username desc',
            ),
            'article_id' => array(
                'asc' => 't.article_id',
                'desc' => 't.article_id desc',
            ),
            'section_id' => array(
                'asc' => 'sections.section_name',
                'desc' => 'sections.section_name desc',
            ),
            'create_date' => array(
                'asc' => 'article.create_date',
                'desc' => 'article.create_date desc',
            ),
            'content_lang' => array(
                'asc' => 'article.content_lang',
                'desc' => 'article.content_lang desc',
            ),
            'published' => array(
                'asc' => 'article.published',
                'desc' => 'article.published desc',
            ),
        );

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort'=>$sort,
        ));
    }

    /**
     * Get users list needed for articles
     * @access public 
     * @return array     
     */
    public function getUsersList() {
        $users = CHtml::listData(Users::model()->findAll(), 'user_id', 'username');
        return $users;
    }

}