<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
Yii::import('zii.widgets.jui.CJuiDialog');

class AMCPopup extends CJuiDialog {

    public $title = null;
    public $content;
    public $displayOnce = false;
    public $cookieName = "AMCPopup";
    
    private $cookieExists = false;

    /**
     * Initializes the widget.
     */
    public function init() {
        if ($this->displayOnce) {
            if (!isset(Yii::app()->request->cookies[$this->cookieName]->value)) {
                $cookie = new CHttpCookie($this->cookieName, $this->cookieName);
                $cookie->expire = time() + 2592000; // expire after one month
                Yii::app()->request->cookies[$this->cookieName] = $cookie;
            } else {
                $this->cookieExists = true;
            }
        }
        
        if (!$this->cookieExists) {
            parent::init();
        }
    }

    /**
     * Generates a tag wich opens the popup
     */
    public function run() {
        if (!$this->cookieExists) {
            echo $this->content;
            parent::run(); // this is required to close any opend tags
        }
    }

}
