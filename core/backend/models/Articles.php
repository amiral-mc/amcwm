<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "articles".
 *
 * The followings are the available columns in table 'articles':
 * @property string $article_id
 * @property integer $section_id
 * @property string $votes
 * @property double $votes_rate
 * @property string $hits
 * @property integer $published
 * @property integer $archive
 * @property string $create_date
 * @property string $writer_id
 * @property string $publish_date
 * @property string $expire_date
 * @property integer $published_mobile
 * @property string $thumb
 * @property string $page_img
 * @property string $country_code
 * @property string $update_date
 * @property integer $comments
 * @property integer $article_sort
 * @property integer $in_ticker
 * @property string $in_slider
 * @property integer $in_spot
 * @property integer $in_list
 * @property integer $parent_article
 * @property integer $is_system
 *
 * The followings are the available model relations:
 * @property Countries $countryCode
 * @property Sections $section
 * @property Writers $writer
 * @property ArticlesComments[] $articleComments
 * @property ArticlesTranslation[] $translationChilds
 * @property Infocus[] $infocuses
 * @property Issues[] $issues
 * @property MaillistArticlesLog[] $maillistArticlesLogs
 * @property News $news
 * @property Essays $essays
 * @property UsersArticles $usersArticles 
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 * 
 */
class Articles extends ParentTranslatedActiveRecord {

    /**
     * Paging limit when displaying articles in dropdown
     */
    const REF_PAGE_SIZE = 10;
    /**
     * Sort field name
     * @var string 
     */
    protected $sortField = "article_sort";

    /**
     * Sort Dependency attributes
     * @var string 
     */
    protected $sortDependencyFields = array('section_id');

    /**
     * Slider uploader file used to upload article thumb image
     * @var array
     * @access public
     */
    public $sliderFile = null;

    /**
     * Image uploader file used to upload article thumb image
     * @var array
     * @access public
     */
    public $imageFile = null;

    /**
     * image uploader used for uploading the article view page top image
     * @var array
     */
    public $pageImg = null;

    /**
     * Social ids added to this active record
     * @var array
     * @access public
     */
    public $socialIds = array();

