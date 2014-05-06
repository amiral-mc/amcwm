<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class PagingDatasetProvider extends CDataProvider {

    /**
     * @var PagingDataset
     * Defaults to null, meaning using Yii::app()->db.
     */
    protected $pageingDataset;

    /**
     * @var array
     */
    protected $pagingDataItems = array();

    /**
     * @var string the name of key field. Defaults to 'id'.
     */
    public $keyField = 'id';

    /**
     * Constructor.
     * @param PagingDataset $pageingDataset
     * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
     */
    public function __construct($pageingDataset, $config = array()) {
        $this->pageingDataset = $pageingDataset;
        $this->pagingDataItems = $this->pageingDataset->getData();
//        if (!isset($config['pagination'])) {
//            $config['pagination']['pageSize'] = $this->pagingDataItems['pager']['pageSize'];
//        }
        $config['pagination']['pageSize'] = $this->pagingDataItems['pager']['pageSize'];
        foreach ($config as $key => $value)
            $this->$key = $value;
    }

    /**
     * Fetches the data from the persistent data storage.
     * @return array list of data items
     */
    protected function fetchData() {
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->setItemCount($this->getTotalItemCount());
        }
        return $this->pagingDataItems['records'];
    }

    /**
     * Fetches the data item keys from the persistent data storage.
     * @return array list of data item keys.
     */
    protected function fetchKeys() {
        $keys = array();
        foreach ($this->getData() as $i => $data)
            $keys[$i] = $data[$this->keyField];
        return $keys;
    }

    /**
     * Calculates the total number of data items.
     * This method is invoked when {@link getTotalItemCount()} is invoked
     * and {@link totalItemCount} is not set previously.
     * The default implementation simply returns 0.
     * You may override this method to return accurate total number of data items.
     * @return integer the total number of data items.
     */
    protected function calculateTotalItemCount() {
        return $this->pagingDataItems['pager']['count'];
    }

}
