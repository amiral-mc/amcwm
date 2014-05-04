<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Base class for all newsletter send mail class
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
abstract class AmcMaillist {

    /**
     * email language
     * @var integer 
     */
    protected $language = null;

    /**
     * Run the mail list task if ready
     * @var boolean
     */
    protected $runMe = false;

    /**
     * @var integer Number of users to send message to them in each step.
     */
    protected $limit;

    /**
     *
     * @var string Email Body message 
     */
    protected $body;

    /**
     *
     * @var string template folder
     */
    protected $templateFolder;

    /**
     *
     * @var array extra options used in child classess 
     */
    protected $extraOptions = array();

    /**
     *
     * @var integer current unix timestamp 
     */
    protected $currentTime;

    /**
     * 
     * @var array message dataset 
     */
    protected $msgDataset;

    /**
     *
     * @var string email subject 
     */
    protected $subject = "";

    /**
     *
     * @var boolean if set to false when finish sending or when the system can not find data to send
     */
    protected $send = false;

    /**
     *
     * @var CConsoleCommand the active command instance 
     */
    protected $command = null;

    /**
     *
     * @var array maillist settings
     */
    protected static $settings;

    /**
     *
     * @var DateTime last run date
     */
    protected $lastRun = null;

    /**
     *
     * @var array data to replaced in body before send
     */
    protected $replace = array();

    /**
     * Constructor
     * @param CConsoleCommand $command
     * @param array $msgDataset
     * @param string $lang
     * @param integer $limit     
     * @param array $options
     */
    public function __construct($command, $msgDataset, $lang, $limit, $options = array()) {
        $settings = new Settings('maillist', 0);
        self::$settings = $settings->getSettings();
        unset($settings);
        $this->command = $command;
        $this->msgDataset = $msgDataset;
        $this->language = $lang;
        if ($this->msgDataset['content_lang']) {
            $this->language = $this->msgDataset['content_lang'];
        } else if (!$this->language) {
            $this->language = AmcWm::app()->getLanguage();
        }
        $this->limit = $limit;
        if (is_array($options)) {
            foreach ($options as $option) {
                $tmpOption = (explode(":", $option));
                if (isset($tmpOption[0]) && isset($tmpOption[1])) {
                    $this->extraOptions[trim($tmpOption[0])] = trim($tmpOption[1]);
                }
            }
        }
        $this->init();
        set_time_limit(1200);
        ignore_user_abort(true);
    }

    /**
     * Replace vars inside templates
     * @param string $template
     * @param array $templateVars
     * @return string
     */
    protected function replaceTemplateVars($template, $templateVars) {

        if (count($templateVars)) {
            $search = array();
            $replace = array();
            foreach ($templateVars as $var => $val) {
                $search[] = "{\$:{$var}}";
                if (is_array($val)) {
                    $template = $this->replaceTemplateLoops($template, array($var => $val));
                } else {

                    $replace[] = $val;
                }
            }
            return str_replace($search, $replace, $template);
        } else {
            return $template;
        }
    }

    /**
     * Replace text inside templates
     * @param string $template
     * @param array $templateVars
     * @return string
     */
    protected function replaceTemplateText($template, $templateVars) {

        if (count($templateVars)) {
            $search = array();
            $replace = array();
            foreach ($templateVars as $var => $val) {
                $search[] = "{t:{$var}}";
                $replace[] = $val;
            }
            return str_replace($search, $replace, $template);
        } else {
            return $template;
        }
    }

    /**
     * Replace loops inside templates
     * @param string $template
     * @param array $templateLoops
     * @return string
     */
    protected function replaceTemplateLoops($template, $templateLoops) {

        if (count($templateLoops)) {
            $search = array();
            $replace = array();
            $i = 0;
            foreach ($templateLoops as $var => $loops) {
                $matches = array();
                preg_match_all("/{loop:{$var}}(.*?){endloop:{$var}}/si", $template, $matches);
                if (isset($matches[0][0]) && isset($matches[1][0])) {
                    $search[$i] = $matches[0][0];
                    $replace[$i] = '';
                    foreach ($loops as $loopKey => $loopData) {
                        $data = $this->replaceTemplateVars($matches[1][0], $loopData);
                        $replace[$i] .= $data;
                    }
                    $i++;
                }
            }
            return str_replace($search, $replace, $template);
        } else {
            return $template;
        }
    }

