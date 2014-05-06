<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * WeatherInfo, get weather info data
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WeatherInfoData extends Dataset {

    /**
     * Current city if
     * @var integer 
     */
    private $_cityId;

    /**
     * Counstructor
     * Make sure you call the parent counstructor so that the method is raised properly.
     * @param integer $cityId, The city id to get contents froms
     * @param integer $limit, The numbers of items to fetch from table     
     * @access public
     */
    public function __construct($cityId = null, $limit = 10) {
        $this->limit = (int) $limit;
        $this->_cityId = (int) $cityId;
    }

    /**
     *
     * Generate weather data
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $this->setItems();
    }

    /**
     * @todo explain the query
     * Set the glossary array list    
     * @access private
     * @return void
     */
    protected function setItems() {
        if ($this->_cityId) {
            $this->addWhere("t.city_id = {$this->_cityId}");
        }
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT SQL_CALC_FOUND_ROWS            
            t.*, tt.city name
            $cols
            FROM  `weather_cities` t
            INNER JOIN services_cities_translation tt ON t.city_id = tt.city_id    
            {$this->joins}
            WHERE tt.content_lang = %s
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ", Yii::app()->db->quoteValue($siteLanguage));
        if ($this->_cityId) {
            $weatherData = Yii::app()->db->createCommand($this->query)->queryRow();
            if ($weatherData) {
                $weatherData['wind_info'] = CJSON::decode($weatherData["wind"]);
                $weatherData['pressure_info'] = CJSON::decode($weatherData["pressure"]);
                $weatherData['uv_index_info'] = CJSON::decode($weatherData["uv_index"]);
                $weatherData['moon_info'] = CJSON::decode($weatherData["moon"]);
                $weatherData['forecast'] = CJSON::decode($weatherData["forecast"]);
                $this->items = $weatherData;
                $this->count = 1;
            }
        } else {
            $weatherData = Yii::app()->db->createCommand($this->query)->queryAll();
            $this->setDataset($weatherData);
        }
    }

    /**
     * gets weathers cities
     * @return array
     */
    public function getItems(){
        return $this->items;
    }
    /**
     *
     * Sets the the items array      
     * @param array $weatherData 
     * @access protected     
     * @return void
     */
    protected function setDataset($weatherData) {
        $publishFolder = AmcWm::app()->getAssetManager()->publish(Yii::getPathOfAlias("icons.weather"), true);
        $index = -1;
        foreach ($weatherData As $item) {
            if ($this->recordIdAsKey) {
                $index = $item['city_id'];
            } else {
                $index++;
            }
            $this->items[$index] = $item;
            $this->items[$index]['wind'] = CJSON::decode($item["wind"]);
            $this->items[$index]['pressure'] = CJSON::decode($item["pressure"]);
            $this->items[$index]['uv_index'] = CJSON::decode($item["uv_index"]);
            $this->items[$index]['moon'] = CJSON::decode($item["moon"]);
            $this->items[$index]['forecast'] = CJSON::decode($item["forecast"]);
            $this->items[$index]['icon'] = "{$publishFolder}/{$item["icon"]}.png";
            
        }
        $this->count = Yii::app()->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
    }

    /**
     * @deprecated since version number aspf
     * @todo move it to extenstion
     * @return string 
     */
    function draw() {
        $output = null;
        if ($this->_cityId) {
            $weatherData = $this->items;
            if (count($weatherData)) {

                $output = CHtml::openTag('div', array("class" => "columns clearfix", "style" => "background-color:#EEEEEE")) . "\n";

                $output .= CHtml::openTag('div', array("class" => "col c_1")) . "\n";
                $output .= CHtml::openTag('div', array("class" => "country_name")) . "\n";
                $output .= Yii::t("servicesInfo", $weatherData["name"]);
                $output .= CHtml::closeTag('div') . "\n";
                $output .= CHtml::openTag('div', array("class" => "w_data")) . "\n";
                $output .= Yii::t("servicesInfo", "sunrise") . " :";
                $output .= Yii::app()->dateFormatter->format("hh:mm a", $weatherData["sunr"]);
                $output .= CHtml::closeTag('div') . "\n";
                $output .= CHtml::openTag('div', array("class" => "w_data")) . "\n";
                $output .= Yii::t("servicesInfo", "sunset") . " :";
                $output .= Yii::app()->dateFormatter->format("hh:mm a", $weatherData["suns"]);
                $output .= CHtml::closeTag('div') . "\n";
                $output .= CHtml::closeTag('div') . "\n";


                $output .= CHtml::openTag('div', array("class" => "col c_2", "style" => "background:URL(" . Yii::app()->baseUrl . "/images/weather/{$weatherData["icon"]}.png) no-repeat scroll 15px 0px transparent ;width:190px;")) . "\n";
                $output .= CHtml::openTag('div', array("class" => "w_temp")) . "\n";
                $output .= $weatherData["temp"] . "°C";
//                $output .= CHtml::tag('img', array("src"=> "http://s.imwx.com/v.20100719.135915/img/wxicon/72/{$weatherData["icon"]}.png")) . "\n";
                $output .= CHtml::openTag('div', array("class" => "w_status")) . "\n";
                $output .= Yii::t("servicesInfo", $weatherData["status"]);
                $output .= CHtml::closeTag('div') . "\n";
                $output .= CHtml::closeTag('div') . "\n";

                $output .= CHtml::closeTag('div') . "\n";


                $output .= CHtml::closeTag('div') . "\n";

                $output .= CHtml::openTag('div', array("class" => "w_details", "style" => "background-color:#EEEEEE")) . "\n";
                $output .= CHtml::openTag('ul') . "\n";
                $output .= CHtml::openTag('li') . "\n";
                $output .= Yii::t("servicesInfo", "feelslik") . " :";
                $output .= CHtml::openTag('span', array("class" => "num")) . "\n";
                $output .= $weatherData["feelslik"] . " °";
                $output .= CHtml::closeTag('span') . "\n";
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li') . "\n";
                $output .= Yii::t("servicesInfo", "wind") . " :";
                $output .= CHtml::openTag('span', array("class" => "num")) . "\n";
                $output .= Yii::t("servicesInfo", $weatherData['wind_info']["from"]);
                $output .= " - " . $weatherData['wind_info']["speed"] . " " . Yii::t("servicesInfo", "windSpeed");
                $output .= CHtml::closeTag('span') . "\n";
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li') . "\n";
                $output .= Yii::t("servicesInfo", "humidity") . " :";
                $output .= CHtml::openTag('span', array("class" => "num")) . "\n";
                $output .= $weatherData["humidity"] . " %";
                $output .= CHtml::closeTag('span') . "\n";
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li') . "\n";
                $output .= Yii::t("servicesInfo", "visibility") . " :";
                $output .= CHtml::openTag('span', array("class" => "num")) . "\n";
                $output .= $weatherData["visibility"] . " " . Yii::t("servicesInfo", "km");
                $output .= CHtml::closeTag('span') . "\n";
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li') . "\n";
                $output .= Yii::t("servicesInfo", "pressure") . " :";
                $output .= CHtml::openTag('span', array("class" => "num")) . "\n";
                $output .= $weatherData['pressure_info']["r"] . " " . Yii::t("servicesInfo", "mb");
                $output .= CHtml::closeTag('span') . "\n";
                $output .= CHtml::closeTag('li') . "\n";
                $output .= CHtml::openTag('li') . "\n";
                $output .= Yii::t("servicesInfo", "uv_index") . " :";
                $output .= CHtml::openTag('span', array("class" => "num")) . "\n";
                $output .= $weatherData['uv_index_info']["i"] . " - ";
                $output .= Yii::t("servicesInfo", "UV " . $weatherData['uv_index_info']["t"]);
                $output .= CHtml::closeTag('span') . "\n";
                $output .= CHtml::closeTag('li') . "\n";
//            $output .= CHtml::openTag('li') . "\n"; 
//                $output .= Yii::t("servicesInfo", "moon") . " :";
//                $output .= CHtml::openTag('span', array("class"=>"num")) . "\n";                 
//                $output .= $weatherData['uv_index_info']["i"] . " - ";
//            $output .= CHtml::closeTag('li') . "\n"; 
                $output .= CHtml::closeTag('ul') . "\n";
                $output .= CHtml::closeTag('div') . "\n";
            }
        }
        return $output;
    }

}
