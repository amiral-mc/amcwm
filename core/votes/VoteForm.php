<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VoteForm extends CFormModel {

    public $option;
    public $question;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('option', 'required'),
        );
    }
    
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(

        );
    }
   

    /**
     * save vote to voters table
     * @return boolean whether save is successful
     */
    public function save() {
        return false;
    }

}
