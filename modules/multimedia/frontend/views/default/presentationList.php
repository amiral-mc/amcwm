<?php
$langSettings = AmcWm::app()->appModule->getSettings('languages');
?>

<?php if (count($directoryData['records'])): ?>
    <div class="presentation-list">
        <ul>        
            <?php foreach ($directoryData['records'] as $row): ?>
                <li>            
                    <div class="date">
                        <?php echo Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $row['publish_date']); ?>
                    </div>
                    <h1 class="title">
                        <?php
                        $fileLang = $langSettings[$row['file_lang']];
                        $drawDocLink = "#";
                        if ($row['file_ext']) {
                            $drawDocLink = $this->createUrl('/site/download', array('f' => "{$mediaSettings['paths']['files']['path']}/{$row['id']}.{$row['file_ext']}"));
                        }
                        ?>
                        <?php echo Html::link($row['title'], $drawDocLink); ?>
                    </h1>
                    <div class="disc">
                        <?php echo $row['description'] ?>
                    </div>
                    <div class="show_more">
                        <?php echo Html::link(AmcWm::t('msgsbase.core', 'Download') . ' <span class="icon"></span>', $drawDocLink); ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>        
    </div>
    <div class="pager">
        <?php
        $pages->route = "/multimedia/default/presentations";
        $pages = new CPagination($directoryData['pager']['count']);
        $pages->setPageSize($directoryData['pager']['pageSize']);
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



