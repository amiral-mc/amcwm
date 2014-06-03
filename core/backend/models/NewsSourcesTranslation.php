<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "news_sources_translation".
 *
 * The followings are the available columns in table 'news_sources_translation':
 * @property integer $source_id
 * @property string $content_lang
 * @property string $source
 *
 * The followings are the available model relations:
 * @property NewsSources $parentContent
 */
class NewsSourcesTranslation extends ChildTranslatedActiveRecord {

    /**
     *
     * @var string column name in news_sources table 
     */
    public $url;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'news_sources_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, source', 'required'),
            array('source_id', 'numerical', 'integerOnly' => true),
            array('content_lang', 'length', 'max' => 2),
            array('source', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('source_id, content_lang, source', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'NewsSources', 'source_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'source_id' => AmcWm::t("msgsbase.sources", 'Source ID'),
            'content_lang' => AmcWm::t("msgsbase.sources", 'Content Lang'),
            'url' => AmcWm::t("msgsbase.sources", 'URL'),
            'source' => AmcWm::t("msgsbase.sources", 'Source'),
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
        $criteria->compare('content_lang', $this->content_lang);
        $criteria->compare('source', $this->source, true);
        $criteria->compare('url', $this->source, true);
        $criteria->select = 't.source_id, t.source, t.content_lang, tp.url';
        $criteria->join .=" inner join news_sources tp on t.source_id = tp.source_id ";
        $sort = new CSort();
        $sort->defaultOrder = 't.source_id';
        $sort->attributes = array(
            'source_id' => array(
                'asc' => 't.source_id',
                'desc' => 't.source_id desc',
            ),
            'article_id' => array(
                'asc' => 'article_id',
                'desc' => 'article_id desc',
            ),
            'source' => array(
                'asc' => 'source',
                'desc' => 'source desc',
            ),
      
            'content_lang' => array(
                'asc' => 't.content_lang',
                'desc' => 't.content_lang desc',
            ),
            'url' => array(
                'asc' => 'tp.url',
                'desc' => 'tp.url desc',
            ),
        );
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>$sort,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return NewsSourcesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

     /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->displayTitle = $this->source;
        parent::afterFind();
    }
}
