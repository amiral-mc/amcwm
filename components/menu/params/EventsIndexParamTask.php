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
class EventsIndexParamTask extends ParamsTask {

    /**
     * Generate the array the contain the data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1) {
        $dataset = new SectionsData("events");
        $dataset->setLanguage(Controller::getContentLanguage());
        $dataset->getTopParentOnly(false);
        $dataset->useRecordIdAsKey(false);
        $dataset->addWhere("section_name like " . Yii::app()->db->quoteValue('%%' . $this->searchFor . '%%'));
        if ($this->selectedRow)
            $dataset->addOrder('FIELD(t.section_id, ' . $this->selectedRow . ') DESC, t.section_id');
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
        $query = sprintf("select section_name from sections_translation 
                 where section_id = %d and content_lang = %s
                ", $itemId, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    public function validate($paramData) {
//        $params = $this->params;
        return true;
    }

}

