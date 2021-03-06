<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * SearchData class.
 * Starts the view class which initializes templates.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SearchData extends SearchContentData {

    /**
     * Minimum word length accepted for searching about it.
     */
    const MIN_WORD_LENGHT = 3;

    /**
     *  the words and phrases to search about in articles and videos
     * @var array 
     */
    protected $keywords;

    /**
     * Constructor, the content type
     * @param string $userInput, the words and phrases to search about in articles and videos
     * @param string $contentType, Content type multimedia  or text   
     * @param integer $limit, The numbers of items to fetch from articles or videos 
     * @access public
     * 
     */
    public function __construct($userInput, $contentType = "news", $limit = 15) {
        parent::__construct($contentType, $limit);
        $this->_setKeywords($userInput);
    }

    /**
     * Set the content results assoicated array that contain's following items:
     * <ul>
     * <li>text:array News dataset
     * <li>videos:array Vidoes daatset
     * <ul>
     * @access protected
     * @return void
     */
    protected function set() {
        $page = (int) Yii::app()->request->getParam('page', 1);
        $listData = null;
        $wheres = array();
        $weights = array();

        if ($this->contentType == 'essays' || $this->contentType == 'articles' || $this->contentType == 'news') {
            $listData = new ArticlesListData($this->tables[$this->contentType], 0, $this->limit);
            $listData->addOrder("create_date desc");
            $listData->addColumn("article_detail", "detail");
            $listData->addColumn("'articles'", "module");
            $listData->addColumn("publish_date");
            $listData->setArchive($this->advancedParams['archive']);
            if (count($this->advancedParams['date'])) {
                $listData->addWhere(sprintf("date(create_date) {$this->advancedParams['date']['opt']} %s", Yii::app()->db->quoteValue($this->advancedParams['date']['value'])));
            }
            if (AmcWm::app()->db->useFullText) {
                if ($this->keywords) {
                    $keyword = Yii::app()->db->quoteValue(str_replace("%", "\%", trim($this->keywords)));
                    $wheres[] = "match(tags, article_header,article_detail) against ({$keyword} in boolean mode)";
                    $weights[] = "match(tags, article_header,article_detail) against ({$keyword} in boolean mode)";
                }
            } else {
                foreach ($this->keywords as $keyword) {
                    $keyword = str_replace("%", "\%", trim($keyword));
                    $keywordLike = Yii::app()->db->quoteValue("%%{$keyword}%%");
                    $keywordLocate = Yii::app()->db->quoteValue($keyword);
                    $wheres[] = "article_header like {$keywordLike}";
                    $wheres[] = "article_detail like {$keywordLike}";
                    $weights[] = "if(locate($keywordLocate,article_header)>0," . Html::utfStringLength($keyword) . ",0) ";
                    $weights[] = "if(locate($keywordLocate,article_detail)>0," . Html::utfStringLength($keyword) . ",0) ";
                }
            }
        }
        switch ($this->contentType) {
            case 'news':
                $listData->addColumn("'news'", "module");
                break;
            case 'essays':
                $listData->addColumn("'essays'", "module");
                break;
            case 'articles':
                $listData->addColumn("'articles'", "module");
                $listData->addWhere('news.article_id is null');
                $listData->addWhere('essays.article_id is null');
                $listData->addJoin('left join news on news.article_id=t.article_id');
                $listData->addJoin('left join essays on essays.article_id=t.article_id');
                break;
            case 'images':
            case 'videos':
                Yii::import("amcwm.modules.multimedia.components.*");
                if ($this->contentType == 'videos') {
                    $header = 'video_header';
                    $listData = new MediaListData(null, MediaListData::VIDEO_TYPE, 0, $this->limit);
                    $listData->addColumn("'videos'", "module");
                } else if ($this->contentType == 'images') {
                    $header = 'image_header';
                    $listData = new MediaListData(null, MediaListData::IAMGE_TYPE, 0, $this->limit);
                    $listData->addColumn("'images'", "module");
                }
                $listData->setLimit($this->limit);
                $listData->addColumn("description", "detail");
                $listData->addColumn("t.gallery_id", "gallery_id");
                $listData->addColumn("publish_date");
                if (count($this->advancedParams['date'])) {
                    $listData->addWhere(sprintf("date(creation_date) {$this->advancedParams['date']['opt']} %s", Yii::app()->db->quoteValue($this->advancedParams['date']['value'])));
                }
                if (AmcWm::app()->db->useFullText) {
                    if ($this->keywords) {
                        $keyword = Yii::app()->db->quoteValue(str_replace("%", "\%", trim($this->keywords)));
                        $wheres[] = "match(tags, $header,description) against ({$keyword} in boolean mode)";
                        $weights[] = "match(tags, $header,description) against ({$keyword} in boolean mode)";
                    }
                } else {
                    foreach ($this->keywords as $keyword) {
                        $keyword = str_replace("%", "\%", trim($keyword));
                        $keywordLike = Yii::app()->db->quoteValue("%%{$keyword}%%");
                        $keywordLocate = Yii::app()->db->quoteValue($keyword);
                        $wheres[] = "{$header} like {$keywordLike}";
                        $wheres[] = "description like {$keywordLike}";
                        $weights[] = "if(locate($keywordLocate,{$header})>0," . Html::utfStringLength($keyword) . ",0) ";
                        $weights[] = "if(locate($keywordLocate,description)>0," . Html::utfStringLength($keyword) . ",0) ";
                    }
                }

                break;
        }
        if ($this->advancedParams['section']) {
            $listData->addWhere('t.section_id = ' . (int) $this->advancedParams['section']);
        }
        if (count($wheres)) {
            $listData->addWhere("(" . implode(" or ", $wheres) . ")");
            $listData->addColumn(implode("+", $weights), "weight");
            $listData->addOrder(" weight desc ");
            if ($listData instanceof SiteData) {
                $pager = new PagingDataset($listData, $this->limit, $page);
                $this->results = $pager->getData();
//                echo($listData->getQuery()->text);
//                die();
            }
        }
    }

    /**
     * extact match check
     * @param string $userInput
     * @access public
     * @return bool
     */
    private function checkExtactMatch($userInput) {
        $userInput = stripslashes($userInput);
        $exactPattern = '/^".+"$/';
        $ok = preg_match($exactPattern, $userInput);
        return $ok;
    }

    /**
     * Sets $_keywords array from user input.
     * @param string $userInput
     * @access public
     * @return void
     */
    private function _setKeywords($userInput) {
        $userInput = str_replace(array('%'), '', $userInput);
        $userInput = stripslashes($userInput);
        if (AmcWm::app()->db->useFullText) {
            if (Html::utfStringLength($userInput) >= self::MIN_WORD_LENGHT) {
                if ($this->checkExtactMatch($userInput)) {
                    $this->keywords = $userInput;
                } else {
                    $this->keywords = "+{$userInput}";
//                    $tmpKeywords = preg_split("/[\s,+]+/", trim($userInput));
                    //                    foreach ($tmpKeywords as $keyword) {
//                        $this->keywords .= "+{$keyword} ";
//                    }
//                    $this->keywords = trim($this->keywords);
                }
            }
        } else {
            $this->keywords = array();
            if ($this->checkExtactMatch($userInput)) {
                $this->keywords[0] = preg_replace('/[\^""\$]/', "", $userInput);
            } else {
                $tmpKeywords = preg_split("/[\s,+]+/", trim($userInput));
                foreach ($tmpKeywords as $keyword) {
                    if (Html::utfStringLength($keyword) >= self::MIN_WORD_LENGHT) {
                        $this->keywords[] = str_replace('"', '', $keyword);
                    }
                }
            }
        }
    }

}
