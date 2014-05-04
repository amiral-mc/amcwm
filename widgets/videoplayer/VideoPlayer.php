<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class VideoPlayer extends CWidget {

    /**
     *
     * @var string Slider title 
     */
    public $title = null;
    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();
    /**
     * @var boolean whether the news items should be HTML-encoded. Defaults to true.
     */
    public $encodeTitle = false;
    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    public $baseScriptUrl;
    /**
     * news class name
     * @var string 
     */
    public $className = 'videoPlayer';
    /**
     *
     * @var string video 
     */
    public $video = NULL;
    /**
     * player width
     * @var int  
     */
    public $width = 400;
    /**
     * player height
     * @var int  
     */
    public $height = 300;
    /**
     *
     * @var string html contain video player. 
     */
    private $videoPlayer = NULL;
    /**
     * allowed video extensions.
     * @var array 
     */
    private static $videosExt = array(
        'flv' => 'flash',
        'wmv' => 'wmedia',
        'avi' => 'wmedia',
    );

    /**
     * Initializes the player widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = $this->className;
        if ($this->encodeTitle) {
            $this->title = CHtml::encode($this->title);
        }
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.videoplayer.assets'));
            $this->baseScriptUrl = $assetsFolder . "/videoplayer";
        }
        $videoUrl = parse_url($this->video);
        $hosted = true;
        $isUrl = array_key_exists('scheme', $videoUrl);
        if ($isUrl) {
            $host = str_replace('www.', '', $videoUrl['host']);
            if (array_key_exists('query', $videoUrl)) {
                $hosted = false;
                $query = $videoUrl['query'];
                $videoUrl['host'] = strtolower($videoUrl['host']);                
                $this->setVideoUrlPlayer($host, $query);
            } else {
                $v = explode("/v/", $videoUrl["path"]);
                if (isset($v[1])) {
                    $query = "v={$v[1]}";
                }
                $this->setVideoUrlPlayer($host, $query);
            }
        }
        if ($hosted) {
            $extPos = strrpos($this->video, '.');
            if ($extPos) {
                $ext = strtolower(substr($this->video, $extPos + 1));
                if (array_key_exists($ext, self::$videosExt)) {
                    $this->setVideoPlayer($ext, $this->video, $isUrl);
                }
            }
        }
    }

    /**
     * @return string html for drawing youtube video
     */
    protected function generateYoutube($code) {
        $url = trim("http://www.youtube.com/v/{$code}");
        //return $url;
        $playerContent = '<object width="' . $this->width . '" height="' . $this->height . '" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codeBase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7" type="application/x-oleobject">';
        $playerContent .= '<param name="allowFullScreen" value="true" />';
        $playerContent .= '<param name="allowscriptaccess" value="always" />';
        $playerContent .= '<param name="movie" value="' . $url . '" />';
        $playerContent .= '<param name="wmode" value="transparent" />';
        $playerContent .= '<embed wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" src="' . $url . '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $this->width . '" height="' . $this->height . '"></embed>';
        $playerContent .= '</object>';
        return $playerContent;
    }

    /**
     * @return string html for drawing flv video
     */
    protected function generateFlv($video) {
        //return $url;
        $url = "{$this->baseScriptUrl}/OSplayer.swf?movie={$video}&btncolor=0x333333&accentcolor=0x31b8e9&txtcolor=0xdddddd&volume=30&autoload=on&autoplay=off&vTitle={$this->title}&showTitle=yes";
        $playerContent = '<div style="background-color:#333333; width:' . $this->width . 'px; height:' . $this->height . 'px;">';
        $playerContent .= '<object width="' . $this->width . '" height="' . $this->height . '" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codeBase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7" type="application/x-oleobject">';
        $playerContent .= '<param name="allowFullScreen" value="true" />';
        $playerContent .= '<param name="allowscriptaccess" value="always" />';
        $playerContent .= '<param name="movie" value="' . $url . '" />';
        $playerContent .= '<param name="wmode" value="transparent" />';
        $playerContent .= '<embed wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" src="' . $url . '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $this->width . '" height="' . $this->height . '"></embed>';
        $playerContent .= '</object>';
        $playerContent .= '</div>';
        return $playerContent;
    }

    /**
     * @return string html for drawing flv video
     */
    protected function generateWMedia($video) {
        $url = "{$video}";
        $playerContent = '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="' . $this->width . '" height="' . $this->height . '" type="application/x-oleobject">';
        $playerContent .= '<param name="src" value="' . $url . '" />';
        $playerContent .= '<param name="wmode" value="transparent" />';
        $playerContent .= '<param name="autostart" value="0" />';
        $playerContent .= '<embed wmode="transparent" pluginspage="http://www.microsoft.com/windows/mediaplayer/download/default.asp" autostart="0" src="' . $url . '" type="application/x-mplayer2" width="' . $this->width . '" height="' . $this->height . '"></embed>';
        $playerContent .= '</object>';
        return $playerContent;
    }

    /**
     *  Set video player plugin for the hosted video
     * @return void
     */
    protected function setVideoPlayer($ext, $video, $isUrl) {
        switch (self::$videosExt[$ext]) {
            case 'flash':
                $this->videoPlayer = $this->generateFlv($video);
                break;
            case 'wmedia':
                if (!$isUrl) {
                    $video = Yii::app()->request->getHostInfo() . $video;
                }
                $this->videoPlayer = $this->generateWMedia($video);
                break;
        }
    }

    /**
     * search into the provided url for the player video code. then generate video url
     * @return void
     */
    protected function setVideoUrlPlayer($host, $query) {
        parse_str($query, $queryVars);
        switch ($host) {
            case 'youtube.com':
                if (array_key_exists('v', $queryVars)) {
                    $this->videoPlayer = $this->generateYoutube($queryVars['v']);
                }
                break;
        }
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $output = "";
        $assetsFolder = "";
        if ($this->videoPlayer) {
            $output .= CHtml::openTag('div', $this->htmlOptions) . "\n";
            $output .= $this->videoPlayer;
            $output .= CHtml::closeTag('div');
        }
        echo $output;
    }

}
