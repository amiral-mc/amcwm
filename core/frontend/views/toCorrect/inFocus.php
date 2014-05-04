<div class="wdl_title"><?php echo AmcWm::t("amcFront", "Article In Foucs"); ?></div>
<div class="wdl_content_shad">	
    <?php
    //$items = array();//$this->getInFocusArticles();
    $items = $inFocusItems->getItems();
    $link = $inFocusItems->getRoute();
    $media = Yii::app()->baseUrl . "/" . Yii::app()->params['multimedia']['infocus']['list']['path'] . "/";
    ?>
    <div class="infocus_title">
        <?php echo Html::link("{$items[0]['header']}", array($link, 'id' => $items[0]['infocus_id'])); ?>
    </div>
    <div align="center">
        <?php
        if ($items[0]["imageExt"]) {
            $infocusImage = $media . $items[0]['infocus_id'] . "." . $items[0]["imageExt"];
            echo Html::link(CHtml::tag('img', array("src" => "$infocusImage")), array($link, 'id' => $items[0]['infocus_id'])) . "\n";
        }
        ?>
    </div>
    <?php 
    if(count($items)>1):
    ?>
    <div class="infocus_readmore"><?php echo AmcWm::t("amcFront", "Read More InFocus"); ?> </div>
    <?php endif;?>
    <div>
        <ul class="infocus_list">
        <?php for ($i = 1; $i < count($items); $i++): ?>
            <li><?php echo Html::link("{$items[$i]['header']}", array($link, 'id' => $items[$i]['infocus_id'])); ?></li>
        <?php endfor; ?>
        </ul>
    </div>
</div>

