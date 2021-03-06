<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MostReadAndCommentData class,  Gets the most read and most comment articles from contents 
 * @package AmcWebManager
 * @subpackage Data
 * @see ArticlesListData
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MostReadAndCommentData extends CComponent {

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
     * Most shared articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @var array 
     */
    protected $sharedArticles;

    /**
     * The numbers of items to fetch from table 
     * @var int 
     */
    protected $limit = 10;

    /**
     * The section id to get contents from, if equal null then we gets contents from all sections
     * @var int 
     */
    protected $sectionId;

    /**
     * Array contain's tables names to get data from, 
     * @var array 
     */
    protected $tables;

    /**
     * Period time in seconds, 
     * If atrribute value is greater than 0 then articles generated from this class must be between "current date" and "current date" subtracted from the value of this attribute 
     * @var int 
     */
    protected $period = 0;

    /**
     *      
     * @var array add custom where 
     */
    protected $wheres = array();

    /**
     * Title length , if greater than 0 then we get the first titleLength characters from content tite
     * @var integer 
     */
    protected $titleLength = 70;

    /**
     * Counstructor
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param int $limit, The numbers of items to fetch from table    
     * @param int $titleLength, if greater than 0 then we get the first titleLength characters from content tite
     * @param integer $period, Period time in seconds. 
     * @param int $sectionId, The section id to get contents from, if equal null then we gets contents from all sections     
     * @access public
     */
    public function __construct($tables, $limit = 10, $titleLength = 70, $period = 0, $sectionId = null) {
        if (!$this->period) {
            $this->period = 60 * 60 * 24 * 30;
        }
        $this->tables = $tables;
        $this->limit = $limit;
        $this->sectionId = $sectionId;
        $this->titleLength = $titleLength;

        //$this->sharedArticles = $shared->getItems();
    }

    /**
     * Add where to wheres array
     */
    public function setWheres($where) {
        $this->wheres[md5($where)] = $where;
    }

    /**
     * Gets the most comments articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @param string $$mediaPathIndex media path index to get images path from it
     * @access public
     * @return array
     */
    public function getCommentsArticles($mediaPathIndex = 'list') {
        if (!$this->commentsArticles) {
            $comments = new ArticlesListData($this->tables, $this->period, $this->limit, $this->sectionId);
            $comments->setMediaPath ( Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths[$mediaPathIndex]['path'] . "/");
            $comments->forceUseIndex = '';
            $comments->setUseCount(false);
            $comments->addOrder('comments desc');
            $comments->addColumn('comments', 'info');
            $comments->setTitleLength($this->titleLength);
            foreach ($this->wheres as $where) {
                $comments->addWhere($where);
            }
            $comments->generate();
            $this->commentsArticles = $comments->getItems();
        }
        return $this->commentsArticles;
    }

    /**
     * Gets the most read articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @param string $$mediaPathIndex media path index to get images path from it
     * @access public
     * @return array
     */
    public function getReadArticles($mediaPathIndex = 'list') {
        if (!$this->readArticles) {
            $read = new ArticlesListData($this->tables, $this->period, $this->limit, $this->sectionId);
            $read->forceUseIndex = '';
            $read->setUseCount(false);
            $read->setMediaPath ( Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths[$mediaPathIndex]['path'] . "/");
            $read->addOrder('hits desc');
            $read->addColumn('hits', 'info');
            $read->setTitleLength($this->titleLength);
            foreach ($this->wheres as $where) {
                $read->addWhere($where);
            }
            $read->generate();
            $this->readArticles = $read->getItems();
        }
        return $this->readArticles;
    }

    /**
     * Gets the most shared articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * </ul>
     * @param string $$mediaPathIndex media path index to get images path from it
     * @access public
     * @return array
     */
    public function getSharedArticles($mediaPathIndex = 'list') {
        if (!$this->sharedArticles) {
            $shared = new ArticlesListData($this->tables, $this->period, $this->limit, $this->sectionId);
            $shared->forceUseIndex = '';
            $shared->setUseCount(false);
            $read->setMediaPath ( Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths[$mediaPathIndex]['path'] . "/");
            $shared->addOrder('shared desc');
            $shared->addColumn('shared', 'info');
            $shared->setTitleLength($this->titleLength);
            foreach ($this->wheres as $where) {
                $shared->addWhere($where);
            }
            $shared->generate();
            $this->sharedArticles = $shared->getItems();
        }
        return $this->sharedArticles;
    }

}
