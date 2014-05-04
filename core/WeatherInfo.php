<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * WeatherInfo, get weather info and display it inside widget
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WeatherInfo extends ExecuteWidget {    

    /**
     * default city id
     * @var integer 
     */
    public $defaultCity ;
    /**
     * prepare widget properties
     */
    protected function prepareProperties() {        
        $list = new WeatherInfoData(AmcWm::app()->request->getParam("city", $this->defaultCity));
        $list->generate();        
        $this->setProperty('items', $list->getItems());
        
        $this->setProperty('defaultCity', $this->defaultCity);
        $this->setProperty('cities', $this->getCities());
    }
    
    protected function getCities(){
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $query = sprintf("SELECT * FROM services_cities sc
            INNER JOIN services_cities_translation sct ON sc.city_id = sct.city_id    
            WHERE sct.content_lang = %s
            ", Yii::app()->db->quoteValue($siteLanguage));
        $dataset = Yii::app()->db->createCommand($query)->queryAll();
        $cities = array();
        foreach ($dataset as $city){
            $cities[$city['city_id']] = $city['city'];
        }
//        $this->setProperty('items', array());
        return $cities;
    }

}

