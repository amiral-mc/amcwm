<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdsWidget
 *
 * @author abdallah
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
