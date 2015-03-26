<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectoryItemData class, Gets the directory record for a given directory id
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirectoryItemData extends Dataset {

    /**
     * Directory id, to get record based on it
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
     * @param integer $directoryId 
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($directoryId, $autoGenerate = true) {
        $this->_cache = Yii::app()->getComponent('cache');
        $this->_id = (int) $directoryId;
        if ($autoGenerate) {
            $this->generate();
        }
    }

    /**
     * Gets the article record associated array
     * @return array 
     * @access public
     */
    public function getDirectory() {
        $record = $this->items['record'];
        return $record;
    }

    /**
     * Gets the article record associated array
     * @return array 
     * @access public
     */
    public function getArticles() {
//        $settings = ArticlesListData::getSettings()->options['articles'];
        $list = new ArticlesListData(array('articles'));
        $list->setRoute('/directory/default/viewArticle');
        $list->addColumn("article_detail", "detail");
//        $list->appendTitles(true);
        $list->addJoin('inner join dir_companies_articles dca on dca.article_id=t.article_id');
        $list->addWhere(sprintf('dca.company_id= %d', $this->_id));
        $list->addParam('dir', $this->_id);
        $list->generate();
        return $list->getItems();
    }

    /**
     * Get company hits count, if cache is not implemented then get the hits from company dataset otherwise get the hits using query 
     * @return integer 
     * @access public
     */
    public function getHits() {
        if ($this->_cache !== null) {
            $hits = Yii::app()->db->createCommand("select hits from dir_companies where company_id = {$this->_id} ")->queryScalar();
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
     * Initilaize items array, adding required array keys
     * @access private
     * @return void
     * 
     */
    private function _initItem() {
        $this->items = array(
            'record' => array(),
            'articles' => array(),
        );
    }

    /**
     * Generate the article dataset array, 
     * @access public
     * @return void
     */
    public function generate($start = 0) {
        $cacheMe = false;
        $this->_initItem();
        if ($this->_cache !== null) {
            $dependencyQuery = "select count(*) 
                from dir_companies_articles ca 
                where ca.company_id = {$this->_id}";
            $dependencyComments = Yii::app()->db->createCommand($dependencyQuery)->queryScalar();
            $this->items = $this->_cache->get('company_' . $this->_id);
            if ($this->items == null) {
                $this->_initItem();
            }
        }

        if (!count($this->items['record'])) {
            $this->setItems();
            $cacheMe = true;
        }

        if ($this->items['record']) {
            $cookieName = "dirHits_{$this->_id}";
            if (!isset(Yii::app()->request->cookies[$cookieName]->value)) {
                Yii::app()->db->createCommand("update dir_companies set hits=hits+1 where company_id = {$this->_id} ")->execute();
                $cookie = new CHttpCookie($cookieName, $cookieName);
                $cookie->expire = time() + 3600;
                $cookie->httpOnly = true;
                Yii::app()->request->cookies[$cookieName] = $cookie;
            }
        }
        if ($this->_cache !== null && $cacheMe && isset(Yii::app()->params["cacheDuration"]["company"])) {
            $this->_cache->set('company_' . $this->_id, $this->items, Yii::app()->params["cacheDuration"]["company"]);
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
     * Get branches list
     * @return array
     */
    protected function getBranches() {
        $command = AmcWm::app()->db->createCommand()->from("dir_companies_branches t")
                ->select('t.branch_id, t.country, t.email, t.phone, t.mobile, t.fax, tt.branch_name, tt.branch_address, tt.city')
                ->join("dir_companies_branches_translation tt", "t.branch_id = tt.branch_id")
                ->where("t.company_id = :companyId")
                ->bindParam(":companyId", $this->_id, PDO::PARAM_INT);
        $branchesRows = $command->queryAll();
        $branches = array();
        if ($branchesRows) {
            $fields = array_keys($branchesRows[0]);
            foreach ($branchesRows as $branch) {
                $attributes = new UsedAttributesList('dir_companies_branches_attributes', $branch['branch_id']);
                $attributes->generate();
                $attributesItems = $attributes->getItems();
                foreach ($fields as $fieldName) {                                                           
                    if (isset($attributesItems[$fieldName])) {
                        $branch['extended'][$fieldName] = array('belong' => array(), 'new' => array());
                        if (isset($attributesItems[$fieldName]['data'])) {
                            $branch['extended'][$fieldName]['belong'] = $attributesItems[$fieldName]['data'];
                        }
                        if (isset($attributesItems[$fieldName]['inheritedAttributes'])) {
                            foreach ($attributesItems[$fieldName]['inheritedAttributes'] as $inheritedName) {
                                if (isset($attributesItems[$inheritedName]['data'])) {
                                    $branch['extended'][$fieldName]['new'][$inheritedName]['label'] = $attributesItems[$inheritedName]['label'];
                                    $branch['extended'][$fieldName]['new'][$inheritedName]['data'] = $attributesItems[$inheritedName]['data'];
                                }
                            }
                        }
                    }
                }
                $branches[] = $branch;
            }
        }
        return $branches;
    }

    /**
     * sets the article record associated array
     * @access private
     * @return void
     */
    private function _setRecord() {
        $mediaSettings = DirectoryListData::getSettings()->mediaSettings;
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        if ($this->_id) {
            $cols = $this->generateColumns();
            $wheres = $this->generateWheres();
            $this->query = sprintf("select t.*, tt.* $cols from dir_companies t
                inner join dir_companies_translation tt on t.company_id = tt.company_id
                {$this->joins}
                where t.published = 1 
                and t.accepted = 1
                and t.company_id = %d
                and tt.content_lang = %s
                $wheres
             ", $this->_id, Yii::app()->db->quoteValue($siteLanguage));
            $this->items['record'] = Yii::app()->db->createCommand($this->query)->queryRow();
            $this->items['branches'] = array();
            if (is_array($this->items['record'])) {
                $this->items['record']['branches'] = $this->getBranches();
                $attributes = new UsedAttributesList('dir_companies_attributes', $this->items['record']['company_id']);
                $attributes->generate();
                $attributesItems = $attributes->getItems();
                foreach ($this->items['record'] as $fieldName => $fieldData) {
                    if (isset($attributesItems[$fieldName])) {
                        $this->items['record']['extended'][$fieldName] = array('belong' => array(), 'new' => array());
                        if (isset($attributesItems[$fieldName]['data'])) {
                            $this->items['record']['extended'][$fieldName]['belong'] = $attributesItems[$fieldName]['data'];
                        }
                        if (isset($attributesItems[$fieldName]['inheritedAttributes'])) {
                            foreach ($attributesItems[$fieldName]['inheritedAttributes'] as $inheritedName) {
                                if (isset($attributesItems[$inheritedName]['data'])) {
                                    $this->items['record']['extended'][$fieldName]['new'][$inheritedName]['label'] = $attributesItems[$inheritedName]['label'];
                                    $this->items['record']['extended'][$fieldName]['new'][$inheritedName]['data'] = $attributesItems[$inheritedName]['data'];
                                }
                            }
                        }
                    }
                }
                if (isset($this->items['record']["create_date"])) {
                    $this->items['record']["create_date"] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $this->items['record']["create_date"]);
                }
                if (isset($this->items['record']["image_ext"])) {
                    $this->items['record']['image'] = Yii::app()->baseUrl . "/{$mediaSettings['paths']['images']['path']}/{$this->items['record']["company_id"]}.{$this->items['record']["image_ext"]}";
                } else {
                    $this->items['record']['image'] = '';
                }
                if (isset($this->items['record']["file_ext"])) {
                    $this->items['record']['attach'] = "{$mediaSettings['paths']['attach']['path']}/{$this->items['record']["company_id"]}.{$this->items['record']['file_ext']}";
                } else {
                    $this->items['record']['attach'] = '';
                }
                $this->count = 1;
            }
        }
    }

}
