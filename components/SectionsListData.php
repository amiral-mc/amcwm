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
class SectionsListData
{

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
    public function __construct(ArticlesListData $dataObject, $sectionsLimit = 10, $articlesLimit = 4)
    {
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
    public function setParentSectionId($sectionId)
    {
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
    public function generate()
    {
        $sections = Data::getInstance()->getSectionsTree($this->_parentSectionId);
        $sectionsCount = count($sections);
        $this->_sectionsLimit = ($sectionsCount < $this->_sectionsLimit || $this->_sectionsLimit == 0) ? $sectionsCount : $this->_sectionsLimit;
        $i = 0;
        $items = array();
        $forwardModules = amcwm::app()->acl->getForwardModules();
        $queries = array();
        while ($i < $this->_sectionsLimit && (list($sectionId, $section) = each($sections))) {
            $dataObject = clone $this->_dataObject;
            $dataObject->setLimit($this->_articlesLimit);
            $dataObject->setSectionId($section['data']["section_id"]);
            $dataObject->useRecordIdAsKey(false);
            $dataObject->addColumn("('{$section['data']["section_id"]}') as main_section");
            $dataObject->addColumn("article_detail");
            $dataObject->setUseCount(false);
            $dataObject->setAutoGenerate(false);
            $dataObject->generate();
            $queries[] = "(" . $dataObject->getQuery()->text . ")";
//            $i++;
        }

        reset($sections);
        if ($queries) {
            $articles = Yii::app()->db->createCommand(implode(" UNION ", $queries))->queryAll();
            $i = 0;
            while ($i < $this->_sectionsLimit && (list($sectionId, $section) = each($sections))) {
                $sectionArticles = $this->generateDataset($articles, $section['data']["section_id"]);
                if ($sectionArticles) {
                    $items[$section['data']["section_id"]]['childs'] = $sectionArticles;
                    $items[$section['data']["section_id"]]['data']['id'] = $section['data']["section_id"];
                    $items[$section['data']["section_id"]]['data']['title'] = $section['data']['section_name'];
                    $items[$section['data']["section_id"]]['data']['settings'] = $section['data']['settings'];
                    //$urlParams = array('list'=>$this->_dataObject->getModuleName(), 'id' => $section['data']['section_id']);
                    $urlParams = array('id' => $section['data']['section_id'], 'title' => $section['data']['section_name']);
                    foreach ($forwardModules as $moduleId => $forwardModule) {
                        if ($dataObject->getModuleName() == key($forwardModule)) {
                            $urlParams['module'] = $moduleId;
                            break;
                        }
                    }
                    $items[$section['data']["section_id"]]['data']['link'] = Html::createUrl("/articles/default/sections", $urlParams);
                }
//            $i++;
            }
        }
        return $items;
    }

    /**
     *
     * Sets the the ArticlesListData.items array      
     * @param array $articles 
     * @access protected     
     * @return void
     */
    protected function generateDataset($articles, $sectionId)
    {
        $settings = ArticlesListData::getSettings();
        $options = $settings->options;
        $mediaPath = Yii::app()->baseUrl . "/" . $settings->mediaPaths['sections']['path'] . "/";
        $useSeoImages = isset($options['default']['check']['seoImages']) && $options['default']['check']['seoImages'] ? $options['default']['check']['seoImages'] : false;
        $items = array();
        $index = 0;
        foreach ($articles As $article) {
            if ($sectionId == $article['main_section']) {
                $items[$index]['title'] = $article["article_header"];
                $items[$index]['article_detail'] = $article["article_detail"];
                $seoTitle = ($useSeoImages) ? Html::seoTitle($article["article_header"]) . "." : "";
                $items[$index]['id'] = $article["article_id"];
                $urlParams = array('id' => $article['article_id'], 'title' => $article["article_header"]);
                $items[$index]['link'] = Html::createUrl('/articles/default/view', array('id' => $article['article_id'], 'title' => $article["article_header"]));
                if ($article["thumb"]) {
                    $items[$index]['imageExt'] = $article["thumb"];
                    $items[$index]['image'] = $mediaPath . "{$seoTitle}" . $article["article_id"] . "." . $article["thumb"];
                } else {
                    $items[$index]['imageExt'] = null;
                    $items[$index]['image'] = null;
                }
                $index ++;
            }
        }
        return $items;
    }

}
