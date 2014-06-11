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
 * @property NewsEditors[] $editors
 * @author Amiral Management Corporation
 * @version 1.0
 */
class News extends ActiveRecord {

    const ACTIVE_BREAKING = 1;
    const EXPIRE_BREAKING = 2;

    /**
     * 
     * @var string editors ids saved in news_editors
     */
    public $editorsIds;

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
            array('editorsIds', 'isArray', 'allowEmpty' => true),
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
            'editors' => array(self::HAS_MANY, 'NewsEditors', 'article_id', 'index' => 'editor_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' => AmcWm::t("msgsbase.core", 'Article ID'),
            'is_breaking' => AmcWm::t("msgsbase.news", 'Breaking News'),
            'source_id' => AmcWm::t("msgsbase.news", 'Source'),
            'editorsIds' => AmcWm::t("msgsbase.news", 'Editors'),
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
        $this->editorsIds = implode(",", array_keys($this->editors));
        parent::afterFind();
    }

    /**
     * This method is invoked after saving a record successfully.
     * The default implementation raises the {@link onAfterSave} event.
     * You may override this method to do postprocessing after record saving.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave() {
        AmcWm::app()->db->createCommand('delete from news_editors where article_id = ' . (int) $this->article_id)->execute();
        if ($this->editorsIds) {
            $editorsIds = explode(',', $this->editorsIds);
            //print_r($editorsIds);
            //die();
            if (is_array($editorsIds)) {
                foreach ($editorsIds as $editorId) {
                    AmcWm::app()->db->createCommand(sprintf('insert into news_editors(article_id, editor_id) values (%d, %d)', $this->article_id, $editorId))->execute();
                }
            }
            $options = AmcWm::app()->appModule->getOptions();
            $query = 'update news'
                    . ' inner join articles on news.article_id = articles.article_id '
                    . ' set is_breaking = ' . self::EXPIRE_BREAKING
                    . ' where is_breaking = 1 and articles.publish_date < "' . date("Y-m-d H:i:s", time() - $options['news']['default']['integer']['breakingExpiredAfter']) . '"';
            AmcWm::app()->db->createCommand($query)->execute();
        }
        parent::afterSave();
    }

    /**
     * Check if we can use editors options or not
     * @return boolean
     */
    public function getHasEditors() {
        return AmcWm::app()->db->createCommand("select count(*) from writers where writer_type in (" . Writers::EDITOR_TYPE . ", " . Writers::BOTH_TYPE . ")")->queryScalar();
    }

    /**
     * This method is invoked before each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
        if ($this->isNewRecord && $virtual == "breaking") {
            $this->is_breaking = self::ACTIVE_BREAKING;
        }
        return parent::beforeSave();
    }

}
