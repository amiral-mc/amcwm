<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
abstract class AmcSocial {
    protected $socialInformations;
    protected $dontPost = false;
    public function __construct($dontPost = false, $socialInformation = array()) {
        $this->dontPost = $dontPost;
        $this->socialInformations = $socialInformation;
    }
    abstract public function connect();
    abstract public function postData($data);
}

?>
