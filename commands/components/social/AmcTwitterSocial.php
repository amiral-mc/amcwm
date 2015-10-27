<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of AmcTwitterSocial
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
class AmcTwitterSocial extends AmcSocial
{

    private $tweet;

    public function connect() {
        $consumerKey = $this->socialInformations['consumerKey'];
        $consumerSecret = $this->socialInformations['consumerSecret'];
        $oAuthToken = $this->socialInformations['oAuthToken'];
        $oAuthSecret = $this->socialInformations['oAuthSecret'];
        $this->tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);
        if (isset(Yii::app()->params['proxy']) && count(Yii::app()->params['proxy'])) {
            $this->tweet->haveProxy = Yii::app()->params['proxy'];    
        }        
    }

    public function postData($data) {
        $postMe = false;
        switch ($data['type']) {
            case 'text':
                $postMe = true;
                $myPostDataHeader = $data['data']['header'];
                $myPostDataLink = $data['data']['link'];
                break;
            case 'image':
                $postMe = true;
                $myPostDataHeader = $data['data']['header'];
                $myPostDataLink = $data['data']['link'];
                break;
            case 'video':
                $postMe = true;
                $myPostDataHeader = $data['data']['header'];
                $myPostDataLink = $data['data']['link'];
                break;
        }


        if (isset($this->socialInformations['shortenUrlApi'])) {
            $url = $this->shortenUrl($myPostDataLink);
            if ($url) {
                $myPostDataLink = $url;
                $myPostDataHeader = mb_substr($myPostDataHeader, 0, 139 - mb_strlen($myPostDataLink));
            }
        }

        if (!$this->dontPost && $postMe) {
            $output = $this->tweet->post('statuses/update', array('status' => $myPostDataHeader . "\r\n" . $myPostDataLink));
            //print_r($output);
        } else {
            echo "Twitter: " . PHP_EOL;
            echo "\t Type: " . $data['type'] . PHP_EOL;
            echo "\t Title:" . $myPostDataHeader . PHP_EOL;
            echo "\t Link: " . $myPostDataLink . PHP_EOL;
            echo "------------------------------------------------------" . PHP_EOL;
        }
    }

}
