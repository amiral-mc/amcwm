<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * MostReadAndCommentData class,  Gets the most read and most comment articles from contents 
 * @package AmcWebManager
 * @subpackage Data
 * @see ArticlesListData
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MostReadAndCommentData {

    /**
     * Most comments articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @var array 
     */
    protected $commentsArticles;
    /**
     * Most read articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @var array 
     */
    protected $readArticles;

    /**
     * Counstructor
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param int $limit, The numbers of items to fetch from table
     * @param int $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct($tables, $limit = 10, $sectionId = null) {
        $period = 60 * 60 * 24 * 30;
        $read = new ArticlesListData($tables,  $period, $limit, $sectionId);        
        $read->setTitleLength(70);
        $read->generate();
        $comments = new ArticlesListData($tables,  $period, $limit, $sectionId);
        $comments->addOrder('comments desc');
        $comments->setTitleLength(70);
        $comments->generate();
        $this->readArticles = $read->getItems();
        $this->commentsArticles = $comments->getItems();        
    }

    /**
     * Gets the most comments articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @access public
     * @return array
     */
    public function getCommentsArticles() {
        return $this->commentsArticles;
    }

    /**
     * Gets the most read articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @access public
     * @return array
     */
    public function getReadArticles() {
        return $this->readArticles;
    }  
}