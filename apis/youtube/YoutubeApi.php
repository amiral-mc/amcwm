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

error_reporting(E_ALL);
set_time_limit(0);

// Youtube::setProxy($host, $port);
// amc.amiral.com
// OAuth Consumer Secret:	 8SxCbgIn5uaXpTzVkM6JoHQ1

//@$_GET['token']
//'1/ODbJhj0AszC8nb9snU1aOgFFT-H68f8ittbEc86NHgQ'
//$y = new Youtube('AI39si7BNqjnRo_wcogqKw8rpJDPI9s7nffJ3tEj_t8cKMWm3ybbG0sR1srbFDik4Ctm_3UshCaPBTuZvwuAsEeO2bXT5Nx0Rg', 'amc.amiral.com', '1/ODbJhj0AszC8nb9snU1aOgFFT-H68f8ittbEc86NHgQ');
//$y->set
//$video = $y->directUpload('my video 22', 'my video description 22', Youtube::DEFAULT_CATEGORY, 'ana', '/var/www/webmanager/youtube/v22.wmv');
//$video = $y->browserUpload('my new video', 'my new video description', Youtube::DEFAULT_CATEGORY, 'ana', '/var/www/webmanager/youtube/v22.wmv');
//if($video){
//    echo $video->code;
//}
//var_dump($video);
//$y->deleteVideo('TjT39ErDkfE');
//echo $y->updateVideoData('test title', 'Test update', 'News', 'ana, ana news', 'jtCJUC6AKXM');

class YoutubeApi extends VendorApiCommponent {

    private $next = NULL;
    private $nextBrowserUrl = NULL;
    private $scope = 'http://gdata.youtube.com';
    private $token = null;
    private $httpClient = null;
    private $youtupe = null;
    private $developerKey = null;
    private $clientId = null;
    private $sessionToken = null;
    private $uploadPath = NULL;

    /**
     * Initializes the api.
     * This method is called by the manager before the api starts to execute.
     * You may override this method to perform the needed initialization for the api.
     * @access public
     */
    public function init() {
        $this->settings = new Settings("multimedia", false);
        $this->next = Html::createUrl("/vendorApi/index", array("lib" => $this->getId()));
        $this->nextBrowserUrl = AmcWm::app()->getRequest()->getHostInfo(). Html::createUrl("/vendorApi/index", array("lib" => $this->getId(), 'action' => 'upload'));
        $options = $this->settings->options['youtubeApi']['text'];
        $this->developerKey = $options['developerKey'];
        $this->sessionToken = $options['sessionID'];
        $this->clientId = $options['clientId'];
        $_SESSION['developerKey'] = $this->developerKey;
        $this->uploadPath = AmcWm::app()->getAssetManager()->getPublishedPath(dirname(__FILE__) . DIRECTORY_SEPARATOR . "upload", true);
        $path = AmcWm::getPathOfAlias("amcwm.apis.youtube");
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
        require_once("Zend/Gdata/AuthSub.php");
        require_once("Zend/Gdata/HttpClient.php");
        require_once("Zend/Gdata/YouTube.php");
        require_once("Zend/Gdata/App/MediaFileSource.php");
        require_once("Zend/Gdata/App/HttpException.php");
        require_once('Zend/Uri/Http.php');
        parent::init();
    }

    public function manage() {
        $this->startYoutube($this->sessionToken);
    }

    public function upload() {
        header('Content-type: text/xml');
        echo '<?xml version="1.0"?>';
        echo '<upload>';
        if (isset($_GET['status'])) {
            echo "<status>{$_GET['status']}</status>";
        }
        if (isset($_GET['id'])) {
            echo "<code>{$_GET['id']}</code>";
        }
        echo '</upload>';
        AmcWm::app()->end();
    }

    public function index() {
        if ($this->sessionToken) {
            $_SESSION['sessionToken'] = $this->sessionToken;
        }
        $this->startYoutube(null);
        echo " SessionToken Is:", $_SESSION['sessionToken'];
    }

    public function startYoutube($sessionToken) {
        if ($sessionToken) {
            $_SESSION['sessionToken'] = $sessionToken;
        }
        $this->setAuthSubHttpClient();
        $this->httpClient->setConfig(array('timeout' => 180));
        $this->youtupe = new Zend_Gdata_YouTube($this->httpClient, NULL, $this->clientId, $_SESSION['developerKey']);
        $this->youtupe->setMajorProtocolVersion(2);
    }

