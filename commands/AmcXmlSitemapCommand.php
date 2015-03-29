<?php

AmcWm::import("amcwm.commands.components.xmlSitemap.*");

class AmcXmlSitemapCommand extends CConsoleCommand {

    public function actionIndex($module = 'news', $sub = null, $route = null, $lang = null) {        
        $xmlClassData = "Amc" . ucfirst($module) . ucfirst($sub) . "XmlSitemapData";        
        $id = $module;
        if ($sub) {
            $id .= "/{$sub}";
        }
        $xmlClassObject = new $xmlClassData($this, $id, $lang);        
        $xmlClassObject->setRoute($route);
        $ok = $xmlClassObject->generate();
        if ($ok){
            echo "XML Site Map has been generated\n";
        }        
    }

    public function init() {
        set_time_limit(0);
        ignore_user_abort(true);
    }

}
