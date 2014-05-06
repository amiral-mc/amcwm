<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * SectionsListData class, Gets the latest article from "N" numbers of sections
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionsListData {

    /**
     * Instance of SiteData class to get articles from
     * @var ArticlesListData 
     */
    private $_dataObject = null;

    /**
     * Number of sections to be displayed
     * @var integer 
     */
    private $_sectionsLimit;

    /**
     * Number of articles to be displayed in each section
     * @var integer 
     */
    private $_articlesLimit;

    /**
     * The parent section id to get sub sections contents from, if equal null then we gets contents from top parent sections
     * @var int 
     */
    private $_parentSectionId;

    /**
     *
     * @param ArticlesListData $dataObject instance of SiteData class to get articles from
     * @param integer $sectionsLimit, Number of sections to be displayed
     * @param integer $articlesLimits, Number of articles to be displayed in each section
     */
    public function __construct(ArticlesListData $dataObject, $sectionsLimit = 10, $articlesLimit = 4) {
        $this->_sectionsLimit = $sectionsLimit;
        $this->_articlesLimit = $articlesLimit;
        $this->_dataObject = $dataObject;
    }

     /**
     * sets parent section id to get sub sections contents from, if equal null or 0  then we gets contents from top parent sections
     * @param integer sectionId 
     * @access public 
     * @return void
     */
    public function setParentSectionId($sectionId) {
        $this->_parentSectionId = (int) $sectionId;
    }
    
    /**
     * Generte the sections dataset associated array, the index of each section item equal the value of section id,
     * each section is associated  array that contain's following items:
     * <ul>
     * <li>childs: array, articles dataset</li>
     * <li>data: array, section data associated array contain's following items:
     * <ul>
     * <li>title: string, section name</li>
     * <li>link: string, link for displaying section list</li>
     * </ul>
     * </li>
     * </ul>
     * @access public
     * @return array
     */
    public function generate() {
        $sections = Data::getInstance()->getSectionsTree($this->_parentSectionId);
        $sectionsCount = count($sections);
        $this->_sectionsLimit = ($sectionsCount < $this->_sectionsLimit || $this->_sectionsLimit == 0) ? $sectionsCount : $this->_sectionsLimit;
        $i = 0;
        $items = array();
        $forwardModules = amcwm::app()->acl->getForwardModules();
        while ($i < $this->_sectionsLimit && (list($sectionId, $section) = each($sections))) {
            $dataObject = clone $this->_dataObject;
            $dataObject->setLimit($this->_articlesLimit);
            $dataObject->setSectionId($section['data']["section_id"]);
            $dataObject->useRecordIdAsKey(false);
            $dataObject->addColumn("article_detail");
            $dataObject->generate();
            $articles = $dataObject->getItems();
            if (count($articles)) {
                $items[$section['data']["section_id"]]['childs'] = $articles;
                $items[$section['data']["section_id"]]['data']['title'] = $section['data']['section_name'];
                //$urlParams = array('list'=>$this->_dataObject->getModuleName(), 'id' => $section['data']['section_id']);
                $urlParams = array('id' => $section['data']['section_id']);
                foreach ($forwardModules as $moduleId => $forwardModule) {
                    if ($dataObject->getModuleName() == key($forwardModule)) {
                        $urlParams['module'] = $moduleId;
                        break;
                    }
                }
                $items[$section['data']["section_id"]]['data']['link'] = Html::createUrl("/articles/default/sections", $urlParams);
            }
        }
        return $items;
    }

}