    /**
     * Initialize the class
     */
    protected function init() {
        $currentDate = new Datetime(date("Y-m-d H:i:s") . " GMT");
        $this->setSubject($this->msgDataset['subject']);
        $this->currentTime = $currentDate->format("U");
        $this->lastRun = new DateTime("{$this->msgDataset['cron_start']} GMT");
        $startDay = (int) $this->lastRun->format("d");
        $startMonth = (int) $this->lastRun->format("m");
        $startYear = (int) $this->lastRun->format("Y");
        $endDay = (int) $currentDate->format("d");
        $endMonth = (int) $currentDate->format("m");
        $endYear = (int) $currentDate->format("Y");
        $this->runMe = false;
        switch ($this->msgDataset['cron_condition']) {
            case "hour":
                $this->_setDayHourWeekMessageDates(60 * 60);
                $this->runMe = ($currentDate->format("U") - $this->lastRun->format('U')) <= 60 * 60;
                break;
            case "day":
                $this->_setDayHourWeekMessageDates(60 * 60 * 24);
                $this->runMe = ($currentDate->format("U") - $this->lastRun->format('U')) <= 60 * 60 * 24;
                break;
            case 'week':
                $this->_setDayHourWeekMessageDates(60 * 60 * 24 * 7);
                $this->runMe = ($currentDate->format("U") - $this->lastRun->format('U')) <= 60 * 60 * 24;
                break;
            case 'month':
                $monthsDiff = ($endYear - $startYear) * 12 + $endMonth - $startMonth;
                if (($monthsDiff % $this->msgDataset['cron_step'] == 0)) {
                    if (!checkdate($endMonth, $endDay, $endYear)) {
                        $endDay = $currentDate->format("t");
                    }
                    $date = "{$endYear}-{$endMonth}-{$endDay} {$this->lastRun->format('H')}:{$this->lastRun->format('i')}:{$this->lastRun->format('s')} GMT";
                    $this->lastRun = new DateTime("{$date}");
                    $this->runMe = ($currentDate->format("U") - $this->lastRun->format('U')) <= 60 * 60 * 24;
                }
                break;
            case 'year':
                $yearsDiff = ($endYear - $startYear);
                if (($yearsDiff % $this->msgDataset['cron_step'] == 0) && $startMonth == $endMonth) {
                    if (!checkdate($endMonth, $endDay, $endYear)) {
                        $endDay = $currentDate->format("t");
                    }
                    $date = "{$endYear}-{$endMonth}-{$endDay} {$this->lastRun->format('H')}:{$this->lastRun->format('i')}:{$this->lastRun->format('s')} GMT";
                    $this->lastRun = new DateTime("{$date}");
                    $this->runMe = ($currentDate->format("U") - $this->lastRun->format('U')) <= 60 * 60 * 24;
                }
                break;
            default :
                if (!$this->msgDataset['cron_condition']) {
                    $this->runMe = ($currentDate->format("U") - $this->lastRun->format('U')) <= 60 * 60 * 24;
                }
        }
    }

    /**
     * Sets last run and next run dates, if messages period equal to hour, day , month and year
     * set email subject
     * @param integer $subject
     */
    private function _setDayHourWeekMessageDates($seconds) {
        $diffTime = ($this->currentTime - $this->lastRun->format("U")) / $seconds;
        if ($diffTime) {
//            $date = clone $this->lastRun;
            $this->lastRun->modify("+" . ((floor($diffTime / $this->msgDataset['cron_step']) * $this->msgDataset['cron_step'] + 1) - 1) . " {$this->msgDataset['cron_condition']}");
//            $date->modify("+" . ((ceil($diffTime / $this->msgDataset['cron_step']) * $this->msgDataset['cron_step'] + 1) - 1) . " {$this->msgDataset['cron_condition']}");
//            if ($this->msgDataset['cron_condition'] == 'hour') {
//                echo "{$this->msgDataset['message_id']}->{$this->msgDataset['cron_condition']}:{$this->msgDataset['cron_step']}\n";
//                echo "------------------------------------------------------\n";
//                echo "{$diffTime} {$this->lastRun->format('Y-m-d H:i:s')} {$date->format("Y-m-d H:i:s")}\n";
//                echo "======================================================\n";
//            }
        }
    }

    /**
     * set email subject
     * @param string $subject
     */
    protected function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * Run the instance and send emails
     */
    public function run() {
        if ($this->cronIsReady()) {
            $this->send();
            $this->updateCronLastRun();
        }
    }

