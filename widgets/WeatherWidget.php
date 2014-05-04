<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * WeatherWidget display weather information
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WeatherWidget extends SideWidget {

    /**
     * @var array list of name-value pairs dataset.
     */
    public $items = null;
    public $cities = null;
    public $defaultCity = null;

    /**
     * Set the data content of this widget
     * @return void
     */
    public function setContentData() {
        $publishWeatherFolder = AmcWm::app()->getAssetManager()->publish(Yii::getPathOfAlias("icons.weather"), true);
        $weatherData = $this->items;
        $output = "";
        if (count($weatherData)) {
            $output .= CHtml::openTag('div', array("class" => "weather", "id" => "weather")) . "\n";
                if($this->cities && !$this->contentOnly){
                    $output .= CHtml::openTag('div', array("class" => "weather_title")) . "\n";
                    $output .= AmcWm::t("amcServicesInfo", 'Weather Status');
                    $output .= CHtml::closeTag('div') . "\n";
                    $output .= CHtml::openTag('div', array("class" => "weather_countries")) . "\n";
                    $output .= $this->widget('widgets.dropDownSwitcher.DropDownSwitcher', array(
                                    'id' => 'country-select',
                                    'items' => $this->cities,
                                    'selected' => $this->defaultCity,
                                    'useMyCss' => true,
                                    'ajaxAction' => array(
                                        'targetDiv'=> 'weatherInformation'
                                    ),
                                    'switcherRouteAction' => '/site/ajax/do/weather',
                                    'selecteName' => 'country',
                                    'options' => array('switcherValue' => null, 'selectName' => 'country')
                                ), true);
                    $output .= CHtml::closeTag('div') . "\n";
                }
                $output .= CHtml::openTag('div', array("id" => "weatherInformation")) . "\n";
                    $output .= CHtml::openTag('div', array("class" => "weather_info")) . "\n";
                    $output .= '<div class="info">
                                    <div class="temp">' . $weatherData["temp"] . '°C</div>
                                    <div class="humidity">' . AmcWm::t("amcServicesInfo", 'humidity') . ' : ' . $weatherData["humidity"] . ' %</div>
                                    <div class="sunrise">' . AmcWm::t("amcServicesInfo", 'sunrise') . ' : ' . Yii::app()->dateFormatter->format("hh:mm a", $weatherData["sunr"]) . '</div>
                                    <div class="sunset">' . AmcWm::t("amcServicesInfo", 'sunset') . ' : ' . Yii::app()->dateFormatter->format("hh:mm a", $weatherData["suns"]) . '</div>
                                </div>';
                    $output .= '
                            <div class="icon"><img src="' . $publishWeatherFolder . '/' . ($weatherData["icon"]<=9?"0{$weatherData["icon"]}":$weatherData["icon"]) . '.png" width="61" height="61"></div>
                            <div class="wind"> ' . AmcWm::t("amcServicesInfo", "wind") . ' : ';
                    $output .= Yii::t("amcServicesInfo", $weatherData['wind_info']["from"]);
                    $output .= " - " . $weatherData['wind_info']["speed"] . " " . AmcWm::t("amcServicesInfo", "windSpeed");
                    $output .= '</div>';
                    $output .= CHtml::closeTag('div') . "\n";

                    $output .= CHtml::openTag('div', array('class' => 'weather_forcasting')) . "\n";
                    $forecasting = $weatherData['forecast'];
                    if (count($forecasting)) {
                        $days = $icons = $temp = '';
                        for ($i = 1; $i <= 4; $i++) {
                            $forcast = $forecasting[$i];
                            $days .= '<td class="day">' . AmcWm::t("amcServicesInfo", $forcast['t']) . '</td>';
                            $icons .= '<td><img src="' . $publishWeatherFolder . '/' . ($forcast['part']['d']["icon"]<=9?"0{$forcast['part']['d']["icon"]}":$forcast['part']['d']["icon"]) . '.png" width="31" height="31"></td>';
                            $temp .= '<td class="temp">' . $forcast['hi'] . '/' . $forcast['low'] . ' </td>';
                        }
                        $output .= "<table><tbody><tr>{$days}</tr><tr>{$icons}</tr><tr>{$temp}</tr></tbody></table>";
                    }
                    $output .= CHtml::closeTag('div') . "\n";
                $output .= CHtml::closeTag('div') . "\n";
            $output .= CHtml::closeTag('div') . "\n";
        }

        $this->contentData = $output;
    }

}