<?php

AmcWm::import('amcwm.modules.multimedia.components.MediaListData');
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
     *
     * @var integer comments in every page
     */
    public $commentsPageSize = 10;

    /**
     *
     * @var string page parameter name in $_GET
     */
    public $pageName = 'page';

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
    private $_personMediaPath = '';

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
        $personSetiings = new Settings("persons", false);
        $mediaPath = $personSetiings->getMediaPaths();
        if (isset($mediaPath['thumb']['path'])) {
            $this->_personMediaPath = Yii::app()->baseUrl . "/" . $mediaPath['thumb']['path'];
        }
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
        $page = (int) Yii::app()->request->getParam($this->pageName, 1) - 1;
        $this->commentsPageSize = (int) $this->commentsPageSize;
        $commentsCMD = Yii::app()->db->createCommand();
        $commentsCMD->from("comments AS c");
        $commentsCMD->select("c.*, co.name owner, p.thumb, p.person_id, pt.name owner_user");
        $commentsCMD->join("products_comments pc", "c.comment_id = pc.product_comment_id");
        $commentsCMD->leftJoin("comments_owners co", "c.comment_id = co.comment_id");
        $commentsCMD->leftJoin("persons p", "c.user_id = p.person_id");
        $commentsCMD->leftJoin("persons_translation pt", sprintf("pt.person_id = p.person_id AND pt.content_lang = %s", Yii::app()->db->quoteValue($siteLanguage)));
        $commentsCMD->where(sprintf("c.published = %d AND (c.hide = 0 OR c.force_display=1) AND pc.product_id = %d AND comment_review is null", ActiveRecord::PUBLISHED, $this->_id));
        $commentsCMD->limit($this->commentsPageSize, $page * $this->commentsPageSize);
        $commentsCountCMD = Yii::app()->db->createCommand();
        $commentsCountCMD->from("comments AS c");
        $commentsCountCMD->select("count(*)");
        $commentsCountCMD->join = $commentsCMD->join;
        $commentsCountCMD->where = $commentsCMD->where;
        $comments = $commentsCMD->queryAll();
        $this->items['comments']['records'] = array();
        foreach ($comments AS $index => $comment) {
            $this->items['comments']['records'][$index]["id"] = $comment["comment_id"];
            $this->items['comments']['records'][$index]["bad_imp"] = $comment["bad_imp"];
            $this->items['comments']['records'][$index]["good_imp"] = $comment["good_imp"];
            $this->items['comments']['records'][$index]["owner"] = ($comment["owner_user"]) ? $comment["owner_user"] : $comment["owner"];
            $this->items['comments']['records'][$index]["title"] = $comment["comment_header"];
            $this->items['comments']['records'][$index]["details"] = $comment["comment"];
            $this->items['comments']['records'][$index]["date"] = $comment["comment_date"];
            $this->items['comments']['records'][$index]["avatar"] = null;
            if ($this->_personMediaPath && $comment['thumb']) {
                $this->items['comments']['records'][$index]["avatar"] = "{$this->_personMediaPath}/{$comment['person_id']}.{$comment['thumb']}";
            }
            $this->items['comments']['records'][$index]["replies"] = array();
            $this->_setReplies($comment["comment_id"], $index);
        }
        $this->items['comments']['pager'] = array('pageSize' => $this->commentsPageSize, 'count' => $commentsCountCMD->queryScalar());
    }

    /**
     * Sets the replies records for the given $commentId
     * @param integer $commentId the comment id the get the replies related to it
     * @param integer $index the array index key inside the comments array
     * @return void
     */
    private function _setReplies($commentId, $index) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $commentsCMD = Yii::app()->db->createCommand();
        $commentsCMD->from("comments AS c");
        $commentsCMD->select("c.*, co.name owner, p.thumb, p.person_id, pt.name owner_user");
        $commentsCMD->leftJoin("comments_owners co", "c.comment_id = co.comment_id");
        $commentsCMD->leftJoin("persons p", "c.user_id = p.person_id");
        $commentsCMD->leftJoin("persons_translation pt", sprintf("pt.person_id = p.person_id AND pt.content_lang = %s", Yii::app()->db->quoteValue($siteLanguage)));
        $commentsCMD->where(sprintf("c.published = %d AND (c.hide = 0 OR c.force_display=1) AND comment_review=%d", ActiveRecord::PUBLISHED, $commentId));
        $replies = $commentsCMD->queryAll();
        $this->items['comments']['records'][$index]["replies"] = array();
        foreach ($replies AS $replay) {
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["id"] = $replay["comment_id"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["bad_imp"] = $replay["bad_imp"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["good_imp"] = $replay["good_imp"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["owner"] = ($replay["owner_user"]) ? $replay["owner_user"] : $replay["owner"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["title"] = $replay["comment_header"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["details"] = $replay["comment"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["date"] = $replay["comment_date"];
            $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["avatar"] = null;
            if ($this->_personMediaPath && $replay['thumb']) {
                $this->items['comments']['records'][$index]["replies"][$replay["comment_id"]]["avatar"] = "{$this->_personMediaPath}/{$replay['person_id']}.{$replay['thumb']}";
            }
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
                $cookie->httpOnly = true;
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
            $this->query = sprintf("SELECT t.*,tt.*                    
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
                $media = new MediaListData($this->items['record']['gallery_id'], SiteData::IAMGE_TYPE, 0, null);
                $media->generate();
                $image = $media->getData();
                $this->items['record']['images'] = $media->getData();
                $media = new MediaListData($this->items['record']['gallery_id'], SiteData::VIDEO_TYPE, 0, null);
                $media->generate();
                $this->items['record']['videos'] = $media->getData();
                if (isset($this->items['record']["create_date"])) {
                    $this->items['record']["create_date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $this->items['record']["create_date"]);
                }
                $this->_setKeywords();
            }
        }
    }

}
