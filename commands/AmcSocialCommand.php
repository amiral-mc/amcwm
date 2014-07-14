<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */
/**
 * /var/www/lib/amcwm/demo/protected/amcc social index --module=news --post=0 --lang=ar
 * /var/www/lib/amcwm/demo/protected/amcc social index --module=multimedia --post=0 --lang=ar --sub=videos
 * /var/www/lib/amcwm/demo/protected/amcc social index --module=multimedia --post=0 --lang=ar --sub=images
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
AmcWm::import("amcwm.commands.components.social.*");

class AmcSocialCommand extends CConsoleCommand {    
    private $_updateDate = null;
    private $_moduleId = 1;
    public function init() {
        $this->_updateDate = date("Y-m-d H:i:s");
        set_time_limit(0);
        ignore_user_abort(true);
    }

    public function actionIndex($module = 'news', $sub = null, $route = null, $limit = 1, $lang = null, $post = true) {        
        $moduleClass = ucfirst($module) . "SocialData";        
        $query = sprintf('select module_id from modules where module = %s and parent_module=1', AmcWm::app()->db->quoteValue($module));        
        $this->_moduleId = AmcWm::app()->db->createCommand($query)->queryScalar();
        if (!$lang) {
            $lang = AmcWm::app()->getLanguage();
        }

        $socialsQuery = "select * from social_networks where enabled = 1";
        $socialsDataset = Yii::app()->db->createCommand($socialsQuery)->queryAll();
        $moduleClassData =  "Amc" . ucfirst($module) . ucfirst($sub) . "SocialData";        
        $msg = '';
        foreach ($socialsDataset as $social) {
            $socialClass = "Amc" . ucfirst($social['class_name']) . "Social";
            if (count(Yii::app()->params[strtolower($social['class_name'])][$lang])) {
                $socialObject = new $socialClass(!$post, Yii::app()->params[strtolower($social['class_name'])][$lang]);
                $socialObject->connect();
                $moduleObject = new $moduleClassData($module, $social['social_id'], $socialObject, $lang , $limit);                
                $moduleObject->setRoute($route);        
                $moduleObject->post();
                $msg .= "{$module} has been posted to {$social['network_name']}". PHP_EOL;
            }
            else{
                $msg .= "{$social['network_name']} not configured" . PHP_EOL;
            }
        }

        echo $msg . PHP_EOL;
        exit;
    }      
}
