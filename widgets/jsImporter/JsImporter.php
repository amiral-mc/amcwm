<?php

class JsImporter extends CWidget {

    public function init() {
        
    }

    public function run() {
        $baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.jsImporter.assets'));
        $clientScripts = Yii::app()->getClientScript();
        $clientScripts->registerScriptFile($baseScriptUrl . '/jquery.scrollto.js', CClientScript::POS_HEAD);
        
        
        $js = "
            $('a').click(function(e){
                $('html,body').scrollTo(this.hash, this.hash);
                //e.preventDefault();
            });
        ";
        $clientScripts->registerScript(__CLASS__, $js, CClientScript::POS_READY);
    }

}
