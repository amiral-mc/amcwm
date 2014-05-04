<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ContactForm extends CFormModel {

    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    //the contact element, used in the contact form that refer to the support center.
    public $contact = null;

    /**
     * contacts list used to fill the contact dropdownlist with the data from the configuration file (contact)
     * @var array
     */
    private $_contacts = array();
    public $contactsList = array();

    public function init() {
        $this->fillContactList();
        parent::init();
    }

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('name, email, subject, body', 'required'),
            array('contact', 'length', 'max' => 10),
            // email has to be a valid email address
            array('email', 'email'),
            // verifyCode needs to be entered correctly
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'name' => Yii::t('contact', 'Name'),
            'email' => Yii::t('contact', 'Email'),
            'subject' => Yii::t('contact', 'Subject'),
            'body' => Yii::t('contact', 'Body'),
            'verifyCode' => Yii::t('contact', 'Verification Code'),
            'contact' => Yii::t('contact', 'Support center'),
        );
    }

    public function fillContactList() {
        if (isset(AmcWm::app()->params['contacts'])) {
            $this->_contacts = AmcWm::app()->params['contacts'];
            $langCategory = AmcWm::app()->params['langCategory'];
        } else {
            $contactsFile = AmcWm::getPathOfAlias("application.config") . DIRECTORY_SEPARATOR . "contacts.php";
            if (is_file($contactsFile)) {
                $contacts = require $contactsFile;
                $langCategory = $contacts['langCategory'];
                $this->_contacts = $contacts['contacts'];
            }
        }

        if ($this->_contacts) {
            foreach ($this->_contacts as $contact) {
                $this->contactsList[$contact['id']] = AmcWm::t($langCategory, $contact['name']);
            }
        }
    }

    public function getContactData($id) {
        $contactData = array();
        if (count($this->_contacts)) {
            foreach ($this->_contacts as $contact) {
                if ($contact['id'] == $id) {
                    $contactData = $contact;
                    break;
                }
            }
        }
        return $contactData;
    }

}
