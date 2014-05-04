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

class MainSlider extends CWidget {

    public $duration = 3000;
    public $images = array();
    public $width = null;
    public $height = 340;

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        parent::init();
    }

    /**
     * Renders the goodle ad part.
     */
    public function run() {

        Yii::app()->clientScript->registerCss(__CLASS__ . $this->getId(), '
            #slideshow {
                position:relative;
                height:' . $this->height . 'px;
            }

            .slideshow .imageSlide {
                text-align:center
            }
            
            .slideshow DIV {
                position:absolute;
                top:0;
                left:0;
                z-index:8;
                width:100%;
            }

            .slideshow DIV.active {
                z-index:10;
            }
            .slideshow DIV.last-active {
                z-index:9;
            }
        ');
        
        Yii::app()->clientScript->registerScript(__CLASS__ . $this->getId(), '
            function slideSwitch1(slideShowId) {
                var $active = $(\'#\'+slideShowId+\' DIV.active\');
                if ( $active.length == 0 ) $active = $(\'#\'+slideShowId+\' DIV:last\');
                var $next =  $active.next().length ? $active.next()
                    : $(\'#\'+slideShowId+\' DIV:first\');
                $active.addClass(\'last-active\');
                $next.css({opacity: 0.0})
                    .addClass(\'active\')
                    .animate({opacity: 1.0}, 1000, function() {
                        $active.removeClass(\'active last-active\');
                    });
            }

            setInterval( "slideSwitch1(\'slideshow\')", ' . $this->duration . ' );
        ', CClientScript::POS_HEAD);
        
        echo '<div class="main_slider_wrapper">
                    <div id="slideshow" class="main_slider slideshow">';
        if (count($this->images)) {
            foreach ($this->images as $img) {
                echo "<div class='imageSlide'>";
                echo "  <img src='{$img}' alt=''/>";
                echo "</div>";
            }
        }
        echo '      </div>
                </div>';
        
    }

}
