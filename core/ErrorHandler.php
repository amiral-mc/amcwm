<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ErrorHandler class, Custom error class
 * @package AmcWebManager
 * @subpackage Error
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ErrorHandler extends CErrorHandler {

    /**
     * Custom error
     * @var array 
     */
    private $_attachedError = array('code' => 0, 'message' => null);

    /**
     * Returns the details about the error that is currently being handled.
     * The error is returned in terms of an array, with the following information:
     * <ul>
     * <li>code - the HTTP status code (e.g. 403, 500)</li>
     * <li>type - the error type (e.g. 'CHttpException', 'PHP Error')</li>
     * <li>message - the error message</li>
     * <li>file - the name of the PHP script file where the error occurs</li>
     * <li>line - the line number of the code where the error occurs</li>
     * <li>trace - the call stack of the error</li>
     * <li>source - the context source code where the error occurs</li>
     * </ul>
     * @return array the error details. Null if there is no error.
     * @since 1.0.6
     */
    public function getError() {
        $error = parent::getError();
        switch ($error['type']) {
            case 'CDbException':
                $error['message'] = null;
                break;
        }
        if (!$error && $this->_attachedError['code']) {
            $error = $this->_attachedError;
        }
        return $error;
    }

    /**
     * Attach custom error message
     * @param int $code
     * @param string $message 
     * @return void
     */
    public function attachError($code, $message) {
        $code = (int) $code;
        if ($code && trim($message)) {
            $this->_attachedError['code'] = $code;
            $this->_attachedError['message'] = $message;            
        }
    }

}

