<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
abstract class AmcSocial
{

    protected $socialInformations;
    protected $dontPost = false;

    public function __construct($dontPost = false, $socialInformation = array()) {
        $this->dontPost = $dontPost;
        $this->socialInformations = $socialInformation;
    }

    abstract public function connect();

    abstract public function postData($data);

    /**
     * Shorten url using google api
     * @param string $url
     * @return string
     */
    protected function shortenUrl($url) {
        $postFields['longUrl'] = $url;
        $apiUrl = "https://www.googleapis.com/urlshortener/v1/url?key=" . $this->socialInformations['shortenUrlApi'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        if (count(Yii::app()->params['proxy'])) {
            curl_setopt($ch, CURLOPT_PROXY, Yii::app()->params['proxy']['host']);
            curl_setopt($ch, CURLOPT_PROXYPORT, Yii::app()->params['proxy']['port']);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $content = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($content);
        //print_r($json);
        if (isset($json->id)) {
            return $json->id;
        } else {
            return null;
        }
    }

}

?>
