<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * PagingDataset class, create sections articles dataset array for paging
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionArticlesPagingDataset extends PagingDataset {

    /**
     * The dataset object to get section from
     * @var SectionArticlesData
     */
    private $_sectionDataset = null;

    /**
     * The dataset object , get top articles to displayed before articles list datagrid
     * @var ArticlesListData
     */
    private $_topArticlesDatset = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * If the count of dataset is equal 0 then call the generate method from $dataset instance
     * @param SectionArticlesData $dataset, The dataset object to get data from
     * @param integer $pageSize, The page size , number of records displayed in each page
     * @param integer $page, Current page number     
     * @param string $articlesTable articles table to get articles from
     * @param integer $topArticlesLimit the top articles to displayed before articles list datagrid
     * @param integer sectionId, Parent section id to get sub sections belong to it, equal null to get top parent sections
     * @access public
     */
    public function __construct(SectionArticlesData $dataset = null, $pageSize = 10, $page = 1, $articlesTable = "articles", $topArticlesLimit = 4, $sectionId = null) {
        $articlesTable = Html::escapeString($articlesTable);        
        $page = (int) $page;
        $pageSize = (int) $pageSize;
        if (!$page) {
            $this->page = 1;
        } else {
            $this->page = $page;
        }
        $this->pageSize = $pageSize;
        if (!$sectionId) {
            trigger_error('$sectionid is required', E_USER_ERROR);
        }
        $this->_sectionDataset = $dataset;
        if ($articlesTable == 'articles') {
            $articlesTable = null;
            $this->_topArticlesDatset = new ArticlesListData(array(), 0, $topArticlesLimit, $sectionId);
            if($this->_sectionDataset == null){
                $this->_sectionDataset = new SectionArticlesData($articlesTable, $sectionId, $pageSize);
            }
            $articlesTables = ArticlesListData::getArticlesTables();
            foreach ($articlesTables as $articleTable) {
                $this->_topArticlesDatset->addJoin("left join {$articleTable} on t.article_id = {$articleTable}.article_id");
                $this->_topArticlesDatset->addWhere("{$articleTable}.article_id is null");                
                $this->_sectionDataset->addArticlesJoin("left join {$articleTable} on t.article_id = {$articleTable}.article_id");
                $this->_sectionDataset->addArticlesWhere("{$articleTable}.article_id is null");
            }
        } else {
            $this->_topArticlesDatset = new ArticlesListData(array($articlesTable), 0, $topArticlesLimit, $sectionId);
            if($this->_sectionDataset == null){
                $this->_sectionDataset = new SectionArticlesData($articlesTable, $sectionId, $pageSize);
            }            
        }
        $this->_sectionDataset->setMediaPath(Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['topContent']['path'] . "/");
        $this->_sectionDataset->addColumn("parent_section", "parentSection");
        $this->_sectionDataset->useRecordIdAsKey(false);

        /**
         *  generate top articles,  displayed before articles list
         */
        $this->_topArticlesDatset->addOrder("create_date");
        $this->_topArticlesDatset->addColumn("create_date");
        $this->_topArticlesDatset->addColumn("article_detail");
        $this->_topArticlesDatset->addColumn("thumb");
        $this->_topArticlesDatset->useRecordIdAsKey(false);
        $this->_topArticlesDatset->generate();
        $topArticlesRecords = $this->_topArticlesDatset->getItems();
        $topArticlesIds = array();
        foreach ($topArticlesRecords as $article) {
            $topArticlesIds[] = $article['id'];
        }

        /**
         *  generate section data and articles list
         */
        if (count($topArticlesIds)) {
            $this->_sectionDataset->addArticlesWhere("(t.article_id not in(" . implode(", ", $topArticlesIds) . "))");
        }
        
        $this->_sectionDataset->addColumn("tags");
        $this->_sectionDataset->addColumn("description");
        $this->_sectionDataset->setArticlesFromRecord(($this->page - 1 ) * $this->pageSize);
        $this->_sectionDataset->addArticlesColumn('article_detail');
        $this->_sectionDataset->addArticlesColumn('article_pri_header', 'priHeader');
        $this->_sectionDataset->addArticlesColumn('publish_date');
        $this->_sectionDataset->subSectionsInUsed(true);
        $this->_sectionDataset->generate();        
        $this->dataset = $this->_sectionDataset->getArticles();
    }

    /**
     * Make sure you call the parent method so that the method is raised properly.
     * Gets PagingDataset data as associated array
     * Possible list names include the following:
     * <ul>
     * <li>sectionId: integer, specifies the section id</li>    
     * <li>sectionTitle: string, specifies the section title</li>    
     * <li>records: array, specifies the records of the current page</li>    
     * <li>pager: array list of name-value pairs dataset.
     * Possible list names include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * </ul> 
     * @access public
     * @return array
     */
    public function getData() {
        $section = $this->_sectionDataset->getItems();
        $data = parent::getData();
        $data['sectionId'] = 0;
        $data['sectionTitle'] = NULL;
        $data['sectionDescription'] = null;
        $data['parentSection'] = null;            
        $data['sectionImage'] = null;        
        if (count($section)) {
            $data['sectionId'] = $section['sectionId'];
            $data['sectionTitle'] = $section['sectionTitle'];
            $data['sectionDescription'] = $section['description'];
            $data['parentSection'] = $section['parentSection'];            
            $data['sectionImage'] = $section['sectionImage'];
        }
        return $data;
    }

    /**
     * Get get top articles to displayed before articles list datagrid
     * @access public
     * @return array 
     */
    public function getTopArticles() {
        return $this->_topArticlesDatset->getItems();
    }

    /**
     * Get keywords for the current section
     * @access public
     * @return array 
     */
    public function getKeywords() {
        return $this->_sectionDataset->getKeywords();
    }

}