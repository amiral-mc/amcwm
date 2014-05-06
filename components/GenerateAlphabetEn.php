<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * GenerateAlphabetEn class.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GenerateAlphabetEn extends GenerateAlphabet {  

    /**
     * set alphabet letters
     * any GenerateAlphabet must implement this class
     * 
     */
    protected function setAlphabet() {
        $letters =  range('a', 'z');
        foreach ($letters as $letter){
            $this->letters[$letter] = $letter;    
        }
    }

}
