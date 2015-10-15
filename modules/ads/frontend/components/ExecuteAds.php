<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Excute ads widget
 * 
 * @package AmcWm.modules.ads.frontend
 * @author Amiral Management Corporation
 * @version 1.0
 */

class ExecuteAds extends CComponent {

    
    public static function getAd($zoneId) {
        $route = AmcWm::app()->getController()->getRoute();
        $id = Yii::app()->request->getParam('id');
        $ads = false;
        if ($route == 'articles/default/sections' && $id) {
            $ads = Yii::app()->db->createCommand()
                    ->select()
                    ->from('ads_zones a')
                    ->join('ads_servers_config c', 'a.server_id = c.server_id')
                    ->join('ads_zones_has_sections s', 'a.ad_id = s.ad_id')
                    ->where('zone_id = ' . $zoneId . " and published = 1 and s.section_id = " . (int) $id)
                    ->queryAll();
            if(!$ads){
                $ads = self::_getDefaultInvocation($zoneId);
            }
        } else {
            $ads = self::_getDefaultInvocation($zoneId);
        }        
        if($ads){
            $adIndex = array_rand($ads);
            if($ads[$adIndex]['header_code']){
                $cs = Yii::app()->clientScript;
                $cs->registerScript('server_id_' . $ads[$adIndex]['server_id'] . '_' . $ads[$adIndex]['server_id'], $ads[$adIndex]['header_code'], CClientScript::POS_HEAD);
            }
            return $ads[$adIndex]['invocation_code'];
        }
        
    }

    private static function _getDefaultInvocation($zoneId) {
        $ads = Yii::app()->db->createCommand()
                ->select('a.ad_id, a.server_id, zone_id, invocation_code, header_code, published, server_name, section_id')                
                ->from('ads_zones a')
                ->join('ads_servers_config c', 'a.server_id = c.server_id')
                ->leftJoin('ads_zones_has_sections s', 'a.ad_id = s.ad_id')
                ->where('zone_id = ' . $zoneId . " and published = 1 and section_id is null")
                ->queryAll();
        return $ads;
    }

}
