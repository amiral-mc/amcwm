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
class AmcAnalyticsController extends BackendController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array('id' => $id
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
//        $dataset = new LoggingListData();
//        $dataset->useRecordIdAsKey(false);
//        $paging = new PagingDataset($dataset, 50, (int) Yii::app()->request->getParam('page', 1));
//        $logData = new PagingDatasetProvider($paging, array());
//        
//        $this->render('index', array(
//            'logData' => $logData
//        ));
    }

}
