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

class WorldNow extends CWidget {

    public $baseScriptUrl;
    public $zones = array();
    public $UTCTime;
    public $timeOffset;
    public $timeZone = 2;

    /**
     * Initializes the scroller widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->UTCTime = gmdate("H:i:s");
        $this->timeOffset = (60 * 60 * $this->timeZone);
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $output = "";

        if (count($this->zones)) {
            $assetsFolder = "";
            if ($this->baseScriptUrl === null) {
                $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.worldNow.assets'));
                $this->baseScriptUrl = $assetsFolder . "/worldnow";
            }

            $output .= CHtml::openTag("table", array("width" => "100%", "algin" => "center"));
            $output .= CHtml::openTag("tr");
            $id = 1;
            foreach ($this->zones as $zone) {
                $this->timeOffset = (60 * 60 * $zone["timezone"]);
                $timeNow = date("a", strtotime($this->UTCTime . " {$zone["timezone"]} hours"));

                $output .= CHtml::openTag("td", array("valign" => "top", "width" => "65"));

                $output .= CHtml::openTag("table");
                $output .= CHtml::openTag("tr");
                $output .= CHtml::openTag("td");

                $flashVars = "clockSkin={$this->baseScriptUrl}/skins/skin_{$timeNow}.png&amp;arrowSkin=4&amp;arrowScale=40&amp;UTCTime={$this->UTCTime}&amp;timeOffset={$this->timeOffset}&amp;arrowHColor=2b292a&amp;arrowMColor=2b292a&amp;arrowSColor=e73120";

                $output .= "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0'  width='62' height='62' id='worldNow_{$id}' style='visibility: visible; '>
                                <param name='movie' value='" . $this->baseScriptUrl . "/devAnalogClock.swf' />
                                <param name='scale' value='noscale' />
                                <param name='quality' value='high'>
                                <param name='wmode' value='transparent' />
                                <param name='flashvars' value='{$flashVars}' />
                                <embed src='{$this->baseScriptUrl}/devAnalogClock.swf' scale='noscale' flashvars='{$flashVars}' wmode='transparent' width='62' height='62' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>
                            </object>
                        ";
                $output .= CHtml::closeTag("td");
                $output .= CHtml::closeTag("tr");
                $output .= CHtml::openTag("tr");
                $output .= CHtml::openTag("td", array('align' => 'center'));
                $output .= $zone["title"];
                $output .= CHtml::closeTag("td");
                $output .= CHtml::closeTag("tr");
                $output .= CHtml::closeTag("table");

                $output .= CHtml::closeTag("td");
                $id++;
            }
            $output .= CHtml::closeTag("tr");
            $output .= CHtml::closeTag("table");
        }
        echo $output;
    }

}
