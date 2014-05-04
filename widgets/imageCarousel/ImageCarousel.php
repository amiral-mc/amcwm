<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of Gallery
 * @author Amiral Management Corporation
 * @version 1.0
 */

class ImageCarousel extends SideWidget {

    /**
     * items contains data for the slider 
     * @var array.
     */
    public $items = array();
    public $container = 'carousel_data';
    public $arrows = array(
        'next' => null,
        'prev' => null,
    );
    
    public $prevClass = "carousel-prev";
    public $nextClass = "carousel-next";
    private $_options = array(
        'ContainerWidth' => '250',
        'ItemWidth' => '85',
        'ContainerHeight' => '93',
        'VeiwElements' => '1',
        'ShowArrows' => true,
        'ShowControllers' => false,
        'ArrowsPosition' => 'sides', /* sides, top, left, none */
        'rtl' => 'true',
    );

    public function setOptions($options) {
        if (count($options)) {
            $this->_options = array_merge($this->_options, $options);
        }
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {
        $baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.imageCarousel.assets'));
        $clientScripts = Yii::app()->getClientScript();

        $clientScripts->registerCssFile($baseScriptUrl . '/jcSlider.css');
        $clientScripts->registerScriptFile($baseScriptUrl . '/js/jquery.jcarousel.min.js', CClientScript::POS_HEAD);

        if (!$this->arrows['prev'])
            $this->arrows['prev'] = $baseScriptUrl . '/images/arrow_l.png';

        if (!$this->arrows['next'])
            $this->arrows['next'] = $baseScriptUrl . '/images/arrow_r.png';

        $this->contentData = "";
        $itemsCount = count($this->items);
        if ($itemsCount) {

            $js = " var jcCounter = 1;
                    $('#{$this->container}').jcarousel({
                            auto: 5,
                            rtl: " . $this->_options['rtl'] . ",
//                            scroll: " . $this->_options['VeiwElements'] . ",
                            scroll: 1,
                            wrap: 'circular',
                            // This tells jCarousel NOT to autobuild prev/next buttons
                            buttonNextHTML: null,
                            buttonPrevHTML: null,
                            initCallback: {$this->container}InitCallback
                        });
                        
                        function {$this->container}InitCallback(carousel) {
                            jQuery('.jcarousel-control a').bind('click', function() {
                                carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
                                return false;
                            });

                            jQuery('.jcarousel-scroll select').bind('change', function() {
                                carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
                                return false;
                            });

                            jQuery('#{$this->container}_next-arrow').bind('click', function() {
                                carousel.next();
                                return false;
                            });

                            jQuery('#{$this->container}_prev-arrow').bind('click', function() {
                                carousel.prev();
                                return false;
                            });
                        };


                    ";

            $clientScripts->registerScript(__CLASS__ . '#' . $this->container, $js, CClientScript::POS_READY);

            $this->contentData .= '<div class="jcSlider" style="width:' . $this->_options['ContainerWidth'] . 'px; height: ' . $this->_options['ContainerHeight'] . 'px">';
            $this->contentData .= '    <div class="jcSliderInner" style="width:' . ((int) $this->_options['ContainerWidth'] - 45) . 'px; height: ' . $this->_options['ContainerHeight'] . 'px">';
            $this->contentData .= '        <ul id="' . $this->container . '" >' . PHP_EOL;
            $itemNum = 1;

            $controls = array();
            foreach ($this->items AS $item) {

                if ($this->_options['VeiwElements'] == 1) {
                    $this->contentData .= '<li style="width:' . ((int) $this->_options['ContainerWidth'] - 45) . 'px; height:' . $this->_options['ContainerHeight'] . 'px;">' . PHP_EOL;
                } else {
                    $itemsCount = ($itemsCount < $this->_options['VeiwElements']) ? $itemsCount : $this->_options['VeiwElements'];
                    $listWidth = ((int) ($this->_options['ContainerWidth'] - 45) / (int) $itemsCount);
//                    $this->contentData .= '<li style="width:' . ((int) $this->_options['ContainerWidth'] - ($this->_options['ItemWidth'] * $this->_options['VeiwElements'])) . 'px; height:' . $this->_options['ContainerHeight'] . 'px;">' . PHP_EOL;
                    $this->contentData .= '<li style="width:' . $listWidth . 'px; height:' . $this->_options['ContainerHeight'] . 'px;">' . PHP_EOL;
                }

                if (is_array($item)) {
                    $alt = (isset($item['title'])) ? CHtml::encode($item['title']) : "";
                    $altTitle = ($alt) ? $alt : "";

                    if (isset($item['link'])) {
                        $linkOptions = (isset($item['linkOptions'])) ? $item['linkOptions'] : array();
                        $image = Html::link(CHtml::image($item['image'], $alt, array('title' => $altTitle)), $item['link'], $linkOptions);
                    } else {
                        $image = CHtml::image($item['image'], $alt, array('title' => $altTitle));
                    }
                } else {
                    $image = $item;
                }

                $this->contentData .= '    <div class="item item_' . $itemNum . '" style="text-align: center;">' . $image . '</div>' . PHP_EOL;
                $this->contentData .= '</li>' . PHP_EOL;

                $controls[] = "<a href='#' class='controls_$itemNum'>$itemNum</a>" . PHP_EOL;
                $itemNum++;
            }
            $this->contentData .= '        </ul>';
            $controlsItems = (Yii::app()->getLocale()->getOrientation() == "rtl") ? $controls : array_reverse($controls);
            $navControls = implode("", $controlsItems);

            if ($this->_options['ShowArrows']) {
                if (isset($this->arrows['prev'])) {
                    $this->contentData .= '
                        <div class="'.$this->prevClass.'">
                            <img id="' . $this->container . '_prev-arrow" class="left-button-image" src="' . $this->arrows['prev'] . '" alt=""/>
                        </div>';
                }
                if (isset($this->arrows['next'])) {
                    $this->contentData .= '
                        <div class="'.$this->nextClass.'">
                            <img id="' . $this->container . '_next-arrow" class="right-button-image" src="' . $this->arrows['next'] . '" alt=""/>
                        </div>';
                }
            }

            if ($this->_options['ShowControllers']) {
                $this->contentData .= '    <div class="controllers">';
                $this->contentData .= '        <a href="javascript:;" id="startStop" class="jcSliderStop">start</a>';
                $this->contentData .= '        <span class="' . $this->container . '-control">' . $navControls . '</span>';
                $this->contentData .= '    </div>';
            }
            $this->contentData .= '    </div>';
            $this->contentData .= '</div>';
        }
    }

}
