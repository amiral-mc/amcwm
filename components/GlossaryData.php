<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * GlossaryData class.
 * Starts the view class which initializes templates.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GlossaryData extends ContentData {
    
    /**
     * Minimum word length accepted for searching about it.
     */
    const MIN_WORD_LENGHT = 1;
    
    /**
     *  the words and phrases to search about the glossary
     * @var array 
     */
    private $_keywords;

    /**
     * check if the searched expression is character
     * @var boolean
     */
    private $_isAlpha = false;    

    /**
     * Counstructor, the content type
     * @param string $userInput, the words and phrases to search about in glossary items
     * @param integer $limit, The numbers of items to fetch from the glossary
     * @access public
     * 
     */
    public function __construct($params) {
        if(isset($params['categoryId']))
            $this->setAdvancedParam('category', $params['categoryId']);
            
        parent::__construct($params['limit']);
        $this->_isAlpha = $params['isAlpha'];
        if (isset($params['keywords']))
            $this->_setKeywords($params['keywords']);
    }

    /**
     * Set the content results assoicated array that contain's the glossary dataset
     * @access protected
     * @return void
     */
    protected function set() {
        $page = (int) Yii::app()->request->getParam('page', 1);
        $wheres = array();
        $weights = array();
        $listData = new GlossaryListData($this->limit);
        if (count($this->_keywords)) {
            foreach ($this->_keywords as $keyword) {
                $keyword = trim($keyword);
                $keyword = str_replace("%", "\%", $keyword);
                if ($this->_isAlpha) {
                    if ($keyword == "#") {
                        $wheres[] = "expression REGEXP '^[[:digit:]]'";
                    } else {
                        $keywordLike = Yii::app()->db->quoteValue("{$keyword}%%");
                        $wheres[] = "expression like {$keywordLike}";
                    }
                } else {
                    $keywordLike = Yii::app()->db->quoteValue("%%{$keyword}%%");
                    $keywordLocate = Yii::app()->db->quoteValue($keyword);
                    $wheres[] = "expression like {$keywordLike}";
                    $wheres[] = "meaning like {$keywordLike}";
                    $wheres[] = "description like {$keywordLike}";
                    $weights[] = "if(locate($keywordLocate, expression)>0," . Html::utfStringLength($keyword) . ", 0) ";
                    $weights[] = "if(locate($keywordLocate, meaning)>0," . Html::utfStringLength($keyword) . ", 0) ";
                    $weights[] = "if(locate($keywordLocate, description)>0," . Html::utfStringLength($keyword) . ", 0) ";
                }
            }
        }

        
        if ($this->advancedParams['category']) {
            $listData->addWhere('t.category_id = ' . (int) $this->advancedParams['category']);
        }        

        if (count($wheres))
            $listData->addWhere("(" . implode(" or ", $wheres) . ")");

        if (count($weights)) {
            $listData->addColumn(implode("+", $weights), "weight");
            $listData->addOrder(" weight DESC ");
        }

        if ($listData instanceof Dataset) {
            $pager = new PagingDataset($listData, $this->limit, $page);
            $this->results = $pager->getData();
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
        $this->_keywords = array();
        if ($this->checkExtactMatch($userInput)) {
            $this->_keywords[0] = preg_replace('/[\^""\$]/', "", $userInput);
        } else {
            $tmpKeywords = preg_split("/[\s,+]+/", trim($userInput));
            foreach ($tmpKeywords as $keyword) {
                if (Html::utfStringLength($keyword) >= self::MIN_WORD_LENGHT) {
                    $this->_keywords[] = str_replace('"', '', $keyword);
                }
            }
        }
    }

    /**
     * function to retrive the cleand keyowrd generated by the private setter function
     * @return array
     */
    public function getKeywords() {
        return $this->_keywords;
    }

    public function getAlphabet() {
        return range('a', 'z');
    }

    /**
     * get all categories recursive 
     * array
     */
    public function getAllCategories() {
        $categoryTree = array();
        $q = sprintf('SELECT * FROM glossary_categories dc 
                INNER JOIN glossary_categories_translation dct ON dct.category_id = dc.category_id
                WHERE dct.content_lang = %s
                AND dc.published = %d
                ORDER BY dc.category_id 
           ', Yii::app()->db->quoteValue(Yii::app()->user->getCurrentLanguage()), ActiveRecord::PUBLISHED);
        $categories = Yii::app()->db->createCommand($q)->queryAll();
        if (count($categories)) {
            foreach ($categories as $category) {
                $categoryTree[$category['category_id']] = $category['category_name'];
            }
        }

        return $categoryTree;
    }

}