    public function getUploadPath() {
        return $this->uploadPath;
    }

    public function setNextURl($next) {
        $this->next = $next;
    }

    public function setNextBrowserURl($nextBrowserUrl) {
        $this->nextBrowserUrl = $nextBrowserUrl;
    }

    /**
     * Update an existing video's meta-data.
     *
     * @param string $newVideoTitle The new title for the video entry.
     * @param string $newVideoDescription The new description for the video entry.
     * @param string $newVideoCategory The new category for the video entry.
     * @param string $newVideoTags The new set of tags for the video entry (whitespace separated).
     * @param string $videoId The video id for the video to be edited.
     * @return void
     */
    function updateVideoData($newVideoTitle, $newVideoDescription, $newVideoCategory, $newVideoTags, $videoId) {
        $feed = $this->youtupe->getuserUploads('default');
        $videoEntryToUpdate = null;
        $error = 0;
        foreach ($feed as $entry) {
            if ($entry->getVideoId() == $videoId) {
                $videoEntryToUpdate = $entry;
                break;
            }
        }
        if (!$videoEntryToUpdate instanceof Zend_Gdata_YouTube_VideoEntry) {
            $error = 1;
        } else {
            try {
                $putUrl = $videoEntryToUpdate->getEditLink()->getHref();
            } catch (Zend_Gdata_App_Exception $e) {
                $error = $e->getCode();
            }
            $videoEntryToUpdate->setVideoTitle($newVideoTitle);
            $videoEntryToUpdate->setVideoDescription($newVideoDescription);
            $videoEntryToUpdate->setVideoCategory($newVideoCategory);
            if ($newVideoTags) {
                $videoEntryToUpdate->setVideoTags($newVideoTags);
            }
            try {
                $updatedEntry = $this->youtupe->updateEntry($videoEntryToUpdate, $putUrl);
            } catch (Zend_Gdata_App_HttpException $e) {
                $error = $e->getCode();
            } catch (Zend_Gdata_App_Exception $e) {
                $error = $e->getCode();
            }
        }
        return $error;
    }

    /**
     * Check the upload status of a video
     *
     * @param string $videoId The video to check.
     * @return Zend_Gdata_YouTube_Extension_State
     */
    public function getVideoState($videoId) {
        $feed = $this->youtupe->getuserUploads('default');
        $state = NULL;
        foreach ($feed as $videoEntry) {
            //echo $videoEntry->getVideoId() . "<br />";
            if ($videoEntry->getVideoId() == $videoId) {
                $state = $videoEntry->getVideoState();
                break;
            }
        }
        return $state;
    }

    /**
     * Deletes a Video.
     *
     * @param string $videoId Id of the video to be deleted.
     * @return int
     */
    public function deleteVideo($videoId) {
        $feed = $this->youtupe->getuserUploads('default');
        $videoEntryToDelete = NULL;
        foreach ($feed as $videoEntry) {
            $currentId = $videoEntry->getVideoId();
            if ($currentId === $videoId) {
                $videoEntryToDelete = $videoEntry;
                break;
            }
        }

        $error = NULL;
        if ($videoEntryToDelete) {
            try {
                $httpResponse = $this->youtupe->delete($videoEntryToDelete);
            } catch (Zend_Gdata_App_Exception $e) {
                $error = $e->getCode();
            }
        }
        return $error;
    }

    public function getZendProxyAdapter() {
        return array(
            'adapter' => 'Zend_Http_Client_Adapter_Proxy',
            'proxy_host' => $this->proxy['host'],
            'proxy_port' =>  $this->proxy['port'],
                //'sslusecontext'=>true
        );
    }

    private function setAuthSubHttpClient() {
        if (count($this->proxy)) {
            $client = new Zend_Gdata_HttpClient('https://www.google.com', $this->getZendProxyAdapter());
            $client->setConfig(array('timeout' => 180));
        } else {
            $client = NULL;
        }
        if (!isset($_SESSION['sessionToken']) && !isset($_GET['token'])) {
            $this->authSubRedirect();
        } else if (!isset($_SESSION['sessionToken']) && isset($_GET['token'])) {
            $_SESSION['sessionToken'] = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token'], $client);
        }
        $this->httpClient = Zend_Gdata_AuthSub::getHttpClient($_SESSION['sessionToken'], $client);
    }

