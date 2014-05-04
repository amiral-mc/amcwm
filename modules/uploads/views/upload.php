<?php
$route = str_replace("ajax", "manageFiles", $route = $this->getRoute());
?>
<div class="tools">
    <div class="file-back">
        <img src= "<?php echo $iconsPath ?>/back.png" />
        <span><?php echo AmcWm::t("msgsbase.core", "_back_") ?></span>
    </div>
</div>
<iframe src="<?php echo Html::createUrl($route, array("dialog" => AmcWm::app()->request->getParam("dialog"), "action" => "upload", "component" => "uploadsFiles", "op" => AmcWm::app()->request->getParam("op"))); ?>" width="350" height="350" frameborder="0" scrolling="no"></iframe>