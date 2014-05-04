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
abstract class AmcService implements IAmcService {

    protected $information = array();
    /**
     *
     * @var array
     */
    protected $service;

    public function __construct($service) {

        set_time_limit(1200);
        ignore_user_abort(true);
        $this->service = $service;
        $this->setInformation();
    }

    public function run() {
        if ($this->cronIsReady()) {
            $this->update();
            $this->updateCronLastRun();
        }
    }

    abstract protected function update();

    /**
     * Check if corn ready to run or not.
     * @param int  $id
     * @access public
     * @return bool
     */
    public function cronIsReady() {
        switch ($this->service['cron_condition']) {
            case "day":
                $currentTime = strtotime(date("Y-m-d"));
                $cronTime = strtotime(date("Y-m-d", $this->service['cron_time']));
                $cronStep = $this->service['cron_step'] * 24 * 60 * 60;
                break;
            case "min":
                $cronTime = $this->service['cron_time'];
                $cronStep = $this->service['cron_step'] * 60;
                $currentTime = time();
                break;
        }
        return ($currentTime >= $cronTime + $cronStep);
    }

    /**
     * Update the cron job datetime.
     * Set cron_time in cron_config table equal to the current datetime.
     * @access public
     * @return void
     */
    public function updateCronLastRun() {
        $currentTime = time();
        $query = sprintf('update services set cron_time = %d where service_id =%d', $currentTime, $this->service['service_id']);
        Yii::app()->db->createCommand($query)->execute();
    }

    public function getInformation() {
        return $this->information;
    }

    /**
     * Get xml from the given $url.
     * @param string $url
     * @param array $postParams
     * @access public
     * @static
     * @return string
     */
    public static function getXmlFromUrl($url, $postParams = array()) {
        $content = "";
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if (count(Yii::app()->params['proxy'])) {
                curl_setopt($ch, CURLOPT_PROXY, Yii::app()->params['proxy']['host']);
                curl_setopt($ch, CURLOPT_PROXYPORT, Yii::app()->params['proxy']['port']);
            }
            if (count($postParams)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
            }
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
            $content = curl_exec($ch);
            curl_close($ch);
        } else {
            $content = file_get_contents($url);
        }
        return $content;
    }

}

?>
