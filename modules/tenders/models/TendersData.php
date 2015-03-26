<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * TendersData class, Gets the tenders records
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TendersData extends Dataset {

    /**
     * Article id, to get record based on it
     * @var integer
     */
    private $_id;
    //private $articleData = array();
    /**
     * Cache implemented in application, used for caching this record  
     * @var CCache 
     */
    private $_cache = null;

    /**
     * Counstructor, the content type
     * @param integer $tenderId 
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($tenderId, $autoGenerate = true) {
        $this->_cache = Yii::app()->getComponent('cache');
        $this->_id = (int) $tenderId;
        if ($autoGenerate) {
            $this->generate();
        }
    }

    /**
     * Gets the article record associated array
     * @return array 
     * @access public
     */
    public function getTender() {
        $record = $this->items['record'];
        return $record;
    }

    /**
     * Gets the the comments associated array inside article dataset array that contain's following items:
     * <ul>
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, article id</li>
     * <li>content: string , article header</li>
     * </ul>
     * <li>records: array, specifies the comments record</li>    
     * <li>pager: array list thst include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * <ul>    
     * @return array 
     * @access public
     */
    public function getComments() {
        $comments = $this->items['comments'];
        return $comments;
    }

    /**
     * Get article comments count, if cache is not implemented then get the comments from article dataset otherwise get the comments using query 
     * @return integer 
     */
    public function getCommentsCount() {
        $comments = $this->items['record']['comments'];
//        if ($this->_cache !== null) {
//            $comments = Yii::app()->db->createCommand("select comments from tenders where tender_id = {$this->_id} ")->queryScalar();
//        }
        return $comments;
    }

    /**
     * Get article hits count, if cache is not implemented then get the hits from article dataset otherwise get the hits using query 
     * @return integer 
     * @access public
     */
    public function getHits() {
        if ($this->_cache !== null) {
            $hits = Yii::app()->db->createCommand("select hits from tenders where tender_id = {$this->_id} ")->queryScalar();
        } else {
            $hits = isset($this->items['record']['hits']) ? $this->items['record']['hits'] : 0;
        }
        return $hits;
    }

    /**
     * Check if the article record found in the database table or not
     * @return boolean
     * @access public
     */
    public function recordIsFound() {
        return isset($this->items['record']) && is_array($this->items['record']) && count($this->items['record']);
    }

    /**
     * sets the the comments associated array inside article dataset array that contain's following items:
     * <ul>     
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, article id</li>
     * <li>content: string , article header</li>
     * </ul>     
     * <li>records: array, specifies the comments record</li>    
     * <li>pager: array list thst include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * <ul>    
     * @access private
     * @return void
     */
    private function _setComments() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $pageSize = Yii::app()->params['pages']['comments'];
        $query = sprintf("
            select c.*, co.name cName, p.name pName 
            from comments c
            inner join tenders_comments ac on ac.comment_id = c.comment_id
            left join comments_owners co on co.comment_id = c.comment_id
            left join persons_translation p on p.person_id = c.user_id and p.content_lang = %s
            where c.published = %d
            and (c.hide = 0 OR c.force_display=1)
            and ac.tender_id = %d
            order by c.comment_date DESC", Yii::app()->db->quoteValue($siteLanguage), ActiveRecord::PUBLISHED, $this->_id
        );
        $comments = Yii::app()->db->createCommand($query)->queryAll();
        $count = count($comments);
        $this->items['comments']['records'] = array();
        foreach ($comments AS $index => $comment) {
            $this->items['comments']['records'][$index]["id"] = $comment["comment_id"];
            $this->items['comments']['records'][$index]["bad_imp"] = $comment["bad_imp"];
            $this->items['comments']['records'][$index]["good_imp"] = $comment["good_imp"];
            $this->items['comments']['records'][$index]["owner"] = ($comment["cName"] != null) ? $comment["cName"] : $comment["pName"];
            $this->items['comments']['records'][$index]["title"] = $comment["comment_header"];
            $this->items['comments']['records'][$index]["details"] = $comment["comment"];
            $this->items['comments']['records'][$index]["date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $comment["comment_date"]);
            $this->items['comments']['records'][$index]["replies"] = array();
            $this->_setReplies($comment["comment_id"], $index);
        }
        $this->items['comments']['content']['id'] = $this->_id;
        $this->items['comments']['content']['title'] = $this->items['record']['title'];
        if (count($this->items['comments']['records'])) {
            $commentsRecords = $this->items['comments']['records'];
            $pageSize = Yii::app()->params['pages']['comments'];
            $pageNo = (int) Yii::app()->request->getParam("page");
            if (!$pageNo) {
                $pageNo = 1;
            }

            $this->items['comments']['records'] = array_slice($commentsRecords, ($pageNo - 1 ) * $pageSize, $pageSize);
        }
        $this->items['comments']['pager'] = array('pageSize' => $pageSize, 'count' => $count,);
    }

    /**
     * Sets the replies records for the given $commentId
     * @param integer $commentId the comment id the get the replies related to it
     * @param integer $index the array index key inside the comments array
     * @return void
     */
    private function _setReplies($commentId, $index) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $query = sprintf(" 
            select c.*, co.name cName, p.name pName from comments c 
            left join comments_owners co on co.comment_id = c.comment_id
            left join persons_translation p on p.person_id = c.user_id and p.content_lang = %s
            where c.published = %d
            and (c.hide = 0 OR c.force_display=1)
            and c.comment_review = %d
            order by c.comment_date DESC
        ", Yii::app()->db->quoteValue($siteLanguage), ActiveRecord::PUBLISHED, $commentId);
        $replies = Yii::app()->db->createCommand($query)->queryAll();
        $this->items['comments']['records'][$index]["replies"] = array();
        foreach ($replies AS $replay) {
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["id"] = $replay["comment_id"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["bad_imp"] = $replay["bad_imp"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["good_imp"] = $replay["good_imp"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["owner"] = ($replay["cName"] != null) ? $replay["cName"] : $replay["pName"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["title"] = $replay["comment_header"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["details"] = $replay["comment"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $replay["comment_date"]);
        }
    }

    /**
     * Initilaize items array, adding required array keys
     * @access private
     * @return void
     * 
     */
    private function _initItem() {
        $this->items = array(
            'record' => array(),
            'comments' => array('content' => array('id' => NULL, 'title' => NULL,), 'records' => array(), 'pager' => array('pageSize' => 10, 'count' => 0)),
        );
    }

    /**
     * Generate the article dataset array, 
     * @access public
     * @return void
     */
    public function generate($start = 0) {
        $dependencyComments = null;
        $cacheMe = false;

        $commentsCount = 0;
        $this->_initItem();
//        if ($this->_cache !== null) {
//            $dependencyQuery = "select count(*) from comments c 
//            inner join tenders_comments ac on ac.article_comment_id = c.comment_id
//            where c.published = 1 and (c.hide = 0 OR c.force_display=1) and ac.tender_id = {$this->_id}";
//            $dependencyComments = Yii::app()->db->createCommand($dependencyQuery)->queryScalar();
//            $this->items = $this->_cache->get('article_' . $this->_id);
//            if ($this->items == null) {
//                $this->_initItem();
//            }
//        }
        if (!count($this->items['record'])) {
            $this->setItems();
            $cacheMe = true;
        }
        if ($dependencyComments != $this->items['comments']['pager']['count'] || $dependencyComments === null) {
            $this->_setComments();
            $cacheMe = true;
        }
        if ($this->items['record']) {
            if (!$this->items['comments']['content']['id']) {
                $this->items['comments']['content']['id'] = $this->_id;
                $this->items['comments']['content']['title'] = $this->items['record']['article_header'];
            }
            $commentsCount = $this->items['comments']['pager']['count'];
            $this->items['comments']['pager']['count'] = $commentsCount;
            $this->items['record']['comments'] = $commentsCount;
            $correctedCommentsQuery = sprintf('update tenders set comments = %d where comments <> %d and tender_id = %d', $commentsCount, $commentsCount, $this->_id);
            Yii::app()->db->createCommand($correctedCommentsQuery)->execute();
            $cookieName = "hits_{$this->_id}";
            if (!isset(Yii::app()->request->cookies[$cookieName]->value)) {
                Yii::app()->db->createCommand("update tenders set hits=hits+1 where tender_id = {$this->_id} ")->execute();
                $cookie = new CHttpCookie($cookieName, $cookieName);
                $cookie->expire = time() + 3600;
                $cookie->httpOnly = true;
                Yii::app()->request->cookies[$cookieName] = $cookie;
            }
        }
        if ($this->_cache !== null && $cacheMe) {
            $this->_cache->set('article_' . $this->_id, $this->items, Yii::app()->params["cacheDuration"]["article"]);
        }
    }

    /**
     * Set the article dataset array   the associated array contain's the following items:
     * <ul>
     * <li>record: array, specifies the article record</li>    
     * <li>comments: array, comments associated that contain's following items:
     * <ul>
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, article id</li>
     * <li>content: string , article header</li>
     * </ul>
     * <li>records: array, specifies the comments record</li>    
     * <li>pager: array list thst include the following:
     * <ul>
     * <li>count: integer, the total results</li>
     * <li>pageSize: integer , the page size , number of records displayed in each page</li>
     * </ul>
     * </li>
     * <ul>    
     * </li>
     * </ul>     
     * @access protected
     * @return void
     */
    protected function setItems() {
        $this->_setRecord();
    }

    /**
     * sets the article record associated array
     * @access private
     * @return void
     */
    private function _setRecord() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if ($this->_id) {
            $cols = $this->generateColumns();
            $wheres = $this->generateWheres();

            $this->query = sprintf("select t.* , tt.*
                $cols 
                from tenders t
                inner join tenders_translation tt on t.tender_id = tt.tender_id
                {$this->joins}       
                where t.published = %d
                and t.tender_id = %d
                and tt.content_lang = %s
                $wheres
             ", ActiveRecord::PUBLISHED, $this->_id, Yii::app()->db->quoteValue($siteLanguage));
            $this->items['record'] = Yii::app()->db->createCommand($this->query)->queryRow();

            if (is_array($this->items['record'])) {
                $this->items['record']["create_date"] = Yii::app()->dateFormatter->format("dd/MM/y (hh:mm a)", $this->items['record']["create_date"]);
                $this->items['record']["rfp_start_date"] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm a)", $this->items['record']["rfp_start_date"]);
                $this->items['record']["rfp_end_date"] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm a)", $this->items['record']["rfp_end_date"]);
                $this->items['record']["submission_start_date"] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm a)", $this->items['record']["submission_start_date"]);
                $this->items['record']["submission_end_date"] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm a)", $this->items['record']["submission_end_date"]);
                $this->items['record']["technical_date"] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm a)", $this->items['record']["technical_date"]);
                $this->items['record']["financial_date"] = Yii::app()->dateFormatter->format("dd-MM-y (hh:mm a)", $this->items['record']["financial_date"]);
                $this->items['record']['rfp_price1'] = ($this->items['record']['rfp_price1']) ? $this->items['record']['rfp_price1'] . " " . AmcWm::app()->getLocale()->getCurrencySymbol($this->items['record']['rfp_price1_currency']) : null;
                $this->items['record']['rfp_price2'] = ($this->items['record']['rfp_price2']) ? $this->items['record']['rfp_price2'] . " " . AmcWm::app()->getLocale()->getCurrencySymbol($this->items['record']['rfp_price2_currency']) : null;
                $this->items['record']['primary_insurance'] = ($this->items['record']['primary_insurance']) ? $this->items['record']['primary_insurance'] . " " . AmcWm::app()->getLocale()->getCurrencySymbol($this->items['record']['primary_insurance_currency']) : null;

                $this->items['record']["tender_type"] = Tenders::model()->getTenderTypes($this->items['record']["tender_type"]);
                $this->items['record']["tender_status"] = Tenders::model()->getTenderStatus($this->items['record']["tender_status"]);
                $this->items['record']["activities"] = TendersActivities::model()->getTenderActivity($this->items['record']["tender_id"]);
            }
        }
    }

}
