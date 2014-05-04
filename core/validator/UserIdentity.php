<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */

class UserIdentity extends CUserIdentity {

    /**
     * User identity id
     * @var int
     */
    private $id;
    /**
     * Account is inactive error flag
     */
    const ERROR_ACCOUNT_IS_INACTIVE=3;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $this->errorCode = Yii::app()->user->authenticate($this->username, $this->password);
        $this->id = Yii::app()->user->getId();
        return !$this->errorCode;
    }
    
    /**
     * Get user id
     * @return int
     */
    public function getId() {
        return $this->id;
    }

}