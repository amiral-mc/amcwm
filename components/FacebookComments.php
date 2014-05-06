<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * FacebookComments class, Gets the facebook comments for a given page url
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class FacebookComments {

    /**
     * Page url to get comments related with it
     * @var string
     */
    private $_pageUrl = null;
    
    /**
     * Counstructor
     * @param string $pageUrl, Page url to get comments related with it 
     * @access public
     */
    public function __construct($pageUrl) {
        $this->_pageUrl = $pageUrl;
    }
    
    /**
     * Gets facebook comments counts for FacebookComments._pageUrl
     * @return integer 
     * @access public
     */
    public function getCommentsCount() {
        $url = $this->_pageUrl;
        $json = $this->getJsonData();
        $commentsCount = ((isset($json->$url->comments))? (int)$json->$url->comments : 0) ;
        return $commentsCount;
    }
    
    /**
     * Gets facebook shares counts for FacebookComments._pageUrl
     * @param integer $added
     * @return integer 
     * @access public
     */
    public function getSharesCount() {
        $url = $this->_pageUrl;
        $json = $this->getJsonData();
        return isset($json->$url->shares)?$json->$url->shares:0;
    }
    
    /**
     * Draw the facebook comments widget
     * @param string $widgetType , Facebook html widget type iframe or javascript ... etc
     * @param string $width
     * @param string $height
     * @param integet $numPosts
     * @return string 
     */
    public function drowFacebookComments($widgetType = "xfbml",  $width = "100%", $height = "40px", $numPosts = 50){        
        $widget = null;
        switch($widgetType){
//            case 'iframe':
//                $widget = '<iframe src="http://www.facebook.com/plugins/comments.php?href='.urlencode($this->_pageUrl).'&amp;permalink=1" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'; height:'.$height.';" allowTransparency="true" ></iframe>';
//            break;
            case 'xfbml':
                $widget .= '<div id="fb-root"></div>';
                $widget .= '<script src="http://connect.facebook.net/ar_AR/all.js#appId='.Yii::app()->params['facebook']['ar']['apiId'].'&amp;xfbml=1"></script>';
                $widget .= '<fb:comments href="'.$this->_pageUrl.'" num_posts="'.$numPosts.'" width="'.$width.'">';
            break;                                
        }
        return $widget;
    }
    
    
    /**
     * Get jsoin data from facebook graph for FacebookComments._pageUrl
     * @return string 
     * @access public
     */
    public function getJsonData(){
        $filecontent = self::_getDataFromUrl('https://graph.facebook.com/?ids=' . urlencode($this->_pageUrl));
        $json = json_decode($filecontent);
        return $json;
    }

    /**
     * Gets page output from a given $url using curl
     * @param string $url
     * @param array $postParams
     * @return string 
     * @access private
     */
    private static function _getDataFromUrl($url, $postParams = array()) {
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
