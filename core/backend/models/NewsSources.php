<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "news_sources".
 *
 * The followings are the available columns in table 'news_sources':
 * @property integer $source_id
 * @property string $url
 *
 * The followings are the available model relations:
 * @property News[] $news
 * @property NewsSourcesTranslation[] $translationChilds
 */
class NewsSources extends ParentTranslatedActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'news_sources';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url', 'length', 'max' => 255),
            array('url', 'url'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('source_id, url', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'news' => array(self::HAS_MANY, 'News', 'source_id'),
            'translationChilds' => array(self::HAS_MANY, 'NewsSourcesTranslation', 'source_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'source_id' => AmcWm::t("msgsbase.sources", 'Source ID'),
            'url' => AmcWm::t("msgsbase.sources", 'URL'),
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

        $criteria->compare('source_id', $this->source_id);
        $criteria->compare('url', $this->url, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return NewsSources the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Check table integrity in delete process
     * @return boolean
     */
    public function integrityCheck() {
        return count($this->news);
    }

    /**
     * Get Sources list
     * @param string $language if not equal null then get sources according to the given $language,
      =     * @access public
     * @return array 
     */
    static public function getSourcesList($language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $query = sprintf(
                "select 
                    s.source_id,
                    t.source
                from news_sources s 
                inner join news_sources_translation t on s. source_id = t. source_id
                where t.content_lang = %s
                order by `source` ", Yii::app()->db->quoteValue($language));
        return CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'source_id', 'source');
    }

}
