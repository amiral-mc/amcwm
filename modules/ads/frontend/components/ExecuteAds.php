<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExecuteAds
 *
 * @author abdallah
 */
Amcwm::import('amcwm.core.backend.models.Sections');

class ExecuteAds extends ExecuteWidget {

    /**
     * @var type integer Zone ID
     */
    public $zoneId;

    protected function prepareProperties() {
        $route = AmcWm::app()->getController()->getRoute();
        $id = Yii::app()->request->getParam('id');
        $zones = false;
        if ($route == 'articles/default/sections' && $id) {
            $zones = Yii::app()->db->createCommand()
                    ->select()
                    ->from('ads_zones a')
                    ->join('ads_servers_config c', 'a.server_id = c.server_id')
                    ->join('ads_zones_has_sections s', 'a.ad_id = s.ad_id')
                    ->where('zone_id = ' . $this->zoneId)
                    ->queryAll();
            if(!$zones){
                $zones = $this->_getDefaultInvocation();
            }
        } else {
            $zones = $this->_getDefaultInvocation();
        }
        $this->setProperty('zones', $zones);
    }

    private function _getDefaultInvocation() {
        $zones = Yii::app()->db->createCommand()
                ->select('a.ad_id, a.server_id, zone_id, invocation_code, header_code, published, server_name, section_id')
                ->from('ads_zones a')
                ->join('ads_servers_config c', 'a.server_id = c.server_id')
                ->leftJoin('ads_zones_has_sections s', 'a.ad_id = s.ad_id')
                ->where('zone_id = ' . $this->zoneId)
                ->andWhere('section_id is null')
                ->queryAll();
        return $zones;
    }

}
