<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * InFocusItemData class.  
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InFocusItemsData extends SearchContentData {

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
     * @param integer $id 
     * @param string $contentType, Content type multimedia  or text   
     * @param integer $limit, The numbers of items to fetch from articles or videos 
     * @param integer $topTextLimit,  The numbers of top items to fetch from articles
     * @param integer $topVideosLimit,  The numbers of top items to fetch from video
     * @access public
     * 
     */
    public function __construct($id, $contentType = "text", $limit = 15, $topTextLimit = 1, $topVideosLimit = 3) {
        $this->_infocus = new InfocusData($id, true);
        parent::__construct($contentType, $limit);
    }

    /**
     * if given $genereate set to true then the system will generate top results
     * @param boolean $genereate 
     * @access public
     */
    public function genereateTopResults($genereate) {
        $this->_genereateTopResults = $genereate;
    }

    /**
     *
     * Generate infocus articles / videos array list    
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if ($this->_genereateTopResults) {
            $this->_setLatest();
        }
        parent::generate();
    }

    /**
     * Set the infocus results assoicated array that contain's following items:
     * <ul>
     * <li>text:array News dataset
     * <li>videos:array Vidoes daatset
     * <ul>
     * @access protected
     * @return void
     */
    protected function set() {
        $page = Yii::app()->request->getParam('page', 1);
        $listData = null;
        $notInContent = array();
        $this->results = array();
        if ($this->_genereateTopResults) {
            foreach ($this->_topResults[$this->contentType] as $top) {
                $notInContent[] = $top['id'];
            }
        }
        switch ($this->contentType) {
            case 'text':
                $listData = new ArticlesListData($this->tables[$this->contentType], 0, $this->limit);
                $listData->addOrder("create_date desc");
                $listData->addColumn("article_detail", "detail");
                $listData->addColumn("'news'", "module");
                $listData->addColumn("publish_date");
                $listData->addJoin("inner join infocus_has_articles f on t.article_id = f.article_id");
                $listData->addWhere("f.infocus_id = " . (int) $this->_infocus->getId());
                if (count($notInContent)) {
                    $listData->addWhere("(t.article_id not in(" . implode(",", $notInContent) . "))");
                }
                break;
            case 'multimedia':
                $listData = new VideosListData();
                $listData->setLimit($this->limit);
                $listData->addColumn("description", "detail");
                $listData->addColumn("'videos'", "module");
                $listData->addColumn("t.gallery_id", "gallery_id");
                $listData->addColumn("publish_date");
                $listData->addJoin("inner join infocus_has_videos f on t.video_id = f.video_id");
                $listData->addWhere("f.infocus_id = " . (int) $this->_infocus->getId());
                if (count($notInContent)) {
                    $listData->addWhere("(t.video_id not in(" . implode(",", $notInContent) . "))");
                }
                break;
        }
        if($listData instanceof SiteData){
            $pager = new PagingDataset($listData, $this->limit, $page);
            $this->results = $pager->getData();
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
        $articles = new ArticlesListData($this->tables["text"], 0, $this->_topTextLimit);
        $articles->addOrder("create_date desc");
        $articles->addColumn("article_detail", "detail");
        $articles->addJoin("inner join infocus_has_articles f on t.article_id = f.article_id");
        $articles->addWhere("f.infocus_id = " . (int) $this->_infocus->getId());
        $articles->useRecordIdAsKey(false);
        $articles->generate();
        $this->_topResults["text"] = $articles->getItems();

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