<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Services extends SideWidget {
   
     /**
     * Initializes the player widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->contentClass .= " noBG";
        parent::init();
    }
    /**
     * @todo explain the query
     * @todo remove queries and dataset generation code from here and add it ExecuteWidget instance
     * Calls {@link renderItem} to render the menu.
     */
    public function setContentData() {
     
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $weather = new WeatherInfoData(1);               
        $weather->generate();
        $curruncies = new CurrunciesInfo('EGP');
        //$prayer = new PrayerInfo(1);
        $query4Select = sprintf("
            select t.city_id, city
            from services_cities t
            inner join  services_cities_translation tt on t.city_id = tt.city_id
            where content_lang = %s
            order by city
        ", Yii::app()->db->quoteValue($siteLanguage));
        $citiesData = Yii::app()->db->createCommand($query4Select)->queryAll();
        $cities = array();
        foreach($citiesData as $city){
            $cities[$city['city_id']] = Yii::t('servicesInfo', $city['city']);
        }
        //$cities = CHtml::listData(Yii::app()->db->createCommand($query4Select)->queryAll(), 'city_id', "city");
        //$countries = CHtml::listData(Yii::app()->db->createCommand($query4Select)->queryAll(), 'code', "country_{$siteLanguage}");
        //$prayer->generate();
        $form = '<div class="service_select"><form>';
        $form .= CHtml::dropDownList('cites_services_list', '1', $cities, array('id' => 'cites_services_list'));
        $form .= '</form></div>';
        $worldNow = $this->widget('amcwm.widgets.worldNow.WorldNow', array("zones" => array(
                        array("timezone" => "+9", "title" => Yii::t("servicesInfo", "Tokyo"))
                        , array("timezone" => "+3", "title" => Yii::t("servicesInfo", "Riyadh"))
                        , array("timezone" => "-5", "title" => Yii::t("servicesInfo", "New York"))
                        , array("timezone" => "+0", "title" => Yii::t("servicesInfo", "London"))
                        )), true);
        $serviceTabs = array
            (
            'servicesCurrencies' => array('title' => AmcWm::t("amcFront", "Currencies"), 'content' => $curruncies->generate()),
            'servicesWeather' => array('title' => AmcWm::t("amcFront", "Weather"), 'content' => $weather->draw()),            
//            'servicesPrayer' => array('title' => AmcWm::t("amcFront", "Prayer Times"), 'content' => ''),
            'servicesWorld' => array('title' => AmcWm::t("amcFront", "World Now"), 'content' => $worldNow),
        );
        $this->contentData = $form . $this->widget('TabView', array('tabs' => $serviceTabs), true);

        $ajax = CHtml::ajax(array(
                    // the controller/function to call
            
                    'url' => Html::createUrl('/site/ajax', array("do"=>"services")),
                    'data' => array('city' => 'js:$(\'#cites_services_list\').val()',),
                    'type' => 'get',
                    'dataType' => 'xml',
                    'error' => "function(jqXHR, textStatus, errorThrown){alert(errorThrown)}",
                    'success' => "function(data){
                // data will contain the xml data passed by the subSections action in the controller
                
                
                if (data){     
                
                
                    weather = $(data).find('weather').text(); // get weather from data (xml)
                    currencies = $(data).find('curruncies').text(); // get currencies from data (xml)
                    $('#servicesWeather').html(weather);
                    $('#servicesCurrencies').html(currencies);
                } 
            } ",
                ));
        Yii::app()->clientScript->registerScript('servicesTabs', "
    $('#cites_services_list').change(function(){
        " . $ajax . "
    });  
");
    }

}