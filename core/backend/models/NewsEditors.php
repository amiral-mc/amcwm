<?php

/**
 * This is the model class for table "news_editors".
 *
 * The followings are the available columns in table 'news_editors':
 * @property string $article_id
 * @property string $editor_id
 * @property Writers[] $editor
 */
class NewsEditors extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'news_editors';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article_id, editor_id', 'required'),
            array('article_id, editor_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('article_id, editor_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'editor' => array(self::BELONGS_TO, 'Writers', 'editor_id',),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' => 'Article',
            'editor_id' => 'Writer',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('article_id', $this->article_id, true);
        $criteria->compare('editor_id', $this->editor_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return NewsWriters the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
