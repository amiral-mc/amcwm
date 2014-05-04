<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * GenerateAlphabet class.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class GenerateAlphabet {

    /**
     *
     * @var array of letters 
     */
    protected $letters = array();

    /**
     *
     * @var array of aliases letters 
     */
    protected $aliasesLetters = array();

    /**
     * Counstructor, the content type  
     * 
     */
    public function __construct() {
        $this->setAlphabet();
    }

    /**
     * get alphabet letters
     * 
     */
    public function getAlphabet() {
        return $this->letters;
    }

    /**
     * 
     * get aliases letters for the given letter
     * @return string
     */
    public function getAliasesLetters($letter) {
        if (isset($this->aliasesLetters[$letter])) {
            return $this->aliasesLetters[$letter];
        }
    }

    /**
     * set alphabet letters
     * any GenerateAlphabet must implement this class
     * 
     */
    abstract protected function setAlphabet();
}
