<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * RelatedArticlesData class, gets related articles to a given article
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RelatedArticlesData extends ArticlesListData {

    /**
     * Current article id , equal null whern we not used RelatedArticlesData.setTagsById
     * @var integer 
     */
    private $_articleId = null;

    /**
     * associated array contain's list of tags to find related articles according to list values.
     * @var array 
     */
    private $_tags = array();

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param array $tables, Tables information to get data from, its array contain's tables list , 
     * @param int $period, Period time in seconds. 
     * @param int $limit, The numbers of items to fetch from table     
     * @param int $sectionId, The section id to get contents from, if equal null then we gets contents from all sections
     * @access public
     */
    public function __construct($tables, $period = 25920000, $limit = 10, $sectionId = null) {
        parent::__construct($tables, $period, $limit, $sectionId);
    }

    /**
     * tags setter
     * @param array|string $tags
     * @access public
     * @return void
     */
    public function setTags($tags) {
        $keywords = null;
        if (is_string($tags)) {
            $keywords = explode(PHP_EOL, $tags);
        } else if (is_array($tags)) {
            $keywords = $tags;
        }
        if (is_array($keywords)) {
            foreach ($keywords as $tag) {
                $this->addTag($tag);
            }
        }
    }

    /**
     * add tag to RelatedArticlesData.tags array
     * 
     * @param string $tag 
     * @access public 
     * @return void
     */
    public function addTag($tag) {
        $this->_tags[md5($tag)] = $tag;
    }

    /**
     * Set tags by getting article tags from table articles for the given article $id
     * @param intget $id 
     * @access public
     * @return void
     */
    public function setTagsById($id) {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $this->_articleId = (int) $id;
        $tagsQuery = sprintf("SELECT tags 
            from articles_translation tt 
            inner join articles t on tt.article_id = t.article_id 
            where tt.article_id =%d 
            and tt.content_lang =%s 
            and t.published = %d" , 
                $this->_articleId, 
                Yii::app()->db->quoteValue($siteLanguage), 
                ActiveRecord::PUBLISHED);
        $relatetTags = Yii::app()->db->createCommand($tagsQuery)->queryRow();
        $keywords = explode(PHP_EOL, $relatetTags['tags']);
        foreach ($keywords as $keyword) {
            $this->addTag($keyword);
        }
    }

    /**
     *
     * Generate articles lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $tagsWheres = array();
        $this->setUseCount(false);
        $this->setTitleLength(70);
        if (AmcWm::app()->db->useFullText) {
            if ($this->_tags) {
                $keywords = "+" . array_shift($this->_tags) . " " . implode(" ", $this->_tags);
                $keywords = Yii::app()->db->quoteValue(trim($keywords));
                $weight = "match(tags, article_header,article_detail) against ({$keywords} in boolean mode)";
                $this->addColumn($weight, "weight");
                $tagsWheres[] = $weight;
                $this->addOrder(" weight desc ");
            }
        } else {
            $this->forceUseIndex = "";
            foreach ($this->_tags as $keyword) {
                $keyword = trim($keyword);
                if ($keyword) {
                    $keyword = str_replace("%", "\%%", $keyword);
                    $keywordLike = "like " . Yii::app()->db->quoteValue("%{$keyword}%");
                    $keywordLocate = Yii::app()->db->quoteValue($keyword);
                    $tagsWheres[] = "tt.tags {$keywordLike}";
                    //$tagsWheres[] = "tt.article_header {$keywordLike}";
                    //$tagsWheres[] = "t.article_detail {$keywordLike}";
//                    $weights[] = "if(locate($keywordLocate,tags)>0," . Html::utfStringLength($keyword) . ",0) ";
//                    $weights[] = "if(locate($keywordLocate,article_header)>0," . Html::utfStringLength($keyword) . ",0) ";
                    //$weights[] = "if(locate($keywordLocate,article_detail)>0," . Html::utfStringLength($keyword) . ",0) ";
//                $weights[] = "if(tt.tags {$keywordLike},5,0) ";
//                $weights[] = "if(tt.article_header {$keywordLike},10,0) ";
                    //$weights[] = "if(t.article_detail {$keywordLike},15,0) ";
                }
                $this->addOrder(" hits desc ");
            }
        }
        if (count($tagsWheres)) {
//            $this->addColumn(sprintf("%s", implode("+", $weights)), "weight");
            //$this->addOrder(" weight desc ");

            if ($this->_articleId) {
                $this->addWhere("t.article_id <> " . $this->_articleId);
            }
            $this->addWhere("(" . implode(" or ", $tagsWheres) . ")");
            parent::generate();
//            die($this->query->text);
        }
    }

}
