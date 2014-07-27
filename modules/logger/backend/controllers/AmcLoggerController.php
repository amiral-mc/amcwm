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
class AmcLoggerController extends BackendController {

    /**
     *
     * @var string table to get listing from
     */
    public $logTable = null;

    /**
     *
     * @var integer item id to list log acording to it
     */
    public $itemId = null;

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     * @access public
     * @return $void
     */
    public function init() {
        if (AmcWm::app()->request->getParam('from')) {
            $_GET[AmcWm::WINDOW_AJAX] = 1;
        }
        parent::init();
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $logId = AmcWm::app()->request->getParam("id");
        $logManager = new DbLogManager($logId);
        $logDetails = $logManager->getLogData();
        $logInfo = $logManager->getLog();
        if (isset($logDetails['data']['options']['template'])) {
            $template = $logDetails['data']['options']['template'];
            $logData = $logDetails['data']['data'];
        } else {
            $template = "_view";
            $logData = $logDetails;
        }
        $this->render('view', array(
            'logDetails' => $logDetails,
            'logData' => $logData,
            'view' => $template,
            'logInfo' => $logInfo
        ));
    }

    /**
     * @return array action filters
     */
    public function filters() {
        $filters = parent::filters();
        $filters[] = 'logTableContext';
        return $filters;
    }

    /**
     * In-class defined filter method, configured for use in the above filters() method
     * It is called before the actionCreate() action method is run in order to ensure a proper gallery context
     */
    public function filterLogTableContext($filterChain) {
        $from = AmcWm::app()->request->getParam('from');
        $itemId = (int) AmcWm::app()->request->getParam('itemId');
        if ($from) {
            $this->logTable = $from;
        }
        if ($itemId && $this->logTable) {
            $this->itemId = $itemId;
        }
        $filterChain->run();
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataset = new LoggingListData($this->logTable, $this->itemId);
        $dataset->useRecordIdAsKey(false);
        $paging = new PagingDataset($dataset, 50, (int) Yii::app()->request->getParam('page', 1));
        $logData = new PagingDatasetProvider($paging, array());
        $this->render('index', array(
            'logData' => $logData
        ));
    }

}
