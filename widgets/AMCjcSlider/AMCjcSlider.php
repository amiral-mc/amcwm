<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AMCjcSlider extension class, displays slider widget the contain images or videos
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AMCjcSlider extends CWidget {

    /**
     * Dom Id of the container
     * @var string 
     */
    public $container = "topNewsSlider";
    /**
     * @var array $data - the data used inside the slider
     */
    public $data = array();
    /**
     * the media width in the container
     * @var int $mediaWidth 
     */
    public $mediaWidth = 423;
    /**
     * the media height in the container     
     * @var int $mediaHeight
     */
    public $mediaHeight = 250;

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        $baseUrl = null;
        $assetsFolder = "";
        if ($baseUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.AMCjcSlider.assets'));
            $baseUrl = $assetsFolder . "/AMCjcSlider";
        }

        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseUrl . '/jcSlider.css');

        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . '/js/jquery.jcarousel.min.js', CClientScript::POS_HEAD);
//        $cs->registerScriptFile($baseUrl . '/js/jquery.jcarousel.js', CClientScript::POS_HEAD);
        //$this->defaultUrl = $this->defaultUrl ? $this->defaultUrl : Yii::app()->request->hostInfo;
        parent::init();
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function run() {
        $output = "";

        if (count($this->data)) {

            $js = " var jcCounter = 1;
                    $('#{$this->container}').jcarousel({
                            auto: 5,
                            rtl: " . ((Yii::app()->getLocale()->getOrientation() == "rtl") ? "true" : "false") . ",
                            scroll: 1,
                            wrap: 'circular',
                            initCallback: {$this->container}_initCallback,
                            itemLoadCallback: {$this->container}_itemLoadCallbackFunction,
                            // This tells jCarousel NOT to autobuild prev/next buttons
                            buttonNextHTML: null,
                            buttonPrevHTML: null
                        });
                            
                        function {$this->container}_itemLoadCallbackFunction(carousel, state){
                            jQuery('.{$this->container}-control a').removeClass('isActive');
                            jQuery('.controls_' + jcCounter).addClass('isActive');
                            jcCounter++; 
                            if(jcCounter>10){jcCounter=1}
                        }
                        
                        function {$this->container}_initCallback(carousel) {
                            jQuery('.{$this->container}-control a').bind('click', function() {
                                carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
                                jQuery('.{$this->container}-control a').removeClass('isActive');
                                jQuery(this).addClass('isActive');
                                jcCounter = jQuery.jcarousel.intval(jQuery(this).text());
                                return false;
                            });

                            jQuery('.{$this->container}-scroll select').bind('change', function() {
                                carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
                                return false;
                            });

                            jQuery('#{$this->container}-next').bind('click', function() {
                                carousel.next();
                                return false;
                            });

                            jQuery('#{$this->container}-prev').bind('click', function() {
                                carousel.prev();
                                return false;
                            });
                            
                            jQuery('.controllers #startStop').bind('click', function() {
                                
                                if (this.className == 'jcSliderStop') {
                                    jQuery(this).removeClass('jcSliderStop');
                                    jQuery(this).addClass('jcSliderStart');
                                    carousel.stopAuto();
                                }else{
                                    jQuery(this).removeClass('jcSliderStart');
                                    jQuery(this).addClass('jcSliderStop');
                                    carousel.startAuto();
                                }
                                return false;
                            });
                        };

                    ";

            Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->container, $js, CClientScript::POS_READY);

            $output .= "<div style='border:1px solid #CCCCCC'>
                            <div style='background: #222222; border:2px solid #FFFFFF'>";

            $output .= '  <div class="jcSlider">';
            $output .= '  <ul id="' . $this->container . '">';
            //$output .= '  <ul>';
            $itemNum = 1;
            $controls = array();
            foreach ($this->data AS $v) {
                $output .= '<li style="width:638px; height:250px;">';
                $output .= '<div class="detail">
                                <h3 class="Lexia-Bold">' . AmcWm::t("amcFront", "Top News Story") . '</h3>
                                <h4>' . $v["title"] . '</h4>
                                <a href="' . $v["link"] . '" title="' . AmcWm::t("amcFront", "Read more") . '" class="more">' . AmcWm::t("amcFront", "Read more") . '</a>
                            </div><!-- /detail -->
                            ';

                if ($v["type"] == SiteData::IAMGE_TYPE) {
                    $output .= '<div class="item item_' . $itemNum . '" align="left">
                                    <img src="' . $v["image"] . '" alt="" width="' . $this->mediaWidth . '" height="' . $this->mediaHeight . '" />
                                </div><!-- /item -->
                                ';
                } else {
                    $output .= '<div class="item item_' . $itemNum . '" align="left">';
                    $output .= '    <div onclick="document.location.href=\'' . $v["link"] . '\'" class="jcSliderPlayBtn"></div>';
                    $output .= '    <img src="' . $v["image"] . '" alt="" width="' . $this->mediaWidth . '" height="' . $this->mediaHeight . '" />';
                    $output .= '</div><!-- /item -->';
                }
                $output .= '</li>';

                $controls[] = "<a href='#' class='controls_$itemNum'>$itemNum</a>" . PHP_EOL;
                $itemNum++;
            } // end foreach

            $controls = (Yii::app()->getLocale()->getOrientation() == "rtl") ? $controls : array_reverse($controls);
            $navControls = implode("", $controls);

            $output .= '</ul>';
            $output .= '    <div class="controllers">';
            $output .= '<a href="javascript:;" id="startStop" class="jcSliderStop">start</a>';
            $output .= '<span class="' . $this->container . '-control">' . $navControls . '</span>';
            $output .= '    </div>';
            $output .= '</div><!-- END AMCjcSlider -->';
            $output .= "</div></div>";

            echo $output;
        }// end count data
    }

}