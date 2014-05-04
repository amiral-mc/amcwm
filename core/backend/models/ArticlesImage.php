<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "articles_image".
 *
 * The followings are the available columns in table 'articles_image':
 * @property string $article_id
 * @property string $image_description
 * 
 * The followings are the available model relations:
 * @property Articles $article
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesImage extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return ArticlesImage the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'articles_image';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article_id, image_description', 'required'),
            array('article_id', 'length', 'max' => 10),
            array('image_description', 'length', 'max' => 150),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('article_id, image_description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'article' => array(self::BELONGS_TO, 'Articles', 'article_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' => 'Article',
            'image_description' =>  Yii::t("articles", 'Image Description'),
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

        $criteria->compare('article_id', $this->article_id, true);
        $criteria->compare('image_description', $this->image_description, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}