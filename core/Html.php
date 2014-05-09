<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Html {

    /**
     * escape string
     * @param string $string
     * @return string escape string
     */
    public static function escapeString($string) {
//    $return = '';
//    for($i = 0; $i < strlen($string); ++$i) {
//        $char = $string[$i];
//        $ord = ord($char);
//        if($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
//            $return .= $char;
//        else
//            $return .= '\\x' . dechex($ord);
//    }
//    return $return;
        if (!empty($string)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $string);
        }
        return $string;
    }

    /**
     *
     * @param type $text
     * @param type $url
     * @param type $htmlOptions
     * @return type 
     */
    public static function link($text, $url = '#', $htmlOptions = array()) {
        $linkUrl = null;
        $urlTitle = null;
        $bookmark = null;
        if (is_array($url)) {
            if (!isset($url['lang'])) {
                $url['lang'] = Controller::getCurrentLanguage();
            }
        }
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            if (is_array($url) && isset($url[0])) {
                if (isset($url["#"])) {
                    $bookmark = "#{$url["#"]}";
                    unset($url["#"]);
                }
                if (isset($url['title'])) {
                    $urlTitle = "/" . urlencode($url['title']);
                    unset($url['title']);
                }
                $linkUrl = $url[0];

                foreach ($url as $paramKey => $paramVal) {
                    if (is_array($paramVal)) {
                        foreach ($paramVal as $paramSubKey => $paramSubVal) {
                            if ($paramSubKey !== 0) {
                                $linkUrl .= "/$paramKey" . urlencode("[{$paramSubKey}]") . "/" . urlencode($paramSubVal);
                            }
                        }
                    } else {
                        $paramVal = urlencode($paramVal);
                        if ($paramKey !== 0) {
                            $linkUrl .= "/{$paramKey}/$paramVal";
                        }
                    }
                }
                $url = array($linkUrl . $urlTitle . $bookmark);
            } else if (!is_array($url)) {
                $linkUrl = $url . '/lang=' . Controller::getCurrentLanguage();
            }
        } else if (!is_array($url) && strpos($url, '?') === false) {
            $url .= '?';
            $linkUrl = $url . '&lang=' . Controller::getCurrentLanguage();
        }
        return CHtml::link($text, $url, $htmlOptions);
    }

    public static function createUrl($route, $params = array()) {
        $url = null;
        $urlTitle = null;
        $bookmark = null;
        if (!isset($params['lang'])) {
            $params['lang'] = Controller::getCurrentLanguage();
        }
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            if (isset($params["#"])) {
                $bookmark = "#{$params["#"]}";
                unset($params['#']);
            }
            if (isset($params['title'])) {
                $urlTitle = "/" . urlencode($params['title']);
                unset($params['title']);
            }
            foreach ($params as $paramKey => $paramVal) {
                if (is_array($paramVal)) {
                    foreach ($paramVal as $paramSubKey => $paramSubVal) {
                        $route .= "/$paramKey" . urlencode("[{$paramSubKey}]") . "/" . urlencode($paramSubVal);
                    }
                } else {
                    $paramVal = urlencode($paramVal);
                    $route .= "/$paramKey/$paramVal";
                }
            }
            $params = array();
        }
        $url = Yii::app()->createUrl($route, $params) . $urlTitle . $bookmark;
        return $url;
    }

    public static function createLinkRoute($link, $route, $params = array()) {
        $urlTitle = null;
        $bookmark = null;
        if (!isset($params['lang'])) {
            $params['lang'] = Controller::getCurrentLanguage();
        }
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            if (isset($params["#"])) {
                $bookmark = "#{$params["#"]}";
                unset($params['#']);
            }
            if (isset($params['title'])) {
                $urlTitle = "/" . urlencode($params['title']);
                unset($params['title']);
            }
            foreach ($params as $paramKey => $paramVal) {
                $paramVal = urlencode($paramVal);
                $route .= "/$paramKey/$paramVal";
            }
            $link = "$link/$route{$urlTitle}{$bookmark}";
        } else {
            foreach ($params as $paramKey => $paramVal) {
                $route .= "&$paramKey=$paramVal";
            }
            $link = "$link?r=$route";
        }

        return $link;
    }

    /**
     * Function to truncate the text with its HTML
     * @param type $text
     * @param type $length
     * @param type $suffix
     * @param type $isHTML
     * @return string 
     */
    public static function truncateHTML($text = '', $length = 100, $suffix = 'read more&hellip;', $isHTML = true) {
        $i = 0;
        $tags = array();
        $text = strip_tags($text, "<p><img><div><span><b>");
        if ($isHTML) {
            preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach ($m as $o) {
                if ($o[0][1] - $i >= $length)
                    break;
                $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
                if ($t[0] != '/')
                    $tags[] = $t;
                elseif (end($tags) == substr($t, 1))
                    array_pop($tags);
                $i += $o[1][1] - $o[0][1];
            }
        }

        $output = substr($text, 0, $length = min(strlen($text), $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');

        // Get everything until last space
        $one = substr($output, 0, strrpos($output, " "));
        // Get the rest
        $two = substr($output, strrpos($output, " "), (strlen($output) - strrpos($output, " ")));
        // Extract all tags from the last bit
        preg_match_all('/<(.*?)>/s', $two, $tags);
        // Add suffix if needed
        if (strlen($text) > $length) {
            $one .= '&nbsp;' . $suffix;
        } else {
            $one .= '&nbsp;read';
        }
        // Re-attach tags
        $output = $one . implode($tags[0]);

        return $output;
    }

    /**
     * multi-byte safe sub string.
     * 1- Returns the portion of string specified by the start and length parameters.
     * 2- if a multi-byte safe mb_substr() is not defined
     * then use mysql substring function instead of php m_substr function.
     * @param string $string
     * @param int $from
     * @param int $length
     * @param boolean $striptags
     * @return string
     */
    public static function utfSubstring($string, $from, $length, $striptags = true) {

        if ($striptags) {
            $string = strip_tags($string);
        }

        if (function_exists("mb_substr")) {
            $rString = mb_substr($string, $from, $length, "UTF-8");
        } else {
            $from = $from + 1;
            $rString = Yii::app()->db->createCommand(sprintf("select substring(%s,%d,%d) str;", Yii::app()->db->quoteValue($string), $from, $length))->queryScalar();
        }
        return $rString;
    }

    /**
     * multi-byte safe string length.
     * 1- Return string length.
     * 2- if a multi-byte safe mb_strlen() is not defined
     * then use mysql length function instead of php m_strlen function.
     * @param string $string
     * @return int
     */
    public static function utfStringLength($string) {
        if (function_exists("mb_strlen")) {
            $length = mb_strlen($string, "UTF-8");
        } else {
            $length = Yii::app()->db->createCommand(sprintf("select length(%s) l;", Yii::app()->db->quoteValue($string)))->queryScalar();
        }
        return $length;
    }

    /**
     * Get video id from video url
     * @access public
     * @return string
     */
    public static function getVideoCode($video) {
        $video = trim($video);
        $urlPars = array();
        $urlArray = parse_url($video);
        $videoId = NULL;
        if (isset($urlArray["query"])) {
            parse_str($urlArray["query"], $urlPars);
            $videoId = $urlPars["v"];
        } elseif (isset($urlArray["path"])) {
            $v = explode("/v/", $urlArray["path"]);
            if (isset($v[1])) {
                $videoId = $v[1];
            }
        }
        return $videoId;
    }

    /**
     * check if the user agen is facebook or not
     * @return boolean
     */
    static function isFacebook() {
        if (!(stristr(AmcWm::app()->request->getUserAgent(), 'facebook') === FALSE)) {
            return true;
        } else {
            return false;
        }
    }

}
