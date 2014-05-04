<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ItemsSideList extension class
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ItemsSideList extends SideWidget {

    /**
     * $items contains the data to show in the widget
     * @var array
     */
    public $items = null;

    /**
     * lang is used in the CSS to load the style of the current language
     */
    public $lang = null;

    /**
     * $route is used in the pageing request
     * @var String 
     */
    public $route = '/site/getSideItemsList';

    /**
     * related type, array that contains 
     * the type and id of the related data, passed to the Ajax request
     * @var array
     */
    public $params = array();

    /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     * @access public
     * @return void
     */
    public function init() {
        if ($this->lang == null) {
            $this->lang = Yii::app()->getLanguage();
        }
        parent::init();
    }

    /**
     * Set the data content of this widget
     * Calls {@link drawList} to render each content row.
     * @return void
     */
    protected function setContentData() {
        $params = null;
        $this->contentData = null;
        if (isset($this->items['pager']) && $this->items['pager']['count']) {
            $this->drawList();
        }
    }

    /**
     * Append text after content
     * @access protected
     * @return string
     */
    protected function appendAfterContent() {
        $pages = new CPagination($this->items['pager']['count']);
        $pages->route = $this->route;
        $pages->params = array("params" => $this->params);
        $pages->params['lang'] = $this->lang;
        $pages->setPageSize($this->items['pager']['pageSize']);
        //echo $pages->itemCount;
        //echo $pages->limit;        
        $pages->offset;
        $output = null;
        if ($pages->limit < $pages->itemCount) {
            $ajaxJs = 'js:function(data) {
                jQuery("#' . $this->htmlOptions['id'] . "_content" . '").html(data);
             }';
            $url = Html::createUrl($this->route);
            //$next = CHtml::ajaxLink('', $url, array('success' => $ajaxJs), array('live' => false));
            $output = '<div class="pager_container">';
            $output .='<ul class="ajax-nav-link">';
            $output .='<li class="ajax-next-link" id="' . $this->htmlOptions['id'] . '_next' . '"></li>';
            $output .='<li class="ajax-prev-link" id="' . $this->htmlOptions['id'] . '_prev' . '"></li>';
            $output .='</ul>';
            $output .= '<div style="clear:both;"></div>';
            $output .= '</div>';
            $cs = Yii::app()->getClientScript();
            $pages->params['page'] = 1;
            $js = 'var sideList = {
                    data : ' . CJSON::encode($pages->params) . ',
                    pageCount : ' . $pages->getPageCount() . ',
                    next : function(){
                        if(sideList.data.page < sideList.pageCount){
                            sideList.data.page ++;
                            sideList.getData();
                        }
                        
                    },
                    prev:  function(){
                        if(sideList.data.page > 0){
                            sideList.data.page --;
                            sideList.getData();
                        }
                    },
                    getData: function(){
                    var height = $("#' . $this->htmlOptions['id'] . '_content' . ' .sub_sec_list").height();
                    var oldHtml = $("#' . $this->htmlOptions['id'] . '_content' . '").html();
                    $("#' . $this->htmlOptions['id'] . '_content' . '").html(\'<div style="text-align:center;height:\'+height+\'px;"><img src="' . AmcWm::app()->request->baseUrl . '/images/loading.gif" /><div>\');                    
                      $.ajax({
                          type: "GET",
                          url: "' . $url . '",
                          data: sideList.data                    
                      })                                            
                      .fail(function() {
                            $("#' . $this->htmlOptions['id'] . '_content' . '").html(oldHtml);
                      })
                      .done(function( html ) {
                            $("#' . $this->htmlOptions['id'] . '_content' . '").html(html);
                      })
                      .always(function() {
                      
                       });
                    }
                };
                $("#' . $this->htmlOptions['id'] . '_next' . '").click(function(event){                                        
                    sideList.next();
                    return false;
                });
                $("#' . $this->htmlOptions['id'] . '_prev' . '").click(function(event){                                        
                    sideList.prev();
                    return false;
                });
        ';
            $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_READY);
        }
        return $output;
    }

    /**
     * draw the content List
     * @access protected
     * @return void
     */
    protected function drawList() {
        if (isset($this->items['records'])) {
            $records = $this->items['records'];
        } else {
            $records = $this->items;
        }
        if (count($records)) {
            $this->contentData .= '<ul class="sub_sec_list">';
            foreach ($records As $item) {
                if (isset($item['label'])) {
                    $title = $item['label'];
                } else {
                    $title = $item['title'];
                }

                if (isset($item['url'])) {
                    $link = $item['url'];
                } else {
                    $link = $item['link'];
                }

                $this->contentData.= '<li><h2>';
                $this->contentData.= Html::link($title, $link, array('title' => CHtml::encode($title)));
                $this->contentData.= '</h2></li>';
            }
            $this->contentData .='</ul>';
        }
    }

}
