<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Image class.
 * Starts the view class which initializes templates.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Image {

    /**
     * index key for image width in array returned from getimagesize
     */
    const IMAGE_WIDTH = 0;

    /**
     * index key for image height in array returned from getimagesize
     */
    const IMAGE_HEIGHT = 1;

    /**
     * index key for image type in array returned from getimagesize
     */
    const IMAGE_TYPE = 2;

    /**
     * Resize according to image width
     */
    const RESIZE_BASED_ON_WIDTH = 1;

    /**
     * * Resize according to image height
     */
    const RESIZE_BASED_ON_HEIGHT = 2;

    /**
     * Image information array
     * @var array 
     */
    private $info = array();

    /**
     * Image file path
     * @var string
     */
    private $imageFile = null;

    /**
     * default background for images
     * @var array
     */
    private $background = array(255, 255, 255,);

    /**
     * Constructor
     * @param string $imageFile
     */
    public function __construct($imageFile) {
        $this->imageFile = str_replace("/", DIRECTORY_SEPARATOR, $imageFile);
        $this->info = @getimagesize($imageFile);
    }

    /**
     * Get uploaded image information
     * @access public
     * @static
     * @return array
     */
    public static function getInfo() {
        return $this->info;
    }

    /**
     * resize image and save it or display it to screen
     * @param int $width
     * @param int $height
     * @param int $resizeOption
     * @param string $saveTo
     * @access public
     * @return bool
     */
    public function resize($width, $height, $resizeOption = self::RESIZE_BASED_ON_WIDTH, $saveTo = null, $coords = array()) {
        $saveTo = str_replace("/", DIRECTORY_SEPARATOR, $saveTo);
        $quality = null;
        $xSource = 0;
        $ySource = 0;        
        $sourceWidth = $this->info[self::IMAGE_WIDTH];
        $sourceHeight = $this->info[self::IMAGE_HEIGHT];
        if ($coords) {
            $xSource = $coords['x'];
            $ySource = $coords['y'];
            $sourceWidth = $coords['x2'] - $coords['x'];
            $sourceHeight = $coords['y2'] - $coords['y'];
        }
        
        switch ($this->info[self::IMAGE_TYPE]) {
            case IMAGETYPE_GIF:
                $im = imagecreatefromgif($this->imageFile);
                $header = "Content-type: image/gif";
                $createFrom = "imagegif";
                break;
            case IMAGETYPE_JPEG:
                $im = imagecreatefromjpeg($this->imageFile);
                $header = "Content-type: image/jpeg";
                $createFrom = "imagejpeg";
                $quality = 90;
                break;
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($this->imageFile);
                $header = "Content-type: image/png";
                $createFrom = "imagepng";
                break;
        }
        switch ($resizeOption) {
            case self::RESIZE_BASED_ON_WIDTH :
                $iWidth = ($sourceWidth < $width) ? $sourceWidth : $width;
                $iHeight = ceil($sourceHeight / ($sourceWidth / $iWidth));
                break;
            case self::RESIZE_BASED_ON_HEIGHT:
                $iHeight = ($sourceHeight < $height) ? $sourceHeight : $height;
                $iWidth = ceil($sourceWidth / ($sourceHeight / $iHeight));
                break;
        }
        $in = imageCreateTrueColor($iWidth, $iHeight);

        imagecopyresampled($in, $im, 0, 0, $xSource, $ySource, $iWidth, $iHeight, $sourceWidth, $sourceHeight);
        imagedestroy($im);
        if (!$saveTo) {
            header($header);
        }
        $createFrom($in, $saveTo, $quality);
        if ($saveTo) {
            //chmod($saveTo, 0777);
        }
        imagedestroy($in);
    }

    /**
     * check if image width and height is not exceeded the supported image width and height
     * @param int $width
     * @param int $height
     * @access public
     * @return bool
     */
    public function checkImageSizeInArea($width, $height) {
        return ($this->info[self::IMAGE_WIDTH] <= $width && $this->info[self::IMAGE_HEIGHT] <= $height);
    }

    /**
     * check exact image width and height
     * @param int $width
     * @param int $height
     * @param int $allowedRatio
     * @access public
     * @return bool
     */
    public function checkExactImageSize($width, $height, $allowedRatio = 1) {
        $ratio = $this->info[self::IMAGE_WIDTH] / $width;
        $nWidth = $width * $ratio;
        $nHeight = $height * $ratio;
        return ($nWidth == $this->info[self::IMAGE_WIDTH] && $nHeight == $this->info[self::IMAGE_HEIGHT] && $ratio <= $allowedRatio);
    }

    /**
     * resize image then crop it and save it or display it to screen     * 
     * @param int $width
     * @param int $height
     * @param string $saveTo
     * @access public
     * @return bool
     */
    public function resizeCrop($width, $height, $saveTo = null, $coords = array()) {
        $saveTo = str_replace("/", DIRECTORY_SEPARATOR, $saveTo);
        $quality = null;
        $xSource = 0;
        $ySource = 0;        
        $sourceWidth = $this->info[self::IMAGE_WIDTH];
        $sourceHeight = $this->info[self::IMAGE_HEIGHT];
        if ($coords) {
            $xSource = $coords['x'];
            $ySource = $coords['y'];
            $sourceWidth = $coords['x2'] - $coords['x'];
            $sourceHeight = $coords['y2'] - $coords['y'];
        }
        switch ($this->info[self::IMAGE_TYPE]) {
            case IMAGETYPE_GIF:
                $im = imagecreatefromgif($this->imageFile);
                $header = "Content-type: image/gif";
                $createFrom = "imagegif";
                break;
            case IMAGETYPE_JPEG:
                $im = imagecreatefromjpeg($this->imageFile);
                $header = "Content-type: image/jpeg";
                $createFrom = "imagejpeg";
                $quality = 90;
                break;
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($this->imageFile);
                $header = "Content-type: image/png";
                $createFrom = "imagepng";
                break;
        }
        $wRatio = ($sourceWidth / $width);
        $hRatio = ($sourceHeight / $height);
        $face = null;
        $ratio = 1;
        if (function_exists('face_detect')) {
            $faces = face_detect($this->imageFile, Yii::getPathOfAlias('application.data') . DIRECTORY_SEPARATOR . 'haarcascade_frontalface_alt.xml');
            if (count($faces)) {
                $face = $faces[0];
                //print_r($face);
            }
        }
        if ($height <= $sourceHeight / $wRatio) {
            // scale based on width                 
            $iWidth = $width;
            $iHeight = ceil($sourceHeight / $wRatio);
            $ySource = abs(ceil(($height - $iHeight)));
            if($coords){
                $ySource += $coords['y'];
            }
            $ratio = $wRatio;
        } else {
            // scale based on height                 
            $iHeight = $height;
            $iWidth = ceil($sourceWidth / $hRatio);
            $xSource = abs(ceil($width - $iWidth));
            if($coords){
                $xSource += $coords['x'];
            }
            $ratio = $hRatio;
        }

        if ($face) {
            $yAfteRatio = ceil($face['y'] / $ratio);
            $xAfteRatio = ceil($face['x'] / $ratio);
            if (($iHeight - $yAfteRatio >= $height)) {
                $ySource = $face['y'];
            } else {
                $ySource = $sourceHeight - ceil($height * $ratio);
            }
            if (($iWidth - $xAfteRatio >= $width)) {
                $xSource = $face['x'];
            } else {
                $xSource = $sourceWidth - ceil($width * $ratio);
            }
//                echo "\nH:" .( $yAfteRatio) ."|". ($iHeight - $yAfteRatio < $height) ."\n";
//                echo "\nW:" .($iWidth - $xAfteRatio < $width)."\n";
        } else if ($sourceHeight / $sourceWidth > 1.3) {
            $ySource = ($coords) ? $coords['y'] : 0;
        }
        //echo "from : $xSource , $ySource | Crob: $width, $height, Scale: $iWidth, $iHeight\n";        
//        imagecopyresampled($in, $im, 0, 0, $xSource, $ySource, $iWidth, $iHeight, $sourceWidth, $sourceHeight);
//        ImageDestroy($im);

        $in = imageCreateTrueColor($width, $height);
        $bg = imagecolorallocate($in, $this->background[0], $this->background[1], $this->background[2]);
        imagefilledrectangle($in, 0, 0, $width, $height, $bg);
        // The cropped coordinates from original
//        $crop = imageCreateTrueColor($cropWidth, $cropHeight);
//        imagecopyresampled($crop, $im, 0, 0, $xSource, $ySource, $cropWidth, $cropHeight, $sourceWidth, $sourceHeight);        
//        $im = $crop;
        imagecopyresampled($in, $im, 0, 0, $xSource, $ySource, $iWidth, $iHeight, $sourceWidth, $sourceHeight);
        ImageDestroy($im);
        if (!$saveTo) {
            header($header);
        }
        $createFrom($in, $saveTo, $quality);
        if ($saveTo) {
            //chmod($saveTo, 0777);
        }
        ImageDestroy($in);
    }

}

?>
