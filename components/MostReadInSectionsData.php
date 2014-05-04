<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * MostReadInSectionsData class,  Gets the most read article from a table in "N" numbers of sections
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MostReadInSectionsData {

    /**
     * Table name to get data from, example "news".. etc
     * @var string 
     */
    private $_table = null;
    /**
     * Router for viewing article details
     * @var string 
     */
    private $_articleRoute = null;
    /**
     * Router for viewing article section list
     * @var string      
     */
    private $_sectionRoute = null;
    /**
     * Number of sections to get most read article from.
     * @var integer 
     */
    private $_sectionsLimit;
    /**
     * Counstructor
     * @param string $table, Table name to get data from, example "news" .. etc
     * @param string $articleRoute, Router for viewing article details
     * @param string $sectionRoute, Router for viewing article section list
     * @access public
     */
    public function __construct($table = "news", $articleRoute = "/articles/default/view", $sectionsLimit = 4) {
        $this->_sectionsLimit = $sectionsLimit;
        $this->_table = $table;
        $this->_articleRoute = $articleRoute;
        $this->_sectionRoute = "/articles/default/sections";        
    }

    /**
     * Generate the most read article from each section, the data generated type is array that contain's 
     * articles list, each article is associated  array that contain's following items:
     * <ul>
     * <li>sectionLink: string, link for displaying section list</li>
     * <li>sectionName: string, article section name</li>
     * <li>headerText: string, article title</li>
     * <li>headerImage: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @access public
     * @return array,  dataset of articles.
     */
    public function generate() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $media = Yii::app()->baseUrl . "/" . Yii::app()->params["multimedia"]['articles']['mostread']['path'] . "/";
        $sections = Data::getInstance()->getSectionsTree();
        $items = array();
        if (count($sections)) {
            $index = 0;
            foreach ($sections As $sectionId => $section) {
                $sectionsTree = Data::getInstance()->getSectionSubIds($sectionId);
                $sectionsTree[$sectionId] = $sectionId;
                $sectionsWheres = implode(',', $sectionsTree);
                $lastReadArticleDateQuery = sprintf("SELECT date(a.create_date)             
                FROM  `articles` a
                inner join articles_translation at on a.article_id = at.article_id            
                inner join {$this->_table} n on a.article_id = n.article_id
                where at.content_lang = %s
                and (a.section_id in ($sectionsWheres) )
                and published = %d
                and a.publish_date <=NOW() 
                and (a.expire_date >=NOW() or a.expire_date is null)
                and (a.archive = 0 or a.archive is null)
                and (thumb is not null or thumb <> 0)              
                order by create_date desc
                limit 1", ActiveRecord::PUBLISHED, 
                        Yii::app()->db->quoteValue($siteLanguage));
                $mostReadDate = Yii::app()->db->createCommand($lastReadArticleDateQuery)->queryScalar();
                $mostReadArticle = null;
                if ($mostReadDate) {
                    $mostReadArticleQuery = sprintf("SELECT             
                    a.article_id, a.hits, a.thumb, at.article_header
                    FROM  `articles` a
                    inner join articles_translation at on a.article_id = at.article_id            
                    inner join {$this->_table} n on a.article_id = n.article_id                    
                    where date(a.create_date) = '" . $mostReadDate . "' 
                    and at.content_lang = %s
                    and (a.section_id in ($sectionsWheres) )
                    and published = 1
                    and (a.expire_date >=NOW() or a.expire_date is null)
                    and (a.archive = 0 or a.archive is null)
                    and a.publish_date <=NOW() 
                    and (thumb is not null or thumb <> 0)
                    ORDER BY hits DESC 
                    LIMIT 0 , 1
                    ", Yii::app()->db->quoteValue($siteLanguage));
                    $mostReadArticle = Yii::app()->db->createCommand($mostReadArticleQuery)->queryRow();
                }
                if (is_array($mostReadArticle) && $index < $this->_sectionsLimit) {
                    $imageLink = Html::createUrl($this->_articleRoute, array('id' => $mostReadArticle["article_id"]));
                    $image = $media . $mostReadArticle["article_id"] . "." . $mostReadArticle["thumb"];
                    $items[$index] = array(                                        
                        'sectionLink' => Html::createUrl($this->_sectionRoute, array("list" => $this->_table, "id" => $sectionId)),
                        'sectionName' => $section['data']['section_name'],
                        'headerText' => Html::utfSubstring($mostReadArticle["article_header"], 0, 70),
                        'headerImage' => $image,
                        'link' => $imageLink,
                    );
                    $index++;
                }
            }
        }
        return $items;
    }

}