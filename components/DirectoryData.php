<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * GlossaryData class.
 * Starts the view class which initializes templates.
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirectoryData extends ContentData {

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
     * the site language (ar/en/etc...)
     * @var string
     */
    private $_siteLanguage;

    /**
     * Counstructor, the content type
     * @param string $userInput, the words and phrases to search about in glossary items
     * @param integer $limit, The numbers of items to fetch from the glossary
     * @access public
     * 
     */
    public function __construct($userInput = null, $category = null, $limit = 10) {
        parent::__construct($limit);
        $this->_siteLanguage = Yii::app()->user->getCurrentLanguage();
        if ($category)
            $this->setAdvancedParam('category', $category);
        if ($userInput)
            $this->_setKeywords($userInput);
        $this->_isAlpha = AmcWm::app()->request->getParam("a") && count($this->_keywords) == 1;
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
        $listData = new DirectoryListData($this->limit);
        $listData->addColumn("url");
        $listData->addColumn("activity");
        if (count($this->_keywords)) {
            $keyword = $this->_keywords[0];
            if ($this->_isAlpha) {
                if ($keyword != "#") {
                    $currentLang = AmcWm::app()->getLanguage();
                    $class = "GenerateAlphabet" . ucfirst($currentLang);
                    $alphabet = new $class();
                    $aliasesLetters = $alphabet->getAliasesLetters($keyword);
                    if($aliasesLetters){
                        foreach ($aliasesLetters as $letter){
                            $keywordLike = Yii::app()->db->quoteValue("{$letter}%%");
                            $wheres[] = "company_name like {$keywordLike}";
                        }                      
                    }
                    else{
                        $keywordLike = Yii::app()->db->quoteValue("{$keyword}%%");
                        $wheres[] = "company_name like {$keywordLike}";
                    }                    
                    
                }
            } else {
                foreach ($this->_keywords as $keyword) {
                    $keyword = trim($keyword);
                    $keyword = str_replace("%", "\%", $keyword);
                    $keywordLike = Yii::app()->db->quoteValue("%%{$keyword}%%");
                    $keywordLocate = Yii::app()->db->quoteValue($keyword);
                    $wheres[] = "company_name like {$keywordLike}";
                    $wheres[] = "email like {$keywordLike}";
                    $wheres[] = "company_address like {$keywordLike}";
                    $weights[] = "if(locate($keywordLocate, company_name)>0," . Html::utfStringLength($keyword) . ", 0) ";
                    $weights[] = "if(locate($keywordLocate, email)>0," . Html::utfStringLength($keyword) . ", 0) ";
                    $weights[] = "if(locate($keywordLocate, company_address)>0," . Html::utfStringLength($keyword) . ", 0) ";
                }
            }
        }

        if (isset($this->advancedParams['category'])) {
            if (isset($this->advancedParams['selectedCategory']) && $this->advancedParams['selectedCategory']) {
                $listData->addWhere('t.category_id = ' . (int) $this->advancedParams['category']);
            } else {
                $categories = array_keys($this->getAllCategories($this->advancedParams['category']));
                $categories[] = (int) $this->advancedParams['category'];
                $listData->addWhere("t.category_id in(" . implode(",", $categories) . ")");
            }
        }

        if (isset($this->advancedParams['nationality'])) {
            $listData->addWhere('t.nationality = ' . $this->advancedParams['nationality']);
        }

        if (count($wheres))
            $listData->addWhere("(" . implode(" or ", $wheres) . ")");

        if (count($weights)) {
            $listData->addColumn(implode("+", $weights), "weight");
            $listData->addOrder(" weight DESC ");
        } else {
            $listData->addOrder(" company_name ");
        }

        if ($listData instanceof Dataset) {
            $pager = new PagingDataset($listData, $this->limit, $page);
            $this->results = $pager->getData();
//            echo $listData->getQuery();
//            die();
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
        $currentLang = AmcWm::app()->getLanguage();
        $class = "GenerateAlphabet" . ucfirst($currentLang);
        $alphabet = new $class();
        return $alphabet->getAlphabet();
    }

    /**
     * get all categories recursive 
     * array
     */
    public function getAllCategories($parent = null, &$categoryTree = array(), $level = 1) {
        if ($parent) {
            $parentWhere = " and dc.parent_category = {$parent}";
        } else {
            $parentWhere = " and dc.parent_category is null ";
        }

        $q = sprintf('SELECT * FROM dir_categories dc 
                INNER JOIN dir_categories_translation dct ON dct.category_id = dc.category_id
                WHERE dct.content_lang = %s
                AND dc.published = %d
                %s
                ORDER BY dc.category_id 
           ', Yii::app()->db->quoteValue($this->_siteLanguage), ActiveRecord::PUBLISHED, $parentWhere);
        $categories = Yii::app()->db->createCommand($q)->queryAll();
        if (count($categories)) {
            foreach ($categories as $category) {
                if ($category['parent_category']) {
                    $padding = str_repeat('-- ', $level) . str_repeat('-- ', $level);
                } else {
                    $padding = "";
                    $level = 0;
                }

                $categoryTree[$category['category_id']] = $padding . $category['category_name'];
                $this->getAllCategories($category['category_id'], $categoryTree, $level + 1);
            }
        }

        return $categoryTree;
    }

    public function parentCategoryData() {
        $categoryData = null;
        if (isset($this->advancedParams['parentCategory'])) {
            $q = sprintf('SELECT * FROM dir_categories dc 
                INNER JOIN dir_categories_translation dct ON dct.category_id = dc.category_id
                WHERE dct.content_lang = %s
                AND dc.published = %d
                AND dc.category_id = %d
           ', Yii::app()->db->quoteValue($this->_siteLanguage), ActiveRecord::PUBLISHED, $this->advancedParams['parentCategory']);
            $categoryData = Yii::app()->db->createCommand($q)->queryRow();
        }
        return $categoryData;
    }

}