    /**
     * Current infocus Id
     * @var integer 
     */
    public $infocusId;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Articles the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'articles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $date = date("Y-m-d H:i:s");
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('publish_date', 'required'),
            array('is_system, infocusId, section_id, in_spot , article_sort , comments,  published, archive, published_mobile, in_ticker', 'numerical', 'integerOnly' => true),
            array('in_ticker, in_spot, in_list', 'length', 'max' => 1),
            array('votes_rate', 'numerical'),            
            array('socialIds', 'isArray', 'allowEmpty' => true),
            array('article_id, votes, hits, writer_id, comments, article_sort, parent_article', 'length', 'max' => 10),
            array('thumb, page_img, in_slider', 'length', 'max' => 3),
            array('country_code', 'length', 'max' => 2),
            array('expire_date, update_date', 'safe'),
            array('expire_date', 'compare', 'compareAttribute' => 'publish_date', 'operator' => '>', 'allowEmpty' => true),
            array('publish_date', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>=', 'on' => 'insert'),
            array('expire_date', 'safe'),
            array('update_date', 'default',
                'value' => $date,
                'setOnEmpty' => false),
            array('imageFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxImageSize']),
            array('imageFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['images']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('pageImg', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxImageSize']),
            array('pageImg', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['pageImage']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('sliderFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxImageSize']),
            array('sliderFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['slider']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            array('sliderFile', 'validateInSlider'),
            array('create_date', 'default',
                'value' => $date,
                'setOnEmpty' => false, 'on' => 'insert'),
            //array('expire_date,publish_date', 'type' , 'datetimeFormat'=>'Y-m-d'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('article_id, section_id, parent_article, votes, votes_rate, hits, published, archive, create_date, writer_id, publish_date, expire_date, published_mobile, thumb, page_img, country_code, update_date, comments, article_sort, in_ticker, in_spot, in_slider, in_list', 'safe', 'on' => 'search'),
        );
    }

    public function validateInSlider($attribute, $params) {
        if ($this->in_slider) {
            if (!$this->sliderFile instanceof CUploadedFile) {
                $sliderFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $this->getModuleSettings()->mediaPaths['slider']['path'] . "/" . $this->article_id . "." . $this->in_slider);
                if (!is_file($sliderFile)) {
                    $this->addError($attribute, Yii::t('yii', "{attribute} cannot be blank.", array("{attribute}" => $this->getAttributeLabel($attribute))));
                }
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'countryCode' => array(self::BELONGS_TO, 'Countries', 'country_code'),
            'section' => array(self::BELONGS_TO, 'Sections', 'section_id'),
            'writer' => array(self::BELONGS_TO, 'Writers', 'writer_id'),
            'articleComments' => array(self::HAS_MANY, 'ArticlesComments', 'article_id'),
            'translationChilds' => array(self::HAS_MANY, 'ArticlesTranslation', 'article_id', "index" => "content_lang"),
            'infocuses' => array(self::HAS_MANY, 'InfocusHasArticles', 'article_id', "index" => "infocus_id"),
            'issues' => array(self::HAS_MANY, 'IssuesArticles', 'article_id', "index" => "issue_id"),
            'maillistArticlesLogs' => array(self::HAS_MANY, 'MaillistArticlesLog', 'article_id'),
            'news' => array(self::HAS_ONE, 'News', 'article_id'),
            'usersArticles' => array(self::HAS_ONE, 'UsersArticles', 'article_id'),
            'dirCompaniesArticles' => array(self::HAS_ONE, 'DirCompaniesArticles', 'article_id'),
            'issuesArticles' => array(self::HAS_ONE, 'IssuesArticles', 'article_id'),
            'essays' => array(self::HAS_ONE, 'Essays', 'article_id'),
            'parentArticle' => array(self::BELONGS_TO, 'Articles', 'parent_article'),            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' => AmcWm::t("msgsbase.core", 'Article ID'),
            'country_code' => AmcWm::t("msgsbase.core", 'Country'),
            'votes' => AmcWm::t("msgsbase.core", 'Votes'),
            'votes_rate' => AmcWm::t("msgsbase.core", 'Votes Rate'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'archive' => AmcWm::t("msgsbase.core", 'Archive'),
            'writer_id' => AmcWm::t("msgsbase.core", 'Writer'),
            'section_id' => AmcWm::t("msgsbase.core", 'Section'),
            'publish_date' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'expire_date' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'socialIds' => AmcWm::t("msgsbase.core", 'Social Networks'),
            'thumb' => AmcWm::t("msgsbase.core", 'Article Photo'),
            'imageFile' => AmcWm::t("msgsbase.core", 'Article Photo'),
            'page_img' => AmcWm::t("msgsbase.core", 'Page Photo'),
            'pageImg' => AmcWm::t("msgsbase.core", 'Page Photo'),
            'infocusId' => AmcWm::t("msgsbase.core", 'In Focus File'),
            'comments' => AmcWm::t("msgsbase.core", 'Comments Counts'),
            'published_mobile' => AmcWm::t("msgsbase.core", 'Published for mobile'),
            'article_sort' => AmcWm::t("msgsbase.core", 'Sort order'),
            'in_ticker' => AmcWm::t("msgsbase.core", 'In Ticker'),
            'in_slider' => AmcWm::t("msgsbase.core", 'In Slider'),
            'in_spot' => AmcWm::t("msgsbase.core", 'In Spot'),
            'in_list' => AmcWm::t("msgsbase.core", 'In List'),
            'sliderFile' => AmcWm::t("msgsbase.core", 'Slider File'),
            'parent_article' => AmcWm::t("msgsbase.core", 'Parent Article'),
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
        $criteria->compare('section_id', $this->section_id);
        $criteria->compare('votes', $this->votes, true);
        $criteria->compare('votes_rate', $this->votes_rate);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('archive', $this->archive);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('writer_id', $this->writer_id, true);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('published_mobile', $this->published_mobile);
        $criteria->compare('thumb', $this->thumb, true);
        $criteria->compare('page_img', $this->page_img, true);
        $criteria->compare('country_code', $this->country_code, true);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('article_sort', $this->article_sort, true);
        $criteria->compare('in_ticker', $this->in_ticker);
        $criteria->compare('in_slider', $this->in_slider);
        $criteria->compare('in_spot', $this->in_spot);
        $criteria->compare('parent_article', $this->parent_article);

//        $sort = new CSort();
//        $sort->defaultOrder = 'create_date desc';

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
//                    'sort' => $sort,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {        
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
        $info = new SocialInfo($virtual, 1 , $this->article_id);     
        $this->socialIds = $info->getSocialIds();
        $virtuals = AmcWm::app()->appModule->getVirtuals();
        if (isset($virtuals[$virtual]['customCriteria'])) {
            $conditionGenerationClass = AmcWm::import($virtuals[$virtual]['customCriteria']['conditionGeneration']['class']);
            $conditionGeneration = new $conditionGenerationClass(AmcWm::app()->getIsBackend());
            $conditionGeneration->canManage($this);
        }
        if (!$this->expire_date || $this->expire_date == '0000-00-00 00:00:00') {
            $this->expire_date = NULL;
        }
        if (!$this->publish_date || $this->publish_date == '0000-00-00 00:00:00') {
            $this->publish_date = NULL;
        }
        if (count($this->infocuses)) {
            foreach ($this->infocuses as $infocus){
                if(isset($infocus->infocus_id)){
                    $this->infocusId = $infocus->infocus_id;
                    break;
                }
            }
        }
        $current = $this->getCurrent();
        if ($current instanceof ChildTranslatedActiveRecord) {
            $this->displayTitle = $current->article_header;
        }
        parent::afterFind();
    }

    /**
     * This method is invoked before each record has been saved
     * @access public
     * @return boolean
     */
    protected function beforeSave() {
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
        $virtuals = AmcWm::app()->appModule->getVirtuals();
        if (isset($virtuals[$virtual]['customCriteria'])) {
            $conditionGenerationClass = AmcWm::import($virtuals[$virtual]['customCriteria']['conditionGeneration']['class']);
            $conditionGeneration = new $conditionGenerationClass(AmcWm::app()->getIsBackend());
            $conditionGeneration->canManage($this);
        }
        if (!$this->expire_date || $this->expire_date == '0000-00-00 00:00:00') {
            $this->expire_date = NULL;
        }
        if (!$this->publish_date || $this->publish_date == '0000-00-00 00:00:00') {
            $this->publish_date = NULL;
        }
        if (!$this->writer_id) {
            $this->writer_id = NULL;
        }
        if (!$this->parent_article) {
            $this->parent_article = NULL;
        }
        if (!$this->country_code) {
            $this->country_code = NULL;
        }
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $types = explode(",", $mediaSettings['info']['extensions']);
        foreach ($types as &$type) {
            $type = trim($type);
        }
        if (array_search($this->in_slider, $types) === false) {
            $this->in_slider = null;
        }
        if (array_search($this->thumb, $types) === false) {
            $this->thumb = null;
        }
        if (array_search($this->page_img, $types) === false) {
            $this->page_img = null;
        }
        return parent::beforeSave();
    }

    /**
     * @todo delete parent article cache and the subs and if parent article change then delete the old parent article cache and the subs
     * @todo i fixed the above todo but need to test it
     */
    protected function afterSave() {
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
        $virtuals = AmcWm::app()->appModule->getVirtuals();
        if (isset($virtuals[$virtual]['customCriteria'])) {
            $conditionGenerationClass = AmcWm::import($virtuals[$virtual]['customCriteria']['conditionGeneration']['class']);
            $conditionGeneration = new $conditionGenerationClass(AmcWm::app()->getIsBackend());
            $conditionGeneration->saveRelated($this);
        }        
        $cache = Yii::app()->getComponent('cache');
        if ($cache !== null) {
            if (isset($this->oldAttributes['parent_article']) && $this->oldAttributes['parent_article'] != $this->parent_article) {
                $query = "select article_id from articles where parent_article = " . (int) $this->oldAttributes['parent_article'];
                $subArticles = Yii::app()->db->createCommand($query)->queryAll();
                $cache->delete('article_' . $this->oldAttributes['parent_article']);
                foreach ($subArticles as $subArticle) {
                    $cache->delete('article_' . $subArticle['article_id']);
                }
            }
            if ($this->parent_article) {
                $query = "select article_id from articles where parent_article = " . (int) $this->parent_article;
                $subArticles = Yii::app()->db->createCommand($query)->queryAll();
                $cache->delete('article_' . $this->parent_article);
                foreach ($subArticles as $subArticle) {
                    $cache->delete('article_' . $subArticle['article_id']);
                }
            } else {
                $cache->delete('article_' . $this->article_id);
            }
        }
        if ($this->infocusId) {
            Yii::app()->db->createCommand('delete from infocus_has_articles where article_id = ' . (int) $this->article_id)->execute();
            Yii::app()->db->createCommand('insert into infocus_has_articles (infocus_id, article_id) values(' . (int) $this->infocusId . ', ' . (int) $this->article_id . ')')->execute();
        }
        $info = new SocialInfo($virtual, 1 , $this->article_id);     
        $info->saveSocial($this->socialIds);
        $this->saveRelatedVirtual();
        parent::afterSave();
    }

    public function saveRelatedVirtual() {
        $settings = new Settings("articles", true);
        $virtuals = $settings->getVirtual($settings->getCurrentVirtual());
        if (isset($virtuals['saveMethod'])) {
            $saveModel = $virtuals['saveMethod'];
            $tableClass = $virtuals['tableModel'];
            $this->$tableClass->$saveModel($this->article_id);
        }
    }

    /**
     * Sort the given model acording to $direction order
     * @param string $direction
     * @param string $language content language
     * @param string $condition condition to be added to update query
     * @access protected
     * @return boolean
     */
    public function sort($direction = "up", $condition = null) {
        if ($this->section_id) {
            $condition = "section_id = " . (int) $this->section_id;
        } else {
            $condition = "section_id is null";
        }
        parent::sort($direction, $condition);
    }

    /**
     * This method is invoked after deleting a active record translion child.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return void
     */
    protected function afterDeleteChild($childAttributes) {
        return $this->correctSort();
    }

    public static function getArticles($pageNumber = 1, $keyword = null, $sectionId = null, $newsOnly = false, $excludeArticle = null) {
        if (!$pageNumber) {
            $pageNumber = 1;
        }
        $wheres = $joins = array();
        if ($excludeArticle) {
            $wheres[] = 't.article_id <> ' . (int) $excludeArticle;
        }
        if ($sectionId) {
            $wheres[] = 't.section_id = ' . (int) $sectionId;
        }
        $keyword = trim($keyword);
        if ($keyword) {
            $wheres[] = sprintf('tt.article_header like %s', Yii::app()->db->quoteValue("%{$keyword}%"));
        }

        $wheres[] = 't.published = ' . ActiveRecord::PUBLISHED;
        $joins[] = ' inner join articles_translation tt on tt.article_id=t.article_id ';
        if ($newsOnly) {
            $joins[] = 'inner join news n on n.article_id=t.article_id';
        } else {
            $joins[] = 'left join news n on n.article_id=t.article_id';
            $wheres[] = 'n.article_id is null';
        }
        $wheres[] = sprintf('tt.content_lang = %s', Yii::app()->db->quoteValue(Controller::getContentLanguage()));

        $where = "WHERE " . implode(" AND ", $wheres);
        $join = implode(" ", $joins);

       $queryCount = "SELECT count(*)
                    FROM articles t
                    {$join}
                    {$where}
                    ";
       $query = "SELECT t.article_id id, tt.article_header text
                    FROM articles t
                    {$join}
                    {$where}
                    limit " . self::REF_PAGE_SIZE . " offset " . (self::REF_PAGE_SIZE * ($pageNumber - 1));
        return array('records'=>Yii::app()->db->createCommand($query)->queryAll(), 'total'=>Yii::app()->db->createCommand($queryCount)->queryScalar());            
    }

    /**
     * This method is invoked before deleting a record.
     * The default implementation raises the {@link onBeforeDelete} event.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the record should be deleted. Defaults to true.
     */
    protected function beforeDelete() {
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
        $virtuals = AmcWm::app()->appModule->getVirtuals();
        if (isset($virtuals[$virtual]['customCriteria'])) {
            $conditionGenerationClass = AmcWm::import($virtuals[$virtual]['customCriteria']['conditionGeneration']['class']);
            $conditionGeneration = new $conditionGenerationClass(AmcWm::app()->getIsBackend());
            $conditionGeneration->canManage($this);
        }
        return parent::beforeDelete();
    }

}