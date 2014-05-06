<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * GenerateAlphabetAr class.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GenerateAlphabetAr extends GenerateAlphabet {

    /**
     * set alphabet letters
     * any GenerateAlphabet must implement this class
     * 
     */
    protected function setAlphabet() {
        $letters = array(
            "ا", "ب", "ت", "ث",
            "ج", "ح", "خ", "د",
            "ذ", "ر", "ز", "س",
            "ش", "ص", "ض", "ط",
            "ظ", "ع", "غ", "ف",
            "ق", "ك", "ل", "م",
            "ن", "ه", "و", "ي"
        );
        $this->aliasesLetters["ا"] = array("أ", "إ", "ا");
        foreach ($letters as $letter) {
            $this->letters[$letter] = $letter;
        }
    }

}
