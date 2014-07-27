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
        $list->generate();
        $articles = $list->getQuery()->queryAll();
        $fileName = $this->generateXmlFileName($dateTime);
        if (count($articles)) {
            $ok = $this->generateMap($fileName, $articles, 'article_id', 'article_header');
            return $ok;
        }
        
//        $query = "update articles set in_xml_map = 1 where  article_id = {$article['article_id']}";            
//        AmcWm::app()->db->createCommand($query)->execute();
    }

    /**
     *      
     * @param string $fileName;
     * @param array $records
     * @param integer $idIndex
     * @param string $titleIndex
     */
    protected function generateMap($fileName, $records, $idIndex, $titleIndex) {
        $xmlPath = Yii::app()->basePath . "/..";
        $xmlDir = dirname("{$xmlPath}{$fileName}");
        if (!is_dir($xmlDir)) {
            mkdir($xmlDir, 0777, true);
        }
        if (function_exists("gzencode")) {
            $fileName = "{$fileName}.gz";
        }
        $saved = false;
        if (!is_file("{$xmlPath}{$fileName}")) {
            $mapView = Yii::getPathOfAlias('amcwm.commands.components.xmlSitemap.views.sitemap') . ".php";
            $xml = $this->console->renderFile($mapView, array('records' => $records, 'model' => $this, 'titleIndex' => $titleIndex, 'idIndex' => $idIndex), true);
            if (function_exists("gzencode")) {
                $xml = gzencode($xml);
            }
            $saved = file_put_contents("{$xmlPath}{$fileName}", $xml);
            if ($saved) {
                if (!is_file(Yii::app()->basePath . "/../sitemap_index.xml")) {
                    $xmlIndexData = '<?xml version="1.0" encoding="utf-8" ?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>';
                } else {
                    $xmlIndexData = file_get_contents(Yii::app()->basePath . "/../sitemap_index.xml");
                }
                $doc = new DOMDocument();
                $doc->loadXML($xmlIndexData);
                $sitemap = $doc->createElement('sitemap');
                $loc = $doc->createElement('loc', Yii::app()->params['siteUrl'] . "{$fileName}");
                $lastmod = $doc->createElement('lastmod', date('c',time()));
                $sitemap->appendChild($loc);
                $sitemap->appendChild($lastmod);
                $doc->documentElement->appendChild($sitemap);
                file_put_contents(Yii::app()->basePath . "/../sitemap_index.xml", $doc->saveXML());
            }
        }
        return $saved;
    }

}
