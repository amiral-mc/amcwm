<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectoryIndexParamTask class, run the section task
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DocumentsIndexParamTask extends ParamsTask {

    /**
     * Generate the array the contain the data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1) {
        $dataset = new DocumentsCategoriesData();
        $dataset->setLanguage(Controller::getContentLanguage());
        $dataset->useRecordIdAsKey(false);
        $dataset->addWhere("category_name like " . Yii::app()->db->quoteValue('%%' . $this->searchFor . '%%'));
        if ($this->selectedRow)
            $dataset->addOrder('FIELD(t.category_id, ' . $this->selectedRow . ') DESC, t.category_id');
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
        return DocumentsCategoriesData::getTitle($this->searchFor);
    }

    public function validate($paramData) {
//        $params = $this->params;
        return true;
    }

}

