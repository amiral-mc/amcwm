<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property string $article_id
 * @property integer $is_breaking
 *
 * The followings are the available model relations:
 * @property Articles $article
 * @property NewsSources $source
 * @property Writers[] $writers
 * @author Amiral Management Corporation
 * @version 1.0
 */
class News extends ActiveRecord {

    /**
     * 
     * @var array writers ids saved in news_writers
     */
    public $writersIds;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return News the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'news';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('is_breaking, source_id', 'numerical', 'integerOnly' => true),
            array('article_id', 'length', 'max' => 10),
            array('writersIds', 'isArray', 'allowEmpty'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('article_id, is_breaking', 'safe', 'on' => 'search'),
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
            'source' => array(self::BELONGS_TO, 'NewsSources', 'source_id'),
            'writers' => array(self::HAS_MANY, 'NewsWriters', 'article_id', 'index'=>'writer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' =>AmcWm::t("msgsbase.core", 'Article ID'),
            'is_breaking' => AmcWm::t("msgsbase.news", 'Breaking News'),
            'source_id' => AmcWm::t("msgsbase.news", 'Source'),
            'writersIds' => AmcWm::t("msgsbase.news", 'Writers'),
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
        $criteria->compare('is_breaking', $this->is_breaking);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
      /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->writersIds = array_keys($this->writers);
        parent::afterFind();
    }
    
     /**
     * This method is invoked after saving a record successfully.
     * The default implementation raises the {@link onAfterSave} event.
     * You may override this method to do postprocessing after record saving.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave() {        
        AmcWm::app()->db->createCommand('delete from news_writers where article_id = ' . (int)$this->article_id)->execute();
        foreach ($this->writersIds as $writerId){
            AmcWm::app()->db->createCommand(sprintf('insert into news_writers(article_id, writer_id) values (%d, %d)' , $this->article_id, $writerId))->execute();
        }
        parent::afterSave();
    }
    
    /**
     * Check if we can use writers options or not
     * @return boolean
     */
    public function getHasWriters(){
        return AmcWm::app()->db->createCommand('select count(*) from writers')->queryScalar();
    }
}