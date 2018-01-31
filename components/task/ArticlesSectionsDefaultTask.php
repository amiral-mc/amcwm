<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesSectionsDefaultTask class, run the default task
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesSectionsDefaultTask extends ArticlesControllerTask {

    /**
     * Run this task
     * @param boolean $displayResult
     * @return boolean
     */
    public function run($displayResult = true) {
        $this->displayResult = $displayResult;
        $limit = Yii::app()->params['pageSize'];
        $render = false;
        if (isset($this->extraParams['limit'])) {
            $limit = $this->extraParams['limit'];
        }
        if (isset($this->params['id'])) {
            if ($this->viewType == "blocks" || $this->viewType == "cols" || $this->viewType == "links") {
                $render = $this->_runBlocksCols($limit);
            } else {
                $render = $this->_runDefault($limit);
            }
        } else {
            $showSectionsList = false;
            if (isset($this->options['showSectionsList'])) {
                $showSectionsList = $this->options['showSectionsList'];
            }
            if ($showSectionsList) {
                $sections = Data::getInstance()->sectionsTopArticles($this->table, $this->module, 0, 6);
                if ($this->displayResult && count($sections)) {
                    $render = true;
                    $this->render('index', array('sections' => $sections));
                }
            } else {
                $articleList = new ArticlesListData(array($this->table), 0, $limit);
                $articleList->forceUseIndex = "use index (articles_create_date_idx)";
                $articleList->addColumn("publish_date");
                $articleList->addColumn("article_detail");
                $articleList->addColumn("section_name");
                if (isset($this->extraParams['routeParams'])) {
                    foreach ($this->extraParams['routeParams'] as $key => $value) {
                        $articleList->addParam($key, $value);
                    }
                }

                $articleList->addJoin("left join sections_translation sectionst on sectionst.section_id = t.section_id and tt.content_lang = sectionst.content_lang");
                if ($this->table == "news") {                    
                    $articleList->addJoin("left join news_sources_translation ns on ns.source_id = news.source_id and ns.content_lang = tt.content_lang");
                    $articleList->addColumn("source");
                }
                $data['widgetImage'] = null;
                if (file_exists("images/front/{$this->module}Image.png")) {
                    $data['widgetImage'] = AmcWm::app()->baseUrl . "/images/front/{$this->module}Image.png";
                }
                if ($this->module == "news") {
                    if ($this->options['showPrimaryHeader']) {
                        $articleList->addColumn('article_pri_header', 'priHeader');
                    }
                }
                $pagingDataset = new PagingDataset($articleList, $limit, Yii::app()->request->getParam("page"));
                
                $itemsList = $pagingDataset->getData();
                if ($this->displayResult && $itemsList['pager']['count']) {
                    $render = true;
                    if ($itemsList['pager']['count']) {
                        $data['pageSiteTitle'] = null;
                        $data['widgetTitle'] = null;
                        $data['pageContentTitle'] = null;
                        $data['sectionId'] = null;
                        $data['pageContent'] = null;
                        $data['itemsList'] = $itemsList;
                        $data['viewOptions'] = $this->options;
                        $data['task'] = $this;
                        $data['descriptionKey'] = "article_detail";
                        $this->render($this->viewType, array('data' => $data));
                    }
                }
            }
        }
        return $render;
    }

    /**
     * Run the default view
     * @param integer $limit , records limits
     * @access private
     * @return boolean
     */
    private function _runDefault($limit) {
        $render = false;
        $topArticlesLimit = 0;
        if (isset($this->options['topArticles']) && $this->options['topArticles'] && $this->displayResult) {
            $topArticlesLimit = $this->options['topArticles'];
        }
        $sectionDataset = new SectionArticlesData($this->table, $this->params["id"], $limit);
        $sectionDataset->addArticlesColumn("section_name");
        $sectionDataset->addArticlesJoin("left join sections_translation sectionst on sectionst.section_id = t.section_id and tt.content_lang = sectionst.content_lang");
        if ($this->module == "news") {
            $sectionDataset->addArticlesColumn("source");
            $sectionDataset->addArticlesJoin("left join news_sources_translation ns on ns.source_id = news.source_id and ns.content_lang = tt.content_lang");
        }

        $pagingDataset = new SectionArticlesPagingDataset($sectionDataset, $limit, Yii::app()->request->getParam("page"), $this->table, $topArticlesLimit, $this->params["id"]);
        $this->dataset = $pagingDataset->getDataset();
        if ($this->displayResult) {
            $itemsList = $pagingDataset->getData();
            $itemsList['top'] = $pagingDataset->getTopArticles();
            if (!empty($itemsList['metaDescription'])) {
                Yii::app()->clientScript->registerMetaTag($itemsList['metaDescription'], "description");
            }
            if ($itemsList['pager']['count'] || count($itemsList['top'])) {
                $render = true;
                $data['pageSiteTitle'] = $itemsList['sectionTitle'];
                $data['widgetTitle'] = $itemsList['sectionTitle'];
                $data['pageContentTitle'] = $itemsList['sectionTitle'];
                $data['sectionId'] = $itemsList['parentSection'];
                $data['pageContent'] = $itemsList['sectionDescription'];
                $data['widgetImage'] = $itemsList['sectionImage'];
                $data['itemsList'] = $itemsList;
                $data['viewOptions'] = $this->options;
                $data['keywords'] = implode(", ", $pagingDataset->getKeywords());
                $data['descriptionKey'] = "article_detail";
                $data['task'] = $this;
                $this->render($this->viewType, array('data' => $data));
            }
        }
        return $render;
    }

    /**
     * Run the blocks or cols view
     * @access private
     * @return boolean
     */
    private function _runBlocksCols($limit) {
        $render = false;
        $articlesTables = $this->settings->extendsTables;
        if ($this->table == 'articles') {
            $articlesTable = null;
            $sectionDataset = new SectionArticlesData($articlesTable, $this->params["id"], $limit);
            $sectionDataset->addColumn("description", 'sectionDescription');
            foreach ($articlesTables as $articleTable) {
                $sectionDataset->addArticlesJoin("left join {$articleTable} on t.article_id = {$articleTable}.article_id");
                $sectionDataset->addArticlesWhere("{$articleTable}.article_id is null");
            }
        } else {
            $sectionDataset = new SectionArticlesData($this->table, $this->params['id'], 10);
        }
        $sectionDataset->forceUseIndex = "use index (articles_create_date_idx)";
        $sectionDataset->setUseCount(false);
        $sectionDataset->setModuleName($this->module);
        $sectionDataset->useRecordIdAsKey(false);
        if ($this->viewType == "blocks") {
            $sectionDataset->setArticleMediaPath(Yii::app()->baseUrl . "/" . $this->settings->mediaPaths['blocks']['path'] . "/");
            $sectionDataset->addArticlesColumn("article_detail", "description");
        }

        if ($this->viewType == "links") {
            $sectionDataset->setArticleMediaPath(Yii::app()->baseUrl . "/" . $this->settings->mediaPaths['images']['path'] . "/");
            $sectionDataset->addArticlesColumn("article_detail", "description");
        }

        if ($this->module == "news") {
            $sectionDataset->addArticlesColumn("source");
            $sectionDataset->addArticlesJoin("left join news_sources_translation ns on ns.source_id = news.source_id and ns.content_lang = tt.content_lang");
        }
        if (isset($this->options['showPrimaryHeader'])) {
            $sectionDataset->addArticlesColumn('article_pri_header', 'priHeader');
        }

        $sectionDataset->setMediaPath(Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['topContent']['path'] . "/");
        $sectionDataset->generate();
        $this->dataset = $sectionDataset->getArticles();
        if ($this->displayResult) {
            $section = $sectionDataset->getItems();
            if (count($section) && count($this->dataset->getItems())) {
                $render = true;
                $data['pageSiteTitle'] = $section['sectionTitle'];
                $data['widgetTitle'] = $section['sectionTitle'];
                $data['pageContentTitle'] = $section['sectionTitle'];
                $data['sectionId'] = $section['sectionId'];
                $data['pageContent'] = $section['sectionDescription'];
                $data['widgetImage'] = $section['sectionImage'];
                $data['itemsList'] = $this->dataset->getItems();
                $data['viewOptions'] = $this->options;
                $data['keywords'] = implode(", ", $sectionDataset->getKeywords());
                $data['task'] = $this;
                $this->render($this->viewType, array('data' => $data));
            }
        }
        return $render;
    }

    /**
     * Renders a view with a layout.
     * @param string $view name of the view to be rendered
     * @param array $data data to be extracted into PHP variables and made available to the view script
     * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function render($view, $data = array(), $return = false) {
        return Yii::app()->getController()->render($view, $data, $return);
    }

    /**
     * get site mapdata used in this task
     * @access public
     * @return array();
     */
    public function getSiteMapData() {
        $mapData = array();
        if ($this->dataset) {
            $rows = $this->dataset->getItems();
            foreach ($rows as $row) {
                $mapData[] = array(
                    "label" => $row['title'],
                    "url" => $row['link'],
                );
            }
        }
        return $mapData;
    }

}