    /**
     * generate list and send email to them
     */
    protected function send() {
        $mailList = $this->getMaillist();
        if (count($mailList)) {
            $this->prepareMessage();
        }
        if ($this->send) {
            foreach ($mailList AS $user) {
                print_r($user);
                if ($user['message_id']) {
                    $query = "update maillist_message_queue set sent = {$this->lastRun->format('U')} where maillist_id = {$user['id']} and message_id = {$this->msgDataset['message_id']};";
                } else {
                    $query = "insert into maillist_message_queue (sent, maillist_id, message_id) values ({$this->lastRun->format('U')}, {$user['id']}, {$this->msgDataset['message_id']});";
                }
                //echo "\n$query\n";
//                //die();
                $sent = $this->sendEmail($user);
                if ($sent) {
                    Yii::app()->db->createCommand($query)->execute();
                }
            }
        }
    }

    /**
     * prepare message before create it
     */
    protected function prepareMessage() {
        if ($this->msgDataset['template']) {
            $this->templateFolder = self::$settings['media']['paths']['templates']['path'] . "/" . "{$this->msgDataset['template']}";
        }
        $this->setMessage();
    }

    /**
     * Set the message data
     */
    abstract protected function setMessage();

    /**
     * 
     * Send email to single user
     * @param array $user
     * @return boolean
     */
    protected function sendEmail($user) {
        $body = str_replace(array(
            "__log__", 
            "__user__",), 
            array('<img src="' . $this->createUrl("maillist/default/log", array('e' => $user['id'], 'lang' => $this->language, 'm' => $this->msgDataset['message_id'])) . '" />'
                , $user['id'],), $this->body);
//
        //file_put_contents("/var/www/t/tu{$this->msgDataset['message_id']}-{$user['id']}.html", $body);
        Yii::app()->mail->sender->Subject = $this->subject;
        Yii::app()->mail->sender->AddAddress($user['email']);
        Yii::app()->mail->sender->SetFrom(Yii::app()->params['adminEmail']);
        Yii::app()->mail->sender->IsHTML();
        Yii::app()->mail->sender->Body = $body;
        $ok = Yii::app()->mail->sender->Send();
        Yii::app()->mail->sender->ClearAddresses();
        $ok = true;
        if ($ok) {            
            echo "\nSent..... {$user['email']}";
        } else {
            echo "\nError..... ";
        }
        return $ok;
    }

    /**
     * Check if corn ready to run or not.
     * @return bool
     */
    protected function cronIsReady() {
        return $this->runMe;
    }

    /**
     * Get maximum of sent for the current msgDataset['message_id'] 
     * @return integer
     */
    protected function getMaxSent() {
        $query = sprintf("select max(sent)
            from maillist_message_queue q
            where q.message_id = %d"
                , $this->msgDataset['message_id']);
        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    /**
     * Create route and append the siteUrl in Yii params to url
     * @param string $route
     * @param array $params
     * @return string
     */
    protected function createUrl($route, $params = array()) {
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            $url = Yii::app()->params['siteUrl'];
        } else {
            $url = Yii::app()->params['siteUrl'] . '/index.php';
        }
        return Html::createLinkRoute($url, $route, $params);
    }

    /**
     * Get the maillist subscribed in the msgDataset channel, if no channel found in dataset then get all mailllist users
     * @return array
     */
    protected function getMaillist() {
        if ($this->runMe) {
            echo "{$this->msgDataset['message_id']}->{$this->msgDataset['cron_condition']}:{$this->msgDataset['cron_step']}\n";
            echo "------------------------------------------------------\n";
            echo "{$this->lastRun->format('Y-m-d H:i:s')}\n";
            echo "======================================================\n";
            $query = sprintf("select 
            t.id, 
            u.email,
            u.name ,
            q.message_id
            from maillist t
            inner join maillist_users u on t.id=u.user_id
            left join maillist_message_queue q on t.id = q.maillist_id and q.message_id = %d
            where (( sent<%d ) or q.message_id is null) and t.status = %d limit %d"
                    , $this->msgDataset['message_id']
                    , $this->lastRun->format('U')
                    , ActiveRecord::PUBLISHED
                    , $this->limit);
//        echo "\n$query\n";
//        die();
////        return array();        
            return Yii::app()->db->createCommand($query)->queryAll();
        } else {
            return array();
        }
    }

    /**
     * Update the cron job datetime.
     * Set cron_time in cron_config table equal to the current datetime.
     * @access public
     * @return void
     */
    protected function updateCronLastRun() {
        $query = sprintf('update maillist_message set cron_time = %d where id =%d', $this->currentTime, $this->msgDataset['message_id']);
        Yii::app()->db->createCommand($query)->execute();
    }

}
