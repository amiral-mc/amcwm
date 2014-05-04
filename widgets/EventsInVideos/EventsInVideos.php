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
class EventsInVideos extends CWidget {
    public $title = null;
    public $lang = null;
    public $totalVideos = 0;
    public $htmlOptions = array();
    
    public $data = array();

    /**
     * Initializes the hijri widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        
        $baseUrl = null;
        $assetsFolder = "";
        if ($baseUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.EventsInVideos.assets'));
            $baseUrl = $assetsFolder . "/eventsInVideos";
        }

        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseUrl . '/css/pagination_'.Yii::app()->getLanguage().'.css');
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl.'/js/jquery.pagination.js', CClientScript::POS_HEAD);
        
        $this->htmlOptions['id'] = $this->getId();
        $this->lang = Yii::app()->getLanguage();
        parent::init();
    }

    public function run() {
        $output = "";
        if(count($this->data)){
            $activeData = $this->data[0];
            //unset ($this->data[0]);
            $output .= "<div style='padding:3px 5px;'>
                        <div class='eventsInVideo'>
                            <div class='eiv_title'>";
            $output .= AmcWm::t("msgsbase.core", "Events in video");
            $output .= "</div>
                        <div class='eiv_container'>	
                        <table class='eiv_table'>
                            <tr>
                                <td class='eiv_contentList' valign='top' height='240'>
                                <div id='contentListItem'>";
            $output .= $this->mediaItem($this->data);
            //$pager = $this->getPagingLinks($this->totalVideos, 6, 'multimedia/videos/topList', 5);
            $output .= "        </div>
                                ";
            $output .= "    </td>
                            <td class='eiv_contentActive' valign='top' rowspan='2'>
                                <div id='eiv_videoContainer'>";

            $output .= CHtml::openTag('div') . "\n";
                $output .= CHtml::openTag('div', array("class" => "eiv_videoContainerTitle")) . "\n";
                    $output .= Html::link($activeData["title"], $activeData["route"]);
                $output .= CHtml::closeTag('div') . "\n";
                $output .= CHtml::openTag('div', array("class" => "eiv_videoContainerData")) . "\n";

                $output .= CHtml::openTag('table') . "\n";
                $output .= CHtml::openTag('tr') . "\n";
                $output .= CHtml::openTag('td', array("valign"=>"top", "width"=>"220")) . "\n";
                    if($activeData["type"] == "videos"){
//                        $output .=  $this->widget('widgets.videoplayer.VideoPlayer', array(
//                                                    'id' => 'videoPlayerH',
//                                                    'className' => 'videoPlayerClass',
//                                                    'width'=>250,
//                                                    'height'=>200,
//                                                    'title'=> $activeData['title'],
//                                                    'video' => $activeData['url'],
//                                                )
//                        , true);
                        $output .= Html::link("", $activeData["route"], array('id'=>'video9876510cdertA82'));
                        $output .= '<div style="position:relative">';
                        $output .= '    <div class="jcSliderPlayBtn" style="top:40%; right:100px;" onclick="document.location.href = $(\'#video9876510cdertA82\').attr(\'href\')"></div>';
                        $outputIMG =    Chtml::image($activeData['thumb']. "?t=".time(), '', array('width'=>'220', 'border'=>0));
                        $output .=      Html::link($outputIMG, $activeData["route"], array('id'=>'video9876510cdertA82AbcDd'));
                        $output .= '</div>';
                        
                    }else{
                        $output .= Chtml::image($activeData['url']. "&t=".time());
                    }
                $output .= CHtml::closeTag('td') . "\n";
                $output .= CHtml::openTag('td', array("valign"=>"middle")) . "\n";
                $output .= "<div class='eiv_details'>" . AmcWm::t("msgsbase.core", "Added on"). " <br /><span>" . $activeData['created'] . "</span></div>";
                $output .= "<div class='eiv_details'>" . AmcWm::t("msgsbase.core", "Viewd"). " <br /><img src='".Yii::app()->request->baseUrl."/images/front/vviews.png' />&nbsp;&nbsp;<span>" . $activeData['hits'] . "</span></div>";
                $output .= "<div class='eiv_details'>" . AmcWm::t("msgsbase.core", "Comments"). " <br /><img src='".Yii::app()->request->baseUrl."/images/front/vcomments.png' />&nbsp;&nbsp;<span>" . $activeData['comments'] . "</span></div>";
                $output .= CHtml::closeTag('td') . "\n";
                $output .= CHtml::closeTag('tr') . "\n";
                $output .= CHtml::closeTag('table') . "\n";

                $output .= CHtml::closeTag('div') . "\n";
            $output .= CHtml::closeTag('div') . "\n";

            $output .= "            </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style='padding:0px 10px'><div id='eInVideosPager'></div></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                </div>
            ";
        }
        
        $pagecount = ceil($this->totalVideos / 6);
        $jsCode = "
            var getVideoDetails = function (videoId, lang){
                jQuery('#eiv_videoContainer').fadeOut('slow', function(){
                    jQuery.ajax({
                        'url':'".Html::createUrl("/multimedia/videos/view", array("ajax"=>"1"))."&id='+videoId+'&lang='+lang,
                        'cache':false,
                        'success':function(html){
                            jQuery('#eiv_videoContainer').html(html);
                            jQuery('#eiv_videoContainer').fadeIn('slow');
                        }
                    });
                });
                
                return false;
            }
            
            
            var PageClick = function(pageclickednumber) {
                $('#eInVideosPager').pager({ pagenumber: pageclickednumber, pagecount: {$pagecount}, buttonClickCallback: PageClick });
                jQuery('#contentListItem').fadeOut(function(){
                    $('#contentListItem').load('".Html::createUrl("/multimedia/videos/topList", array("ajax"=>"1"))."&max=6&page=' + pageclickednumber, {}, function() { jQuery('#contentListItem').fadeIn(); });
                });
            }
        ";
        Yii::app()->getClientScript()->registerScript("videosDetails", $jsCode, CClientScript::POS_HEAD);
        
        
        $jsCodePaging = "$('#eInVideosPager').pager({ pagenumber: 1, pagecount: {$pagecount}, buttonClickCallback: PageClick });";
        Yii::app()->getClientScript()->registerScript("videosPaging", $jsCodePaging, CClientScript::POS_READY);
        
        echo $output;
    }
    
    
    private function mediaItem($data, $rowlimit=3) {
        $output = "";
        if(count($data)){
            $output = CHtml::openTag('table', array("cellpadding"=>"5", 'cellspacing'=>'2', 'width'=>'100%')) . "\n";
                $output .= CHtml::openTag('tr') . "\n";
                $c = 1;
                foreach ($data as $media){
                    if($c>6)break;
                    $output .= CHtml::openTag('td', array("align" => "center","valign" => "top", 'class'=>'eiv_listTitle')) . "\n";
                            $title = CHtml::tag('img', array("src" => $media["thumb"], "width"=>"77")) . "\n";
                            $title .= "<br />";
                            $title .= Html::utfSubstring($media["title"], 0, 40);
                            
                        $output .= Html::link($title, $media['route']) . "\n";
                        //$output .= CHtml::ajaxLink($title, array('multimedia/videos/view', "ajax"=>"1", "id"=>$media["id"], "lang"=>$this->lang), array("update" => "#eiv_videoContainer"), array('id' => "result_" . $media["id"], 'onclick' => "$('#video_details_" . $media["id"] . "').show();"));
//                        $output .= CHtml::tag("a", array('href'=>'javascript:;', 'onclick' => "getVideoDetails({$media["id"]}, '{$this->lang}');"), $title);
                    $output .= CHtml::closeTag('td');
                    
                    $output .= ($c%$rowlimit==0)?CHtml::closeTag('tr'):"";
                    $c++;
                }
            $output .= CHtml::closeTag('table');
            
        }
        
        return $output;
    }
    
    public function getPagingLinks($total, $no_per_page, $link, $start = 0) {
        $numbers = ceil($total / $no_per_page);
        for ($i = 0; $i < $numbers; $i++) {
            if($i>=0 && $i<10){
                $linkPageNo = $i * $no_per_page;
                $paging_array[] = ($linkPageNo == $start) ? "<span class='ajaxPagingCurrent'>" . ($i + 1) . "</span>" : CHtml::ajaxLink(($i + 1), array($link, "page"=>($i + 1), "lang"=>$this->lang),array("update" => "#contentListItem"), array("class"=>"ajaxPaging"));
            }
        }
        $paging = implode(' ', array_reverse($paging_array));
        return $paging;
    }
}

?>
