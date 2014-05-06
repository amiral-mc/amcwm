<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of PrayerInfo
 * @author Amiral Management Corporation amc.amiral.com
 */
class PrayerInfo {

    private $cityId;
    private $timeFormate = "hh:mm";

    public function __construct($cityId = 1) {
        $this->cityId = $cityId;
    }

    /**
     * @todo explain the query
     * @return string
     */
    function generate() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();

        $output = "";
        $timeNow = date("Hi");

        $query = sprintf("select pt.*, sc.city as cityName 
                        from prayer_times pt
                        inner join services_cities_translation sc on sc.city_id = pt.city_id
                        where pt.city_id = %d and sc.content_lang = %s
                        ", $this->cityId, Yii::app()->db->quoteValue($siteLanguage));
        $prayerData = Yii::app()->db->createCommand($query)->queryRow();
        if (count($prayerData)) {

            $output .= CHtml::openTag('div', array("class" => "p_country_name"));
            $output .= Yii::t("servicesInfo", $prayerData["cityName"]);
            $output .= CHtml::closeTag("div");
            $output .= CHtml::openTag('div', array("class" => "p_details", "style" => "background-color: #EEEEEE;"));
            $output .= CHtml::openTag("table", array("width" => "100%"));
            $output .= CHtml::openTag("tr");
            $output .= CHtml::openTag("td", array("class" => "p_label " . ((date("Hi", $prayerData["fajr"]) < $timeNow && $timeNow > date("Hi", $prayerData["isha"])) ? "p_active" : "" )));
            $output .= Yii::t("servicesInfo", "Fajr");
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_label"));
            $output .= Yii::t("servicesInfo", "sunrise");
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_label " . ((date("Hi", $prayerData["dhuhr"]) > $timeNow && date("Hi", $prayerData["dhuhr"]) < date("Hi", $prayerData["asr"])) ? "p_active" : "" )));
            $output .= Yii::t("servicesInfo", "Zuhr");
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_label " . ((date("Hi", $prayerData["asr"]) > $timeNow && $timeNow > date("Hi", $prayerData["dhuhr"])) ? "p_active" : "" )));
            $output .= Yii::t("servicesInfo", "Asr");
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_label " . ((date("Hi", $prayerData["maghrib"]) > $timeNow && $timeNow > date("Hi", $prayerData["asr"])) ? "p_active" : "" )));
            $output .= Yii::t("servicesInfo", "Maghreb");
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_label " . ((date("Hi", $prayerData["isha"]) > $timeNow && $timeNow > date("Hi", $prayerData["maghrib"])) ? "p_active" : "" )));
            $output .= Yii::t("servicesInfo", "Isha");
            $output .= CHtml::closeTag("td");
            $output .= CHtml::closeTag("tr");
            $output .= CHtml::openTag("tr");
            $output .= CHtml::openTag("td", array("class" => "p_time " . ((date("Hi", $prayerData["fajr"]) < $timeNow && $timeNow > date("Hi", $prayerData["isha"])) ? "p_active" : "" )));
            $output .= Yii::app()->dateFormatter->format($this->timeFormate, $prayerData["fajr"]) . " " . Yii::t("servicesInfo", date("A", $prayerData["fajr"]));
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_time"));
            $output .= Yii::app()->dateFormatter->format($this->timeFormate, $prayerData["sunrise"]) . " " . Yii::t("servicesInfo", date("A", $prayerData["sunrise"]));
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_time " . ((date("Hi", $prayerData["dhuhr"]) > $timeNow && date("Hi", $prayerData["dhuhr"]) < date("Hi", $prayerData["asr"])) ? "p_active" : "" )));
            $output .= Yii::app()->dateFormatter->format($this->timeFormate, $prayerData["dhuhr"]) . " " . Yii::t("servicesInfo", date("A", $prayerData["dhuhr"]));
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_time " . ((date("Hi", $prayerData["asr"]) > $timeNow && $timeNow > date("Hi", $prayerData["dhuhr"])) ? "p_active" : "" )));
            $output .= Yii::app()->dateFormatter->format($this->timeFormate, $prayerData["asr"]) . " " . Yii::t("servicesInfo", date("A", $prayerData["asr"]));
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_time " . ((date("Hi", $prayerData["maghrib"]) > $timeNow && $timeNow > date("Hi", $prayerData["asr"])) ? "p_active" : "" )));
            $output .= Yii::app()->dateFormatter->format($this->timeFormate, $prayerData["maghrib"]) . " " . Yii::t("servicesInfo", date("A", $prayerData["maghrib"]));
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "p_time " . ((date("Hi", $prayerData["isha"]) > $timeNow && $timeNow > date("Hi", $prayerData["maghrib"])) ? "p_active" : "" )));
            $output .= Yii::app()->dateFormatter->format($this->timeFormate, $prayerData["isha"]) . " " . Yii::t("servicesInfo", date("A", $prayerData["isha"]));
            $output .= CHtml::closeTag("td");
//            $output .= CHtml::openTag("tr");
//                $output .= CHtml::openTag("td", array("class"=>"p_pmam"));
//                    $output .= Yii::t("servicesInfo", "AM");
//                $output .= CHtml::closeTag("td");
//                $output .= CHtml::openTag("td", array("class"=>"p_pmam"));
//                    $output .= Yii::t("servicesInfo", "AM");
//                $output .= CHtml::closeTag("td");
//                $output .= CHtml::openTag("td", array("class"=>"p_pmam"));
//                    $output .= Yii::t("servicesInfo", "AM");
//                $output .= CHtml::closeTag("td");
//                $output .= CHtml::openTag("td", array("class"=>"p_pmam"));
//                    $output .= Yii::t("servicesInfo", "AM");
//                $output .= CHtml::closeTag("td");
//                $output .= CHtml::openTag("td", array("class"=>"p_pmam"));
//                    $output .= Yii::t("servicesInfo", "AM");
//                $output .= CHtml::closeTag("td");
//                $output .= CHtml::openTag("td", array("class"=>"p_pmam"));
//                    $output .= Yii::t("servicesInfo", "AM");
//                $output .= CHtml::closeTag("td");
//            $output .= CHtml::closeTag("tr");
            $output .= CHtml::closeTag("table");
            $output .= CHtml::closeTag("div");
        }

        return $output;
    }

}

?>
