<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MultimediaApplicationModule , multimedi application
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MultimediaApplicationModule extends ApplicationModule {

    /**
     * Use dopesheet api or not
     * @var boolean 
     */
    public $useDopeSheet = true;

    /**
     * Use infocus or not
     * @var boolean 
     */
    public $useInfocus = true;

    /**
     * Use kewords or not
     * @var boolean 
     */
    public $useKeywords = true;

    /**
     * Use post to social or not
     * @var boolean 
     */
    public $useSocials = true;

    /**
     * get youtube options;
     */
    public function getYoutubeOptions() {
        $options = $this->options['youtubeApi'];
        $youtubeOptions = array();
        if (isset($options['text'])) {
            $youtubeOptions = $options['text'];
        }
        return $youtubeOptions;
    }

    /**
     * Use youtube api or not
     */
    public function youtubeApiIsEnabled() {
        $options = $this->options['youtubeApi'];
        $enabled = false;
        if (isset($options['text'])) {
            $youtubeOptions = $this->options['youtubeApi']['text'];
            $enabled = (isset($youtubeOptions['clientId']) && isset($youtubeOptions['developerKey']) && isset($youtubeOptions['sessionID']));
        }
        return $enabled;
    }

}
