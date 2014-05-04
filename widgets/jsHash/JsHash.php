<?php

class JsHash extends CWidget {

    public $baseScriptUrl;

    public function run() {
        if ($this->baseScriptUrl === null) {
            $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.jsHash.assets'));
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($this->baseScriptUrl . '/Hash.js');
    }

}