<?php

Yii::import("amcwm.vendors.PHPMailer.PHPMailer");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * WebApplication extends CWebApplication by providing AamcWm functionalities
 * @package AmcWm.Mailer
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Mailer extends CApplicationComponent {

    /**
     * Method to send mail: ("mail", "sendmail", or "smtp").
     * @var string
     */
    public $mailer = 'mail';
    
    /**
     * PHPMailer public attributes
     * @var array()
     */
    public $mailerAttributes = array();
    

    /**
     * @var PHPMailer Holds the PHPMailer
     */
    protected $sender;

    /**
     * Sets the path of the sendmail program.
     * @var string
     */
    public $sendmail = '/usr/sbin/sendmail';

    /**
     * Sets the CharSet of the message.
     * @var string
     */
    public $charSet = 'iso-8859-1';

    /**
     * Gets the PHPMailer
     * @return PHPMailer
     */
    public function getSender() {
        if ($this->sender === null) {
            $this->sender = new PHPMailer();
            foreach ($this->mailerAttributes as $attribute=>$value){
                $this->sender->$attribute = $value;
            }
            $this->sender->Mailer = $this->mailer;
            $this->sender->CharSet = $this->charSet;
            $this->sender->Sendmail = $this->sendmail;
            
        }
        return $this->sender;
    }

    /**
     * Renders a view file and send send it in email body.
     * This method includes the view file as a PHP script
     * and captures the display result if required.
     * @param string $viewFile_ view file
     * @param array $data data to be extracted and made available to the view file
     * @return boolean
     */
    public function sendView($viewFile, $data) {
        $controller = new CController('Mailer');
        $viewPath = Yii::getPathOfAlias($viewFile) . '.php';
        $this->getSender()->Body = $controller->renderInternal($viewPath, $data, true);
        //die($this->getSender()->Body);
        $sent = $this->getSender()->Send();
        $this->sender = null;
        return $sent;
    }

    /**
     * send message
     * @param string $message
     * @return boolean
     */
    public function send($message) {
        $this->getSender()->Body = $message;
        $sent = $this->getSender()->Send();
        $this->sender = null;
        return $sent;
    }

}
