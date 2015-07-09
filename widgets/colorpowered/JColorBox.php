<?php

/**
 * ColorBox
 *
 * @version 1.00
 * @author maimairel <maimairel@yahoo.com>
 */
class JColorBox extends CWidget
{

    private static $_instances = 0;
    protected $scriptUrl;
    protected $scriptFile;
    public $cssFile;
    /*
     * $cookieExpiration default to 3 days
     */
    public $cookieExpiration = '259200';

    /**
     *
     * @var string cookie name  
     */
    public $cookieName = "amc_popup";

    public function init() {
        $this->scriptUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');

        $this->scriptFile = YII_DEBUG ? '/js/jquery.colorbox.js' : '/js/jquery.colorbox-min.js';

        if ($this->cssFile == NULL)
            $this->cssFile = '/colorbox.css';

        $this->registerClientScript();
    }

    public function registerClientScript() {
        $cs = Yii::app()->clientScript;

        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->scriptUrl . $this->scriptFile);

        $cs->registerCssFile($this->scriptUrl . $this->cssFile);
    }

    public function addInstance($selector, $options = array()) {
        self::$_instances++;
        $runMe = isset($options['open']) && $options['open'];
        if ($runMe) {            
            if ($this->cookieName) {
//                Html::printR($_COOKIE, 1);
                if (!isset(Yii::app()->request->cookies[$this->cookieName . self::$_instances]->value)) {                    
                    if (isset($options['remove'])) {
                        unset($options['remove']);
                    }
                    $cookie = new CHttpCookie($this->cookieName . self::$_instances, 1);
                    $cookie->expire = time() + $this->cookieExpiration; // expire after 3 days
                    $cookie->httpOnly = true;
                    Yii::app()->request->cookies[$this->cookieName . self::$_instances] = $cookie;
                    $runMe = true;
                } else {
                    unset($options['open']);
                    if (isset($options['remove'])) {
                        $runMe = false;
                    }
                }
            }
        }
        if ($runMe) {
            $options = CJavaScript::encode($options);
            $id = __CLASS__ . '_' . sprintf("%x", crc32($selector . $options));
            Yii::app()->clientScript->registerScript($id, "jQuery('$selector').colorbox($options);");
        }

        return $this;
    }

}
