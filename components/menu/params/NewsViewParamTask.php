<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesViewTask class, run the section task
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class NewsViewParamTask extends ParamsTask {

    /**
     * Generate the array the contain the data 
     * @param integer $page, current page
     * @access public
     * @return void   
     * @todo change the articles module and get the list of news with paging.  
     */
    public function generate($page = 1) {
        $dataset = new ArticlesListData(array("news"));    
        $dataset->setLanguage(Controller::getContentLanguage());
        $dataset->useRecordIdAsKey(false);
        $dataset->addWhere("tt.article_header like " . Yii::app()->db->quoteValue('%%' . $this->searchFor . '%%'));
        if ($this->selectedRow)
            $dataset->addOrder('FIELD(t.article_id, ' . $this->selectedRow . ') DESC, t.article_id');
        $paging = new PagingDataset($dataset, $this->pageSize, $page);
        $this->setDataProvider($paging);        
        $this->success = true;
    }

    /**
     * Get the item title
     * @access public
     * @return string
     */
    public function getTitle() {
        $itemId = $this->searchFor;
        $query = sprintf("select article_header from articles_translation 
                 where article_id = %d and content_lang = %s
                ", $itemId, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    public function validate($paramData) {
        $valid = true;
        foreach ($this->params as $param){
            switch ($param['param_type']){
                case 'ROUTE':
                    if($paramData[$param['param_id']] == null){
                        $valid = false;
                    }
                break;
            }
        }
        return $valid;
    }

}

