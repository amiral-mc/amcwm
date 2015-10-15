<?php


/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Ads widget
 * 
 * @package AmcWm.modules.ads.frontend
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AdsWidget extends Widget{
    
    /**
     * @var type array Zones Items Data
     */
    public $zones;
    
    public function run(){
        $cs = Yii::app()->clientScript;
        foreach($this->zones as $zone){
            $cs->registerScript('server_id_' . $zone['server_id'] . '_' . $zone['server_id'], $zone['header_code'], CClientScript::POS_HEAD);
            echo $zone['invocation_code'];
        }
    }
}
