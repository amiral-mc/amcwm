<?php
if(!isset($msgAlias)){
    $msgAlias = "msgsbase";
}
?>
<div id="news_search">
    <div class="news_search_brief"><?php echo AmcWm::t("{$msgAlias}.core", "Please select one of our media libraries") ?></div>
    <div class="form-search-container">
        <?php        
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'form-media-libs',
            'action' => array("/multimedia/default/select"),
            'method' => "get",
            'htmlOptions' => array(
                "class" => "form-search",
            ),
        ));
        $libraries['images'] = AmcWm::t("app", '_BLOCK_IMAGES_TITLE_');
        $libraries['videos'] = AmcWm::t("app", '_BLOCK_VIDEOS_TITLE_');
        $libraries['presentations'] = AmcWm::t("app", '_BLOCK_PRESENTATIONS_TITLE_');
        $libraryGalleris = array();
        foreach ($galleries as $gallery) {
            $libraryGalleris[$gallery["id"]] = $gallery["title"];
        }
        ?>
        <?php echo CHTML::label(AmcWm::t("{$msgAlias}.core", "Library"), 'lib'); ?>
        <?php echo CHTML::dropDownList('lib', $labSelected, $libraries, array('onchange' => "$('#form-media-libs').submit()", 'prompt' => AmcWm::t("{$msgAlias}.core", "Library Type"))); ?> 
        <?php $this->endWidget(); ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'form-media-lib-gallery',
            'action' => array("/multimedia/{$labSelected}/index"),
            'method' => "get",
            'htmlOptions' => array(
                "class" => "form-search",
            ),
        ));
        ?>
        <?php if ($labSelected != "presentations"): ?>
            <?php echo CHTML::label(AmcWm::t("{$msgAlias}.core", "Galleries"), 'gid'); ?>
            <?php echo CHTML::dropDownList('gid', $galleryId, $libraryGalleris, array('onchange' => "$('#form-media-lib-gallery').submit()", 'prompt' => AmcWm::t("{$msgAlias}.core", "-- select --"))); ?>
        <?php endif; ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
