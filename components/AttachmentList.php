<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AttachmentList, Generate attachment data 
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttachmentList extends Dataset {

    /**
     * Flags of attach content types
     */
    const IMAGE = 'IMAGE';
    const INTERNAL_VIDEO = 'INTERNAL_VIDEO';
    const EXTERNAL_VIDEO = 'EXTERNAL_VIDEO';
    const LINK = 'LINK';

    /**
     * current table structure
     * @var array
     */
    private $_table;

    /**
     * current module id
     * @var array
     */
    private $_moduleId;

    /**
     * current item primary id value
     * @var integer
     */
    private $_id;

    /**
     * Setting instance
     * @var Settings
     * @var array
     */
    private $_settings = null;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param string $module
     * @param string $table
     * @param integer $id
     * @param integer $limit
     */
    public function __construct($module, $table, $id, $limit = 10) {
        $this->_settings = new Settings($module, false);
        $this->_table = $this->_settings->getTableStruct($table);
        $this->_id = $id;
        $moduleData = amcwm::app()->acl->getModule($module);
        $this->_moduleId = (int) $moduleData['id'];
        $this->limit = $limit;
        $this->recordIdAsKey = false;
    }

    /**
     *
     * Generate lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        if (!count($this->orders)) {
         $this->addOrder("t.attach_sort asc");
        }
        parent::generate();
    }

    /**
     * Set the attachment items
     * @todo explain the query
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT 
            t.attach_id
            ,t.content_type
            ,t.attach_url
            ,tt.title {$cols}
            FROM  attachment t
            left join attachment_translation tt on t.attach_id = tt.attach_id and tt.content_lang = %s 
            {$this->joins}
            where module_id = %d  and table_id = %d  and ref_id = %d
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ", Yii::app()->db->quoteValue($siteLanguage), $this->_moduleId, $this->_table['id'], $this->_id);
        $dataset = Yii::app()->db->createCommand($this->query)->queryAll();
        $index = -1;
        foreach ($dataset As $row) {
            if ($this->recordIdAsKey) {
                $index = $row['attach_id'];
            } else {
                $index++;
            }
            $this->items[$index]['title'] = $row["title"];
            $this->items[$index]['content_type'] = $row["content_type"];
            $this->items[$index]['id'] = $row["attach_id"];
            $this->items[$index]['link'] = $row["attach_url"];
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $row[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}