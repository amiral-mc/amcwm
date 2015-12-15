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
class AmcFacebookSocial extends AmcSocial {

    public function connect() {
        
    }

    public function postData($data) {
        $header = $data['data']['header'];
        $url = $data['data']['link'];
        $image = $data['data']['image'];
        $this->postItem($header, $url, $image, $data['type']);
    }

    private function postItem($header, $itemUrl, $itemImage, $type) {
        if(isset($this->socialInformations['shortenUrlApi'])){
            $url = $this->shortenUrl($itemUrl);
            if($url){
                $itemUrl = $url;
            }
        }
        $url = "https://graph.facebook.com/{$this->socialInformations['pageId']}/feed";
        $postFields['access_token'] = $this->socialInformations['pageAccessToken'];        
        $facebookPostToTwitter =  isset(Yii::app()->params['facebookPostToTwitter']) ? Yii::app()->params['facebookPostToTwitter'] : false;
        if($facebookPostToTwitter){
            $header = mb_substr($header, 0, 139 - mb_strlen($itemUrl));
        }
        $postFields['name'] = $header;
        $postFields['link'] = $itemUrl;
        if($itemImage){
            $postFields['picture'] = $itemImage;
        }
        //$postFields['type'] = 'link';
        
        if (function_exists('curl_init') && !$this->dontPost) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if (isset(Yii::app()->params['proxy']) && count(Yii::app()->params['proxy'])) {
                curl_setopt($ch, CURLOPT_PROXY, Yii::app()->params['proxy']['host']);
                curl_setopt($ch, CURLOPT_PROXYPORT, Yii::app()->params['proxy']['port']);
            }
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
            $content = curl_exec($ch);
//            echo "Result: " ;
//            print_r($content);
//            echo PHP_EOL;
            curl_close($ch);
        } else {
            echo 'Facebook' . PHP_EOL;
            echo "\t Type: " . $type . PHP_EOL;
            echo "\t Title:" . $header . PHP_EOL;
            echo "\t Link: " . $itemUrl . PHP_EOL;
            echo "\t Image: " . $itemImage . PHP_EOL;
            echo "------------------------------------------------------" . PHP_EOL;
        }
    }

}
