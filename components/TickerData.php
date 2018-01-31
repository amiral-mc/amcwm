<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * TickerData class, Gets the articles to displayed inside news ticker area from breaking news.
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TickerData {

    /**
     * Breaking type flag
     */
    const BREAKING = 1;

    /**
     * News type flag
     */
    const NEWS = 2;

    /**
     * Ticker type , News or breaking
     * @var int 
     */
    protected $type = 1;

    /**
     * Article ListData instance
     * @var ArticlesListData
     */
    protected $articles;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @todo fix bug if $limit = 0
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param integer $limit, The numbers of items to fetch from table         
     * @param boolean $breakingOnly
     * @param string $lang
     * @param integer $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct($tables = array('news'), $limit = 10, $breakingOnly = false, $lang = null, $cols = array(), $sectionId = null) {

        $this->articles = new ArticlesListData($tables, 0, $limit, $sectionId);
        $this->articles->setDetailsIsNotEmpty(false);
        foreach ($cols as $col) {
            $this->articles->addColumn($col);
        }
        if ($lang) {
            $this->articles->setLanguage($lang);
        }
        $settings = $this->articles->getSettings()->options['news']['default'];
        $resultBreaking = false;
        if ($settings['check']['addToBreaking']) {
            $this->articles->setModuleName("news");
            $this->articles->forceUseIndex = "";
            $this->articles->addOrder("t.create_date desc");
            $this->articles->addWhere("news.is_breaking = 1");
            $this->articles->setDateCompareField('publish_date');
            $this->articles->setFromDate(date("Y-m-d H:i:s", time() - $settings['integer']['breakingExpiredAfter']));
            $this->articles->setUseCount(false);
            $this->articles->generate();
            $items = $this->articles->getItems();
            $resultBreaking = (bool) count($items);
        }
        if ($resultBreaking) {
            $this->type = self:: BREAKING;
        } else if (!$breakingOnly) {
            $this->articles = new ArticlesListData($tables, 0, $limit, $sectionId);
            foreach ($cols as $col) {
                $this->articles->addColumn($col);
            }
            if ($lang) {
                $this->articles->setLanguage($lang);
            }
            $this->articles->setModuleName("news");
            $this->articles->forceUseIndex = "";
            $this->articles->addOrder("t.create_date desc");
            $this->articles->addWhere("news.is_breaking = 0 and in_ticker = 1");
            $this->articles->setUseCount(false);
            $this->articles->generate();
            $items = $this->articles->getItems();
            $this->type = self:: NEWS;
        }
    }

    /**
     * return Ticker type , News or breaking
     * @access public 
     * @return integer 
     */
    public function getTickerType() {
        return $this->type;
    }

    /**
     * Get the articles ListData instance
     * @access public
     * @return ArticlesListData
     */
    public function getArticles() {
        return $this->articles;
    }

}
