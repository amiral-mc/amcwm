<?php
$colorbox = $this->widget('amcwm.widgets.colorpowered.JColorBox');
switch ($labSelected) {
    case 'videos':
        $colorboxAttributes = "height:'450px', width:'490px;'";
        break;
    case 'images':
        $colorboxAttributes = "height:'600px', width:'660px;'";
        break;
}

Yii::app()->clientScript->registerScript('popupVidew', "    
    $('.media-popup').click(
        function(event){
            event.preventDefault();                       
            $.colorbox({href:$(this).data('mediaLink'), {$colorboxAttributes}});
            //return false;
        }        
    );    
");
?>

<?php if ($activeGallery['pager']['count']) : ?>
    <div id="media_list">
        <ul>
            <?php foreach ($activeGallery['records'] as $row): ?>
                <?php
                $seoLink = Html::createUrl($route, $row["params"]);
                $row["params"]['ajax'] = 1;
                $link = Html::createUrl($route, $row["params"]);
                ?>
                <li>
                    <a class="multimedia_item media-popup" href="<?php echo $seoLink; ?>" data-media-link="<?php echo $link; ?>">
                        <span><img src="<?php echo $row["thumb"] ?>" alt="<?php echo CHtml::encode($row["title"]) ?>"/></span>								
                        <b><?php echo $row["title"]; ?></b>
                    </a>
                </li>							
            <?php endforeach; ?>
        </ul>      
    </div>
    <div class="pager">
        <?php
        $pages = new CPagination($activeGallery['pager']['count']);
        $pages->setPageSize($activeGallery['pager']['pageSize']);
        $this->widget('CLinkPager', array('pages' => $pages));
        ?>
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


