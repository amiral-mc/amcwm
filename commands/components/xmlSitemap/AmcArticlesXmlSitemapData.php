<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AmcXmlSitemapData  use contents in xml sitemap
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcArticlesXmlSitemapData extends AmcXmlSitemapData {

    /**
     *
     * @var string current route 
     */
    protected $route = 'articles/default/view';

    /**
     *
     * @var string table to get data from 
     */
    protected $table = "articles";

    /**
     *
     * Post content to social network
     * @return boolean
     */
    public function createXmlLinks() {
        $list = new ArticlesListData(array($this->table), 0, $this->limit);
        $dateTime = time();
        $list->setFromDate(date("Y-m-d H:00:00", $dateTime - $this->period));
        $list->setToDate(date("Y-m-d H:59:59", $dateTime - $this->period));
        $list->setLanguage($this->language);
        //$list->addWhere('t.in_xml_map = 0');
        $list->setDateCompareField("publish_date");
        $list->setAutoGenerate(false);
        $list->addColumn("create_date");
        $list->addColumn("publish_date");
        if ($this->table == 'articles') {
            $articlesTables = ArticlesListData::getArticlesTables();
            foreach ($articlesTables as $articleTable) {
                $list->addJoin("LEFT JOIN {$articleTable} ON t.article_id = {$articleTable}.article_id");
                $list->addWhere("{$articleTable}.article_id IS NULL");
            }
        }
        $list->generate();
        $articles = $list->getQuery()->queryAll();
        $fileName = $this->generateXmlFileName($dateTime);
        if (count($articles)) {
            $ok = $this->generateMap($fileName, $articles, 'article_id', 'article_header');
            return $ok;
        }
    }
}