    private function authSubRedirect() {
        header('Location:' . $this->getAuthSubRequestUrl());
        exit;
    }

    private function getAuthSubRequestUrl() {
        $secure = 0;
        $session = 1;
        return Zend_Gdata_AuthSub::getAuthSubTokenUri($this->next, $this->scope, $secure, $session);
    }

    public static function getCategories() {
        static $categories = array();
        if (!count($categories)) {
            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://gdata.youtube.com/schemas/2007/categories.cat');
                if ($this->proxy) {
                    curl_setopt($ch, CURLOPT_PROXY, $this->proxy['host']);
                    curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy['port']);
                }
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
                $content = curl_exec($ch);
                $data = new SimpleXMLElement($content);

                $nodes = $data->children('atom', true);
                if ($nodes) {
                    foreach ($nodes as $item) {
                        $categories[(string) $item->attributes()->term] = (string) $item->attributes()->label;
                    }
                }
            } else {
                trigger_error('Curl is requried for youtube api, please install it first.', E_USER_ERROR);
            }
        }
        return $categories;
    }

    public function browserUpload($videoTitle, $videoDescription, $videoCategory, $videoTags, $file) {        
        $myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
        $myVideoEntry->setVideoTitle($videoTitle);
        $myVideoEntry->setVideoDescription($videoDescription);
        $myVideoEntry->setVideoCategory($videoCategory);
        if ($videoTags) {
            $myVideoEntry->setVideoTags($videoTags);
        }        
        $tokenHandlerUrl = 'http://gdata.youtube.com/action/GetUploadToken';
        $tokenArray = $this->youtupe->getFormUploadToken($myVideoEntry, $tokenHandlerUrl);
        $tokenValue = $tokenArray['token'];
        $postUrl = $tokenArray['url'] . '?nexturl=' . urlencode($this->nextBrowserUrl);
        $postParams['file'] = "@{$file}";
        $postParams['token'] = $tokenValue;
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $postUrl);
            if ($this->proxy) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy['host']);
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy['port']);
            }
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
            $content = curl_exec($ch);
            $error = false;
            $errorContent = NULL;
            try {
                $data = @(new SimpleXMLElement($content));
            } catch (Exception $e) {
                $error = true;
                $errorContent = $content;
            }

            $video = new stdClass();
            $video->htmlStatus = -1;
            $video->code = null;
            $video->errorContent = strip_tags($errorContent);
            $video->state = null;
            $video->error = $error;
            if (isset($data->status)) {
                $video->htmlStatus = (int) $data->status;
            }
            if (isset($data->code)) {
                $video->code = (string) $data->code;
                $video->state = $this->getVideoState($video->code);
            }
            curl_close($ch);
        } else {
            trigger_error('Curl is requried for youtube api, please install it first.', E_USER_ERROR);
        }
        return $video;
    }

    public function directUpload($videoTitle, $videoDescription, $videoCategory, $videoTags, $file, $contentType = 'application/octet-stream') {
        $myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
        $filesource = $this->youtupe->newMediaFileSource($file);
        $filesource->setContentType($contentType);
        $filesource->setSlug(basename($file));
        $myVideoEntry->setMediaSource($filesource);
        $myVideoEntry->setVideoTitle($videoTitle);
        $myVideoEntry->setVideoDescription($videoDescription);
        $myVideoEntry->setVideoCategory($videoCategory);
        if ($videoTags) {
            $myVideoEntry->setVideoTags($videoTags);
        }
        $uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';
//        $this->httpClient->setConfig(self::$proxy);
//        $this->httpClient->setAdapter(self::$proxy['adapter']);
//$this->httpClient->ana();
        $video = new stdClass();
        $video->code = null;
        $video->state = null;
        try {
            $newEntry = $this->youtupe->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
            if ($newEntry) {
//echo get_class($newEntry);
                $video->code = $newEntry->getVideoId();
                $video->state = $newEntry->getVideoState();
            }
        } catch (Zend_Gdata_App_HttpException $e) {
            echo $e->getMessage();
        } catch (Zend_Gdata_App_Exception $e) {
            echo $e->getMessage();
        }

        if (isset($data->status)) {
            $video->htmlStatus = (int) $data->status;
        }
        return $video;
    }

}
