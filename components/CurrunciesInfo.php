<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of CurrunciesInfo
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */

class CurrunciesInfo {

    private $countryCode;

    /**
     * @todo explain the query
     * @param string $countryCode 
     */
    public function __construct($countryCode = 'EGP') {
        $this->countryCode = $countryCode;
    }

    /**
     * @todo explain the query
     * @return string
     */
    function generate() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();

        $output = "";

        $query = sprintf("select cc.*
                        , c.* 
                        , (select ct.currency_name from currency_translation ct where ct.currency_code = cc.compare_to and ct.content_lang = %s) as compareto
                        from currency_compare cc
                        inner join currency_translation c on c.currency_code = cc.compare_from
                        inner join foreign_currencies fc on fc.currency_code = cc.compare_to                       
                        where c.currency_code = %s and c.content_lang = %s
                        order by cc.rate desc", Yii::app()->db->quoteValue($siteLanguage), Yii::app()->db->quoteValue($this->countryCode), Yii::app()->db->quoteValue($siteLanguage));
        $currunciesData = Yii::app()->db->createCommand($query)->queryAll();
        if (count($currunciesData)) {
            $output .= CHtml::openTag('div', array("class" => "c_title", "style" => "background-color: rgb(238, 238, 238);"));
            $output .= Yii::t('servicesInfo', '{currency} Against Foreign Currencies', array('{currency}' => Yii::t('servicesInfo', $currunciesData[0]["currency_name"])));

            $output .= CHtml::closeTag("div");
            $output .= CHtml::openTag('div', array("class" => "c_details", "style" => "background-color: rgb(238, 238, 238);"));
            $output .= CHtml::openTag('table', array("cellpadding" => 3, "cellspacing" => 3, "width" => "100%"));
            $output .= CHtml::openTag('tr');
            $r = 1;
            foreach ($currunciesData as $curruncy) {
                $output .= CHtml::openTag('td', array("align" => "center"));
                $title = Yii::t('servicesInfo', $curruncy["compareto"]);
                $output .= "<img src='". Yii::app()->baseUrl . "/images/curruncies_flg/" . strtolower($curruncy["compare_to"]) . ".png' title='{$title}' /> <br /> ";
                $output .= "<span class='c_num'>" . round($curruncy["rate"], 3) . "</span>";
                $output .= CHtml::closeTag('td');
                $output .= ( $r % 7 == 0) ? "</tr><tr>" : "";
                $r++;
            }
            $output .= CHtml::closeTag('tr');
            $output .= CHtml::closeTag("table");
            $output .= CHtml::closeTag("div");
        } else {
            $output .= '<div>' . Yii::t('servicesInfo', 'No data available for this country') . '</div>';
        }
        return $output;
    }

}

?>
