<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */

AmcWm::import("amcwm.commands.components.services.*");
AmcWm::import("application.commands.components.services.*");

class AmcServicesCommand extends CConsoleCommand {

    private $updateDate = null;

    public function actionIndex($service) {
        $serviceQuery = sprintf("select * from services where enabled=1 and service_name = %s", Yii::app()->db->quoteValue($service));

        $serviceDataset = Yii::app()->db->createCommand($serviceQuery)->queryRow();        
        if ($serviceDataset) {
            $className = "Amc" . ucfirst($serviceDataset['class_name']) . "Service";
            $serviceClass = new $className($serviceDataset);
            $serviceClass->run();
            $msg = "{$service} has been updated.";
        }
        else{
            $msg = "Error: no such {$service}";
        }
        echo $msg . PHP_EOL;
        exit;
    }

    public function init() {
        $this->updateDate = date("Y-m-d H:i:s");
        set_time_limit(0);
        ignore_user_abort(true);
    }

}

