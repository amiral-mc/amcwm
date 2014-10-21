<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * HttpSession provides session-level data management and the related configurations.
 *
 * @package AmcWm.web
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class HttpSession extends CHttpSession {

    /**
     * Starts the session if it has not started yet.
     */
    public function open() {
        if ($this->getUseCustomStorage())
            @session_set_save_handler(array($this, 'openSession'), array($this, 'closeSession'), array($this, 'readSession'), array($this, 'writeSession'), array($this, 'destroySession'), array($this, 'gcSession'));

        @session_start();
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id();
            $_SESSION['initiated'] = true;
        }
        if (!isset($_SESSION['hashed'])) {
            $_SESSION['hashed'] = $this->securityHash();
        }
        if ($_SESSION['hashed'] != $this->securityHash()) {
            $this->destroy();
        }
        if (YII_DEBUG && session_id() == '') {
            $message = Yii::t('yii', 'Failed to start session.');
            if (function_exists('error_get_last')) {
                $error = error_get_last();
                if (isset($error['message']))
                    $message = $error['message'];
            }
            Yii::log($message, CLogger::LEVEL_WARNING, 'system.web.CHttpSession');
        }
    }

    /**
     * Security hash to avoid session fixation
     * @return string
     */
    public function securityHash() {
        $hashedString = "CAMp{oKq9!~";
        $proxyHeaders = array(
            'HTTP_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP'
        );
        foreach ($proxyHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $hashedString .= $_SERVER[$header];
                break;
            }
        }
        $hashedString .= $_SERVER['REMOTE_ADDR'];
        $hashedString .= empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
        return md5($hashedString);
    }

}
