<?php
//$mediaSettings = $this->module->appModule->mediaSettings;
$options = $this->module->appModule->options;


$this->beginClip('tendersList');
?>
<div id="tender_view">
    <p class="tender_intro"><?php echo AmcWm::t('app', '_TENDER_INTRO_'); ?></p>
    <div>
        <div class="tender_class"><?php echo AmcWm::t('msgsbase.core', 'Current tenders'); ?></div>
        <div class="tender_grid">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'data-grid',
                'dataProvider' => $currentTenders,
                'columns' => array(
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'Title'),
                        'value' => 'CHtml::link($data["title"], array("/tenders/default/view", "id"=>$data["id"]), array("class"=>"tender_title_list"));',
                        'type' => 'html'
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'RFP start date'),
                        'value' => '$data["rfp_start_date"]',
                        'htmlOptions' => array('style' => 'width:110px; text-align:center')
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'Submission start date'),
                        'value' => '$data["submission_start_date"]',
                        'htmlOptions' => array('style' => 'width:100px; text-align:center')
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'RFP Price'),
                        'value' => '$data["rfp_price1"]',
                        'htmlOptions' => array('style' => 'width:80px; text-align:center')
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'Primary insurance'),
                        'value' => '$data["primary_insurance"]',
                        'htmlOptions' => array('style' => 'width:100px; text-align:center')
                    ),
                )
            ));
            ?>
        </div>
    </div>

    <div style="padding-top: 20px;">
        <div class="tender_class"><?php echo AmcWm::t('msgsbase.core', 'Past tenders'); ?></div>
        <div class="tender_grid">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'data-grid',
                'dataProvider' => $pastTenders,
                'columns' => array(
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'Title'),
                        'value' => 'CHtml::link($data["title"], array("/tenders/default/view", "id"=>$data["id"]), array("class"=>"tender_title_list"));',
                        'type' => 'html'
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'RFP start date'),
                        'value' => '$data["rfp_start_date"]',
                        'htmlOptions' => array('style' => 'width:110px; text-align:center')
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'Submission start date'),
                        'value' => '$data["submission_start_date"]',
                        'htmlOptions' => array('style' => 'width:100px; text-align:center')
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'RFP Price'),
                        'value' => '$data["rfp_price1"]',
                        'htmlOptions' => array('style' => 'width:80px; text-align:center')
                    ),
                    array(
                        'name' => AmcWm::t('msgsbase.core', 'Primary insurance'),
                        'value' => '$data["primary_insurance"]',
                        'htmlOptions' => array('style' => 'width:100px; text-align:center')
                    ),
                )
            ));
            ?>
        </div>
    </div>
</div>
<?php
$this->endClip('tendersList');

$pageContentTitle = AmcWm::t('msgsbase.core', 'Tenders');
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array($options['default']['text']['homeRoute']), false);
$title = AmcWm::t('msgsbase.core', 'Tenders');
$catImage = null;

$widgetImage = Data::getInstance()->getPageImage('tenders', null, $catImage, AmcWm::app()->baseUrl . "/images/front/tendersImage.jpg");


$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $this->clips['tendersList'],
    'title' => $title,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $pageContentTitle,
));
?>