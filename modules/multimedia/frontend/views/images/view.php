<?php
    $options = $this->module->appModule->options;            
?>
<?php if (count($media)): ?>
    <?php $this->pageTitle = $this->pageTitle . ' - ' . $media['title']; ?>
    <div class="main_video">
        <div class="v_detail_statistics">
            <span class="news_detail_share">
            
            </span>				   				
            <span class="news_detail_date_time"><?php echo AmcWm::t("amcFront", "last update") . ' ' . Yii::app()->dateFormatter->format("EEEE dd MMM y, hh:mm a ('GMT' ZZZZ)", $media['updated']); ?></span>
            <!--<span class="news_detail_statistics_icon"><img src="<?php echo Yii::app()->baseUrl ?>/images/front/comments.png" alt="" />[<strong><?php echo $media['comments']; ?></strong>]</span>-->
            <span class="news_detail_statistics_icon"><img src="<?php echo Yii::app()->baseUrl ?>/images/front/views.png" alt="" />[<strong><?php echo $media['hits']; ?></strong>]</span>
        </div>
        <div style="width:<?php echo $options['default']['integer']['imageWidth'];?>;overflow: hidden;">
            <img src="<?php echo $media['url']?>" alt="<?php echo $media['title']?>" />           
        </div>
        <div class="main_video_data">
            <h1 class="main_video_title"><?php echo $media['title']; ?></h1>
            <div class="main_video_desc"><?php echo $media['description']; ?></div>          
        </div>

    </div>


<?php else: ?>
    <div style="margin: 100px auto;width:300px;">
        <div style="margin: 10px auto;width:95px;">
            <img src="<?php echo AmcWm::app()->baseUrl ?>/images/warning.png" alt="">
        </div>
        <div class="error" style="text-align: center;">
            <?php echo AmcWm::t("msgsbase.core", "Empty Media List"); ?>
        </div>
    </div>

<?php endif; ?>
