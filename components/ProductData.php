<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ProductData class, gets the product record for a given product id
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ProductData extends Dataset {

    /**
     * Product id, to get record based on it
     * @var integer
     */
    private $_id;

    /**
     * Cache implemented in application, used for caching this record  
     * @var CCache 
     */
    private $_cache = null;

    /**
     * Instance of facebook comment class
     * @var FacebookComments
     */
    private $_facebookComments;

    /**
     * Counstructor, the content type
     * @param integer $productId 
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($productId, $autoGenerate = true) {
        $this->_cache = Yii::app()->getComponent('cache');
        $this->_id = (int) $productId;
        if ($autoGenerate) {
            $this->generate();
        }
    }

    /**
     * Gets the product record associated array
     * @return array 
     * @access public
     */
    public function getProduct() {
        $record = $this->items['record'];
        return $record;
    }

    /**
     * Gets the the comments associated array inside product dataset array that contain's following items:
     * <ul>
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, product id</li>
     * <li>content: string , product header</li>
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
     * Get instance of facebook comment class
     * @return FacebookComments 
     */
    public function getFacebookComments() {
        return $this->_facebookComments;
    }

    /**
     * Get product comments count, if cache is not implemented then get the comments from product dataset otherwise get the comments using query 
     * @return integer 
     */
    public function getCommentsCount() {
        $comments = $this->items['record']['comments'];
        if ($this->_cache !== null) {
            $comments = Yii::app()->db->createCommand("SELECT comments FROM products WHERE product_id = {$this->_id} ")->queryScalar();
        }
        return $comments;
    }

    /**
     * Get product hits count, if cache is not implemented then get the hits from product dataset otherwise get the hits using query 
     * @return integer 
     * @access public
     */
    public function getHits() {
        if ($this->_cache !== null) {
            $hits = Yii::app()->db->createCommand("SELECT hits FROM products WHERE product_id = {$this->_id} ")->queryScalar();
        } else {
            $hits = isset($this->items['record']['hits']) ? $this->items['record']['hits'] : 0;
        }
        return $hits;
    }

    /**
     * Check if the product record found in the database table or not
     * @return boolean
     * @access public
     */
    public function recordIsFound() {
        return isset($this->items['record']) && is_array($this->items['record']) && count($this->items['record']);
    }

    /**
     * sets the the comments associated array inside product dataset array that contain's following items:
     * <ul>     
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, product id</li>
     * <li>content: string , product header</li>
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
            SELECT c.*, co.name cName, pt.name pName 
            FROM comments c
            JOIN products_comments pc ON c.comment_id = pc.product_comment_id
            LEFT JOIN comments_owners co ON c.comment_id = co.comment_id
            LEFT JOIN persons_translation pt ON c.user_id = pt.person_id AND pt.content_lang = %s
            WHERE c.published = %d
            AND (c.hide = 0 OR c.force_display=1)
            AND pc.product_id = %d
            ORDER BY c.comment_date DESC", Yii::app()->db->quoteValue($siteLanguage)
                , ActiveRecord::PUBLISHED
                , $this->_id
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
        $this->items['comments']['content']['title'] = $this->items['record']['product_name'];
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
            SELECT c.*, co.name cName, pt.name pName
            FROM comments c
            LEFT JOIN comments_owners co on c.comment_id = co.comment_id
            LEFT JOIN persons_translation pt on c.user_id = pt.person_id AND pt.content_lang = %s
            WHERE c.published = %d
            AND (c.hide = 0 OR c.force_display=1)
            AND c.comment_review = %d
            ORDER BY c.comment_date DESC
        ", Yii::app()->db->quoteValue($siteLanguage)
                , ActiveRecord::PUBLISHED
                , $commentId);
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
     * Generate the product dataset array,
     * @todo change facebook commment to cron job     
     * @access public
     * @return void
     */
    public function generate($start = 0) {
//        $mostCommentReadWidget = false; // ($cache) ? Yii::app()->cache->get("mostReadComments{$currentAppLang}") : false;
//        if($cache){
//            Yii::app()->cache->set("mostReadComments{$currentAppLang}", $mostCommentReadWidget, Yii::app()->params["cacheDuration"]["comments"]);
//        }
        $dependencyComments = null;
        $cacheMe = false;
        //$this->_facebookComments = new FacebookComments(Yii::app()->request->getHostInfo() . Yii::app()->request->getUrl());
        $facebookCommentsCount = 0; // $this->_facebookComments->getCommentsCount();
        $commentsCount = 0;
        $this->_initItem();
        if ($this->_cache !== null) {
            $dependencyQuery = sprintf("SELECT COUNT(*) FROM comments c
            JOIN products_comments pc ON c.comment_id = pc.product_comment_id
            WHERE c.published = %d
            AND (c.hide = 0 OR c.force_display=1)
            AND pc.product_id = %d", ActiveRecord::PUBLISHED, $this->_id);
            $dependencyComments = Yii::app()->db->createCommand($dependencyQuery)->queryScalar();
            $this->items = $this->_cache->get('product_' . $this->_id);
            if (!$this->items || !isset($this->items['record']) || !$this->items['record']) {
                $this->_initItem();
            } else {
                isset($this->items['record']['comments']) ? $this->items['record']['comments'] = $this->getCommentsCount() : 0;
                isset($this->items['record']['hits']) ? $this->items['record']['hits'] = $this->getHits() : 0;
            }
        }
        if (!$this->items['record']) {
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
                $this->items['comments']['content']['title'] = $this->items['record']['product_name'];
            }
            $commentsCount = $this->items['comments']['pager']['count'] + $facebookCommentsCount;
            $this->items['comments']['pager']['count'] = $commentsCount;
            $this->items['record']['comments'] = $commentsCount;
            $correctedCommentsQuery = sprintf('UPDATE products set comments = %d WHERE comments <> %d AND product_id = %d', $commentsCount, $commentsCount, $this->_id);
            Yii::app()->db->createCommand($correctedCommentsQuery)->execute();
            $cookieName = "hits_{$this->_id}";
            if (!isset(Yii::app()->request->cookies[$cookieName]->value)) {
                Yii::app()->db->createCommand("UPDATE products SET hits=hits+1 WHERE product_id = {$this->_id} ")->execute();
                $cookie = new CHttpCookie($cookieName, $cookieName);
                $cookie->expire = time() + 3600;
                Yii::app()->request->cookies[$cookieName] = $cookie;
            }
        }
        if ($this->_cache !== null && $cacheMe) {
            $this->_cache->set('product_' . $this->_id, $this->items, Yii::app()->params["cacheDuration"]["product"]);
        }
    }

    /**
     * Set the product dataset array   the associated array contain's the following items:
     * <ul>
     * <li>record: array, specifies the product record</li>    
     * <li>comments: array, comments associated that contain's following items:
     * <ul>
     * <li>content: array list thst include the following:
     * <ul>
     * <li>id: integer, product id</li>
     * <li>content: string , product header</li>
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
     * sets the product keywords inside record associated array, 
     * merge tags and sections and product source if exist
     * @access private
     * @return void
     */
    private function _setKeywords() {
        $this->items['record']['keywordsLinks'] = null;
        $this->items['record']['keywords'] = array();
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $sections = array();
        $sectionQuery = sprintf(
                "SELECT
                    t.section_id,
                    t.parent_section,
                    t.image_ext,
                    tt.section_name,
                    tt.description as section_description
                FROM sections t 
                JOIN sections_translation tt on t.section_id = tt.section_id
                WHERE t.section_id = %d                    
                AND published= %d
                AND content_lang = %s"
                , $this->items['record']['section_id']
                , ActiveRecord::PUBLISHED
                , Yii::app()->db->quoteValue($siteLanguage)
        );
        $firstSection = Yii::app()->db->createCommand($sectionQuery)->queryRow();
        if (isset($firstSection["image_ext"])) {
            $this->items['record']['sectionImage'] = Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['topContent']['path'] . "/" . $firstSection["section_id"] . "." . $firstSection["image_ext"];
        } else {
            $this->items['record']['sectionImage'] = null;
        }
        $this->items['record']['section_name'] = $firstSection['section_name'];
        $this->items['record']['section_description'] = $firstSection['section_description'];
        if (is_array($firstSection)) {
            $sections[] = $firstSection['section_name'];
            $sections[] = $firstSection['section_description'];
            if ($firstSection['parent_section']) {
                $sectionParentQuery = sprintf(
                        "SELECT
                        t.section_id,
                        tt.section_name,
                        tt.description as section_description
                    FROM sections t
                    JOIN sections_translation tt ON t.section_id = tt.section_id
                    WHERE t.section_id = %d
                    AND published = %d
                    AND content_lang = %s"
                        , $firstSection['parent_section']
                        , ActiveRecord::PUBLISHED
                        , Yii::app()->db->quoteValue($siteLanguage)
                );
                $second = Yii::app()->db->createCommand($sectionParentQuery)->queryRow();
                $sections[] = $second['section_name'];
                $sections[] = $second['section_description'];
            }
        }
        if ($this->items['record']["source"]) {
            $this->items['record']['keywords'][md5($this->items['record']["source"])] = $this->items['record']["source"];
        }
        if (count($sections)) {
            foreach ($sections as $keyword) {
                $keyword = trim($keyword);
                $this->items['record']['keywords'][md5($keyword)] = $keyword;
            }
        }
        if ($this->items['record']['tags']) {
            str_replace("\n\r", "\n", $this->items['record']['tags']);
            $keywordsTags = explode("\n", $this->items['record']['tags']);
            $separator = null;
            foreach ($keywordsTags as $keyword) {
                $keyword = trim($keyword);
                $this->items['record']['keywords'][md5($keyword)] = $keyword;
                $this->items['record']['keywordsLinks'] .= $separator . Html::link($keyword, array("/site/search", "q" => "\"{$keyword}\""), array('title' => CHtml::encode($keyword)));
                $separator = " / ";
            }
        }
    }

    /**
     * sets the product record associated array
     * @access private
     * @return void
     */
    private function _setRecord() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if ($this->_id) {
            $cols = $this->generateColumns();
            $wheres = $this->generateWheres();
            $currentDate = date("Y-m-d H:i:s");
            $this->query = sprintf("SELECT t.* ,
                    tt.product_brief, tt.product_name,
                    tt.product_specifications, tt.product_description, tt.tags
                    , pa.page_img as parent_img
                    $cols 
                FROM products t
                JOIN products_translation tt ON t.product_id = tt.product_id
                {$this->joins}       
                WHERE t.published = %d
                AND t.product_id = %d             
                AND t.publish_date <= '{$currentDate}'            
                AND (t.expire_date >'{$currentDate}' or t.expire_date is null)
                AND tt.content_lang = %s
                $wheres
             ", ActiveRecord::PUBLISHED, $this->_id, Yii::app()->db->quoteValue($siteLanguage));
            //die($this->query);
            $this->items['record'] = Yii::app()->db->createCommand($this->query)->queryRow();
            if (is_array($this->items['record'])) {
                if (isset($this->items['record']["create_date"])) {
                    $this->items['record']["create_date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $this->items['record']["create_date"]);
                }
                $this->_setKeywords();
            }
        }
    }

}
