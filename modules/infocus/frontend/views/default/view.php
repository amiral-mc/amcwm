<?php
$drawImage = NULL;
if ($id && $infocusData['imageExt']) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params['multimedia']['infocus']['list']['path'] . "/" . $id . "." . $infocusData['imageExt']))) {
        $drawImage = CHtml::image(Yii::app()->baseUrl . "/" . Yii::app()->params['multimedia']['infocus']['list']['path'] . "/" . $id . "." . $infocusData['imageExt'] . "?" . time(), "", array("style"=>"margin:5px;"));
    }
}

$drawBackground = NULL;
if ($id && $infocusData['background']) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params["multimedia"]['infocus']['backgrounds']['path'] . "/" . $id . "." . $infocusData['background']))) {
        $drawBackground = Yii::app()->baseUrl . "/" . Yii::app()->params["multimedia"]['infocus']['backgrounds']['path'] . "/" . $id . "." . $infocusData['background'] . "?" . time();
    }
}
//echo $drawBackground

$drawBanner = NULL;
if ($id && $infocusData['banner']) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params["multimedia"]['infocus']['banners']['path'] . "/" . $id . "." . $infocusData['banner']))) {
        $drawBanner = Yii::app()->baseUrl . "/" . Yii::app()->params["multimedia"]['infocus']['banners']['path'] . "/" . $id . "." . $infocusData['banner'] . "?" . time();
    }
}

//
?>
<?php if($drawBackground):?>
<style>
    body{
        background: #<?php echo $infocusData['bgcolor']?> url(<?php echo $drawBackground?>) no-repeat top center !important;
    }
</style>
<?php endif; ?>
<?php if($drawBanner):?>
<style>
    #ana_services{
        margin-bottom: 2px;
    }
    #infocusBanner{
        background: url(<?php echo $drawBanner?>) no-repeat top !important;
        width: 990px;
        height: 100px;
        padding-bottom: 5px;
    }
</style>
<?php endif; ?>

<div>
    <table width="642">
        <tr>
            <td class="infocus_right_area">
                <?php
                if(count($infocusLatestData["text"])){
                ?>
                <div class="wdr_infocus">
                    <div class="wdr_infocus_shdcaheader"></div>
                    <div class="wdr_infocus_shdcontent">
                        <?php 
                        $img = "";
                        $media = Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths['images']['path'];
                        if($infocusLatestData['text'][0]['imageExt']){
                            $img = "<div><img src='$media/{$infocusLatestData['text'][0]['id']}.{$infocusLatestData['text'][0]['imageExt']}'></div>";
                        }else{
                            $img = "<div><img src='".Yii::app()->baseUrl."/images/front/en/no_image.jpg'></div>";
                        }
                        echo $img;
                        ?>
                        <div class="infocus_txt_news">
                            <?php
                                echo Html::link($infocusLatestData['text'][0]['title'], array('/articles/default/view', "id"=>$infocusLatestData['text'][0]['id']), "");
                            ?>
                        </div>
                        <div class="infocus_txt_news_details">
                            <?php
                                echo Html::utfSubstring($infocusLatestData['text'][0]['detail'], 0, 150), "&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo Html::link(AmcWm::t("amcFront", "More"), array('/articles/default/view', "id"=>$infocusLatestData['text'][0]['id']), array("class"=>"search_more"));
                            ?>
                        </div>
                    </div>
                    <div class="wdr_infocus_shdmnfooter"></div>
                </div>
                <?php } ?>
            </td>
            <td class="infocus_left_area">
                <?php
                if(count($infocusLatestData["multimedia"])){
                ?>
                <div class="wdl_infocus">
                    <div class="wdl_infocus_shdcaheader"></div>
                        <div class="wdl_infocus_shdcontent">
                            <?php 
                            $img = "";                            
                            $media = Yii::app()->baseUrl . "/" . VideosListData::getSettings()->mediaPaths['videos']['thumb']['path'];                                                       
                            if($infocusLatestData['multimedia'][0]['imageExt']){
                                $img = "<div><img class='infocus_v_img' src='{$infocusLatestData['multimedia'][0]['image']}'></div>";
                            }else{
//                                $ytubeCode = Html::getVideoCode($infocusLatestData['multimedia'][0]['video']);
                                $this->widget('application.extensions.videoplayer.VideoPlayer', array(
                                    'id' => 'videoPlayer1',
                                    'className' => 'videoPlayerClass',
                                    'width' => 270,
                                    'height' => 190,
                                    'title' => $infocusLatestData['multimedia'][0]['title'],
                                    'video' => $infocusLatestData['multimedia'][0]['video'],
                                    )
                                );
//                                $img = "<div><img class='infocus_v_img' src='".Yii::app()->baseUrl."/images/front/en/no_image.jpg'></div>";
                            }
                            echo $img;
                            ?>
                            <div class="infocus_txt_news">
                                <?php
                                    echo Html::link($infocusLatestData['multimedia'][0]['title'], array('/multimedia/videos/index', "id"=>$infocusLatestData['multimedia'][0]['id'], "gid"=>$infocusLatestData['multimedia'][0]["gallery_id"]), "");
                                ?>
                            </div>
                            <div class="infocus_dotted_line"></div>
                            <div>
                                <table cellspacing="0" cellpadding="0" width="270">
                                    <?php
                                    $allLatstVideos = $infocusLatestData['multimedia'];
                                    unset($allLatstVideos[0]);
                                    foreach ($allLatstVideos AS $video){
                                        
                                        if($video['imageExt']){
                                            $mediaImage = str_replace("{gallery_id}", $video['gallery_id'], "{$media}/{$video['id']}.{$video['imageExt']}");
                                            $img = "<div><img src='{$mediaImage}' width='50'></div>";
                                        }else{                                            
                                            $img = "<div><img src='".Yii::app()->baseUrl . "/images/front/" . Yii::app()->getLanguage() . "/no_image.jpg" ."' width='50'></div>";
                                        }
                                        
                                        echo "<tr>";
                                            echo "<td class='infocus_v_img_small'>";
                                                echo $img;
                                            echo "</td>";
                                            echo "<td class='infocus_v_desc'>";
                                                echo Html::link($video["title"], array('/multimedia/videos/index', "id"=>$video["id"], "gid"=>$video["gallery_id"]), "");
                                            echo "</td>";
                                        echo "</tr>";
                                        echo "<tr><td colspan='2' style='height:2px;' ></td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    <div class="wdl_infocus_shdmnfooter"></div>
                </div>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>
<?php
$this->widget('widgets.InfocusWidget', array(
    'data' => $infocusItems,
    'contentType' => $contentType,
    'page' => $page,
    'focusId' => $id,
    'routers' => Yii::app()->params['routers'],
    'htmlOptions' => array('style' => 'padding-top:5px;',),
));
?>
<div style="clear: both; height: 5px;"></div>