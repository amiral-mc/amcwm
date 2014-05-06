<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ApiNewsData class, Gets the news needed for api classes
 * @package AmcWebManager
 * @subpackage Api
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ApiNewsData {
    
    private $createdDate = null;
    private $keywords = array();
    private $sectionId = 0;
    private $lang = 'ar';
    private $getDetail = false;
    private $query = null;

    public function __construct($lang, $createdDate = null, $sectionId = 0, $keywords = array(), $getDetail = false) {
        $this->createdDate = $createdDate;
        $this->keywords = $keywords;
        $this->sectionId = $sectionId;
        $this->lang = $lang;
        $this->getDetail = $getDetail;
        $this->route = '/articles/default/view';
    }

    public function getData($limit, $start, $repeated) {
        $this->setQuery($limit, $start, $repeated);
        $items = Yii::app()->db->createCommand($this->query)->queryAll();        
        return $items;
    }
        

    private function setQuery($limit = 10, $start = 0, $repeated = false) {
        $createdDate = null;
        if ($this->createdDate) {
            $createdDate = ' and a.create_date > ' . Yii::app()->db->quoteValue($this->createdDate);
        }
        $limitString = "";
        if ($limit) {
            $limitString = " LIMIT " . (int) $start . ", " . (int) $limit;
        }
        $sectionsWheres = null;
        $sectionJoin = '';
        $sectionWhere = '';
        if ($this->sectionId) {
            $sectionJoin = 'inner join sections s on a.section_id = s.section_id ';
            $sectionWhere = 'and s.published = 1';
            $sectionsTree = array();
            $childSectionsQuery = sprintf(
                    "select section_id from sections where parent_section = %d
             and published=1
             and content_lang = %s"
                    , $this->sectionId
                    , Yii::app()->db->quoteValue($this->lang)
            );
            $childsSections = Yii::app()->db->createCommand($childSectionsQuery)->queryAll();
            $sectionsTree = array();
            $sectionsTree[] = $this->sectionId;
            foreach ($childsSections as $childsSection) {
                $sectionsTree[] = $childsSection['section_id'];
            }
            $sectionsWheres = ' and (a.section_id in (' . implode(',', $sectionsTree) . ')) ';
        }
        $order = ($repeated) ? 'asc' : 'desc';
        $keywordsWheres = array();
        $keywordsWheresQuery = null;
        $weightsOrder = null;
        $weightsField = null;
        foreach ($this->keywords as $keyword) {
            $keyword = trim($keyword);
            if ($keyword) {
                $keyword = str_replace("%", "\%%", $keyword);
                $keywordLike = "like " . Yii::app()->db->quoteValue("%%{$keyword}%%");
                $keywordLocate = Yii::app()->db->quoteValue($keyword);
                $keywordsWheres[] = "a.article_header {$keywordLike}";
                $keywordsWheres[] = "a.article_detail {$keywordLike}";
            }
        }
        if (count($keywordsWheres)) {
            $keywordsWheresQuery = " and(" . implode(" or ", $keywordsWheres) . ") ";
        }
        if ($this->getDetail) {
            $detailCol = ', a.article_detail, a.tags';
        } else {
            $detailCol = "";
        }
        $pubDate = date('Y-m-d H:i:s');
        $this->query = sprintf("select 
          a.article_header
            ,a.article_id
            ,a.publish_date
            ,a.create_date
            ,a.thumb            
            $detailCol
            from articles a use index(articles_create_date_idx)
            inner join news t on a.article_id = t.article_id 
            $sectionJoin
            where a.published = 1 
            and a.publish_date <='$pubDate'
            $sectionWhere
            and (a.content_lang = %s or a.content_lang is null or a.content_lang ='')
            and (a.archive = 0 or a.archive is null)            
            $sectionsWheres
            $createdDate
            $keywordsWheresQuery    
            and (a.expire_date >='{$pubDate}' or a.expire_date is null)
            order by a.create_date {$order} {$limitString}", Yii::app()->db->quoteValue($this->lang));                        
    }    
    
    public function generateDataLinks($itemsData) {               
        $items = array();
        foreach ($itemsData as $itemKey => $item) {
            $items[$itemKey]['id'] = $item['article_id'];
            $items[$itemKey]['title'] = $item['article_header'];
            $items[$itemKey]['link'] = Html::createUrl($this->route, array('id' => $item['article_id']));
            $items[$itemKey]['media'] = NULL;
            $items[$itemKey]['publish_date'] = $item['publish_date'];
            $items[$itemKey]['create_date'] = $item['create_date'];
            if ($item['thumb']) {
                $items[$itemKey]['image_ext'] = $item['thumb'];
            } else {
                $items[$itemKey]['image_ext'] = null;
            }
        }
        return $items;
    }   
}