<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesListData class,  gets articles as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesListData extends SiteData {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * If equal true then append sub titles to the results
     * @var boolean 
     */
    private $_appendTitles = false;

    /**
     * parent article
     * @var integer 
     */
    protected $parentArticle = null;

    /**
     * Article language
     * @var integer 
     */
    protected $language = null;

    /**
     *
     * @var boolean , execlude articles  with empty details
     */
    protected $detailsIsNotEmpty = true;
    
    /**
     * Auto generate data set
     * @var boolean 
     */
    protected $generateDataset = true;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @todo fix bug if $limit = 0
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param integer $period, Period time in seconds. 
     * @param integer $limit, The numbers of items to fetch from table     
     * @param integer $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct($tables = array(), $period = 0, $limit = 10, $sectionId = null) {
        if (!$this->language) {
            $this->language = Yii::app()->getLanguage();
        }
        $this->route = "/articles/default/view";
        $this->period = $period;
        if ($limit !== NULL) {
            $this->limit = (int) $limit;
        } else {
            $this->limit = null;
        }
        $this->tables = $tables;
        $this->sectionId = $sectionId;
        $wheresForTables = array();
        $tablesCount = count($this->tables);
        if ($tablesCount == 1) {
            $table = current($this->tables);
            if ($table != "articles") {
                $this->joins .= " inner JOIN {$table} ON t.article_id = {$table}.article_id ";
            }
        } else if ($tablesCount > 1) {
            foreach ($this->tables as $table) {
                if ($table != "articles") {
                    $this->joins .= " LEFT JOIN {$table} ON t.article_id = {$table}.article_id ";
                    $wheresForTables[] = " {$table}.article_id is not null ";
                }
            }
            $this->addWhere("(" . implode(" or ", $wheresForTables) . ")");
        }

        $this->addWhere("(t.in_list = 1)");
        if ($this->parentArticle) {
            $this->addWhere("t.parent_article = " . (int) $this->parentArticle);
        } else {
            $this->addWhere("t.parent_article is null");
        }

        if (isset(Yii::app()->useIssue) && Yii::app()->useIssue) {
            $currentIssue = Issue::getInstance()->getCurrentIssueId();
            $this->joins .= " inner JOIN issues_articles isa ON t.article_id = isa.article_id ";
            $this->addWhere("isa.issue_id = " . $currentIssue);
        }

        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths['list']['path'] . "/";
    }

    /**
     * Get articles setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("articles", false);
        }
        return self::$_settings;
    }

    /**
     * Get articles used in the system
     * @return array
     * @access public 
     */
    static public function getArticlesTables() {
        return self::getSettings()->getExtendsTables();
    }

    /**
     * Auto generate dataset
     * @param boolean $ok
     */
    public function setAutoGenerate($ok){
        $this->generateDataset = $ok;
    }
    /**
     *
     * Generate articles lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if ($this->detailsIsNotEmpty) {
            $this->addWhere("tt.article_detail is not null");
        }
        if ($this->period) {
            $this->toDate = date('Y-m-d 23:59:59');
            $this->fromDate = date('Y-m-d 00:00:01', time() - $this->period);
        }
        if ($this->fromDate) {
            $this->addWhere("t.{$this->dateCompareField} >= '{$this->fromDate}'");
        }
        if ($this->toDate) {
            $this->addWhere("t.{$this->dateCompareField} <='{$this->toDate}'");
        }
        if (!count($this->orders)) {
            $virtual = self::getSettings()->getCurrentVirtual();
            $sorting = self::getSettings()->getTablesSoringOrders();
            if (isset($sorting[$virtual])) {
                $this->addOrder("{$sorting[$virtual]['sortField']} {$sorting[$virtual]['order']}");
            } else {
                $this->addOrder("hits desc");
            }
        }
        switch ($this->archive) {
            case 1:
                $this->addWhere('(t.archive = 0 or t.archive is null)');
                break;
            case 2:
                $this->addWhere('t.archive = 1');
                break;
        }
        $this->setItems();
    }

    /**
     * If the given $ok equal true then execlude articles  with empty details
     * @param boolean $ok
     * @return void
     */
    public function setDetailsIsNotEmpty($ok) {
        $this->detailsIsNotEmpty = $ok;
    }

    /**
     * Set the articles parent id
     * @param integer $articleId
     * @access public
     * @return void
     */
    public function setParentArticle($articleId) {
        $this->parentArticle = $articleId;
    }

    /**
     * If the given $ok equal true then append sub titles to the results
     * @param boolean $ok
     * @access public
     * @return void
     */
    public function appendTitles($ok) {
        $this->_appendTitles = $ok;
    }

    /**
     * set article $language
     * @access public
     * @return void
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * @todo explain the query
     * Set the articles array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        $currentDate = date("Y-m-d H:i:s");
        $sectionsList = array();
        if ($this->sectionId) {
            if ($this->useSubSections) {
                if (is_array($this->sectionId)) {
                    $sections = $this->sectionId;
                    foreach ($sections as $section) {
                        $sectionList = Data::getInstance()->getSectionSubIds($section);
                        $sectionList[] = (int) $section;
                        if (is_array($sectionList) && $sectionList) {
                            $sectionsList = array_merge($sectionsList, $sectionList);
                        }
                    }
                } else {
                    $sectionsList = Data::getInstance()->getSectionSubIds($this->sectionId);
                    $sectionsList[] = (int) $this->sectionId;
                }
                $this->addWhere("(t.section_id in (" . implode(',', $sectionsList) . "))");
            } else {
                $this->addWhere("t.section_id = {$this->sectionId}");
            }
        }
        $orders = $this->generateOrders(NULL);
        $cols = $this->generateColumns();
        $wheres = sprintf("tt.content_lang = %s
         and t.publish_date <= '{$currentDate}'            
         and (t.expire_date  >= '{$currentDate}' or t.expire_date is null)  
         and t.published = %d", Yii::app()->db->quoteValue($this->language), ActiveRecord::PUBLISHED);
        $wheres .= $this->generateWheres();
        $command = AmcWm::app()->db->createCommand();
        $command->from("articles t force index (articles_create_date_idx)");
        $command->join = 'inner join articles_translation tt on t.article_id = tt.article_id';
        $command->join .= $this->joins;
        $command->select("t.article_id, t.hits, t.thumb, tt.article_header $cols");
        $command->where($wheres);
        $command->order = $orders;
        $this->count = Yii::app()->db->createCommand("select count(*) from articles t {$command->join} where {$command->where}")->queryScalar();
        if ($this->limit !== null) {
            $command->limit($this->limit, $this->fromRecord);
        }
        if ($this->generateDataset) {
            $articles = $command->queryAll();
            $this->setDataset($articles);
        }
        $this->query = $command;
    }

    /**
     *
     * Sets the the ArticlesListData.items array      
     * @param array $articles 
     * @access protected     
     * @return void
     */
    protected function setDataset($articles) {
        $index = -1;
        $options = self::getSettings()->options;
        $useSeoImages = isset($options['default']['check']['seoImages']) && $options['default']['check']['seoImages'] ? $options['default']['check']['seoImages'] : false;
        foreach ($articles As $article) {
            if ($this->recordIdAsKey) {
                $index = $article['article_id'];
            } else {
                $index++;
            }
            if ($this->titleLength) {
                $this->items[$index]['title'] = Html::utfSubstring($article["article_header"], 0, $this->titleLength);
            } else {
                $this->items[$index]['title'] = $article["article_header"];
            }
            $seoTitle = ($useSeoImages) ? Html::seoTitle($article["article_header"]) . "." : "";

            $this->items[$index]['id'] = $article["article_id"];
            $urlParams = array('id' => $article['article_id'], 'title' => $article["article_header"]);
            foreach ($this->params as $paramIndex => $paramValue) {
                $urlParams[$paramIndex] = $paramValue;
            }
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), $urlParams);
            if ($this->_appendTitles) {
                $query = "select title from articles_titles where article_id = {$article['article_id']}";
                $titles = Yii::app()->db->createCommand($query)->queryAll(false);
                $this->items[$index]['titles'] = array();
                foreach ($titles as $title) {
                    $this->items[$index]['titles'][] = $title[0];
                }
            }
            if ($article["thumb"]) {
                $this->items[$index]['imageExt'] = $article["thumb"];
                $this->items[$index]['image'] = $this->mediaPath . "{$seoTitle}" . $article["article_id"] . "." . $article["thumb"];
            } else {
                $this->items[$index]['imageExt'] = null;
                $this->items[$index]['image'] = null;
            }
            $this->items[$index]['type'] = $this->type;

            if ($this->checkIsActive) {
                $this->items[$index]['isActive'] = Data::getInstance()->isCurrentRoute($this->route, array("id" => $article['article_id']));
            }

            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $article[$colIndex];
            }
        }
    }

}
