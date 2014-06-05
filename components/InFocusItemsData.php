<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * InFocusItemsData class.
 * Starts the view class which initializes templates.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InFocusItemsData extends SearchData {

    /**
     * The numbers of top items to fetch from articles
     * @var int 
     */
    private $_topTextLimit = 1;

    /**
     * The numbers of top items to fetch from videos
     * @var int 
     */
    private $_topVideosLimit = 3;

    /**
     *
     * @var InfocusData 
     */
    private $_infocus = null;

    /**
     * if set to true then the system will generate top results 
     * @var boolean 
     */
    private $_genereateTopResults;

    /**
     * Top results assoicated array that contain's following items:
     * <ul>
     * <li>text:array News array list
     * <li>multimedia:array vidos array list
     * <ul>
     * @var array 
     */
    private $_topResults;

    /**
     * Counstructor, the content type
     * @param string $userInput, the words and phrases to search about in articles and videos
     * @param string $contentType, Content type multimedia  or text   
     * @param integer $limit, The numbers of items to fetch from articles or videos 
     * @access public
     * 
     */
    public function __construct($id, $userInput, $contentType = "news", $limit = 15) {
        $this->_infocus = new InfocusData($id, true);
        parent::__construct($userInput, $contentType, $limit);
    }

    /**
     * if given $genereate set to true then the system will generate top results
     * @param boolean $genereate 
     * @param integer $topTextLimit,  The numbers of top items to fetch from articles
     * @param integer $topVideosLimit,  The numbers of top items to fetch from video
     * @access public
     */
    public function genereateTopResults($genereate, $topTextLimit = 1, $topVideosLimit = 3) {
        $this->_topTextLimit = $topTextLimit;
        $this->_topVideosLimit = $topVideosLimit;
        $this->_genereateTopResults = $genereate;
    }

    /**
     * Set the content results assoicated array that contain's following items:
     * <ul>
     * <li>text:array News dataset
     * <li>videos:array Vidoes daatset
     * <ul>
     * @access protected
     * @return void
     */
    protected function set() {
        if ($this->_genereateTopResults) {
            $this->_setLatest();
        }
        $page = (int) Yii::app()->request->getParam('page', 1);
        $listData = null;
        $wheres = array();
        $weights = array();
        $notInContent = array();
        if ($this->_genereateTopResults) {
            foreach ($this->_topResults[$this->contentType] as $top) {
                $notInContent[] = $top['id'];
            }
        }
        switch ($this->contentType) {
            case 'news':
                $listData = new ArticlesListData($this->tables[$this->contentType], 0, $this->limit);
                $listData->addOrder("create_date desc");
                $listData->addColumn("article_detail", "detail");
                $listData->addColumn("'news'", "module");
                $listData->addColumn("publish_date");
                $wheres[] = "f.infocus_id = " . (int) $this->_infocus->getId();
                $listData->addJoin("inner join infocus_has_articles f on t.article_id = f.article_id");
                $listData->setArchive($this->advancedParams['archive']);
                if (count($notInContent)) {
                    $listData->addWhere("(t.article_id not in(" . implode(",", $notInContent) . "))");
                }
                if (count($this->advancedParams['date'])) {
                    $listData->addWhere(sprintf("date(create_date) {$this->advancedParams['date']['opt']} %s", Yii::app()->db->quoteValue($this->advancedParams['date']['value'])));
                }
                $listData->addJoin('inner join news n on n.article_id=t.article_id');

                foreach ($this->keywords as $keyword) {
                    $keyword = str_replace("%", "\%", trim($keyword));
                    $keywordLike = Yii::app()->db->quoteValue("%%{$keyword}%%");
                    $keywordLocate = Yii::app()->db->quoteValue($keyword);
                    $wheres[] = "article_header like {$keywordLike}";
                    $wheres[] = "article_detail like {$keywordLike}";
                    $weights[] = "if(locate($keywordLocate,article_header)>0," . Html::utfStringLength($keyword) . ",0) ";
                    $weights[] = "if(locate($keywordLocate,article_detail)>0," . Html::utfStringLength($keyword) . ",0) ";
                }
                break;
            case 'articles':
                $listData = new ArticlesListData($this->tables[$this->contentType], 0, $this->limit);
                $listData->addOrder("create_date desc");
                $listData->addColumn("article_detail", "detail");
                $listData->addColumn("'articles'", "module");
                $listData->addColumn("publish_date");
                $wheres[] = "f.infocus_id = " . (int) $this->_infocus->getId();
                $listData->addJoin("inner join infocus_has_articles f on t.article_id = f.article_id");
                $listData->setArchive($this->advancedParams['archive']);
                if (count($notInContent)) {
                    $listData->addWhere("(t.article_id not in(" . implode(",", $notInContent) . "))");
                }
                $listData->setArchive($this->advancedParams['archive']);
                if (count($this->advancedParams['date'])) {
                    $listData->addWhere(sprintf("date(create_date) {$this->advancedParams['date']['opt']} %s", Yii::app()->db->quoteValue($this->advancedParams['date']['value'])));
                }
                $listData->addWhere('n.article_id is null');
                $listData->addJoin('left join news n on n.article_id=t.article_id');

                foreach ($this->keywords as $keyword) {
                    $keyword = str_replace("%", "\%", trim($keyword));
                    $keywordLike = Yii::app()->db->quoteValue("%%{$keyword}%%");
                    $keywordLocate = Yii::app()->db->quoteValue($keyword);
                    $wheres[] = "article_header like {$keywordLike}";
                    $wheres[] = "article_detail like {$keywordLike}";
                    $weights[] = "if(locate($keywordLocate,article_header)>0," . Html::utfStringLength($keyword) . ",0) ";
                    $weights[] = "if(locate($keywordLocate,article_detail)>0," . Html::utfStringLength($keyword) . ",0) ";
                }
                break;
            case 'multimedia':
                $listData = new VideosListData();
                $listData->setLimit($this->limit);
                $listData->addColumn("description", "detail");
                $listData->addColumn("'videos'", "module");
                $listData->addColumn("t.gallery_id", "gallery_id");
                $listData->addColumn("publish_date");
                if (count($this->advancedParams['date'])) {
                    $listData->addWhere(sprintf("date(creation_date) {$this->advancedParams['date']['opt']} %s", Yii::app()->db->quoteValue($this->advancedParams['date']['value'])));
                }
                $listData->addJoin("inner join infocus_has_videos f on t.video_id = f.video_id");
                $wheres[] = "f.infocus_id = " . (int) $this->_infocus->getId();
                if (count($notInContent)) {
                    $listData->addWhere("(t.video_id not in(" . implode(",", $notInContent) . "))");
                }
                foreach ($this->keywords as $keyword) {
                    $keyword = str_replace("%", "\%", trim($keyword));
                    $keywordLike = Yii::app()->db->quoteValue("%%{$keyword}%%");
                    $keywordLocate = Yii::app()->db->quoteValue($keyword);
                    $wheres[] = "video_header like {$keywordLike}";
                    $wheres[] = "description like {$keywordLike}";
                    $weights[] = "if(locate($keywordLocate,video_header)>0," . Html::utfStringLength($keyword) . ",0) ";
                    $weights[] = "if(locate($keywordLocate,description)>0," . Html::utfStringLength($keyword) . ",0) ";
                }
                break;
        }
        if ($this->advancedParams['section']) {
            $listData->addWhere('t.section_id = ' . (int) $this->advancedParams['section']);
        }
        if (count($wheres)) {
            $listData->addWhere("(" . implode(" or ", $wheres) . ")");
            if(count($weights)){
                $listData->addColumn(implode("+", $weights), "weight");
                $listData->addOrder(" weight desc ");
            }

            if ($listData instanceof SiteData) {
                $pager = new PagingDataset($listData, $this->limit, $page);
                $this->results = $pager->getData();
            }
        }
    }

    /**
     * Set the leatest infocus array
     * Top results assoicated array that contain's following items:
     * <ul>
     * <li>text:array News array list
     * <li>videos:array vidos array list
     * <ul>
     * @access private
     * @return void
     */
    private function _setLatest() {
        $articles = new ArticlesListData($this->tables["news"], 0, $this->_topTextLimit);
        $articles->addOrder("create_date desc");
        $articles->addColumn("article_detail", "detail");
        $articles->addJoin("inner join infocus_has_articles f on t.article_id = f.article_id");
        $articles->addWhere("f.infocus_id = " . (int) $this->_infocus->getId());
        $articles->useRecordIdAsKey(false);
        $articles->generate();
        $this->_topResults["articles"] = $articles->getItems();
        $this->_topResults["news"] = $articles->getItems();

        $videos = new VideosListData();
        $videos->setLimit($this->_topVideosLimit);
        $videos->addColumn("description", "detail");
        $videos->addColumn("video");
        $videos->addColumn("t.gallery_id", "gallery_id");
        $videos->addJoin("inner join infocus_has_videos f on t.video_id = f.video_id");
        $videos->addWhere("f.infocus_id = " . (int) $this->_infocus->getId());
        $videos->useRecordIdAsKey(false);
        $videos->generate();
        $this->_topResults["multimedia"] = $videos->getItems();
    }

    /**
     * get Infocus data 
     * @access public
     * @return string     
     */
    public function getInfocusData() {
        return $this->_infocus->getItems();
    }

    /**
     * Get the latest results    
     * @access public          
     * @return array 
     */
    public function getLatestResults() {
        return $this->_topResults;
    }

}
