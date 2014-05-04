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
class DeviceDetection {

    public function BeginRequest() {
        require_once 'MobileDetect.php';
        $detect = new MobileDetect();
        if ($detect->isMobile()) {
            Yii::app()->defaultController = "mobile";
        }
//        if ($detect->isMobile()) {
//            $cookieName = "isMobile";
//            if (!isset(Yii::app()->request->cookies[$cookieName]->value)) {
//                $cookie = new CHttpCookie($cookieName, $cookieName);
//                $cookie->expire = time() + 3600;
//                Yii::app()->request->cookies[$cookieName] = $cookie;
//                
//                //mobile/details&id=9862&lang=ar
//                $router = 'mobile/index';
//                $siteLanguage = Yii::app()->user->getCurrentLanguage();
//                $articleId = Yii::app()->request->getParam("id");
//                $langCode = Yii::app()->request->getParam("lang");
//                $extraUrl = array();
//                $extraUrl["lang"] = isset ($langCode)?$langCode:$siteLanguage;
//                if(isset ($articleId)){
//                    $router = 'mobile/details';
//                    $extraUrl["id"] = $articleId;
//                }
//                
//                
//                Yii::app()->request->redirect(Html::createUrl($router, $extraUrl));
//            }
//        }
    }

}

?>
