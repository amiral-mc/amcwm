<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "articles_translation".
 *
 * The followings are the available columns in table 'articles_translation':
 * @property string $article_id
 * @property string $content_lang
 * @property string $article_header
 * @property string $article_pri_header
 * @property string $article_detail
 * @property string $tags
 * @property string $image_description
 *
 * The followings are the available model relations:
 * @property ArticlesTitles[] $titles
 * @property Articles $parentContent
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesTranslation extends ChildTranslatedActiveRecord {

    public $step_title;
    public $create_date;
    public $section_name;
    public $comments;
    public $in_list;
    public $published;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ArticlesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'articles_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article_header, article_detail', 'required'),
            array('article_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('article_header, article_pri_header', 'length', 'max' => 500),
            array('tags', 'length', 'max' => 1024),
            array('image_description', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('article_id, content_lang, article_header, article_pri_header, article_detail, tags, image_description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'titles' => array(self::HAS_MANY, 'ArticlesTitles', 'article_id, content_lang'),
            'parentContent' => array(self::BELONGS_TO, 'Articles', 'article_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'article_id' => AmcWm::t("msgsbase.core", 'Article ID'),
            'article_header' => AmcWm::t("msgsbase.core", 'Article Header'),
            'article_pri_header' => AmcWm::t("msgsbase.core", 'Article Primary Header'),
            'tags' => AmcWm::t("msgsbase.core", 'Tags'),
            'article_detail' => AmcWm::t("msgsbase.core", 'Article Detail'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'image_description' => AmcWm::t("msgsbase.core", 'Image Description'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $articlesTables = array();
        $table = $this->parentContent->getModuleTable();
        if (AmcWm::app()->appModule) {
            $articlesTables = AmcWm::app()->appModule->getExtendsTables();
        }
        $criteria = new CDbCriteria;
        $criteria->compare('article_id', $this->article_id, true);
        $criteria->compare('t.content_lang', $this->content_lang);
        $criteria->compare('article_header', $this->article_header, true);
        $criteria->compare('article_pri_header', $this->article_pri_header, true);
        $criteria->compare('article_detail', $this->article_detail, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('image_description', $this->image_description, true);
        $criteria->compare('p.votes', $this->getParentContent()->votes, true);
        $criteria->compare('p.votes_rate', $this->getParentContent()->votes_rate);
        $criteria->compare('p.hits', $this->getParentContent()->hits, true);
        $criteria->compare('p.published', $this->getParentContent()->published);
        $criteria->compare('p.archive', $this->getParentContent()->archive);
        $criteria->compare('p.create_date', $this->getParentContent()->create_date, true);
        $criteria->compare('p.writer_id', $this->getParentContent()->writer_id, true);
        $criteria->compare('p.section_id', $this->getParentContent()->section_id);
        $criteria->compare('p.publish_date', $this->getParentContent()->publish_date, true);
        $criteria->compare('p.expire_date', $this->getParentContent()->expire_date, true);
        $criteria->compare('p.country_code', $this->getParentContent()->country_code, true);
        $criteria->join .=" inner join articles p on t.article_id = p.article_id";        
        $criteria->join .=" left join sections_translation st on p.section_id = st.section_id and st.content_lang = " . Yii::app()->db->quoteValue($this->content_lang);
        $sort = new CSort();
        $virtual = AmcWm::app()->appModule->getCurrentVirtual();
//        $route = AmcWm::app()->backendName."/{$virtual}/default/create";
//        die($route);
        $virtuals = AmcWm::app()->appModule->getVirtuals();
        if (isset($virtuals[$virtual]['customCriteria'])) {
            if (isset($virtuals[$virtual]['customCriteria']['join'])) {
                $criteria->join .= $virtuals[$virtual]['customCriteria']['join'];
            }
            $conditionGenerationClass = AmcWm::import($virtuals[$virtual]['customCriteria']['conditionGeneration']['class'], true);
            $conditionGeneration = new $conditionGenerationClass(AmcWm::app()->getIsBackend());
            $criteria->addCondition($conditionGeneration->getListCondition());
        }
        if ($table == $this->parentContent->tableName()) {
            foreach ($articlesTables as $articleTable) {
                $criteria->join .=" left join {$articleTable} on p.article_id = {$articleTable}.article_id ";
                $criteria->addCondition("{$articleTable}.article_id is null");
            }
        } else {
            $criteria->join .=" inner join {$table} on p.article_id = {$table}.article_id ";
        }
        $wheres = AmcWm::app()->appModule->getTablesWheres();
        if (isset($wheres[$table])) {

            foreach ($wheres[$table] as $data) {
                if (isset($data['inBackendOnly']) && $data['inBackendOnly'] == AmcWm::app()->getIsBackend()) {
                    if ($data['type'] != 'integer') {
                        $ref = AmcWm::app()->db->quoteValue(AmcWm::app()->request->getParam($data['ref']));
                    } else {
                        $ref = AmcWm::app()->request->getParam($data['ref']);
                    }

                    $criteria->addCondition(sprintf($table . '.' . $data['sql'], $ref), $data['operator']);
                }
            }
        }
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        $order = "{$sorting[$table]['sortField']} {$sorting[$table]['order']}";
        $criteria->select = "t.article_id, t.article_header, t.content_lang, p.published, p.in_list, p.comments, p.publish_date, p.create_date, st.section_name";
        if (AmcWm::app()->hasComponent("workflow")) {
            $currentFlow = AmcWm::app()->workflow->module->getFlowFromRoute(AmcWm::app()->backendName . "/articles/default/delete", false);
            $usersSteps = AmcWm::app()->workflow->module->getUserStepsIds();
            if (isset($usersSteps[$currentFlow['stepId']]) && isset($currentFlow['step_title']['DeleteContent'])) {
                $criteria->addCondition("p.published != " . ManageArticles::DELETE_APPROVAL);                
            }
            $taskJoin = AmcWm::app()->workflow->module->generateTaskJoin("t.article_id");
            //$criteria->join .= AmcWm::app()->workflow->module->generateTaskJoin("t.article_id");            
            if($taskJoin){                
                //$criteria->addCondition($taskCondition);
                $criteria->join .= $taskJoin;
                $criteria->select .= ", step_title";
            }
            $sort->defaultOrder = "workflow_tasks.step_id desc, p.section_id, {$order}";    
        }
        else{
            $sort->defaultOrder = "p.section_id , {$order}";    
        }

        
        
        
        $sort->attributes = array(
            'article_header' => array(
                'asc' => 'article_header',
                'desc' => 'article_header desc',
            ),
            'article_id' => array(
                'asc' => 'article_id',
                'desc' => 'article_id desc',
            ),
            'comments' => array(
                'asc' => 'comments',
                'desc' => 'comments desc',
            ),
            'section_id' => array(
                'asc' => 'st.section_name',
                'desc' => 'st.section_name desc',
            ),
            'create_date' => array(
                'asc' => 'create_date',
                'desc' => 'create_date desc',
            ),
            'content_lang' => array(
                'asc' => 't.content_lang',
                'desc' => 't.content_lang desc',
            ),
            'published' => array(
                'asc' => 'published',
                'desc' => 'published desc',
            ),
        );

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->displayTitle = $this->article_header;
        parent::afterFind();
    }

}