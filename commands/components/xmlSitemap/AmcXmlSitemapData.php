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
abstract class AmcXmlSitemapData extends CComponent {

    /**
     * Content language
     * @var integer 
     */
    protected $language = null;

    /**
     *
     * @var CConsole 
     */
    protected $console;

    /**
     *
     * @var string current route 
     */
    protected $route;

    /**
     *
     * @var integer content to post each time
     */
    protected $limit = -1;

    /**    
     * @var integer Period time in seconds, 
     */
    protected $period = 86400;       
    
    /**
     *
     * @var string id of the current class 
     */
    protected $id = null;  

    /**
     * 
     * Constructor
     * @param CConsole $console
     * @param string $id
     * @param string $lang
     * @param integer $limit
     */
    public function __construct($console, $id, $lang) {
        $this->id = $id;
        if (!$lang) {
            $lang = Yii::app()->getLanguage();
        }        
        $this->console = $console;
        $this->language = $lang;
    }

    /**
     * 
     * @param type $route
     * @param type $params
     * @return string
     */
    public function createUrl($route, $params) {

        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            $url = Yii::app()->params['siteUrl'];
        } else {
            $url = Yii::app()->params['siteUrl'] . '/index.php';
        }
        return Html::createConsoleUrl($url, $route, $params);
    }
    
    /**
     * Sets social route used to generate link
     * @param string $route
     */
    public function setRoute($route) {
        if ($route) {
            $this->route = $route;
        }
    }
    
    /**
     * Gets route used to generate link
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }
    
    /**
     * Get language
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Generate xml sitemap
     */
    public function generate(){
        return $this->createXmlLinks();
    }

    /**
     * Generete xml file name based on time
     * @param integer $dateTime
     */
    public function generateXmlFileName($dateTime){
        return "/xmlsitemap/{$this->id}/{$this->language}/" . date("Ymd", $dateTime - $this->period) . "_" . date("H", $dateTime - $this->period) . ".xml";
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
                $lastmod = $doc->createElement('lastmod', date('c', time()));
                $sitemap->appendChild($loc);
                $sitemap->appendChild($lastmod);
                $doc->documentElement->appendChild($sitemap);
                file_put_contents(Yii::app()->basePath . "/../sitemap_index.xml", $doc->saveXML());
            }
        }
        return $saved;
    }

    /**
     *
     * Post content to social network
     * @return boolean
     */
    abstract protected function createXmlLinks();
    
    
    
}
