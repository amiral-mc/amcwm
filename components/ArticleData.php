<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticleData class, Gets the article record for a given article id
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticleData extends Dataset {

    /**
     * Module name , news or articles .. etc
     * @var string 
     */
    private $_moduleName;

    /**
     * Article id, to get record based on it
     * @var integer
     */
    private $_id;
    //private $articleData = array();
    /**
     * Cache implemented in application, used for caching this record  
     * @var CCache 
     */
    private $_cache = null;

    /**
     * Instance of facebook comment class
     * @var FacebookComments
     */
    private $_facebookComments;

    /**
     * Counstructor, the content type
     * @param integer $articleId 
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($articleId, $autoGenerate = true) {
        $this->_cache = Yii::app()->getComponent('cache');
        $this->_id = (int) $articleId;
        if ($autoGenerate) {
            $this->generate();
        }
    }

    /**
     * Gets the module name that handle the output
     * @access public
     * @return string 
     */
    public function getModuleName() {
        if (!$this->_moduleName) {
            $articlesTables = ArticlesListData::getArticlesTables();
            $this->_moduleName = "articles";
            foreach ($articlesTables as $module => $table) {
                $inTable = Yii::app()->db->createCommand("select article_id from $table where article_id ={$this->_id}")->queryScalar();
                if ($inTable) {
                    $this->_moduleName = $module;
                    break;
                }
            }
        }
        return $this->_moduleName;
    }

    /**
     * Gets the article record associated array
     * @return array 
     * @access public
     */
    public function getArticle() {
        $record = $this->items['record'];
        return $record;
    }

    /**
     * Gets the article record associated array
     * @return array 
     * @access public
     */
    public function getSubs() {
        return $this->items['subs'];
    }

    public function getParentSubs() {
        return $this->items['parentSubs'];
    }

    public function getArticleSubs() {
        if (count($this->getSubs())) {
            $subs = $this->getSubs();
        } else {
            $subs = $this->getParentSubs();
        }
        return $subs;
    }

    /**
     * Gets the the comments associated array inside article dataset array that contain's following items:
     * <ul>
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, article id</li>
     * <li>content: string , article header</li>
     * </ul>
     * <li>records: array, specifies the comments record</li>    
     * <li>pager: array list thst include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * <ul>    
     * @return array 
     * @access public
     */
    public function getComments() {
        $comments = $this->items['comments'];
        return $comments;
    }

    /**
     * Get instance of facebook comment class
     * @return FacebookComments 
     */
    public function getFacebookComments() {
        return $this->_facebookComments;
    }

    /**
     * Get article comments count, if cache is not implemented then get the comments from article dataset otherwise get the comments using query 
     * @return integer 
     */
    public function getCommentsCount() {
        $comments = $this->items['record']['comments'];
        if ($this->_cache !== null) {
            $comments = Yii::app()->db->createCommand("select comments from articles where article_id = {$this->_id} ")->queryScalar();
        }
        return $comments;
    }

    /**
     * Get article hits count, if cache is not implemented then get the hits from article dataset otherwise get the hits using query 
     * @return integer 
     * @access public
     */
    public function getHits() {
        if ($this->_cache !== null) {
            $hits = Yii::app()->db->createCommand("select hits from articles where article_id = {$this->_id} ")->queryScalar();
        } else {
            $hits = isset($this->items['record']['hits']) ? $this->items['record']['hits'] : 0;
        }
        return $hits;
    }

    /**
     * Check if the article record found in the database table or not
     * @return boolean
     * @access public
     */
    public function recordIsFound() {
        return isset($this->items['record']) && is_array($this->items['record']) && count($this->items['record']);
    }

    /**
     * sets the the comments associated array inside article dataset array that contain's following items:
     * <ul>     
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, article id</li>
     * <li>content: string , article header</li>
     * </ul>     
     * <li>records: array, specifies the comments record</li>    
     * <li>pager: array list thst include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * <ul>    
     * @access private
     * @return void
     */
    private function _setComments() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $pageSize = Yii::app()->params['pages']['comments'];
        $query = sprintf("
            select c.*, co.name cName, p.name pName 
            from comments c
            inner join articles_comments ac on ac.article_comment_id = c.comment_id
            left join comments_owners co on co.comment_id = c.comment_id
            left join persons_translation p on p.person_id = c.user_id and p.content_lang = %s
            where c.published = %d
            and (c.hide = 0 OR c.force_display=1)
            and ac.article_id = %d
            order by c.comment_date DESC", Yii::app()->db->quoteValue($siteLanguage)
                , ActiveRecord::PUBLISHED
                , $this->_id
        );
        $comments = Yii::app()->db->createCommand($query)->queryAll();
        $count = count($comments);
        $this->items['comments']['records'] = array();
        foreach ($comments AS $index => $comment) {
            $this->items['comments']['records'][$index]["id"] = $comment["comment_id"];
            $this->items['comments']['records'][$index]["bad_imp"] = $comment["bad_imp"];
            $this->items['comments']['records'][$index]["good_imp"] = $comment["good_imp"];
            $this->items['comments']['records'][$index]["owner"] = ($comment["cName"] != null) ? $comment["cName"] : $comment["pName"];
            $this->items['comments']['records'][$index]["title"] = $comment["comment_header"];
            $this->items['comments']['records'][$index]["details"] = $comment["comment"];
            $this->items['comments']['records'][$index]["date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $comment["comment_date"]);
            $this->items['comments']['records'][$index]["replies"] = array();
            $this->_setReplies($comment["comment_id"], $index);
        }
        $this->items['comments']['content']['id'] = $this->_id;
        $this->items['comments']['content']['title'] = $this->items['record']['article_header'];
        if (count($this->items['comments']['records'])) {
            $commentsRecords = $this->items['comments']['records'];
            $pageSize = Yii::app()->params['pages']['comments'];
            $pageNo = (int) Yii::app()->request->getParam("page");
            if (!$pageNo) {
                $pageNo = 1;
            }

            $this->items['comments']['records'] = array_slice($commentsRecords, ($pageNo - 1 ) * $pageSize, $pageSize);
        }
        $this->items['comments']['pager'] = array('pageSize' => $pageSize, 'count' => $count,);
    }

    /**
     * Sets the replies records for the given $commentId
     * @param integer $commentId the comment id the get the replies related to it
     * @param integer $index the array index key inside the comments array
     * @return void
     */
    private function _setReplies($commentId, $index) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $query = sprintf(" 
            select c.*, co.name cName, p.name pName from comments c 
            left join comments_owners co on co.comment_id = c.comment_id
            left join persons_translation p on p.person_id = c.user_id and p.content_lang = %s
            where c.published = %d
            and (c.hide = 0 OR c.force_display=1)
            and c.comment_review = %d
            order by c.comment_date DESC
        ", Yii::app()->db->quoteValue($siteLanguage)
                , ActiveRecord::PUBLISHED
                , $commentId);
        $replies = Yii::app()->db->createCommand($query)->queryAll();
        $this->items['comments']['records'][$index]["replies"] = array();
        foreach ($replies AS $replay) {
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["id"] = $replay["comment_id"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["bad_imp"] = $replay["bad_imp"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["good_imp"] = $replay["good_imp"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["owner"] = ($replay["cName"] != null) ? $replay["cName"] : $replay["pName"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["title"] = $replay["comment_header"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["details"] = $replay["comment"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $replay["comment_date"]);
        }
    }

    /**
     * Initilaize items array, adding required array keys
     * @access private
     * @return void
     * 
     */
    private function _initItem() {
        $this->items = array(
            'record' => array(),
            'subs' => array(),
            'parentSubs' => array(),
            'comments' => array('content' => array('id' => NULL, 'title' => NULL,), 'records' => array(), 'pager' => array('pageSize' => 10, 'count' => 0)),
        );
    }

    /**
     * Generate the article dataset array,
     * @todo change facebook commment to cron job     
     * @access public
     * @return void
     */
    public function generate($start = 0) {
//        $mostCommentReadWidget = false; // ($cache) ? Yii::app()->cache->get("mostReadComments{$currentAppLang}") : false;
//        if($cache){
//            Yii::app()->cache->set("mostReadComments{$currentAppLang}", $mostCommentReadWidget, Yii::app()->params["cacheDuration"]["comments"]);
//        }
        $dependencyComments = null;
        $cacheMe = false;
        //$this->_facebookComments = new FacebookComments(Yii::app()->request->getHostInfo() . Yii::app()->request->getUrl());
        $facebookCommentsCount = 0; // $this->_facebookComments->getCommentsCount();
        $commentsCount = 0;
        $this->_initItem();
        if ($this->_cache !== null) {
            $dependencyQuery = sprintf("select count(*) from comments c 
            inner join articles_comments ac on ac.article_comment_id = c.comment_id
            where c.published = %d 
            and (c.hide = 0 OR c.force_display=1) 
            and ac.article_id = %d", ActiveRecord::PUBLISHED, $this->_id);
            $dependencyComments = Yii::app()->db->createCommand($dependencyQuery)->queryScalar();
            $this->items = $this->_cache->get('article_'  . AmcWm::app()->getLanguage() . $this->_id);
            if (!$this->items || !isset($this->items['record']) || !$this->items['record']) {
                $this->_initItem();
            } else {
                isset($this->items['record']['comments']) ? $this->items['record']['comments'] = $this->getCommentsCount() : 0;
                isset($this->items['record']['hits']) ? $this->items['record']['hits'] = $this->getHits() : 0;
            }
        }
        if (!$this->items['record']) {
            $this->setItems();
            $cacheMe = true;
        }
        if ($dependencyComments != $this->items['comments']['pager']['count'] || $dependencyComments === null) {
            $this->_setComments();
            $cacheMe = true;
        }
        if ($this->items['record']) {
            if (!$this->items['comments']['content']['id']) {
                $this->items['comments']['content']['id'] = $this->_id;
                $this->items['comments']['content']['title'] = $this->items['record']['article_header'];
            }
            $commentsCount = $this->items['comments']['pager']['count'] + $facebookCommentsCount;
            $this->items['comments']['pager']['count'] = $commentsCount;
            $this->items['record']['comments'] = $commentsCount;
            $correctedCommentsQuery = sprintf('update articles set comments = %d where comments <> %d and article_id = %d', $commentsCount, $commentsCount, $this->_id);
            Yii::app()->db->createCommand($correctedCommentsQuery)->execute();
            $cookieName = "hits_{$this->_id}";
            if (!isset(Yii::app()->request->cookies[$cookieName]->value)) {
                Yii::app()->db->createCommand("update articles set hits=hits+1 where article_id = {$this->_id} ")->execute();
                $cookie = new CHttpCookie($cookieName, $cookieName);
                $cookie->expire = time() + 3600;
                $cookie->httpOnly = true;
                Yii::app()->request->cookies[$cookieName] = $cookie;
            }
        }
        if ($this->_cache !== null && $cacheMe) {
            $this->_cache->set('article_' . AmcWm::app()->getLanguage() . $this->_id, $this->items, Yii::app()->params["cacheDuration"]["article"]);
        }
    }

    /**
     * Set the article dataset array   the associated array contain's the following items:
     * <ul>
     * <li>record: array, specifies the article record</li>    
     * <li>comments: array, comments associated that contain's following items:
     * <ul>
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, article id</li>
     * <li>content: string , article header</li>
     * </ul>
     * <li>records: array, specifies the comments record</li>    
     * <li>pager: array list thst include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * <ul>    
     * </li>
     * </ul>     
     * @access protected
     * @return void
     */
    protected function setItems() {
        $this->_setRecord();
    }

    /**
     * sets the article keywords inside record associated array, 
     * merge tags and sections and article source if exist
     * @access private
     * @return void
     */
    private function _setKeywords() {
        $this->items['record']['keywordsLinks'] = null;
        $this->items['record']['keywords'] = array();
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $sections = array();
        $sectionQuery = sprintf(
                "select 
                    t.section_id, 
                    t.parent_section, 
                    t.image_ext,
                    tt.section_name, 
                    tt.description as section_description
                    from sections t 
                    inner join sections_translation tt on t.section_id = tt.section_id
                    where t.section_id = %d                    
             and published= %d
             and content_lang = %s"
                , $this->items['record']['section_id']
                , ActiveRecord::PUBLISHED
                , Yii::app()->db->quoteValue($siteLanguage)
        );
        $firstSection = Yii::app()->db->createCommand($sectionQuery)->queryRow();
        if (isset($firstSection["image_ext"])) {
            $this->items['record']['sectionImage'] = Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['topContent']['path'] . "/" . $firstSection["section_id"] . "." . $firstSection["image_ext"];
        } else {
            $this->items['record']['sectionImage'] = null;
        }
        $this->items['record']['section_name'] = $firstSection['section_name'];
        $this->items['record']['section_description'] = $firstSection['section_description'];
        if (is_array($firstSection)) {
            $sections[] = $firstSection['section_name'];
            $sections[] = $firstSection['section_description'];
            if ($firstSection['parent_section']) {
                $sectionParentQuery = sprintf(
                        "select 
                    t.section_id, 
                    tt.section_name,
                    tt.description as section_description
                    from sections t 
                    inner join sections_translation tt on t.section_id = tt.section_id
                    where t.section_id = %d                    
                    and published = %d
                    and content_lang = %s"
                        , $firstSection['parent_section']
                        , ActiveRecord::PUBLISHED
                        , Yii::app()->db->quoteValue($siteLanguage)
                );
                $second = Yii::app()->db->createCommand($sectionParentQuery)->queryRow();
                $sections[] = $second['section_name'];
                $sections[] = $second['section_description'];
            }
        }
        if ($this->items['record']["source"]) {
            $this->items['record']['keywords'][md5($this->items['record']["source"])] = $this->items['record']["source"];
        }
        if (count($sections)) {
            foreach ($sections as $keyword) {
                $keyword = trim($keyword);
                $this->items['record']['keywords'][md5($keyword)] = $keyword;
            }
        }
        if ($this->items['record']['tags']) {
            str_replace("\n\r", "\n", $this->items['record']['tags']);
            $keywordsTags = explode("\n", $this->items['record']['tags']);
            $separator = null;
            foreach ($keywordsTags as $keyword) {
                $keyword = trim($keyword);
                $this->items['record']['keywords'][md5($keyword)] = $keyword;
                $this->items['record']['keywordsLinks'] .= $separator . Html::link($keyword, array("/site/search", "q" => "\"{$keyword}\""), array('title' => CHtml::encode($keyword)));
                $separator = " / ";
            }
        }
    }

    /**
     * sets the article record associated array
     * @access private
     * @return void
     */
    private function _setRecord() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if ($this->_id) {
            $moduleName = $this->getModuleName();
            if ($moduleName == 'news') {
                $this->addJoin("inner join news n on n.article_id = t.article_id");
                $this->addJoin("left join news_sources_translation ns on ns.source_id = n.source_id and ns.content_lang = tt.content_lang");
                $this->addColumn("source");
            } else {
                $this->addColumn("' '", 'source');
            }
            $cols = $this->generateColumns();
            $wheres = $this->generateWheres();
            $currentDate = date("Y-m-d H:i:s");

            $this->query = sprintf("select t.* , 
                    tt.*
                    , pa.page_img as parent_img
                    $cols 
                from articles t
                inner join articles_translation tt on t.article_id = tt.article_id
                left join articles pa on pa.article_id = t.parent_article
                {$this->joins}       
                where t.published = %d
                and t.article_id = %d             
                and t.publish_date <= '{$currentDate}'            
                and (t.expire_date >'{$currentDate}' or t.expire_date is null)
                and tt.content_lang = %s
                $wheres
             ", ActiveRecord::PUBLISHED, $this->_id, Yii::app()->db->quoteValue($siteLanguage));
            //die($this->query);
            $this->items['record'] = Yii::app()->db->createCommand($this->query)->queryRow();

            if (is_array($this->items['record'])) {
                if ($moduleName == 'news') {
                    $query = 'select editor_id, name , content_lang from news_editors n '
                            . ' inner join persons_translation p on p.person_id = n.editor_id'
                            . ' where n.article_id = ' . (int) $this->_id;
                    $editors = Yii::app()->db->createCommand($query)->queryAll();
                    $this->items['record']['editors'] = array();

                    foreach ($editors as $editor) {
                        if (!isset($this->items['record']['writers'][$editor['editor_id']]) || $editor['content_lang'] == $siteLanguage) {
                            $this->items['record']['editors'][$editor['editor_id']] = $editor['name'];
                        }
                    }
                    if ($this->items['record']['source']) {
                        $this->items['record']['editors']['orgSource'] = $this->items['record']['source'];
                        $this->items['record']['source'] = implode(' - ', $this->items['record']['editors']);
                    }
                }

                if (isset($this->items['record']["create_date"])) {
                    $this->items['record']["create_date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $this->items['record']["create_date"]);
                }
                $this->items['record']['titles'] = Yii::app()->db->createCommand(sprintf("SELECT title FROM `articles_titles` where `article_id`= %d and content_lang=%s", $this->_id, Yii::app()->db->quoteValue($siteLanguage)))->queryAll();
//                if ($moduleName == "news") {
//                    $this->items['record']["article_detail"] = strip_tags($this->items['record']["article_detail"], "<br /><br><p><b><img><a><li><ul><ol><iframe>");
//                }

                if (isset($this->items['record']["page_img"])) {
                    $this->items['record']['page_img'] = Yii::app()->baseUrl . "/" . Data::getSettings('articles')->mediaPaths['pageImage']['path'] . "/" . $this->items['record']['article_id'] . "." . $this->items['record']['page_img'];
                } else {
                    $this->items['record']['page_img'] = null;
                }

                if (isset($this->items['record']["parent_img"])) {
                    $this->items['record']['parent_img'] = Yii::app()->baseUrl . "/" . Data::getSettings('articles')->mediaPaths['pageImage']['path'] . "/" . $this->items['record']['parent_article'] . "." . $this->items['record']['parent_img'];
                } else {
                    $this->items['record']['parent_img'] = null;
                }

                /**
                 * get all sub articles
                 */
                $subsWhere = sprintf(' and t.published = %d
                                and t.publish_date <= %s
                                and (t.expire_date > %s or t.expire_date is null)
                                and tt.content_lang = %s', ActiveRecord::PUBLISHED, Yii::app()->db->quoteValue($currentDate), Yii::app()->db->quoteValue($currentDate), Yii::app()->db->quoteValue($siteLanguage)
                );

                $subsWhere .=" order by article_sort "; 
                $qSubs = 'select t.article_id, t.thumb, t.section_id, tt.article_header, tt.article_pri_header, tt.article_detail from articles t
                    inner join articles_translation tt on t.article_id = tt.article_id
                    where t.parent_article = ' . $this->items['record']["article_id"] . $subsWhere;
                $this->items['subs'] = Yii::app()->db->createCommand($qSubs)->queryAll();
                $this->items['record']['parentData'] = null;
                if ($this->items['record']["parent_article"]) {

                    $qParentTitle = 'select t.article_id, tt.article_header, tt.article_pri_header from articles t
                        inner join articles_translation tt on t.article_id = tt.article_id
                        where t.article_id = ' . $this->items['record']["parent_article"] . $subsWhere;
                    ;
                    $this->items['record']['parentData'] = Yii::app()->db->createCommand($qParentTitle)->queryRow();

                    $qParentSubs = 'select t.article_id, tt.article_header from articles t
                        inner join articles_translation tt on t.article_id = tt.article_id
                        where t.parent_article = ' . $this->items['record']["parent_article"] . $subsWhere;
                    $this->items['parentSubs'] = Yii::app()->db->createCommand($qParentSubs)->queryAll();
                }
                $this->_setKeywords();
            }
        }
    }

}
