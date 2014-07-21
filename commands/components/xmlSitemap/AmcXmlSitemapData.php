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
    protected $period = 3600;       
    
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
     * Post content to social network
     * @return boolean
     */
    abstract protected function createXmlLinks();
    
    
    
}
